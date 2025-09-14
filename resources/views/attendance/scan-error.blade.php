@extends('components.app-layout')

@section('title', 'QR Code Error')
@section('subtitle', 'Attendance Scan')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Error Display -->
    <div class="text-center">
        <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-exclamation-triangle text-2xl text-white"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">QR Code Error</h1>
        <p class="text-gray-600">There was an issue with the QR code you scanned</p>
    </div>

    <!-- Error Message -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
        <div class="bg-red-50 border border-red-200 rounded-xl p-6 mb-6">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-times text-white text-sm"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-red-800">Error</h3>
                    <p class="text-red-700">{{ $message }}</p>
                </div>
            </div>
        </div>

        <div class="text-center">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">What can you do?</h3>
            <div class="space-y-3 text-left">
                <div class="flex items-start space-x-3">
                    <i class="fas fa-circle text-blue-500 text-xs mt-2"></i>
                    <p class="text-gray-600">Ask the event organizer for a new QR code</p>
                </div>
                <div class="flex items-start space-x-3">
                    <i class="fas fa-circle text-blue-500 text-xs mt-2"></i>
                    <p class="text-gray-600">Try scanning the QR code again</p>
                </div>
                <div class="flex items-start space-x-3">
                    <i class="fas fa-circle text-blue-500 text-xs mt-2"></i>
                    <p class="text-gray-600">Contact the administrator for manual attendance entry</p>
                </div>
            </div>
        </div>

        <div class="flex justify-center space-x-4 mt-8">
            <a href="{{ route('attendance.index') }}" 
               class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors">
                <i class="fas fa-home mr-2"></i>
                Go to Attendance
            </a>
            <button onclick="history.back()" 
                    class="inline-flex items-center px-6 py-3 bg-gray-600 text-white font-medium rounded-xl hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Go Back
            </button>
        </div>
    </div>

    <!-- Common Issues -->
    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl p-6">
        <h3 class="font-semibold text-gray-900 mb-3">Common Issues</h3>
        <div class="space-y-2 text-sm text-gray-600">
            <div class="flex items-start space-x-3">
                <i class="fas fa-clock text-orange-500 mt-1"></i>
                <p><strong>Expired QR Code:</strong> The QR code may have expired. Ask for a new one.</p>
            </div>
            <div class="flex items-start space-x-3">
                <i class="fas fa-ban text-red-500 mt-1"></i>
                <p><strong>Deactivated QR Code:</strong> The QR code has been deactivated by the administrator.</p>
            </div>
            <div class="flex items-start space-x-3">
                <i class="fas fa-wifi text-blue-500 mt-1"></i>
                <p><strong>Network Issues:</strong> Check your internet connection and try again.</p>
            </div>
        </div>
    </div>
</div>
@endsection
