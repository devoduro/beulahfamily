@extends('components.app-layout')

@section('title', 'Bulk SMS Campaign')
@section('subtitle', 'Professional messaging platform')

<style>
/* Enhanced SMS Form Animations */
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes pulse-glow {
    0%, 100% {
        box-shadow: 0 0 5px rgba(59, 130, 246, 0.3);
    }
    50% {
        box-shadow: 0 0 20px rgba(59, 130, 246, 0.6);
    }
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.fade-in-up {
    animation: fadeInUp 0.6s ease-out;
}

.pulse-glow {
    animation: pulse-glow 2s infinite;
}

.slide-in-right {
    animation: slideInRight 0.5s ease-out;
}

/* Custom scrollbar for member list */
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Progress step transitions */
.progress-step {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.progress-step.active {
    transform: scale(1.1);
}

/* Recipient card hover effects */
.recipient-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

/* Cost estimation pulse */
.cost-pulse {
    animation: pulse 1s infinite;
}

/* Template card selection effect */
.template-card.selected {
    border-color: #3b82f6;
    background-color: #eff6ff;
    transform: scale(1.02);
}

/* Notification slide animations */
.notification-enter {
    transform: translateX(100%);
    opacity: 0;
}

.notification-enter-active {
    transform: translateX(0);
    opacity: 1;
    transition: all 0.3s ease-out;
}

.notification-exit {
    transform: translateX(0);
    opacity: 1;
}

.notification-exit-active {
    transform: translateX(100%);
    opacity: 0;
    transition: all 0.3s ease-in;
}
</style>

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-indigo-50/30 to-purple-50/30">
    <!-- Enterprise Header -->
    <div class="bg-white border-b border-slate-200/60 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-6">
                    <div class="relative">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-xl">
                            <i class="fas fa-satellite-dish text-white text-2xl"></i>
                        </div>
                        <div class="absolute -top-1 -right-1 w-5 h-5 bg-green-500 rounded-full border-2 border-white animate-pulse"></div>
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-slate-900">Bulk SMS Campaign</h1>
                        <p class="text-slate-600 text-lg">Create and send professional SMS campaigns to your community</p>
                    </div>
                </div>
                
                <!-- Quick Stats -->
                <div class="hidden lg:flex items-center space-x-8">
                    @if($balance['success'])
                    <div class="text-center">
                        <div class="text-2xl font-black text-green-600">{{ $balance['currency'] }} {{ number_format($balance['balance'], 2) }}</div>
                        <div class="text-sm text-slate-500">Available Balance</div>
                    </div>
                    @endif
                    <div class="text-center">
                        <div class="text-2xl font-black text-blue-600">{{ count($members) }}</div>
                        <div class="text-sm text-slate-500">Total Members</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-black text-purple-600" id="campaign-cost">GHS0.00</div>
                        <div class="text-sm text-slate-500">Estimated Cost</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Campaign Builder -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Progress Workflow -->
        <div class="mb-12">
            <div class="flex items-center justify-center">
                <div class="flex items-center space-x-4">
                    <!-- Step 1 -->
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold shadow-lg" id="step-1">
                            <i class="fas fa-edit text-sm"></i>
                        </div>
                        <span class="ml-3 text-sm font-semibold text-slate-700">Compose</span>
                    </div>
                    <div class="w-16 h-1 bg-slate-300 rounded-full" id="progress-1"></div>
                    
                    <!-- Step 2 -->
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-slate-300 rounded-full flex items-center justify-center text-slate-500 font-bold" id="step-2">
                            <i class="fas fa-users text-sm"></i>
                        </div>
                        <span class="ml-3 text-sm font-medium text-slate-500">Audience</span>
                    </div>
                    <div class="w-16 h-1 bg-slate-300 rounded-full" id="progress-2"></div>
                    
                    <!-- Step 3 -->
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-slate-300 rounded-full flex items-center justify-center text-slate-500 font-bold" id="step-3">
                            <i class="fas fa-clock text-sm"></i>
                        </div>
                        <span class="ml-3 text-sm font-medium text-slate-500">Schedule</span>
                    </div>
                    <div class="w-16 h-1 bg-slate-300 rounded-full" id="progress-3"></div>
                    
                    <!-- Step 4 -->
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-slate-300 rounded-full flex items-center justify-center text-slate-500 font-bold" id="step-4">
                            <i class="fas fa-paper-plane text-sm"></i>
                        </div>
                        <span class="ml-3 text-sm font-medium text-slate-500">Send</span>
                    </div>
                </div>
            </div>
        </div>

        <form id="sms-form" class="space-y-8">
            @csrf
            
            <!-- Message Composition -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-8">
                <div class="flex items-center mb-8">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl flex items-center justify-center shadow-lg mr-4">
                        <i class="fas fa-edit text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Compose Message</h2>
                        <p class="text-gray-500 text-sm">Create your SMS content</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Message Content -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-semibold text-gray-800 mb-2">Message Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="title" required 
                                   class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md" 
                                   placeholder="Enter message title for reference">
                        </div>

                        <!-- Template Selection -->
                        <div>
                            <label for="template_id" class="block text-sm font-semibold text-gray-800 mb-2">Use Template (Optional)</label>
                            <select name="template_id" id="template_id" class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md">
                                <option value="">Select a template</option>
                                @foreach($templates as $template)
                                    <option value="{{ $template->id }}" data-message="{{ $template->message }}">
                                        {{ $template->name }} ({{ ucfirst($template->category) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Message Content -->
                        <div>
                            <label for="message" class="block text-sm font-semibold text-gray-800 mb-2">Message Content <span class="text-red-500">*</span></label>
                            <textarea name="message" id="message" rows="6" required 
                                      class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md resize-none" 
                                      placeholder="Type your message here..." maxlength="1600"></textarea>
                            <div class="flex justify-between items-center mt-2">
                                <span class="text-sm text-gray-500">
                                    <span id="char-count">0</span>/1600 characters
                                </span>
                                <span class="text-sm text-gray-500">
                                    <span id="sms-count">0</span> SMS
                                </span>
                            </div>
                        </div>

                        <!-- Sender Name -->
                        <div>
                            <label for="sender_name" class="block text-sm font-semibold text-gray-800 mb-2">Sender Name (Optional)</label>
                            <input type="text" name="sender_name" id="sender_name" maxlength="11"
                                   class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md" 
                                   placeholder="e.g., BeulahFam (max 11 chars)">
                            <p class="text-xs text-gray-500 mt-1">Custom sender name (11 characters max). Leave blank to use default.</p>
                        </div>
                    </div>

                    <!-- Cost Estimation -->
                    <div class="space-y-6">
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200">
                            <h3 class="text-lg font-semibold text-green-900 mb-4 flex items-center">
                                <i class="fas fa-calculator mr-2"></i>
                                Cost Estimation
                            </h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-green-700">Recipients:</span>
                                    <span class="font-semibold text-green-900" id="recipient-count">0</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-green-700">SMS Count:</span>
                                    <span class="font-semibold text-green-900" id="total-sms">0</span>
                                </div>
                                <div class="flex justify-between border-t border-green-200 pt-2">
                                    <span class="text-green-700">Estimated Cost:</span>
                                    <span class="font-bold text-green-900" id="estimated-cost">GHS 0.00</span>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200">
                            <h3 class="text-lg font-semibold text-blue-900 mb-4">Quick Actions</h3>
                            <div class="space-y-3">
                                <button type="button" id="preview-btn" class="w-full px-4 py-2 bg-blue-100 text-blue-700 rounded-xl hover:bg-blue-200 transition-colors text-sm font-medium">
                                    <i class="fas fa-eye mr-2"></i>Preview Message
                                </button>
                                <button type="button" id="save-template-btn" class="w-full px-4 py-2 bg-purple-100 text-purple-700 rounded-xl hover:bg-purple-200 transition-colors text-sm font-medium">
                                    <i class="fas fa-save mr-2"></i>Save as Template
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Next-Gen Recipient Selection System -->
            <div class="relative bg-gradient-to-br from-slate-50 via-white to-blue-50/50 rounded-3xl shadow-2xl border border-slate-200/60 overflow-hidden">
                <!-- Animated Background Pattern -->
                <div class="absolute inset-0 opacity-5">
                    <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25% 25%, #3b82f6 2px, transparent 2px), radial-gradient(circle at 75% 75%, #8b5cf6 2px, transparent 2px); background-size: 50px 50px;"></div>
                </div>
                
                <!-- Header with Progress Indicator -->
                <div class="relative p-8 lg:p-12">
                    <div class="text-center mb-10">
                        <div class="inline-flex items-center space-x-4 mb-8">
                            <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-600 to-purple-600 rounded-2xl shadow-xl">
                                <i class="fas fa-users-cog text-white text-2xl"></i>
                            </div>
                            <div class="text-left">
                                <h2 class="text-3xl font-black text-slate-900">Audience Selection</h2>
                                <p class="text-slate-600 text-lg">Choose your messaging strategy</p>
                            </div>
                        </div>
                        
                        <!-- Progress Steps -->
                        <div class="flex items-center justify-center space-x-4 mb-8">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-bold">1</div>
                                <span class="ml-2 text-sm font-medium text-slate-700">Select Type</span>
                            </div>
                            <div class="w-12 h-0.5 bg-slate-300"></div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-slate-300 rounded-full flex items-center justify-center text-slate-500 text-sm font-bold">2</div>
                                <span class="ml-2 text-sm font-medium text-slate-500">Configure</span>
                            </div>
                            <div class="w-12 h-0.5 bg-slate-300"></div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-slate-300 rounded-full flex items-center justify-center text-slate-500 text-sm font-bold">3</div>
                                <span class="ml-2 text-sm font-medium text-slate-500">Review</span>
                            </div>
                        </div>
                    </div>

                    <!-- Smart Selection Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-10">
                        <!-- Broadcast Option -->
                        <div class="recipient-card group cursor-pointer" data-type="all" data-category="broadcast">
                            <input type="radio" name="recipient_type" value="all" class="hidden">
                            <div class="relative bg-white border-2 border-slate-200 rounded-2xl p-6 transition-all duration-500 group-hover:border-blue-400 group-hover:shadow-xl group-hover:-translate-y-1">
                                <!-- Selection Indicator -->
                                <div class="absolute -top-2 -right-2 w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center opacity-0 transition-all duration-300 transform scale-75">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                                
                                <!-- Icon -->
                                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-broadcast-tower text-white text-xl"></i>
                                </div>
                                
                                <!-- Content -->
                                <h3 class="text-lg font-bold text-slate-900 mb-2">Broadcast</h3>
                                <p class="text-slate-600 text-sm mb-4">Send to everyone in your church community</p>
                                
                                <!-- Stats -->
                                <div class="flex items-center justify-between">
                                    <span class="text-2xl font-black text-blue-600">{{ count($members) }}</span>
                                    <span class="text-xs text-slate-500 bg-slate-100 px-2 py-1 rounded-full">All Members</span>
                                </div>
                                
                                <!-- Hover Effect -->
                                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-indigo-600/5 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>
                        </div>

                        <!-- Active Members Option -->
                        <div class="recipient-card group cursor-pointer" data-type="members" data-category="active">
                            <input type="radio" name="recipient_type" value="members" class="hidden">
                            <div class="relative bg-white border-2 border-slate-200 rounded-2xl p-6 transition-all duration-500 group-hover:border-emerald-400 group-hover:shadow-xl group-hover:-translate-y-1">
                                <!-- Selection Indicator -->
                                <div class="absolute -top-2 -right-2 w-8 h-8 bg-emerald-600 rounded-full flex items-center justify-center opacity-0 transition-all duration-300 transform scale-75">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                                
                                <!-- Icon -->
                                <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-user-check text-white text-xl"></i>
                                </div>
                                
                                <!-- Content -->
                                <h3 class="text-lg font-bold text-slate-900 mb-2">Active Only</h3>
                                <p class="text-slate-600 text-sm mb-4">Target engaged and active members</p>
                                
                                <!-- Stats -->
                                <div class="flex items-center justify-between">
                                    <span class="text-2xl font-black text-emerald-600">{{ count($members) }}</span>
                                    <span class="text-xs text-slate-500 bg-slate-100 px-2 py-1 rounded-full">Active</span>
                                </div>
                                
                                <!-- Hover Effect -->
                                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-green-600/5 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>
                        </div>

                        <!-- Location-Based Option -->
                        <div class="recipient-card group cursor-pointer" data-type="chapter" data-category="location">
                            <input type="radio" name="recipient_type" value="chapter" class="hidden">
                            <div class="relative bg-white border-2 border-slate-200 rounded-2xl p-6 transition-all duration-500 group-hover:border-orange-400 group-hover:shadow-xl group-hover:-translate-y-1">
                                <!-- Selection Indicator -->
                                <div class="absolute -top-2 -right-2 w-8 h-8 bg-orange-600 rounded-full flex items-center justify-center opacity-0 transition-all duration-300 transform scale-75">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                                
                                <!-- Icon -->
                                <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-map-marked-alt text-white text-xl"></i>
                                </div>
                                
                                <!-- Content -->
                                <h3 class="text-lg font-bold text-slate-900 mb-2">By Location</h3>
                                <p class="text-slate-600 text-sm mb-4">Target specific chapters or branches</p>
                                
                                <!-- Stats -->
                                <div class="flex items-center justify-between">
                                    <span class="text-2xl font-black text-orange-600">{{ count($chapters) }}</span>
                                    <span class="text-xs text-slate-500 bg-slate-100 px-2 py-1 rounded-full">Chapters</span>
                                </div>
                                
                                <!-- Hover Effect -->
                                <div class="absolute inset-0 bg-gradient-to-br from-orange-500/5 to-red-500/5 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>
                        </div>

                        <!-- Smart Filter Option -->
                        <div class="recipient-card group cursor-pointer" data-type="custom" data-category="advanced">
                            <input type="radio" name="recipient_type" value="custom" class="hidden">
                            <div class="relative bg-white border-2 border-slate-200 rounded-2xl p-6 transition-all duration-500 group-hover:border-purple-400 group-hover:shadow-xl group-hover:-translate-y-1">
                                <!-- Selection Indicator -->
                                <div class="absolute -top-2 -right-2 w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center opacity-0 transition-all duration-300 transform scale-75">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                                
                                <!-- Icon -->
                                <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-filter text-white text-xl"></i>
                                </div>
                                
                                <!-- Content -->
                                <h3 class="text-lg font-bold text-slate-900 mb-2">Smart Filter</h3>
                                <p class="text-slate-600 text-sm mb-4">Advanced targeting with multiple criteria</p>
                                
                                <!-- Stats -->
                                <div class="flex items-center justify-between">
                                    <span class="text-2xl font-black text-purple-600">∞</span>
                                    <span class="text-xs text-slate-500 bg-slate-100 px-2 py-1 rounded-full">Custom</span>
                                </div>
                                
                                <!-- Hover Effect -->
                                <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-pink-500/5 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Live Preview Panel -->
                    <div class="bg-gradient-to-r from-slate-50 to-blue-50 rounded-2xl p-6 border border-slate-200/60">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-eye text-white"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900">Live Preview</h4>
                                    <p class="text-slate-600 text-sm" id="selection-description">Select an option above to see your audience</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-4xl font-black bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent" id="recipient-count">0</div>
                                <div class="text-sm text-slate-500">Recipients</div>
                            </div>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="mt-4">
                            <div class="flex justify-between text-xs text-slate-500 mb-1">
                                <span>Audience Size</span>
                                <span id="percentage-display">0%</span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-blue-600 to-purple-600 h-2 rounded-full transition-all duration-500" id="progress-bar" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                    <!-- Chapter Selection -->
                    <div id="chapter-selection" class="hidden">
                        <label for="chapter" class="block text-sm font-semibold text-gray-800 mb-2">Select Chapter</label>
                        <select name="chapter" id="chapter" class="block w-full md:w-1/2 px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md">
                            <option value="">Select Chapter</option>
                            <option value="ACCRA">ACCRA</option>
                            <option value="KUMASI">KUMASI</option>
                            <option value="NEW JESSY">NEW JESSY</option>
                        </select>
                    </div>

                <!-- Ultra-Modern Custom Selection -->
                <div id="custom-selection" class="hidden mt-8">
                    <div class="bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 rounded-3xl p-8 border border-purple-200/50">
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">Smart Member Selection</h3>
                                <p class="text-gray-600">Use advanced filters to target your audience precisely</p>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-gray-500">Filtered</div>
                                <div class="text-3xl font-bold text-purple-600" id="filtered-count">{{ count($members) }}</div>
                            </div>
                        </div>
                        
                        <!-- Ultra-Modern Filter Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700">🔍 Search Members</label>
                                <input type="text" id="member-search" placeholder="Type to search..." 
                                       class="w-full px-4 py-3 bg-white/70 border-2 border-purple-200 rounded-2xl focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all text-sm font-medium shadow-sm">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700">📍 Chapter</label>
                                <select id="chapter-filter" class="w-full px-4 py-3 bg-white/70 border-2 border-purple-200 rounded-2xl focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all text-sm font-medium shadow-sm">
                                    <option value="">All Chapters</option>
                                    @foreach($chapters as $chapter)
                                        <option value="{{ $chapter }}">{{ $chapter }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700">👥 Gender</label>
                                <select id="gender-filter" class="w-full px-4 py-3 bg-white/70 border-2 border-purple-200 rounded-2xl focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all text-sm font-medium shadow-sm">
                                    <option value="">All Genders</option>
                                    @foreach($genders as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700">💍 Marital Status</label>
                                <select id="marital-filter" class="w-full px-4 py-3 bg-white/70 border-2 border-purple-200 rounded-2xl focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all text-sm font-medium shadow-sm">
                                    <option value="">All Status</option>
                                    @foreach($maritalStatuses as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700">🎂 Age Range</label>
                                <div class="flex space-x-2">
                                    <input type="number" id="min-age" placeholder="Min" min="0" max="120"
                                           class="w-full px-3 py-3 bg-white/70 border-2 border-purple-200 rounded-2xl focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all text-sm font-medium shadow-sm">
                                    <input type="number" id="max-age" placeholder="Max" min="0" max="120"
                                           class="w-full px-3 py-3 bg-white/70 border-2 border-purple-200 rounded-2xl focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all text-sm font-medium shadow-sm">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Member Selection Area -->
                        <div class="bg-gray-50 rounded-2xl p-4 max-h-96 overflow-y-auto">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-sm font-medium text-gray-700">
                                    <span id="filtered-count">{{ count($members) }}</span> members found
                                </span>
                                <div class="space-x-2">
                                    <button type="button" id="select-all-filtered" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Select All Filtered</button>
                                    <button type="button" id="clear-all-btn" class="text-sm text-red-600 hover:text-red-800 font-medium">Clear All</button>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3" id="member-list">
                                @foreach($members as $member)
                                <label class="member-card flex items-center p-3 hover:bg-white rounded-xl transition-all duration-200 border border-transparent hover:border-blue-200 hover:shadow-sm cursor-pointer"
                                       data-name="{{ strtolower(e($member->first_name . ' ' . $member->last_name)) }}"
                                       data-chapter="{{ e($member->chapter) }}"
                                       data-gender="{{ e($member->gender) }}"
                                       data-marital="{{ e($member->marital_status) }}"
                                       data-age="{{ $member->date_of_birth ? \Carbon\Carbon::parse($member->date_of_birth)->age : 0 }}">
                                    <input type="checkbox" name="custom_recipients[]" value="{{ $member->id }}" 
                                           class="mr-3 text-blue-600 focus:ring-blue-500 rounded member-checkbox">
                                    
                                    <div class="flex items-center space-x-3 flex-1 min-w-0">
                                        <!-- Member Photo -->
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full flex items-center justify-center flex-shrink-0">
                                            @if($member->photo)
                                                <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ e($member->first_name) }}" class="w-10 h-10 rounded-full object-cover">
                                            @else
                                                <i class="fas fa-user text-white text-sm"></i>
                                            @endif
                                        </div>
                                        
                                        <!-- Member Info -->
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ e($member->first_name) }} {{ e($member->last_name) }}
                                            </p>
                                            <div class="flex items-center space-x-2 text-xs text-gray-500">
                                                <span>{{ e($member->chapter) }}</span>
                                                @if($member->gender)
                                                    <span>•</span>
                                                    <span class="capitalize">{{ e($member->gender) }}</span>
                                                @endif
                                                @if($member->date_of_birth)
                                                    <span>•</span>
                                                    <span>{{ \Carbon\Carbon::parse($member->date_of_birth)->age }}y</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-400">{{ e($member->phone) }}</p>
                                        </div>
                                        
                                        <!-- Status Badges -->
                                        <div class="flex flex-col space-y-1">
                                            @if($member->marital_status)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                    @if($member->marital_status === 'married') bg-green-100 text-green-800
                                                    @elseif($member->marital_status === 'single') bg-blue-100 text-blue-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ ucfirst(e($member->marital_status)) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Selection Summary -->
                        <div class="mt-4 flex justify-between items-center">
                            <span class="text-sm text-gray-600">
                                <span id="selected-count">0</span> members selected
                            </span>
                            <div class="flex items-center space-x-4">
                                <div class="text-xs text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Use filters to narrow down your selection
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scheduling Options -->
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/30 p-8">
                <div class="flex items-center mb-8">
                    <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-500 rounded-2xl flex items-center justify-center shadow-lg mr-4">
                        <i class="fas fa-clock text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Delivery Options</h2>
                        <p class="text-gray-500 text-sm">Choose when to send your message</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="delivery_option" value="now" checked class="mr-2 text-blue-600 focus:ring-blue-500">
                            <span class="text-gray-700 font-medium">Send Now</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="delivery_option" value="schedule" class="mr-2 text-blue-600 focus:ring-blue-500">
                            <span class="text-gray-700 font-medium">Schedule for Later</span>
                        </label>
                    </div>

                    <div id="schedule-options" class="hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="schedule_date" class="block text-sm font-semibold text-gray-800 mb-2">Date</label>
                                <input type="date" name="schedule_date" id="schedule_date" 
                                       class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md">
                            </div>
                            <div>
                                <label for="schedule_time" class="block text-sm font-semibold text-gray-800 mb-2">Time</label>
                                <input type="time" name="schedule_time" id="schedule_time" 
                                       class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-gray-200/50 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium shadow-sm hover:shadow-md">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" id="send-btn" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 text-white font-bold rounded-2xl hover:from-purple-700 hover:via-indigo-700 hover:to-blue-700 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-paper-plane mr-3"></i>
                    <span id="send-btn-text">Send SMS</span>
                    <div class="ml-3 w-6 h-6 border-2 border-white border-t-transparent rounded-full animate-spin hidden" id="loading-spinner"></div>
                </button>
                <p class="text-sm text-gray-600 mt-4">
                    <i class="fas fa-shield-alt mr-1"></i>
                    Messages are sent securely via MNotify
                </p>
            </div>
        </form>
    </div>
</div>

<script>
// Clean start - using event listeners instead of onclick

document.addEventListener('DOMContentLoaded', function() {
    // Character and SMS count
    const messageTextarea = document.getElementById('message');
    const charCount = document.getElementById('char-count');
    const smsCount = document.getElementById('sms-count');
    
    function updateCounts() {
        const length = messageTextarea.value.length;
        const smsCountValue = Math.ceil(length / 160) || 0;
        
        charCount.textContent = length;
        smsCount.textContent = smsCountValue;
        
        updateCostEstimation();
    }
    
    messageTextarea.addEventListener('input', updateCounts);

    // Advanced Template Selection with Animation
    document.getElementById('template_id').addEventListener('change', function() {
        if (this.value) {
            const selectedOption = this.options[this.selectedIndex];
            const message = selectedOption.dataset.message;
            if (message) {
                // Smooth transition animation
                messageTextarea.style.opacity = '0.5';
                messageTextarea.style.transform = 'scale(0.98)';
                
                setTimeout(() => {
                    messageTextarea.value = message;
                    updateCounts();
                    validateForm();
                    
                    // Restore animation
                    messageTextarea.style.opacity = '1';
                    messageTextarea.style.transform = 'scale(1)';
                    messageTextarea.style.transition = 'all 0.3s ease-out';
                    
                    // Flash success indicator
                    showNotification('Template loaded successfully!', 'success');
                }, 200);
            }
        }
    });

    // Enhanced Character and SMS Counting with Visual Feedback
    function updateCounts() {
        const length = messageTextarea.value.length;
        const smsCountValue = Math.ceil(length / 160) || 0;
        
        // Update counters with animation
        animateCounterUpdate(charCount, length);
        animateCounterUpdate(smsCount, smsCountValue);
        
        // Visual feedback based on length
        updateMessageLengthIndicator(length);
        
        // Update global state
        window.smsState = window.smsState || {};
        window.smsState.messageLength = length;
        window.smsState.smsCount = smsCountValue;
        
        updateCostEstimation();
        validateForm();
    }
    
    function animateCounterUpdate(element, newValue) {
        if (!element) return;
        
        const currentValue = parseInt(element.textContent) || 0;
        if (currentValue === newValue) return;
        
        element.style.transform = 'scale(1.1)';
        element.style.color = '#3b82f6';
        
        setTimeout(() => {
            element.textContent = newValue;
            element.style.transform = 'scale(1)';
            element.style.transition = 'all 0.2s ease-out';
            
            setTimeout(() => {
                element.style.color = '';
            }, 200);
        }, 100);
    }
    
    function updateMessageLengthIndicator(length) {
        const maxLength = 1600;
        const percentage = (length / maxLength) * 100;
        
        // Color coding based on usage
        let colorClass = 'text-green-600';
        if (percentage > 80) colorClass = 'text-red-600';
        else if (percentage > 60) colorClass = 'text-orange-600';
        
        charCount.className = `font-bold ${colorClass}`;
        
        // Add warning for long messages
        if (length > 160) {
            const smsWarning = document.getElementById('sms-warning') || createSMSWarning();
            smsWarning.textContent = `This message will be sent as ${Math.ceil(length / 160)} SMS parts`;
            smsWarning.style.display = 'block';
        } else {
            const smsWarning = document.getElementById('sms-warning');
            if (smsWarning) smsWarning.style.display = 'none';
        }
    }
    
    function createSMSWarning() {
        const warning = document.createElement('div');
        warning.id = 'sms-warning';
        warning.className = 'text-xs text-orange-600 mt-1 hidden';
        messageTextarea.parentNode.appendChild(warning);
        return warning;
    }

    // Advanced Form Validation System
    function validateForm() {
        const title = document.getElementById('title').value.trim();
        const message = messageTextarea.value.trim();
        const recipientType = document.querySelector('input[name="recipient_type"]:checked');
        
        let isValid = true;
        let errors = [];
        
        // Title validation
        if (!title) {
            errors.push('Message title is required');
            highlightField('title', false);
            isValid = false;
        } else {
            highlightField('title', true);
        }
        
        // Message validation
        if (!message) {
            errors.push('Message content is required');
            highlightField('message', false);
            isValid = false;
        } else if (message.length > 1600) {
            errors.push('Message is too long (max 1600 characters)');
            highlightField('message', false);
            isValid = false;
        } else {
            highlightField('message', true);
        }
        
        // Recipient validation
        if (!recipientType) {
            errors.push('Please select an audience type');
            isValid = false;
        }
        
        // Update send button state
        const sendBtn = document.getElementById('send-btn');
        if (sendBtn) {
            sendBtn.disabled = !isValid;
            sendBtn.style.opacity = isValid ? '1' : '0.6';
        }
        
        // Show/hide errors
        displayValidationErrors(errors);
        
        return isValid;
    }
    
    function highlightField(fieldId, isValid) {
        const field = document.getElementById(fieldId);
        if (!field) return;
        
        field.classList.remove('border-red-300', 'border-green-300');
        field.classList.add(isValid ? 'border-green-300' : 'border-red-300');
        
        // Add shake animation for errors
        if (!isValid) {
            field.style.animation = 'shake 0.5s ease-in-out';
            setTimeout(() => {
                field.style.animation = '';
            }, 500);
        }
    }
    
    function displayValidationErrors(errors) {
        let errorContainer = document.getElementById('validation-errors');
        
        if (errors.length === 0) {
            if (errorContainer) errorContainer.remove();
            return;
        }
        
        if (!errorContainer) {
            errorContainer = document.createElement('div');
            errorContainer.id = 'validation-errors';
            errorContainer.className = 'bg-red-50 border border-red-200 rounded-xl p-4 mb-6';
            document.getElementById('sms-form').prepend(errorContainer);
        }
        
        errorContainer.innerHTML = `
            <div class="flex items-center mb-2">
                <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                <h4 class="text-red-800 font-semibold">Please fix the following errors:</h4>
            </div>
            <ul class="text-red-700 text-sm space-y-1">
                ${errors.map(error => `<li>• ${error}</li>`).join('')}
            </ul>
        `;
    }
    
    // Elegant Notification System
    function showNotification(message, type = 'info', duration = 3000) {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-xl shadow-lg transform translate-x-full transition-all duration-300 ${getNotificationClasses(type)}`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${getNotificationIcon(type)} mr-3"></i>
                <span class="font-medium">${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white/80 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Slide in animation
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        // Auto remove
        setTimeout(() => {
            notification.style.transform = 'translateX(full)';
            setTimeout(() => notification.remove(), 300);
        }, duration);
    }
    
    function getNotificationClasses(type) {
        const classes = {
            success: 'bg-green-500 text-white',
            error: 'bg-red-500 text-white',
            warning: 'bg-orange-500 text-white',
            info: 'bg-blue-500 text-white'
        };
        return classes[type] || classes.info;
    }
    
    function getNotificationIcon(type) {
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            warning: 'fa-exclamation-triangle',
            info: 'fa-info-circle'
        };
        return icons[type] || icons.info;
    }

    // SENIOR DEVELOPER SOLUTION: Bulletproof Recipient Selection System
    console.log('🚀 Initializing Bulletproof Recipient Selection System...');
    
    const totalMembers = @json(count($members));
    let selectedType = null;
    
    // Robust initialization with multiple fallback strategies
    function initializeRecipientSelection() {
        console.log('📋 Setting up recipient cards...');
        
        // Strategy 1: Direct class selector
        let cards = document.querySelectorAll('.recipient-card');
        console.log(`Found ${cards.length} cards with .recipient-card`);
        
        // Strategy 2: Fallback to data attribute selector
        if (cards.length === 0) {
            cards = document.querySelectorAll('[data-type]');
            console.log(`Fallback: Found ${cards.length} cards with data-type`);
        }
        
        // Strategy 3: Manual selection by container
        if (cards.length === 0) {
            const container = document.querySelector('.grid.grid-cols-1.md\\:grid-cols-2.xl\\:grid-cols-4');
            if (container) {
                cards = container.children;
                console.log(`Manual: Found ${cards.length} cards in container`);
            }
        }
        
        if (cards.length === 0) {
            console.error('❌ No recipient cards found! DOM structure issue.');
            return;
        }
        
        // Attach events to each card with robust error handling
        Array.from(cards).forEach((card, index) => {
            console.log(`🎯 Setting up card ${index}:`, {
                type: card.dataset.type,
                category: card.dataset.category,
                hasRadio: !!card.querySelector('input[type="radio"]')
            });
            
            // Multiple event binding strategies for maximum compatibility
            bindCardEvents(card, index);
        });
        
        console.log('✅ Recipient selection system initialized successfully!');
    }
    
    // Robust event binding with multiple strategies
    function bindCardEvents(card, index) {
        const type = card.dataset.type;
        
        if (!type) {
            console.warn(`⚠️ Card ${index} missing data-type attribute`);
            return;
        }
        
        // Strategy 1: Standard click event
        card.addEventListener('click', function(e) {
            handleCardSelection(this, e);
        });
        
        // Strategy 2: Keyboard accessibility
        card.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                handleCardSelection(this, e);
            }
        });
        
        // Strategy 3: Touch events for mobile
        card.addEventListener('touchend', function(e) {
            e.preventDefault();
            handleCardSelection(this, e);
        });
        
        // Make card focusable for accessibility
        card.setAttribute('tabindex', '0');
        card.setAttribute('role', 'button');
        card.setAttribute('aria-label', `Select ${type} recipients`);
        
        console.log(`✅ Events bound to card ${index} (${type})`);
    }
    
    // Centralized card selection handler
    function handleCardSelection(card, event) {
        event.preventDefault();
        event.stopPropagation();
        
        const type = card.dataset.type;
        const category = card.dataset.category;
        
        console.log(`🎯 Card selected: ${type} (${category})`);
        
        // Find radio button with multiple strategies
        let radio = card.querySelector('input[type="radio"]');
        if (!radio) {
            radio = card.querySelector('input[name="recipient_type"]');
        }
        if (!radio) {
            radio = card.querySelector(`input[value="${type}"]`);
        }
        
        if (!radio) {
            console.error(`❌ No radio button found for card: ${type}`);
            return;
        }
        
        // Update selection state
        selectedType = type;
        radio.checked = true;
        
        // Visual feedback
        updateCardVisuals(type);
        updateProgressSteps(type);
        toggleConfigurationSections(type);
        updateLivePreview(type, category);
        
        console.log(`✅ Selection completed: ${type}`);
        
        // Trigger change event for other listeners
        radio.dispatchEvent(new Event('change', { bubbles: true }));
    }
    
    // Robust visual update system
    function updateCardVisuals(selectedType) {
        console.log(`🎨 Updating visuals for: ${selectedType}`);
        
        document.querySelectorAll('.recipient-card').forEach(card => {
            const type = card.dataset.type;
            const indicator = card.querySelector('.absolute.-top-2, .absolute');
            const cardDiv = card.querySelector('.relative');
            
            if (type === selectedType) {
                // Activate selected card
                if (indicator) {
                    indicator.classList.remove('opacity-0', 'scale-75');
                    indicator.classList.add('opacity-100', 'scale-100');
                }
                if (cardDiv) {
                    cardDiv.classList.remove('border-slate-200');
                    const colorMap = {
                        'all': 'border-blue-400',
                        'members': 'border-emerald-400', 
                        'chapter': 'border-orange-400',
                        'custom': 'border-purple-400'
                    };
                    cardDiv.classList.add(colorMap[type] || 'border-blue-400');
                }
            } else {
                // Reset other cards
                if (indicator) {
                    indicator.classList.remove('opacity-100', 'scale-100');
                    indicator.classList.add('opacity-0', 'scale-75');
                }
                if (cardDiv) {
                    cardDiv.classList.remove('border-blue-400', 'border-emerald-400', 'border-orange-400', 'border-purple-400');
                    cardDiv.classList.add('border-slate-200');
                }
            }
        });
    }
    
    // Initialize the system
    initializeRecipientSelection();
    
    // Update progress steps
    function updateProgressSteps(type) {
        const steps = document.querySelectorAll('[class*="w-8 h-8"]');
        steps.forEach((step, index) => {
            if (index === 0) { // Step 1 - always active when selection is made
                step.classList.remove('bg-slate-300');
                step.classList.add('bg-blue-600');
                step.classList.remove('text-slate-500');
                step.classList.add('text-white');
            } else if (index === 1 && (type === 'chapter' || type === 'custom')) { // Step 2 - active for configurable options
                step.classList.remove('bg-slate-300');
                step.classList.add('bg-blue-600');
                step.classList.remove('text-slate-500');
                step.classList.add('text-white');
            }
        });
    }
    
    // Toggle configuration sections
    function toggleConfigurationSections(type) {
        const chapterSection = document.getElementById('chapter-selection');
        const customSection = document.getElementById('custom-selection');
        
        // Hide all sections first
        [chapterSection, customSection].forEach(section => {
            if (section) {
                section.classList.add('hidden');
            }
        });
        
        // Show relevant section with animation
        if (type === 'chapter' && chapterSection) {
            setTimeout(() => {
                chapterSection.classList.remove('hidden');
                chapterSection.style.opacity = '0';
                chapterSection.style.transform = 'translateY(10px)';
                setTimeout(() => {
                    chapterSection.style.opacity = '1';
                    chapterSection.style.transform = 'translateY(0)';
                    chapterSection.style.transition = 'all 0.3s ease-out';
                }, 50);
            }, 100);
        } else if (type === 'custom' && customSection) {
            setTimeout(() => {
                customSection.classList.remove('hidden');
                customSection.style.opacity = '0';
                customSection.style.transform = 'translateY(10px)';
                setTimeout(() => {
                    customSection.style.opacity = '1';
                    customSection.style.transform = 'translateY(0)';
                    customSection.style.transition = 'all 0.3s ease-out';
                }, 50);
            }, 100);
        }
    }
    
    // Update live preview with advanced animations
    function updateLivePreview(type, category) {
        const recipientCountEl = document.getElementById('recipient-count');
        const descriptionEl = document.getElementById('selection-description');
        const progressBar = document.getElementById('progress-bar');
        const percentageDisplay = document.getElementById('percentage-display');
        
        let count = 0;
        let description = '';
        let percentage = 0;
        
        switch(type) {
            case 'all':
                count = totalMembers;
                description = 'Broadcasting to entire church community';
                percentage = 100;
                break;
            case 'members':
                count = totalMembers;
                description = 'Targeting active and engaged members';
                percentage = 100;
                break;
            case 'chapter':
                count = 0;
                description = 'Select a chapter to see member count';
                percentage = 0;
                break;
            case 'custom':
                count = 0;
                description = 'Configure filters to see targeted audience';
                percentage = 0;
                break;
        }
        
        // Animate count change
        if (recipientCountEl) {
            recipientCountEl.style.transform = 'scale(0.8)';
            recipientCountEl.style.opacity = '0.5';
            setTimeout(() => {
                recipientCountEl.textContent = count;
                recipientCountEl.style.transform = 'scale(1)';
                recipientCountEl.style.opacity = '1';
                recipientCountEl.style.transition = 'all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1)';
            }, 150);
        }
        
        // Update description
        if (descriptionEl) {
            descriptionEl.style.opacity = '0.5';
            setTimeout(() => {
                descriptionEl.textContent = description;
                descriptionEl.style.opacity = '1';
                descriptionEl.style.transition = 'opacity 0.3s ease-in-out';
            }, 150);
        }
        
        // Animate progress bar
        if (progressBar && percentageDisplay) {
            setTimeout(() => {
                progressBar.style.width = percentage + '%';
                percentageDisplay.textContent = percentage + '%';
            }, 200);
        }
    }
    
    console.log('Next-Gen Recipient Selection System initialized successfully!');

    // Chapter selection
    document.getElementById('chapter').addEventListener('change', updateCostEstimation);

    // Custom recipients
    const memberCheckboxes = document.querySelectorAll('.member-checkbox');
    const selectedCount = document.getElementById('selected-count');
    
    function updateSelectedCount() {
        const checked = document.querySelectorAll('.member-checkbox:checked').length;
        selectedCount.textContent = checked;
        updateCostEstimation();
    }
    
    memberCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    // Select/Clear all buttons
    document.getElementById('select-all-btn').addEventListener('click', function() {
        const visibleCheckboxes = document.querySelectorAll('.member-checkbox:not([style*="display: none"])');
        visibleCheckboxes.forEach(cb => cb.checked = true);
        updateSelectedCount();
    });
    
    document.getElementById('clear-all-btn').addEventListener('click', function() {
        memberCheckboxes.forEach(cb => cb.checked = false);
        updateSelectedCount();
    });

    // Enhanced filtering system
    const memberCards = document.querySelectorAll('.member-card');
    const filteredCount = document.getElementById('filtered-count');
    
    function applyFilters() {
        const searchTerm = document.getElementById('member-search').value.toLowerCase();
        const chapterFilter = document.getElementById('chapter-filter').value;
        const genderFilter = document.getElementById('gender-filter').value;
        const maritalFilter = document.getElementById('marital-filter').value;
        const minAge = parseInt(document.getElementById('min-age').value) || 0;
        const maxAge = parseInt(document.getElementById('max-age').value) || 999;
        
        let visibleCount = 0;
        
        memberCards.forEach(card => {
            const name = card.dataset.name;
            const chapter = card.dataset.chapter;
            const gender = card.dataset.gender;
            const marital = card.dataset.marital;
            const age = parseInt(card.dataset.age) || 0;
            
            let shouldShow = true;
            
            // Search filter
            if (searchTerm && !name.includes(searchTerm) && !chapter.toLowerCase().includes(searchTerm)) {
                shouldShow = false;
            }
            
            // Chapter filter
            if (chapterFilter && chapter !== chapterFilter) {
                shouldShow = false;
            }
            
            // Gender filter
            if (genderFilter && gender !== genderFilter) {
                shouldShow = false;
            }
            
            // Marital status filter
            if (maritalFilter && marital !== maritalFilter) {
                shouldShow = false;
            }
            
            // Age filter
            if (age < minAge || age > maxAge) {
                shouldShow = false;
            }
            
            if (shouldShow) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
                // Uncheck hidden members
                const checkbox = card.querySelector('.member-checkbox');
                checkbox.checked = false;
            }
        });
        
        filteredCount.textContent = visibleCount;
        updateSelectedCount();
    }
    
    // Auto-apply filters on input
    document.getElementById('member-search').addEventListener('input', applyFilters);
    document.getElementById('chapter-filter').addEventListener('change', applyFilters);
    document.getElementById('gender-filter').addEventListener('change', applyFilters);
    document.getElementById('marital-filter').addEventListener('change', applyFilters);
    document.getElementById('min-age').addEventListener('input', applyFilters);
    document.getElementById('max-age').addEventListener('input', applyFilters);
    
    // Apply filters button
    document.getElementById('apply-filters').addEventListener('click', applyFilters);
    
    // Select all filtered members
    document.getElementById('select-all-filtered').addEventListener('click', function() {
        const visibleCheckboxes = document.querySelectorAll('.member-card:not([style*="display: none"]) .member-checkbox');
        visibleCheckboxes.forEach(cb => cb.checked = true);
        updateSelectedCount();
    });

    // Delivery options
    document.querySelectorAll('input[name="delivery_option"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const scheduleOptions = document.getElementById('schedule-options');
            const sendBtnText = document.getElementById('send-btn-text');
            
            if (this.value === 'schedule') {
                scheduleOptions.classList.remove('hidden');
                sendBtnText.textContent = 'Schedule SMS';
            } else {
                scheduleOptions.classList.add('hidden');
                sendBtnText.textContent = 'Send SMS';
            }
        });
    });

    // Advanced Cost Estimation with Real-time Updates
    function updateCostEstimation() {
        const recipientType = document.querySelector('input[name="recipient_type"]:checked')?.value;
        let recipientCount = 0;
        
        // Calculate recipient count based on selection type
        if (recipientType === 'all' || recipientType === 'members') {
            recipientCount = @json($members->count());
        } else if (recipientType === 'chapter') {
            const selectedChapter = document.getElementById('chapter').value;
            if (selectedChapter) {
                const chapterCounts = @json($members->groupBy('chapter')->map->count());
                recipientCount = chapterCounts[selectedChapter] || 0;
            }
        } else if (recipientType === 'custom') {
            recipientCount = document.querySelectorAll('.member-checkbox:checked').length;
        }
        
        const messageLength = messageTextarea.value.length;
        const smsCountValue = Math.ceil(messageLength / 160) || 1;
        const totalSms = recipientCount * smsCountValue;
        const costPerSms = 0.05; // GHS 0.05 per SMS
        const estimatedCost = totalSms * costPerSms;
        
        // Animate cost updates
        animateCostUpdate('recipient-count', recipientCount);
        animateCostUpdate('total-sms', totalSms);
        animateCostUpdate('estimated-cost', `GHS ${estimatedCost.toFixed(2)}`);
        animateCostUpdate('campaign-cost', `$${(estimatedCost * 0.27).toFixed(2)}`); // USD conversion
        
        // Update progress indicators
        updateProgressIndicators(recipientCount, totalSms, estimatedCost);
        
        // Store in global state
        window.smsState = window.smsState || {};
        window.smsState.recipientCount = recipientCount;
        window.smsState.totalSms = totalSms;
        window.smsState.estimatedCost = estimatedCost;
        
        // Validate budget constraints
        validateBudget(estimatedCost);
    }
    
    function animateCostUpdate(elementId, newValue) {
        const element = document.getElementById(elementId);
        if (!element) return;
        
        const currentValue = element.textContent;
        if (currentValue === newValue.toString()) return;
        
        // Pulse animation for cost changes
        element.style.transform = 'scale(1.05)';
        element.style.color = '#059669'; // Green color for updates
        
        setTimeout(() => {
            element.textContent = newValue;
            element.style.transform = 'scale(1)';
            element.style.transition = 'all 0.3s ease-out';
            
            setTimeout(() => {
                element.style.color = '';
            }, 300);
        }, 150);
    }
    
    function updateProgressIndicators(recipientCount, totalSms, estimatedCost) {
        // Update progress steps based on completion
        const steps = document.querySelectorAll('[id^="step-"]');
        const progressBars = document.querySelectorAll('[id^="progress-"]');
        
        // Step 1: Message composed
        const hasMessage = messageTextarea.value.trim().length > 0;
        updateStepStatus(0, hasMessage, steps, progressBars);
        
        // Step 2: Audience selected
        const hasAudience = recipientCount > 0;
        updateStepStatus(1, hasAudience, steps, progressBars);
        
        // Step 3: Ready to schedule/send
        const readyToSend = hasMessage && hasAudience;
        updateStepStatus(2, readyToSend, steps, progressBars);
        
        // Update campaign cost indicator in header
        const campaignCostEl = document.getElementById('campaign-cost');
        if (campaignCostEl && estimatedCost > 0) {
            campaignCostEl.parentElement.classList.add('animate-pulse');
            setTimeout(() => {
                campaignCostEl.parentElement.classList.remove('animate-pulse');
            }, 1000);
        }
    }
    
    function updateStepStatus(stepIndex, isCompleted, steps, progressBars) {
        if (stepIndex >= steps.length) return;
        
        const step = steps[stepIndex];
        const progressBar = progressBars[stepIndex];
        
        if (isCompleted) {
            step.classList.remove('bg-slate-300', 'text-slate-500');
            step.classList.add('bg-blue-600', 'text-white');
            
            if (progressBar) {
                progressBar.classList.remove('bg-slate-300');
                progressBar.classList.add('bg-blue-600');
            }
        } else {
            step.classList.remove('bg-blue-600', 'text-white');
            step.classList.add('bg-slate-300', 'text-slate-500');
            
            if (progressBar) {
                progressBar.classList.remove('bg-blue-600');
                progressBar.classList.add('bg-slate-300');
            }
        }
    }
    
    function validateBudget(estimatedCost) {
        const availableBalance = @json($balance['success'] ? $balance['balance'] : 0);
        const costInCurrency = estimatedCost; // Assuming same currency
        
        if (costInCurrency > availableBalance && availableBalance > 0) {
            showNotification('Insufficient balance! Please top up your account.', 'warning');
            
            // Disable send button
            const sendBtn = document.getElementById('send-btn');
            if (sendBtn) {
                sendBtn.disabled = true;
                sendBtn.title = 'Insufficient balance';
            }
        } else {
            // Re-enable send button if other validations pass
            validateForm();
        }
    }

    // Enhanced Form Submission with Sophisticated Error Handling
    document.getElementById('sms-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Pre-submission validation
        if (!validateForm()) {
            showNotification('Please fix the validation errors before sending.', 'error');
            return;
        }
        
        // Confirm before sending
        const recipientCount = window.smsState?.recipientCount || 0;
        const estimatedCost = window.smsState?.estimatedCost || 0;
        
        if (!confirmSending(recipientCount, estimatedCost)) {
            return;
        }
        
        const formData = new FormData(this);
        const sendBtn = document.getElementById('send-btn');
        const loadingSpinner = document.getElementById('loading-spinner');
        const deliveryOption = document.querySelector('input[name="delivery_option"]:checked').value;
        
        // Add scheduling data if needed
        if (deliveryOption === 'schedule') {
            const scheduleDate = document.getElementById('schedule_date').value;
            const scheduleTime = document.getElementById('schedule_time').value;
            
            if (!scheduleDate || !scheduleTime) {
                showNotification('Please select both date and time for scheduling.', 'error');
                return;
            }
            
            // Validate future date
            const scheduledDateTime = new Date(scheduleDate + ' ' + scheduleTime);
            const now = new Date();
            
            if (scheduledDateTime <= now) {
                showNotification('Scheduled time must be in the future.', 'error');
                return;
            }
            
            formData.append('is_scheduled', '1');
            formData.append('scheduled_at', scheduleDate + ' ' + scheduleTime);
        }
        
        // Add campaign metadata
        formData.append('recipient_count', recipientCount);
        formData.append('estimated_cost', estimatedCost);
        formData.append('sms_count', window.smsState?.smsCount || 1);
        
        // Show enhanced loading state
        showLoadingState(sendBtn, loadingSpinner, deliveryOption);
        
        // Create progress tracker
        const progressTracker = createProgressTracker();
        
        fetch('@php echo route("sms.store"); @endphp', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            updateProgressTracker(progressTracker, 'Processing response...', 60);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            updateProgressTracker(progressTracker, 'Finalizing...', 90);
            
            setTimeout(() => {
                hideLoadingState(sendBtn, loadingSpinner);
                progressTracker.remove();
                
                if (data.success) {
                    showSuccessMessage(data, deliveryOption, recipientCount);
                    
                    // Redirect after success animation
                    setTimeout(() => {
                        window.location.href = '@php echo route("sms.index"); @endphp';
                    }, 2000);
                } else {
                    showErrorMessage(data.message || 'Unknown error occurred');
                }
            }, 500);
        })
        .catch(error => {
            console.error('SMS Campaign Error:', error);
            hideLoadingState(sendBtn, loadingSpinner);
            progressTracker.remove();
            
            showErrorMessage('Network error. Please check your connection and try again.');
        });
    });
    
    function confirmSending(recipientCount, estimatedCost) {
        const message = `
            📱 SMS Campaign Confirmation
            
            Recipients: ${recipientCount} members
            Estimated Cost: GHS ${estimatedCost.toFixed(2)}
            
            Are you sure you want to proceed?
        `;
        
        return confirm(message);
    }
    
    function showLoadingState(sendBtn, loadingSpinner, deliveryOption) {
        sendBtn.disabled = true;
        loadingSpinner.classList.remove('hidden');
        
        const btnText = sendBtn.querySelector('#send-btn-text');
        if (btnText) {
            btnText.textContent = deliveryOption === 'schedule' ? 'Scheduling...' : 'Sending...';
        }
        
        sendBtn.classList.add('animate-pulse');
    }
    
    function hideLoadingState(sendBtn, loadingSpinner) {
        sendBtn.disabled = false;
        loadingSpinner.classList.add('hidden');
        sendBtn.classList.remove('animate-pulse');
        
        const btnText = sendBtn.querySelector('#send-btn-text');
        if (btnText) {
            btnText.textContent = 'Send SMS';
        }
    }
    
    function createProgressTracker() {
        const tracker = document.createElement('div');
        tracker.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-50';
        tracker.innerHTML = `
            <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 shadow-2xl">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-paper-plane text-white text-2xl animate-bounce"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Sending SMS Campaign</h3>
                    <p class="text-gray-600 mb-4" id="progress-text">Preparing your message...</p>
                    <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" id="progress-bar-fill" style="width: 20%"></div>
                    </div>
                    <p class="text-sm text-gray-500">Please don't close this window</p>
                </div>
            </div>
        `;
        
        document.body.appendChild(tracker);
        return tracker;
    }
    
    function updateProgressTracker(tracker, text, percentage) {
        const progressText = tracker.querySelector('#progress-text');
        const progressBar = tracker.querySelector('#progress-bar-fill');
        
        if (progressText) progressText.textContent = text;
        if (progressBar) progressBar.style.width = percentage + '%';
    }
    
    function showSuccessMessage(data, deliveryOption, recipientCount) {
        const isScheduled = deliveryOption === 'schedule';
        const title = isScheduled ? '📅 SMS Scheduled Successfully!' : '✅ SMS Sent Successfully!';
        const message = isScheduled 
            ? `Your message has been scheduled and will be sent at the specified time to ${recipientCount} recipients.`
            : `Your message has been sent to ${recipientCount} recipients. Delivery will complete within 2-5 minutes.`;
        
        showNotification(title, 'success', 5000);
        
        // Create success overlay
        const overlay = document.createElement('div');
        overlay.className = 'fixed inset-0 bg-green-500/90 flex items-center justify-center z-50';
        overlay.innerHTML = `
            <div class="text-center text-white">
                <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas ${isScheduled ? 'fa-clock' : 'fa-check'} text-4xl animate-bounce"></i>
                </div>
                <h2 class="text-3xl font-bold mb-4">${title}</h2>
                <p class="text-xl mb-6">${message}</p>
                <div class="text-lg">
                    <p>Campaign ID: ${data.campaign_id || 'N/A'}</p>
                    <p>Status: ${isScheduled ? 'Scheduled' : 'Processing'}</p>
                </div>
            </div>
        `;
        
        document.body.appendChild(overlay);
        
        setTimeout(() => {
            overlay.remove();
        }, 3000);
    }
    
    function showErrorMessage(errorMessage) {
        showNotification('❌ SMS Campaign Failed', 'error', 5000);
        
        const errorOverlay = document.createElement('div');
        errorOverlay.className = 'fixed inset-0 bg-red-500/90 flex items-center justify-center z-50';
        errorOverlay.innerHTML = `
            <div class="text-center text-white max-w-md mx-4">
                <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-exclamation-triangle text-4xl"></i>
                </div>
                <h2 class="text-3xl font-bold mb-4">Campaign Failed</h2>
                <p class="text-xl mb-6">${errorMessage}</p>
                <button onclick="this.parentElement.parentElement.remove()" 
                        class="bg-white text-red-600 px-6 py-3 rounded-xl font-semibold hover:bg-gray-100 transition-colors">
                    Try Again
                </button>
            </div>
        `;
        
        document.body.appendChild(errorOverlay);
    }
    
    // Initialize all event listeners
    messageTextarea.addEventListener('input', updateCounts);
    document.getElementById('title').addEventListener('input', validateForm);
    document.getElementById('sender_name').addEventListener('input', validateForm);
    
    // Initial validation
    validateForm();
});
</script>
@endsection
