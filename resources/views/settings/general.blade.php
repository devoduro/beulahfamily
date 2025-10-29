@extends('components.app-layout')

@section('title', 'Church Settings')
@section('subtitle', 'Configure church information, branding, and system preferences')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-2">Beulah Family Management Settings</h2>
        <p class="text-gray-600">Configure your church's information, branding, contact details, and system preferences all in one place.</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('settings.general.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Organization Information -->
            <div class="space-y-4 col-span-1 md:col-span-2">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="fas fa-building mr-2 text-primary-600"></i>
                    Organization Information
                </h3>
                
                <div>
                    <label for="organization_name" class="block text-sm font-medium text-gray-700 mb-1">Organization Name*</label>
                    <input type="text" name="organization_name" id="organization_name" 
                           value="{{ $settings->where('key', 'organization_name')->first()->value ?? old('organization_name', 'Beulah Family') }}" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50" 
                           required>
                    @error('organization_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="organization_slogan" class="block text-sm font-medium text-gray-700 mb-1">Slogan/Motto</label>
                    <input type="text" name="organization_slogan" id="organization_slogan" 
                           value="{{ $settings->where('key', 'organization_slogan')->first()->value ?? old('organization_slogan') }}" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50"
                           placeholder="Enter your organization's slogan or motto">
                    @error('organization_slogan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="organization_description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="organization_description" id="organization_description" rows="3" 
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50"
                              placeholder="Brief description of your organization">{{ $settings->where('key', 'organization_description')->first()->value ?? old('organization_description') }}</textarea>
                    @error('organization_description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Logo Section -->
            <div class="space-y-4 col-span-1 md:col-span-2">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="fas fa-image mr-2 text-primary-600"></i>
                    Organization Logo
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="organization_logo" class="block text-sm font-medium text-gray-700 mb-1">Upload Logo</label>
                        <input type="file" name="organization_logo" id="organization_logo" 
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                        <p class="mt-1 text-xs text-gray-500">Recommended size: 200x200px. Max 2MB. Formats: JPG, PNG, GIF.</p>
                        @error('organization_logo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        @php
                            $logoSetting = $settings->where('key', 'organization_logo')->first();
                        @endphp
                        @if($logoSetting && $logoSetting->value)
                            <div class="flex flex-col items-center">
                                <p class="text-sm font-medium text-gray-700 mb-2">Current Logo</p>
                                <img src="{{ asset('storage/' . $logoSetting->value) }}" alt="Organization Logo" class="max-h-24 max-w-24 object-contain border border-gray-200 rounded">
                            </div>
                        @else
                            <div class="flex flex-col items-center">
                                <p class="text-sm font-medium text-gray-700 mb-2">No Logo Uploaded</p>
                                <div class="w-24 h-24 bg-gray-100 rounded flex items-center justify-center">
                                    <i class="fas fa-church text-gray-400 text-3xl"></i>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="space-y-4 col-span-1 md:col-span-2">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="fas fa-phone mr-2 text-primary-600"></i>
                    Contact Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="organization_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input type="tel" name="organization_phone" id="organization_phone" 
                               value="{{ $settings->where('key', 'organization_phone')->first()->value ?? old('organization_phone') }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50"
                               placeholder="Enter phone number">
                        @error('organization_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="organization_email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" name="organization_email" id="organization_email" 
                               value="{{ $settings->where('key', 'organization_email')->first()->value ?? old('organization_email') }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50"
                               placeholder="Enter email address">
                        @error('organization_email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="col-span-1 md:col-span-2">
                        <label for="organization_website" class="block text-sm font-medium text-gray-700 mb-1">Website URL</label>
                        <input type="url" name="organization_website" id="organization_website" 
                               value="{{ $settings->where('key', 'organization_website')->first()->value ?? old('organization_website') }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50"
                               placeholder="https://example.com">
                        @error('organization_website')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Location Information -->
            <div class="space-y-4 col-span-1 md:col-span-2">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="fas fa-map-marker-alt mr-2 text-primary-600"></i>
                    Location Information
                </h3>
                
                <div>
                    <label for="organization_address" class="block text-sm font-medium text-gray-700 mb-1">Street Address</label>
                    <textarea name="organization_address" id="organization_address" rows="3" 
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50"
                              placeholder="Enter street address">{{ $settings->where('key', 'organization_address')->first()->value ?? old('organization_address') }}</textarea>
                    @error('organization_address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="organization_city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                        <input type="text" name="organization_city" id="organization_city" 
                               value="{{ $settings->where('key', 'organization_city')->first()->value ?? old('organization_city') }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50"
                               placeholder="Enter city">
                        @error('organization_city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="organization_state" class="block text-sm font-medium text-gray-700 mb-1">State/Province</label>
                        <input type="text" name="organization_state" id="organization_state" 
                               value="{{ $settings->where('key', 'organization_state')->first()->value ?? old('organization_state') }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50"
                               placeholder="Enter state/province">
                        @error('organization_state')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="organization_postal_code" class="block text-sm font-medium text-gray-700 mb-1">Postal Code</label>
                        <input type="text" name="organization_postal_code" id="organization_postal_code" 
                               value="{{ $settings->where('key', 'organization_postal_code')->first()->value ?? old('organization_postal_code') }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50"
                               placeholder="Enter postal code">
                        @error('organization_postal_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="organization_country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                        <input type="text" name="organization_country" id="organization_country" 
                               value="{{ $settings->where('key', 'organization_country')->first()->value ?? old('organization_country') }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50"
                               placeholder="Enter country">
                        @error('organization_country')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Branding Colors -->
            <div class="space-y-4 col-span-1 md:col-span-2">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="fas fa-palette mr-2 text-primary-600"></i>
                    Branding Colors
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="primary_color" class="block text-sm font-medium text-gray-700 mb-1">Primary Color</label>
                        <div class="flex items-center space-x-2">
                            <input type="color" name="primary_color" id="primary_color" 
                                   value="{{ $settings->where('key', 'primary_color')->first()->value ?? old('primary_color', '#3b82f6') }}" 
                                   class="h-10 w-16 rounded border border-gray-300 cursor-pointer">
                            <input type="text" 
                                   value="{{ $settings->where('key', 'primary_color')->first()->value ?? old('primary_color', '#3b82f6') }}" 
                                   class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50"
                                   readonly>
                        </div>
                        @error('primary_color')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="secondary_color" class="block text-sm font-medium text-gray-700 mb-1">Secondary Color</label>
                        <div class="flex items-center space-x-2">
                            <input type="color" name="secondary_color" id="secondary_color" 
                                   value="{{ $settings->where('key', 'secondary_color')->first()->value ?? old('secondary_color', '#64748b') }}" 
                                   class="h-10 w-16 rounded border border-gray-300 cursor-pointer">
                            <input type="text" 
                                   value="{{ $settings->where('key', 'secondary_color')->first()->value ?? old('secondary_color', '#64748b') }}" 
                                   class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50"
                                   readonly>
                        </div>
                        @error('secondary_color')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <p class="text-xs text-gray-500">These colors will be used throughout the system for branding consistency.</p>
            </div>

            <!-- Church Administration -->
            <div class="space-y-4 col-span-1 md:col-span-2">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="fas fa-church mr-2 text-primary-600"></i>
                    Church Administration
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="church_code" class="block text-sm font-medium text-gray-700 mb-1">Church Code</label>
                        <input type="text" name="church_code" id="church_code" 
                               value="{{ $settings->where('key', 'church_code')->first()->value ?? old('church_code') }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50"
                               placeholder="e.g., BFC, SLCOE">
                        <p class="mt-1 text-xs text-gray-500">Unique identifier for your church</p>
                        @error('church_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="pastor_signature" class="block text-sm font-medium text-gray-700 mb-1">Pastor's Title</label>
                        <input type="text" name="pastor_signature" id="pastor_signature" 
                               value="{{ $settings->where('key', 'pastor_signature')->first()->value ?? old('pastor_signature') }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50"
                               placeholder="e.g., Senior Pastor, Lead Pastor">
                        <p class="mt-1 text-xs text-gray-500">Title for official documents</p>
                        @error('pastor_signature')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Bulletin & Document Settings -->
            <div class="space-y-4 col-span-1 md:col-span-2">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="fas fa-file-alt mr-2 text-primary-600"></i>
                    Bulletin & Document Settings
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="bulletin_prefix" class="block text-sm font-medium text-gray-700 mb-1">Bulletin Number Prefix</label>
                        <input type="text" name="bulletin_prefix" id="bulletin_prefix" 
                               value="{{ $settings->where('key', 'bulletin_prefix')->first()->value ?? old('bulletin_prefix') }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50"
                               placeholder="e.g., BUL-, DOC-">
                        <p class="mt-1 text-xs text-gray-500">Prefix for bulletin and document numbers</p>
                        @error('bulletin_prefix')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="bulletin_watermark" class="block text-sm font-medium text-gray-700 mb-1">Document Watermark</label>
                        <input type="text" name="bulletin_watermark" id="bulletin_watermark" 
                               value="{{ $settings->where('key', 'bulletin_watermark')->first()->value ?? old('bulletin_watermark') }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50"
                               placeholder="e.g., CONFIDENTIAL, DRAFT">
                        <p class="mt-1 text-xs text-gray-500">Watermark text for documents</p>
                        @error('bulletin_watermark')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="col-span-1 md:col-span-2">
                        <label for="bulletin_footer" class="block text-sm font-medium text-gray-700 mb-1">Document Footer Text</label>
                        <textarea name="bulletin_footer" id="bulletin_footer" rows="2" 
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50"
                                  placeholder="Footer text that appears on bulletins and documents">{{ $settings->where('key', 'bulletin_footer')->first()->value ?? old('bulletin_footer') }}</textarea>
                        @error('bulletin_footer')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Email Settings -->
            <div class="space-y-4 col-span-1 md:col-span-2">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="fas fa-envelope mr-2 text-primary-600"></i>
                    Email Configuration
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="email_from_address" class="block text-sm font-medium text-gray-700 mb-1">System Email Address</label>
                        <input type="email" name="email_from_address" id="email_from_address" 
                               value="{{ $settings->where('key', 'email_from_address')->first()->value ?? old('email_from_address') }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50"
                               placeholder="noreply@yourchurch.org">
                        <p class="mt-1 text-xs text-gray-500">Email address used for system notifications</p>
                        @error('email_from_address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="email_from_name" class="block text-sm font-medium text-gray-700 mb-1">System Email Name</label>
                        <input type="text" name="email_from_name" id="email_from_name" 
                               value="{{ $settings->where('key', 'email_from_name')->first()->value ?? old('email_from_name') }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50"
                               placeholder="Church Management System">
                        <p class="mt-1 text-xs text-gray-500">Display name for system emails</p>
                        @error('email_from_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-4 border-t border-gray-200">
            <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                <i class="fas fa-save mr-2"></i>
                Save Changes
            </button>
        </div>
    </form>
</div>

<script>
    // Update color input text when color picker changes
    document.getElementById('primary_color').addEventListener('input', function(e) {
        e.target.nextElementSibling.value = e.target.value;
    });
    
    document.getElementById('secondary_color').addEventListener('input', function(e) {
        e.target.nextElementSibling.value = e.target.value;
    });
</script>
@endsection
