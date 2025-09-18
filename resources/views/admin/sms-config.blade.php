<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold gradient-text">SMS Configuration</h1>
                <p class="text-gray-600 mt-2">Configure and test your Pastech Solutions SMS service integration</p>
            </div>

            <!-- Current Configuration Status -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Current Configuration Status</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <span class="text-sm font-medium text-gray-700">API Key</span>
                            <span class="text-sm {{ config('services.mnotify.api_key') ? 'text-green-600' : 'text-red-600' }}">
                                @if(config('services.mnotify.api_key'))
                                    <i class="fas fa-check-circle mr-1"></i>Configured
                                @else
                                    <i class="fas fa-times-circle mr-1"></i>Not Set
                                @endif
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <span class="text-sm font-medium text-gray-700">Sender ID</span>
                            <span class="text-sm {{ config('services.mnotify.sender_id') ? 'text-green-600' : 'text-red-600' }}">
                                @if(config('services.mnotify.sender_id'))
                                    <i class="fas fa-check-circle mr-1"></i>{{ config('services.mnotify.sender_id') }}
                                @else
                                    <i class="fas fa-times-circle mr-1"></i>Not Set
                                @endif
                            </span>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="p-4 bg-blue-50 rounded-lg">
                            <h3 class="text-sm font-medium text-blue-900 mb-2">Current Balance</h3>
                            <div class="text-2xl font-bold text-blue-600" id="current-balance">
                                {{ number_format($balance['balance'] ?? 0, 2) }} {{ $balance['currency'] ?? 'GHS' }}
                            </div>
                            @if(!$balance['success'])
                                <p class="text-sm text-red-600 mt-1">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>{{ $balance['error'] ?? 'Unable to fetch balance' }}
                                </p>
                            @endif
                        </div>
                        
                        <button onclick="testConnection()" class="w-full px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-sync-alt mr-2" id="test-icon"></i>Test Connection
                        </button>
                    </div>
                </div>
            </div>

            <!-- Configuration Instructions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Setup Instructions</h2>
                
                <div class="prose max-w-none">
                    <ol class="list-decimal list-inside space-y-3 text-gray-700">
                        <li>
                            <strong>Get your Pastech Solutions API credentials:</strong>
                            <ul class="list-disc list-inside ml-6 mt-2 space-y-1">
                                <li>Contact Pastech Solutions to create an account and get your API credentials</li>
                                <li>Get your API Key and Sender ID from your account manager</li>
                                <li>Ensure your account has sufficient SMS credits</li>
                            </ul>
                        </li>
                        
                        <li>
                            <strong>Update your environment file (.env):</strong>
                            <div class="bg-gray-100 rounded-lg p-4 mt-2 font-mono text-sm">
                                MNOTIFY_API_KEY=your_api_key_here<br>
                                MNOTIFY_SENDER_ID=your_sender_id_here
                            </div>
                        </li>
                        
                        <li>
                            <strong>Clear configuration cache:</strong>
                            <div class="bg-gray-100 rounded-lg p-4 mt-2 font-mono text-sm">
                                php artisan config:clear
                            </div>
                        </li>
                        
                        <li>
                            <strong>Test the connection</strong> using the "Test Connection" button above
                        </li>
                    </ol>
                </div>
            </div>

            <!-- API Documentation -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">API Documentation</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-medium text-gray-900 mb-2">Useful Links</h3>
                        <ul class="space-y-2 text-sm">
                            <li>
                                <a href="https://readthedocs.mnotify.com/" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center">
                                    <i class="fas fa-external-link-alt mr-2"></i>MNotify API Documentation
                                </a>
                            </li>
                            <li>
                                <a href="https://readthedocs.mnotify.com/#tag/Reports-and-Stats/operation/balance/sms" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center">
                                    <i class="fas fa-external-link-alt mr-2"></i>Balance API Endpoint
                                </a>
                            </li>
                            <li>
                                <a href="https://mnotify.com/dashboard" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center">
                                    <i class="fas fa-external-link-alt mr-2"></i>MNotify Dashboard
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="font-medium text-gray-900 mb-2">Support</h3>
                        <p class="text-sm text-gray-600 mb-3">
                            If you're having issues with the SMS integration, check the following:
                        </p>
                        <ul class="space-y-1 text-sm text-gray-600">
                            <li>• Ensure your API key is valid and active</li>
                            <li>• Check that your account has sufficient balance</li>
                            <li>• Verify your sender ID is approved</li>
                            <li>• Check the Laravel logs for detailed error messages</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    async function testConnection() {
        const button = event.target;
        const icon = document.getElementById('test-icon');
        const balanceElement = document.getElementById('current-balance');
        
        // Show loading state
        icon.classList.add('fa-spin');
        button.disabled = true;
        button.textContent = 'Testing...';
        
        try {
            const response = await fetch('/api/sms-balance', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                balanceElement.textContent = parseFloat(data.balance).toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' ' + (data.currency || 'GHS');
                
                // Show success message
                showNotification('Connection successful! Balance updated.', 'success');
            } else {
                showNotification('Connection failed: ' + (data.error || 'Unknown error'), 'error');
                console.error('MNotify API Error:', data.error);
            }
        } catch (error) {
            console.error('Error testing connection:', error);
            showNotification('Connection test failed: ' + error.message, 'error');
        } finally {
            // Remove loading state
            icon.classList.remove('fa-spin');
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-sync-alt mr-2"></i>Test Connection';
        }
    }
    
    function showNotification(message, type) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Remove after 5 seconds
        setTimeout(() => {
            notification.remove();
        }, 5000);
    }
    </script>
</x-app-layout>
