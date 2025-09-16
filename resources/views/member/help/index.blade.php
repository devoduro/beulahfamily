@extends('member.layouts.app')

@section('title', 'Help & Support')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Help & Support</h1>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">Find answers to common questions or get in touch with our support team</p>
    </div>

    <!-- Search Bar -->
    <div class="max-w-2xl mx-auto mb-12">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="text" placeholder="Search for help articles, guides, or FAQs..." 
                   class="w-full pl-10 pr-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg">
        </div>
    </div>

    <!-- Quick Help Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
        <!-- Getting Started -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-rocket text-blue-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Getting Started</h3>
            <p class="text-gray-600 text-sm mb-4">Learn how to use your member portal and access key features</p>
            <a href="#getting-started" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                View Guide <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <!-- Account Management -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-user-cog text-green-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Account Management</h3>
            <p class="text-gray-600 text-sm mb-4">Update your profile, change passwords, and manage settings</p>
            <a href="#account" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                View Guide <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <!-- Donations -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-heart text-purple-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Donations & Giving</h3>
            <p class="text-gray-600 text-sm mb-4">Learn how to make donations and track your giving history</p>
            <a href="#donations" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                View Guide <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <!-- Events -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-calendar-alt text-orange-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Events & Programs</h3>
            <p class="text-gray-600 text-sm mb-4">Register for events and manage your participation</p>
            <a href="#events" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                View Guide <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <!-- Ministries -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-hands-helping text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Ministry Involvement</h3>
            <p class="text-gray-600 text-sm mb-4">Join ministries and track your service activities</p>
            <a href="#ministries" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                View Guide <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <!-- Technical Support -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-tools text-indigo-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Technical Support</h3>
            <p class="text-gray-600 text-sm mb-4">Troubleshoot login issues and technical problems</p>
            <a href="#technical" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                View Guide <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- FAQ Section -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-xl font-semibold text-gray-900">Frequently Asked Questions</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- FAQ Item -->
                        <div class="border border-gray-200 rounded-lg">
                            <button class="w-full px-4 py-4 text-left flex items-center justify-between hover:bg-gray-50 focus:outline-none focus:bg-gray-50" onclick="toggleFAQ(this)">
                                <span class="font-medium text-gray-900">How do I reset my password?</span>
                                <i class="fas fa-chevron-down text-gray-400 transform transition-transform"></i>
                            </button>
                            <div class="hidden px-4 pb-4">
                                <p class="text-gray-600 text-sm">To reset your password, go to the login page and click "Forgot Password". Enter your email address or member ID, and you'll receive a password reset link via email. Follow the instructions in the email to create a new password.</p>
                            </div>
                        </div>

                        <!-- FAQ Item -->
                        <div class="border border-gray-200 rounded-lg">
                            <button class="w-full px-4 py-4 text-left flex items-center justify-between hover:bg-gray-50 focus:outline-none focus:bg-gray-50" onclick="toggleFAQ(this)">
                                <span class="font-medium text-gray-900">How do I make a donation online?</span>
                                <i class="fas fa-chevron-down text-gray-400 transform transition-transform"></i>
                            </button>
                            <div class="hidden px-4 pb-4">
                                <p class="text-gray-600 text-sm">Navigate to the "Donations" section from your dashboard. Select the donation type (tithe, offering, or special), enter the amount, choose your payment method (mobile money, card, or bank transfer), and follow the payment instructions. You'll receive a confirmation email once the donation is processed.</p>
                            </div>
                        </div>

                        <!-- FAQ Item -->
                        <div class="border border-gray-200 rounded-lg">
                            <button class="w-full px-4 py-4 text-left flex items-center justify-between hover:bg-gray-50 focus:outline-none focus:bg-gray-50" onclick="toggleFAQ(this)">
                                <span class="font-medium text-gray-900">How do I register for church events?</span>
                                <i class="fas fa-chevron-down text-gray-400 transform transition-transform"></i>
                            </button>
                            <div class="hidden px-4 pb-4">
                                <p class="text-gray-600 text-sm">Go to the "Events & Programs" section to view upcoming events. Click on any event to see details and click the "Register" button. Some events may require additional information or have registration fees. You'll receive a confirmation email with event details.</p>
                            </div>
                        </div>

                        <!-- FAQ Item -->
                        <div class="border border-gray-200 rounded-lg">
                            <button class="w-full px-4 py-4 text-left flex items-center justify-between hover:bg-gray-50 focus:outline-none focus:bg-gray-50" onclick="toggleFAQ(this)">
                                <span class="font-medium text-gray-900">How do I join a ministry?</span>
                                <i class="fas fa-chevron-down text-gray-400 transform transition-transform"></i>
                            </button>
                            <div class="hidden px-4 pb-4">
                                <p class="text-gray-600 text-sm">Visit the "Ministries" section to see available ministry opportunities. Browse through the different ministries and click "Join Ministry" on any that interest you. Ministry leaders will review your application and contact you with next steps.</p>
                            </div>
                        </div>

                        <!-- FAQ Item -->
                        <div class="border border-gray-200 rounded-lg">
                            <button class="w-full px-4 py-4 text-left flex items-center justify-between hover:bg-gray-50 focus:outline-none focus:bg-gray-50" onclick="toggleFAQ(this)">
                                <span class="font-medium text-gray-900">How do I update my profile information?</span>
                                <i class="fas fa-chevron-down text-gray-400 transform transition-transform"></i>
                            </button>
                            <div class="hidden px-4 pb-4">
                                <p class="text-gray-600 text-sm">Click on your profile picture in the top navigation and select "Profile" or navigate to the "Profile" section from your dashboard. You can edit your personal information, contact details, emergency contacts, and communication preferences. Don't forget to save your changes.</p>
                            </div>
                        </div>

                        <!-- FAQ Item -->
                        <div class="border border-gray-200 rounded-lg">
                            <button class="w-full px-4 py-4 text-left flex items-center justify-between hover:bg-gray-50 focus:outline-none focus:bg-gray-50" onclick="toggleFAQ(this)">
                                <span class="font-medium text-gray-900">Can I access my donation history for tax purposes?</span>
                                <i class="fas fa-chevron-down text-gray-400 transform transition-transform"></i>
                            </button>
                            <div class="hidden px-4 pb-4">
                                <p class="text-gray-600 text-sm">Yes! Go to the "Donations" section to view your complete donation history. You can filter by date range and export your donation records as a PDF or CSV file for tax purposes. Annual giving statements are also automatically generated and emailed to you in January.</p>
                            </div>
                        </div>

                        <!-- FAQ Item -->
                        <div class="border border-gray-200 rounded-lg">
                            <button class="w-full px-4 py-4 text-left flex items-center justify-between hover:bg-gray-50 focus:outline-none focus:bg-gray-50" onclick="toggleFAQ(this)">
                                <span class="font-medium text-gray-900">How do I manage my family members' information?</span>
                                <i class="fas fa-chevron-down text-gray-400 transform transition-transform"></i>
                            </button>
                            <div class="hidden px-4 pb-4">
                                <p class="text-gray-600 text-sm">Navigate to the "Family & Connections" section where you can add, edit, or remove family members. You can register family members as church members, track their involvement, and manage their information. Each family member can have their own login if they're old enough.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Support -->
        <div class="space-y-6">
            <!-- Contact Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-headset text-blue-600 mr-2"></i>Contact Support
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-phone text-blue-600"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">Phone Support</div>
                            <div class="text-sm text-gray-600">+233 24 123 4567</div>
                            <div class="text-xs text-gray-500">Mon-Fri 9AM-5PM</div>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-envelope text-green-600"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">Email Support</div>
                            <div class="text-sm text-gray-600">support@beulahfamily.org</div>
                            <div class="text-xs text-gray-500">Response within 24 hours</div>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fab fa-whatsapp text-purple-600"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">WhatsApp</div>
                            <div class="text-sm text-gray-600">+233 24 123 4567</div>
                            <div class="text-xs text-gray-500">Quick responses</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Ticket -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-ticket-alt text-green-600 mr-2"></i>Submit Support Ticket
                    </h3>
                </div>
                <div class="p-6">
                    <form class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Brief description of your issue">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option>Select category</option>
                                <option>Account Issues</option>
                                <option>Donation Problems</option>
                                <option>Event Registration</option>
                                <option>Technical Issues</option>
                                <option>General Inquiry</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                            <textarea rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Please describe your issue in detail..."></textarea>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-medium transition-colors">
                            Submit Ticket
                        </button>
                    </form>
                </div>
            </div>

            <!-- Office Hours -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                <div class="flex items-start">
                    <i class="fas fa-clock text-blue-600 mt-1 mr-3"></i>
                    <div>
                        <h4 class="font-medium text-blue-900 mb-2">Office Hours</h4>
                        <div class="text-sm text-blue-700 space-y-1">
                            <div>Monday - Friday: 9:00 AM - 5:00 PM</div>
                            <div>Saturday: 10:00 AM - 2:00 PM</div>
                            <div>Sunday: After service - 2:00 PM</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Emergency Contact -->
            <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-red-600 mt-1 mr-3"></i>
                    <div>
                        <h4 class="font-medium text-red-900 mb-2">Emergency Contact</h4>
                        <p class="text-sm text-red-700 mb-2">For urgent pastoral care or emergencies outside office hours:</p>
                        <div class="text-sm text-red-700 font-medium">+233 24 123 4568</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleFAQ(button) {
    const content = button.nextElementSibling;
    const icon = button.querySelector('i');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.classList.add('rotate-180');
    } else {
        content.classList.add('hidden');
        icon.classList.remove('rotate-180');
    }
}

// Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[type="text"]');
    
    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        const faqItems = document.querySelectorAll('.border.border-gray-200.rounded-lg');
        
        faqItems.forEach(item => {
            const question = item.querySelector('span').textContent.toLowerCase();
            const answer = item.querySelector('.hidden p').textContent.toLowerCase();
            
            if (question.includes(query) || answer.includes(query)) {
                item.style.display = 'block';
            } else {
                item.style.display = query === '' ? 'block' : 'none';
            }
        });
    });
    
    // Handle support ticket form
    const ticketForm = document.querySelector('form');
    ticketForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Submitting...';
        submitBtn.disabled = true;
        
        // Simulate form submission
        setTimeout(() => {
            alert('Support ticket submitted successfully! You will receive a confirmation email shortly.');
            
            // Reset form
            this.reset();
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 2000);
    });
});
</script>
@endsection
