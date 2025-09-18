@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-xl font-semibold">Template Preview — {{ $template->name }}</h1>
            <a href="{{ url()->previous() }}" class="text-slate-600 hover:text-slate-900">Back</a>
        </div>
        <div class="bg-white rounded-lg border border-slate-200 p-6 overflow-auto shadow-sm">
            <div class="flex justify-center">
                <iframe title="template preview" class="bg-white shadow-md rounded border border-gray-200" style="width: 794px; height: 1123px;" srcdoc="{!! str_replace('"', '&quot;', $html) !!}"></iframe>
            </div>
        </div>
    </div>
    </div>
@endsection
