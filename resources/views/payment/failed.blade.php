@extends('layouts.main')

@section('header')
<h1 class="text-3xl font-bold leading-tight tracking-tight text-red-600">Payment Failed</h1>
@endsection

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="px-6 py-8 text-center">
            <!-- Error Icon -->
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-6">
                <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 mb-4">Payment was unsuccessful</h2>
            <p class="text-gray-600 mb-8">
                We're sorry, but your payment could not be processed. Please try again or contact our support team.
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
                        <dt class="text-sm font-medium text-gray-500">Attempt Date</dt>
                        <dd class="text-sm text-gray-900">{{ $payment->created_at->format('M d, Y - H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="text-sm text-red-600 font-medium capitalize">{{ $payment->status }}</dd>
                    </div>
                    @if($payment->transaction_id)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Reference ID</dt>
                        <dd class="text-sm text-gray-900">{{ $payment->transaction_id }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            <!-- Error Reasons -->
            <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-8">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Common reasons for payment failure:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Insufficient funds in your account</li>
                                <li>Incorrect card details entered</li>
                                <li>Card expired or blocked</li>
                                <li>Bank security restrictions</li>
                                <li>Network connectivity issues</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <form method="POST" action="{{ route('payment.initiate', $payment->cv) }}" class="inline">
                    @csrf
                    <button type="submit" 
                            class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Try Payment Again
                    </button>
                </form>

                <a href="{{ route('cv.preview', $payment->cv) }}" 
                   class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Preview CV
                </a>

                <a href="{{ route('cv.index') }}" 
                   class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    My CVs
                </a>
            </div>

            <!-- Support Information -->
            <div class="mt-8 border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Need Help?</h3>
                <div class="text-sm text-gray-600 space-y-2">
                    <p>If you continue to experience payment issues, please contact our support team:</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <a href="mailto:support@cvbuilder.com" 
                           class="inline-flex items-center text-indigo-600 hover:text-indigo-800">
                            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            support@cvbuilder.com
                        </a>
                        <a href="tel:+20123456789" 
                           class="inline-flex items-center text-indigo-600 hover:text-indigo-800">
                            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            +20 123 456 789
                        </a>
                    </div>
                    <p class="mt-4 text-xs text-gray-500">
                        Please include your order ID ({{ $payment->order_id }}) when contacting support.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
