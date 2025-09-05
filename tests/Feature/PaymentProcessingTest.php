<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Cv;
use App\Models\Template;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class PaymentProcessingTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $template;
    protected $cv;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->template = Template::factory()->create([
            'name' => 'Professional Template',
            'is_active' => true
        ]);
        
        $this->cv = Cv::factory()->create([
            'user_id' => $this->user->id,
            'template_id' => $this->template->id,
            'title' => 'Test CV',
            'status' => 'completed'
        ]);
    }

    /** @test */
    public function user_can_initiate_payment_for_cv()
    {
        $this->actingAs($this->user);

        $response = $this->post(route('payment.initiate'), [
            'cv_id' => $this->cv->id
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('payments', [
            'user_id' => $this->user->id,
            'cv_id' => $this->cv->id,
            'amount' => 100.00,
            'currency' => 'EGP',
            'status' => 'pending'
        ]);
    }

    /** @test */
    public function payment_callback_processes_successful_payment()
    {
        $payment = Payment::factory()->create([
            'user_id' => $this->user->id,
            'cv_id' => $this->cv->id,
            'status' => 'pending',
            'paymob_order_id' => '12345'
        ]);

        // Mock PayMob callback data
        $callbackData = [
            'obj' => [
                'id' => 67890,
                'order' => [
                    'id' => 12345
                ],
                'success' => true,
                'amount_cents' => 10000, // EGP 100 in cents
                'pending' => false
            ]
        ];

        $response = $this->post(route('payment.callback'), $callbackData);

        $response->assertRedirect(route('payment.success', $payment));
        
        $payment->refresh();
        $this->assertEquals('completed', $payment->status);
        $this->assertNotNull($payment->paid_at);
        $this->assertEquals(67890, $payment->paymob_transaction_id);
    }

    /** @test */
    public function failed_payment_is_handled_correctly()
    {
        $payment = Payment::factory()->create([
            'user_id' => $this->user->id,
            'cv_id' => $this->cv->id,
            'status' => 'pending',
            'paymob_order_id' => '12345'
        ]);

        // Mock PayMob failed callback data
        $callbackData = [
            'obj' => [
                'id' => 67890,
                'order' => [
                    'id' => 12345
                ],
                'success' => false,
                'amount_cents' => 10000,
                'pending' => false
            ]
        ];

        $response = $this->post(route('payment.callback'), $callbackData);

        $response->assertRedirect(route('payment.failed', $payment));
        
        $payment->refresh();
        $this->assertEquals('failed', $payment->status);
    }

    /** @test */
    public function user_cannot_download_cv_without_payment()
    {
        $this->actingAs($this->user);
        
        $response = $this->get(route('cv.download', $this->cv));
        
        $response->assertRedirect(route('cv.preview', $this->cv));
        $response->assertSessionHas('error', 'You need to complete payment to download this CV.');
    }

    /** @test */
    public function user_can_download_cv_after_successful_payment()
    {
        $this->actingAs($this->user);
        
        // Create successful payment
        Payment::factory()->create([
            'user_id' => $this->user->id,
            'cv_id' => $this->cv->id,
            'status' => 'completed',
            'paid_at' => now()
        ]);

        // Update CV status
        $this->cv->update(['status' => 'paid']);

        $response = $this->get(route('cv.download', $this->cv));
        
        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    /** @test */
    public function payment_amount_is_validated()
    {
        $this->actingAs($this->user);

        // Try to manipulate amount in request
        $response = $this->post(route('payment.initiate'), [
            'cv_id' => $this->cv->id,
            'amount' => 1 // Try to pay EGP 1 instead of 100
        ]);

        // Payment should still be created with correct amount
        $this->assertDatabaseHas('payments', [
            'user_id' => $this->user->id,
            'cv_id' => $this->cv->id,
            'amount' => 100.00, // Correct amount enforced by server
            'currency' => 'EGP'
        ]);
    }

    /** @test */
    public function user_cannot_initiate_payment_for_already_paid_cv()
    {
        $this->actingAs($this->user);

        // Mark CV as paid
        $this->cv->update(['status' => 'paid']);
        
        Payment::factory()->create([
            'user_id' => $this->user->id,
            'cv_id' => $this->cv->id,
            'status' => 'completed'
        ]);

        $response = $this->post(route('payment.initiate'), [
            'cv_id' => $this->cv->id
        ]);

        $response->assertRedirect(route('cv.preview', $this->cv));
        $response->assertSessionHas('info', 'This CV has already been paid for.');
    }
}
