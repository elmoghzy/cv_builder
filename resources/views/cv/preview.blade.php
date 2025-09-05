@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">CV Preview</h1>
                    <p class="text-gray-600">{{ $cv->title }}</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('cv.edit', $cv) }}" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit CV
                    </a>
                    
                    @if(!$cv->is_paid)
                        <form action="{{ route('payment.initiate', $cv) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition duration-200">
                                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                Pay & Download (EGP 100)
                            </button>
                        </form>
                    @else
                        <a href="{{ route('cv.download', $cv) }}" 
                           class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition duration-200">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download PDF
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- CV Preview Container -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Preview Controls -->
            <div class="bg-gray-50 border-b border-gray-200 p-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <span class="text-sm font-medium text-gray-700">Template:</span>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                            {{ $cv->template->name }}
                        </span>
                        <span class="text-sm font-medium text-gray-700">Status:</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($cv->is_paid) bg-green-100 text-green-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ $cv->is_paid ? 'Paid' : 'Unpaid' }}
                        </span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="window.print()" 
                                class="text-gray-600 hover:text-gray-800 p-2 rounded-md hover:bg-gray-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- CV Preview -->
            <div class="p-8 bg-white" style="min-height: 842px; width: 595px; margin: 0 auto; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                <div id="cv-preview" class="print:shadow-none">
                    {!! $html !!}
                </div>
            </div>
        </div>

        <!-- Payment Info -->
        @if(!$cv->is_paid)
        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-yellow-800">
                        Payment Required for Download
                    </h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>To download your CV as a PDF, you need to complete the payment process. The cost is EGP 100, and you'll get:</p>
                        <ul class="mt-2 list-disc list-inside space-y-1">
                            <li>ATS-compliant PDF format</li>
                            <li>Professional formatting</li>
                            <li>Lifetime access to download</li>
                            <li>Email notification when ready</li>
                        </ul>
                    </div>
                    <div class="mt-4">
                        <form action="{{ route('payment.initiate', $cv) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700 transition duration-200">
                                Proceed to Payment (EGP 100)
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #cv-preview, #cv-preview * {
        visibility: visible;
    }
    #cv-preview {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    .print\:shadow-none {
        box-shadow: none !important;
    }
}
</style>
@endsection
