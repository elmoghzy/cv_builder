<?php

namespace App\Filament\User\Resources\CvResource\Pages;

use App\Filament\User\Resources\CvResource;
use App\Models\Cv;
use App\Models\Payment;
use App\Services\PayMobService;
use App\Services\CvService;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;

class ViewCv extends ViewRecord
{
    protected static string $resource = CvResource::class;

    protected function getHeaderActions(): array
    {
        $record = $this->getRecord();

        $payAction = Actions\Action::make('pay_now')
            ->label('Pay Now')
            ->icon('heroicon-o-credit-card')
            ->color('success')
            ->visible(fn (): bool => !$record->is_paid)
            ->action(function () use ($record) {
                $amountCents = (int) (config('paymob.cv_price_cents', 5000));
                $service = app(PayMobService::class);
                $orderId = 'cv-' . $record->id . '-' . time();

                $billing = [
                    'apartment' => 'NA', 'email' => auth()->user()->email, 'floor' => 'NA',
                    'first_name' => auth()->user()->name, 'street' => 'NA', 'building' => 'NA',
                    'phone_number' => 'NA', 'shipping_method' => 'NA', 'postal_code' => 'NA',
                    'city' => 'Cairo', 'country' => 'EG', 'last_name' => auth()->user()->name, 'state' => 'Cairo'
                ];

                $res = $service->createPayment([
                    'amount' => $amountCents,
                    'currency' => 'EGP',
                    'items' => [[
                        'name' => 'CV Purchase',
                        'amount_cents' => $amountCents,
                        'quantity' => 1,
                    ]],
                    'order_id' => $orderId,
                    'billing_data' => $billing,
                ]);

                Payment::create([
                    'user_id' => auth()->id(),
                    'cv_id' => $record->id,
                    'order_id' => (string)($res['id'] ?? $orderId),
                    'amount' => $amountCents / 100,
                    'currency' => 'EGP',
                    'status' => 'pending',
                    'paymob_data' => $res,
                ]);

                $iframeUrl = $service->getIframeUrl($res['payment_key']);
                return redirect()->away($iframeUrl);
            });

        $downloadAction = Actions\Action::make('download_pdf')
            ->label('Download PDF')
            ->icon('heroicon-o-arrow-down-tray')
            ->visible(fn (): bool => (bool) $record->is_paid && $record->pdf_path)
            ->url(fn (): string => asset('storage/' . $this->getRecord()->pdf_path))
            ->openUrlInNewTab();

        return [
            Actions\EditAction::make(),
            $payAction,
            $downloadAction,
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Details')
                    ->schema([
                        TextEntry::make('title')->label('Title'),
                        TextEntry::make('template.name')->label('Template'),
                        TextEntry::make('status')->label('Status'),
                    ])->columns(3),

                Section::make('Preview')
                    ->schema([
                        ViewEntry::make('preview')
                            ->view('filament.user.cvs.preview')
                            ->viewData([
                                'cv' => $this->getRecord(),
                                'html' => app(CvService::class)->generateHtml($this->getRecord()),
                                'price' => number_format((config('paymob.cv_price_cents', 5000) / 100), 2),
                            ])
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
