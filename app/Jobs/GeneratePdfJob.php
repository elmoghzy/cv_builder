<?php

namespace App\Jobs;

use App\Models\Cv;
use App\Services\CvService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\CvReadyMail;

class GeneratePdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $cv;
    protected $paymentId;
    
    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * The maximum number of seconds the job can run.
     */
    public $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct(Cv $cv, string $paymentId = null)
    {
        $this->cv = $cv;
        $this->paymentId = $paymentId;
    }

    /**
     * Execute the job.
     */
    public function handle(CvService $cvService): void
    {
        try {
            Log::info('Starting PDF generation for CV', [
                'cv_id' => $this->cv->id,
                'user_id' => $this->cv->user_id,
                'payment_id' => $this->paymentId
            ]);

            // Mark CV as paid and generate PDF
            if ($this->paymentId) {
                $cvService->markAsPaid($this->cv, $this->paymentId);
            }

            // Refresh the model to get updated data
            $this->cv->refresh();

            Log::info('PDF generation completed successfully', [
                'cv_id' => $this->cv->id,
                'pdf_path' => $this->cv->pdf_path
            ]);

            // Send email notification to user
            try {
                Mail::to($this->cv->user->email)
                    ->send(new CvReadyMail($this->cv));
                    
                Log::info('CV ready email sent', [
                    'cv_id' => $this->cv->id,
                    'user_email' => $this->cv->user->email
                ]);
            } catch (\Exception $e) {
                Log::warning('Failed to send CV ready email', [
                    'cv_id' => $this->cv->id,
                    'error' => $e->getMessage()
                ]);
            }

        } catch (\Exception $e) {
            Log::error('PDF generation failed', [
                'cv_id' => $this->cv->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('PDF generation job failed permanently', [
            'cv_id' => $this->cv->id,
            'payment_id' => $this->paymentId,
            'error' => $exception->getMessage(),
            'attempts' => $this->attempts()
        ]);

        // Notify user of failure
        try {
            Mail::to($this->cv->user->email)
                ->send(new \App\Mail\CvGenerationFailedMail($this->cv));
        } catch (\Exception $e) {
            Log::error('Failed to send CV generation failure notification', [
                'cv_id' => $this->cv->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
