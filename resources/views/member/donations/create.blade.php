@extends('member.layouts.app')

@section('title', 'Make Donation')

@section('content')
<!-- Enhanced Background with Gradient -->
<div class="min-h-screen bg-gradient-to-br from-green-50 via-blue-50 to-purple-50 relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-green-400 to-blue-500 rounded-full opacity-10 animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-gradient-to-tr from-purple-400 to-pink-500 rounded-full opacity-10 animate-pulse" style="animation-delay: 2s"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full opacity-5 animate-pulse" style="animation-delay: 4s"></div>
    </div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Enhanced Header -->
        <div class="text-center mb-12">
            <div class="flex items-center justify-center mb-6">
                <a href="{{ route('member.dashboard') }}" class="group flex items-center justify-center w-12 h-12 bg-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300 mr-6 hover:scale-110">
                    <i class="fas fa-arrow-left text-gray-600 group-hover:text-green-600 transition-colors"></i>
                </a>
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-xl">
                    <i class="fas fa-heart text-white text-2xl"></i>
                </div>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Make a Donation</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Your generous giving helps build God's kingdom and supports our church community</p>
            <div class="mt-6 flex items-center justify-center space-x-6 text-sm text-gray-500">
                <span class="flex items-center">
                    <i class="fas fa-shield-alt text-green-500 mr-2"></i>Secure Payment
                </span>
                <span class="flex items-center">
                    <i class="fas fa-receipt text-blue-500 mr-2"></i>Tax Receipt
                </span>
                <span class="flex items-center">
                    <i class="fas fa-heart text-red-500 mr-2"></i>Kingdom Impact
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Enhanced Donation Form -->
            <div class="lg:col-span-2">
                <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
                    <div class="p-8 border-b border-gray-100 bg-gradient-to-r from-green-50 to-emerald-50">
                        <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-4 shadow-lg">
                                <i class="fas fa-heart text-white"></i>
                            </div>
                            Donation Details
                        </h2>
                        <p class="text-gray-600 mt-2">Choose your giving preferences below</p>
                    </div>
                    <form class="p-8 space-y-8">
                    @csrf
                    
                    <!-- Enhanced Donation Type -->
                    <div>
                        <label class="block text-lg font-semibold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-tags text-green-600 mr-3"></i>Donation Type *
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <label class="group relative flex items-center p-6 bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-100 rounded-2xl cursor-pointer hover:from-blue-100 hover:to-indigo-100 hover:border-blue-200 transition-all duration-300 hover:shadow-lg">
                                <input type="radio" name="donation_type" value="tithe" class="sr-only peer">
                                <div class="w-6 h-6 border-2 border-blue-300 rounded-full flex items-center justify-center peer-checked:border-blue-600 peer-checked:bg-blue-600 transition-all duration-200">
                                    <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-percentage text-blue-600 mr-2"></i>
                                        <div class="font-bold text-gray-900">Tithe</div>
                                    </div>
                                    <div class="text-sm text-gray-600">10% of income</div>
                                    <div class="text-xs text-blue-600 mt-1">"Honor the Lord with your wealth" - Proverbs 3:9</div>
                                </div>
                            </label>
                            <label class="group relative flex items-center p-6 bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-100 rounded-2xl cursor-pointer hover:from-green-100 hover:to-emerald-100 hover:border-green-200 transition-all duration-300 hover:shadow-lg">
                                <input type="radio" name="donation_type" value="offering" class="sr-only peer">
                                <div class="w-6 h-6 border-2 border-green-300 rounded-full flex items-center justify-center peer-checked:border-green-600 peer-checked:bg-green-600 transition-all duration-200">
                                    <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-hand-holding-heart text-green-600 mr-2"></i>
                                        <div class="font-bold text-gray-900">Offering</div>
                                    </div>
                                    <div class="text-sm text-gray-600">General giving</div>
                                    <div class="text-xs text-green-600 mt-1">"Give cheerfully" - 2 Corinthians 9:7</div>
                                </div>
                            </label>
                            <label class="group relative flex items-center p-6 bg-gradient-to-br from-purple-50 to-pink-50 border-2 border-purple-100 rounded-2xl cursor-pointer hover:from-purple-100 hover:to-pink-100 hover:border-purple-200 transition-all duration-300 hover:shadow-lg">
                                <input type="radio" name="donation_type" value="special" class="sr-only peer">
                                <div class="w-6 h-6 border-2 border-purple-300 rounded-full flex items-center justify-center peer-checked:border-purple-600 peer-checked:bg-purple-600 transition-all duration-200">
                                    <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-star text-purple-600 mr-2"></i>
                                        <div class="font-bold text-gray-900">Special</div>
                                    </div>
                                    <div class="text-sm text-gray-600">Project/Event</div>
                                    <div class="text-xs text-purple-600 mt-1">"Special purpose giving"</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Enhanced Amount Section -->
                    <div>
                        <label for="amount" class="block text-lg font-semibold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-dollar-sign text-green-600 mr-3"></i>Donation Amount *
                        </label>
                        <div class="relative mb-6">
                            <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                                <span class="text-green-600 text-2xl font-bold">₵</span>
                            </div>
                            <input type="number" id="amount" name="amount" step="0.01" min="1" required
                                   class="w-full pl-16 pr-6 py-6 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-green-500/20 focus:border-green-500 text-2xl font-bold bg-gradient-to-r from-gray-50 to-white shadow-inner transition-all duration-300"
                                   placeholder="0.00">
                            <div class="absolute inset-y-0 right-0 pr-6 flex items-center">
                                <span class="text-gray-400 text-sm">GHS</span>
                            </div>
                        </div>
                        <!-- Enhanced Quick Amount Buttons -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                            <button type="button" onclick="setAmount(50)" class="group relative p-4 bg-gradient-to-br from-green-100 to-emerald-100 hover:from-green-200 hover:to-emerald-200 border-2 border-green-200 hover:border-green-300 rounded-xl text-green-700 font-semibold transition-all duration-300 hover:shadow-lg hover:scale-105">
                                <div class="text-lg">₵50</div>
                                <div class="text-xs opacity-75">Basic</div>
                            </button>
                            <button type="button" onclick="setAmount(100)" class="group relative p-4 bg-gradient-to-br from-blue-100 to-indigo-100 hover:from-blue-200 hover:to-indigo-200 border-2 border-blue-200 hover:border-blue-300 rounded-xl text-blue-700 font-semibold transition-all duration-300 hover:shadow-lg hover:scale-105">
                                <div class="text-lg">₵100</div>
                                <div class="text-xs opacity-75">Standard</div>
                            </button>
                            <button type="button" onclick="setAmount(200)" class="group relative p-4 bg-gradient-to-br from-purple-100 to-pink-100 hover:from-purple-200 hover:to-pink-200 border-2 border-purple-200 hover:border-purple-300 rounded-xl text-purple-700 font-semibold transition-all duration-300 hover:shadow-lg hover:scale-105">
                                <div class="text-lg">₵200</div>
                                <div class="text-xs opacity-75">Generous</div>
                            </button>
                            <button type="button" onclick="setAmount(500)" class="group relative p-4 bg-gradient-to-br from-orange-100 to-red-100 hover:from-orange-200 hover:to-red-200 border-2 border-orange-200 hover:border-orange-300 rounded-xl text-orange-700 font-semibold transition-all duration-300 hover:shadow-lg hover:scale-105">
                                <div class="text-lg">₵500</div>
                                <div class="text-xs opacity-75">Blessing</div>
                            </button>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-500">Or enter a custom amount above</p>
                        </div>
                    </div>

                    <!-- Enhanced Purpose Section -->
                    <div>
                        <label for="purpose" class="block text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-comment-alt text-blue-600 mr-3"></i>Purpose/Notes
                        </label>
                        <div class="relative">
                            <textarea id="purpose" name="purpose" rows="4" 
                                      class="w-full px-6 py-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 bg-gradient-to-r from-gray-50 to-white shadow-inner transition-all duration-300 resize-none"
                                      placeholder="Share the heart behind your giving (optional)..."></textarea>
                            <div class="absolute bottom-3 right-3 text-xs text-gray-400">
                                <i class="fas fa-heart text-red-400 mr-1"></i>Optional
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Payment Method -->
                    <div>
                        <label class="block text-lg font-semibold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-credit-card text-purple-600 mr-3"></i>Payment Method *
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <label class="group relative p-6 bg-gradient-to-br from-blue-50 to-cyan-50 border-2 border-blue-100 rounded-2xl cursor-pointer hover:from-blue-100 hover:to-cyan-100 hover:border-blue-200 transition-all duration-300 hover:shadow-lg">
                                <input type="radio" name="payment_method" value="mobile_money" class="sr-only peer">
                                <div class="w-6 h-6 border-2 border-blue-300 rounded-full flex items-center justify-center peer-checked:border-blue-600 peer-checked:bg-blue-600 transition-all duration-200 mb-4">
                                    <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                </div>
                                <div class="text-center">
                                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                                        <i class="fas fa-mobile-alt text-white text-2xl"></i>
                                    </div>
                                    <div class="font-bold text-gray-900 mb-2">Mobile Money</div>
                                    <div class="text-sm text-gray-600">MTN • Vodafone • AirtelTigo</div>
                                    <div class="text-xs text-blue-600 mt-2">Instant & Secure</div>
                                </div>
                            </label>
                            <label class="group relative p-6 bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-100 rounded-2xl cursor-pointer hover:from-green-100 hover:to-emerald-100 hover:border-green-200 transition-all duration-300 hover:shadow-lg">
                                <input type="radio" name="payment_method" value="card" class="sr-only peer">
                                <div class="w-6 h-6 border-2 border-green-300 rounded-full flex items-center justify-center peer-checked:border-green-600 peer-checked:bg-green-600 transition-all duration-200 mb-4">
                                    <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                </div>
                                <div class="text-center">
                                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                                        <i class="fas fa-credit-card text-white text-2xl"></i>
                                    </div>
                                    <div class="font-bold text-gray-900 mb-2">Debit/Credit Card</div>
                                    <div class="text-sm text-gray-600">Visa • Mastercard</div>
                                    <div class="text-xs text-green-600 mt-2">Global Payment</div>
                                </div>
                            </label>
                            <label class="group relative p-6 bg-gradient-to-br from-purple-50 to-pink-50 border-2 border-purple-100 rounded-2xl cursor-pointer hover:from-purple-100 hover:to-pink-100 hover:border-purple-200 transition-all duration-300 hover:shadow-lg">
                                <input type="radio" name="payment_method" value="bank_transfer" class="sr-only peer">
                                <div class="w-6 h-6 border-2 border-purple-300 rounded-full flex items-center justify-center peer-checked:border-purple-600 peer-checked:bg-purple-600 transition-all duration-200 mb-4">
                                    <div class="w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                </div>
                                <div class="text-center">
                                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                                        <i class="fas fa-university text-white text-2xl"></i>
                                    </div>
                                    <div class="font-bold text-gray-900 mb-2">Bank Transfer</div>
                                    <div class="text-sm text-gray-600">Direct Transfer</div>
                                    <div class="text-xs text-purple-600 mt-2">Traditional Method</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Enhanced Anonymous Option -->
                    <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-2xl p-6 border-2 border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="relative">
                                    <input type="checkbox" id="anonymous" name="anonymous" class="sr-only peer">
                                    <div class="w-6 h-6 border-2 border-gray-300 rounded-lg flex items-center justify-center peer-checked:border-blue-600 peer-checked:bg-blue-600 transition-all duration-200 cursor-pointer">
                                        <i class="fas fa-check text-white text-sm opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                    </div>
                                </div>
                                <label for="anonymous" class="ml-4 cursor-pointer">
                                    <div class="font-semibold text-gray-900">Anonymous Donation</div>
                                    <div class="text-sm text-gray-600">Keep your identity private</div>
                                </label>
                            </div>
                            <div class="text-blue-600">
                                <i class="fas fa-user-secret text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Submit Button -->
                    <div class="pt-8 border-t-2 border-gray-100">
                        <button type="submit" class="group relative w-full bg-gradient-to-r from-green-600 via-emerald-600 to-green-700 hover:from-green-700 hover:via-emerald-700 hover:to-green-800 text-white py-6 px-8 rounded-2xl font-bold text-xl transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1 overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <div class="relative flex items-center justify-center">
                                <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center mr-4">
                                    <i class="fas fa-heart text-white"></i>
                                </div>
                                <span>Proceed to Payment</span>
                                <i class="fas fa-arrow-right ml-4 group-hover:translate-x-1 transition-transform duration-200"></i>
                            </div>
                        </button>
                        <div class="text-center mt-4">
                            <p class="text-sm text-gray-500 flex items-center justify-center">
                                <i class="fas fa-lock text-green-500 mr-2"></i>
                                Secured by 256-bit SSL encryption
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Enhanced Sidebar -->
        <div class="space-y-8">
            <!-- Enhanced Giving Impact -->
            <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                            <i class="fas fa-hands-helping text-white"></i>
                        </div>
                        Your Kingdom Impact
                    </h3>
                </div>
                <div class="p-6">
                    <div class="text-center mb-6">
                        <div class="relative">
                            <div class="w-20 h-20 bg-gradient-to-br from-green-400 to-emerald-600 rounded-3xl flex items-center justify-center mx-auto mb-4 shadow-xl">
                                <i class="fas fa-heart text-white text-3xl animate-pulse"></i>
                            </div>
                            <div class="absolute -top-2 -right-2 w-8 h-8 bg-yellow-400 rounded-full animate-bounce"></div>
                        </div>
                        <p class="text-gray-600 font-medium">Your generous giving transforms lives:</p>
                    </div>
                    <div class="space-y-4">
                        <div class="group flex items-center p-3 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl hover:from-blue-100 hover:to-blue-200 transition-all duration-300">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-4 shadow-md">
                                <i class="fas fa-church text-white"></i>
                            </div>
                            <span class="text-gray-800 font-medium">Church operations & ministry</span>
                        </div>
                        <div class="group flex items-center p-3 bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl hover:from-purple-100 hover:to-purple-200 transition-all duration-300">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mr-4 shadow-md">
                                <i class="fas fa-users text-white"></i>
                            </div>
                            <span class="text-gray-800 font-medium">Community outreach programs</span>
                        </div>
                        <div class="group flex items-center p-3 bg-gradient-to-r from-green-50 to-green-100 rounded-xl hover:from-green-100 hover:to-green-200 transition-all duration-300">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center mr-4 shadow-md">
                                <i class="fas fa-graduation-cap text-white"></i>
                            </div>
                            <span class="text-gray-800 font-medium">Educational initiatives</span>
                        </div>
                        <div class="group flex items-center p-3 bg-gradient-to-r from-red-50 to-red-100 rounded-xl hover:from-red-100 hover:to-red-200 transition-all duration-300">
                            <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center mr-4 shadow-md">
                                <i class="fas fa-hand-holding-heart text-white"></i>
                            </div>
                            <span class="text-gray-800 font-medium">Charitable activities</span>
                        </div>
                    </div>
                    <div class="mt-6 p-4 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl border border-yellow-200">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-orange-600">100%</div>
                            <div class="text-sm text-orange-700">of your donation goes directly to ministry</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Security Notice -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-100 border-2 border-blue-200 rounded-3xl p-6 shadow-lg">
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-xl">
                        <i class="fas fa-shield-alt text-white text-2xl"></i>
                    </div>
                    <h4 class="font-bold text-blue-900 mb-3 text-lg">Bank-Level Security</h4>
                    <p class="text-sm text-blue-800 mb-4">Your donation is protected by military-grade 256-bit SSL encryption and PCI DSS compliance.</p>
                    <div class="flex items-center justify-center space-x-4 text-xs text-blue-700">
                        <span class="flex items-center">
                            <i class="fas fa-lock mr-1"></i>SSL Encrypted
                        </span>
                        <span class="flex items-center">
                            <i class="fas fa-shield-check mr-1"></i>PCI Compliant
                        </span>
                    </div>
                </div>
            </div>

            <!-- Enhanced Tax Information -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-100 border-2 border-green-200 rounded-3xl p-6 shadow-lg">
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-xl">
                        <i class="fas fa-file-invoice text-white text-2xl"></i>
                    </div>
                    <h4 class="font-bold text-green-900 mb-3 text-lg">Tax Benefits</h4>
                    <p class="text-sm text-green-800 mb-4">Receive instant tax-deductible receipts via email. All donations are eligible for tax benefits under Ghana Revenue Authority guidelines.</p>
                    <div class="bg-white/50 rounded-xl p-3">
                        <div class="text-xs text-green-700 font-medium">
                            <i class="fas fa-envelope mr-1"></i>Instant Email Receipt
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Contact Support -->
            <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
                <div class="p-6 bg-gradient-to-r from-orange-50 to-red-50 border-b border-gray-100">
                    <h4 class="font-bold text-gray-900 text-lg flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-headset text-white"></i>
                        </div>
                        Need Help?
                    </h4>
                </div>
                <div class="p-6 space-y-4">
                    <a href="tel:+233241234567" class="group flex items-center p-4 bg-gradient-to-r from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 rounded-2xl transition-all duration-300 hover:shadow-md">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-4 shadow-lg group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-phone text-white"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">Call Support</div>
                            <div class="text-sm text-gray-600">+233 24 123 4567</div>
                        </div>
                    </a>
                    <a href="mailto:finance@beulahfamily.org" class="group flex items-center p-4 bg-gradient-to-r from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 rounded-2xl transition-all duration-300 hover:shadow-md">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center mr-4 shadow-lg group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-envelope text-white"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">Email Finance Team</div>
                            <div class="text-sm text-gray-600">Quick response guaranteed</div>
                        </div>
                    </a>
                    <div class="text-center pt-4 border-t border-gray-100">
                        <p class="text-xs text-gray-500">
                            <i class="fas fa-clock mr-1"></i>Available 24/7 for donation support
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function setAmount(amount) {
    document.getElementById('amount').value = amount;
}

// Form validation and enhancement
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const amountInput = document.getElementById('amount');
    
    // Format amount input
    amountInput.addEventListener('input', function() {
        let value = this.value;
        if (value && !isNaN(value)) {
            this.value = parseFloat(value).toFixed(2);
        }
    });
    
    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const amount = amountInput.value;
        const donationType = document.querySelector('input[name="donation_type"]:checked');
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
        
        if (!amount || amount <= 0) {
            alert('Please enter a valid donation amount.');
            return;
        }
        
        if (!donationType) {
            alert('Please select a donation type.');
            return;
        }
        
        if (!paymentMethod) {
            alert('Please select a payment method.');
            return;
        }
        
        // Show processing state
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
        submitBtn.disabled = true;
        
        // Here you would normally submit to your payment processor
        setTimeout(() => {
            alert('Payment processing would be implemented here with your chosen payment gateway (Paystack, etc.)');
            submitBtn.innerHTML = '<i class="fas fa-heart mr-2"></i>Proceed to Payment';
            submitBtn.disabled = false;
        }, 2000);
    });
});
</script>
@endsection
