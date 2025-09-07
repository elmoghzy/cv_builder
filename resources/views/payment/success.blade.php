@extends('layouts.main')

@section('header')
<h1 class="text-3xl font-bold leading-tight tracking-tight text-green-600">Payment Successful!</h1>
@endsection

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="px-6 py-8 text-center">
            <x-alert type="success" title="Payment Successful" message="Your CV will be ready shortly." class="mb-8" />
            <!-- Success Icon -->
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
                <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 mb-4">Your payment was successful!</h2>
            <p class="text-gray-600 mb-8">
                Thank you for your purchase. Your CV is being prepared for download.
            </p>

            <!-- Payment Details -->
            <div class="bg-gray-50 rounded-lg p-6 mb-8 text-left">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Details</h3>
                <dl class="grid grid-cols-1 gap-x-4 gap-y-3 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Order ID</dt>
                        <dd class="text-sm text-gray-900">{{ $payment->order_id }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Amount</dt>
                        <dd class="text-sm text-gray-900">{{ $payment->amount }} {{ $payment->currency }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">CV Title</dt>
                        <dd class="text-sm text-gray-900">{{ $payment->cv->title }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Payment Date</dt>
                        <dd class="text-sm text-gray-900">{{ $payment->paid_at->format('M d, Y - H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                        <dd class="text-sm text-gray-900 capitalize">{{ $payment->payment_method ?? 'Credit Card' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Transaction ID</dt>
                        <dd class="text-sm text-gray-900">{{ $payment->transaction_id }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Status Message -->
            <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-8">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">CV Processing</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>
                                Your CV is being generated as a high-quality PDF. This process usually takes 1-2 minutes.
                                You will receive an email notification when your CV is ready for download.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('cv.index') }}" 
                   class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    View My CVs
                </a>

                @if($payment->cv->is_paid)
                    <a href="{{ route('cv.download', $payment->cv) }}" 
                       class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Download CV
                    </a>
                @else
                    <button type="button" 
                            disabled
                            class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-gray-400 cursor-not-allowed">
                        <svg class="-ml-1 mr-2 h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    </button>
                @endif
            </div>

            <!-- Email Notice -->
            <div class="mt-8 text-sm text-gray-500">
                <p>
                    An email confirmation has been sent to <strong>{{ $payment->user->email }}</strong>
                </p>
                <p class="mt-2">
                    Need help? Contact our support team at 
                    <a href="mailto:support@cvbuilder.com" class="text-indigo-600 hover:text-indigo-800">support@cvbuilder.com</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-refresh page every 30 seconds if CV is not ready yet
@if(!$payment->cv->is_paid)
    setTimeout(function() {
        location.reload();
    }, 30000);
@endif
</script>
@endsection
