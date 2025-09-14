@extends('components.app-layout')

@section('title', 'Gateway Settings')
@section('subtitle', 'Manage payment and communication gateways')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 bg-clip-text text-transparent">Gateway Settings</h1>
                <p class="text-slate-600 text-lg mt-1">Configure payment and communication services</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 mt-4 sm:mt-0">
                <a href="{{ route('settings.index') }}" class="inline-flex items-center px-6 py-3 bg-white/80 backdrop-blur-sm text-slate-700 font-semibold rounded-xl border border-slate-200 hover:bg-white hover:shadow-md transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Settings
                </a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-emerald-500 mr-3"></i>
                    <span class="text-emerald-800 font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                    <span class="text-red-800 font-medium">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Gateway Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
            @foreach($gateways as $key => $gateway)
                <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-xl border border-white/40 p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <!-- Gateway Header -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-{{ $gateway['color'] }}-500 to-{{ $gateway['color'] }}-600 rounded-2xl flex items-center justify-center">
                                <i class="{{ $gateway['icon'] }} text-white text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-xl font-bold text-slate-900">{{ $gateway['name'] }}</h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $gateway['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    <i class="fas fa-{{ $gateway['status'] === 'active' ? 'check-circle' : 'times-circle' }} mr-1"></i>
                                    {{ ucfirst($gateway['status']) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <p class="text-slate-600 text-sm mb-6">{{ $gateway['description'] }}</p>

                    <!-- Settings -->
                    <div class="space-y-3 mb-6">
                        @foreach($gateway['settings'] as $setting => $value)
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-600 capitalize">{{ str_replace('_', ' ', $setting) }}:</span>
                                <span class="font-medium text-slate-900">
                                    @if($value)
                                        @if(str_contains($value, '••••'))
                                            <span class="text-gray-500">{{ $value }}</span>
                                        @else
                                            {{ $value }}
                                        @endif
                                    @else
                                        <span class="text-red-500">Not configured</span>
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-3">
                        <button onclick="testGateway('{{ $key }}')" 
                                class="flex-1 bg-{{ $gateway['color'] }}-500 text-white py-3 px-4 rounded-xl hover:bg-{{ $gateway['color'] }}-600 transition-colors font-medium text-sm">
                            <i class="fas fa-plug mr-2"></i>Test Connection
                        </button>
                        <button onclick="configureGateway('{{ $key }}')" 
                                class="flex-1 bg-gray-500 text-white py-3 px-4 rounded-xl hover:bg-gray-600 transition-colors font-medium text-sm">
                            <i class="fas fa-cog mr-2"></i>Configure
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Configuration Instructions -->
        <div class="mt-12 bg-white/90 backdrop-blur-xl rounded-2xl shadow-xl border border-white/40 p-8">
            <h3 class="text-2xl font-bold text-slate-900 mb-6 flex items-center">
                <i class="fas fa-info-circle text-blue-500 mr-3"></i>
                Configuration Instructions
            </h3>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Paystack Instructions -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-slate-800 flex items-center">
                        <i class="fas fa-credit-card text-blue-500 mr-2"></i>
                        Paystack Setup
                    </h4>
                    <div class="text-sm text-slate-600 space-y-2">
                        <p>1. Create account at <a href="https://paystack.com" target="_blank" class="text-blue-600 hover:underline">paystack.com</a></p>
                        <p>2. Get your API keys from Settings > API Keys</p>
                        <p>3. Add to your .env file:</p>
                        <div class="bg-gray-100 p-3 rounded-lg font-mono text-xs">
                            PAYSTACK_PUBLIC_KEY=pk_test_xxx<br>
                            PAYSTACK_SECRET_KEY=sk_test_xxx<br>
                            PAYSTACK_MERCHANT_EMAIL=your@email.com
                        </div>
                    </div>
                </div>

                <!-- MNotify Instructions -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-slate-800 flex items-center">
                        <i class="fas fa-sms text-green-500 mr-2"></i>
                        MNotify Setup
                    </h4>
                    <div class="text-sm text-slate-600 space-y-2">
                        <p>1. Create account at <a href="https://mnotify.com" target="_blank" class="text-green-600 hover:underline">mnotify.com</a></p>
                        <p>2. Get your API key from dashboard</p>
                        <p>3. Add to your .env file:</p>
                        <div class="bg-gray-100 p-3 rounded-lg font-mono text-xs">
                            MNOTIFY_API_KEY=your_api_key<br>
                            MNOTIFY_SENDER_ID=your_sender_id
                        </div>
                    </div>
                </div>

                <!-- Email Instructions -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-slate-800 flex items-center">
                        <i class="fas fa-envelope text-purple-500 mr-2"></i>
                        Email Setup
                    </h4>
                    <div class="text-sm text-slate-600 space-y-2">
                        <p>1. Configure SMTP settings</p>
                        <p>2. Use Gmail, Mailgun, or other provider</p>
                        <p>3. Add to your .env file:</p>
                        <div class="bg-gray-100 p-3 rounded-lg font-mono text-xs">
                            MAIL_MAILER=smtp<br>
                            MAIL_HOST=smtp.gmail.com<br>
                            MAIL_PORT=587<br>
                            MAIL_USERNAME=your@email.com<br>
                            MAIL_PASSWORD=your_password
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Configuration Modal -->
<div id="configModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-slate-800">Gateway Configuration</h3>
                    <button onclick="closeConfigModal()" class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors">
                        <i class="fas fa-times text-gray-600"></i>
                    </button>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        <span class="text-blue-800 text-sm">
                            Gateway configuration requires updating your .env file. Please follow the instructions above and restart your application after making changes.
                        </span>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button onclick="closeConfigModal()" class="flex-1 bg-gray-300 text-gray-700 font-semibold py-3 px-6 rounded-xl hover:bg-gray-400 transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function testGateway(gateway) {
    const button = event.target;
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Testing...';
    button.disabled = true;
    
    fetch(`/system-config/${gateway}/test`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        showNotification(data.message, data.success ? 'success' : 'error');
    })
    .catch(error => {
        showNotification('Error testing gateway connection', 'error');
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function configureGateway(gateway) {
    document.getElementById('configModal').classList.remove('hidden');
}

function closeConfigModal() {
    document.getElementById('configModal').classList.add('hidden');
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check' : 'exclamation-triangle'} mr-2"></i>
        ${message}
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Close modal when clicking outside
document.getElementById('configModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeConfigModal();
    }
});
</script>
@endpush
@endsection
