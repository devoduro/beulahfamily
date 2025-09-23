@props(['field', 'name', 'value' => null, 'errors' => null])

@php
    $fieldId = 'field_' . $name;
    $hasError = $errors && $errors->has($name);
    $errorMessage = $hasError ? $errors->first($name) : null;
    $isRequired = $field['required'] ?? false;
    $fieldType = $field['type'] ?? 'text';
    $fieldLabel = $field['label'] ?? ucfirst(str_replace('_', ' ', $name));
    $fieldPlaceholder = $field['placeholder'] ?? '';
    $fieldOptions = $field['options'] ?? [];
    $conditional = $field['conditional'] ?? null;
@endphp

<div class="form-field-wrapper {{ $conditional ? 'conditional-field' : '' }}" 
     @if($conditional) 
        data-conditional="{{ json_encode($conditional) }}" 
        style="display: none;"
     @endif>
    
    <!-- Field Label -->
    <label for="{{ $fieldId }}" class="block text-sm font-semibold text-gray-700 mb-2">
        {{ $fieldLabel }}
        @if($isRequired)
            <span class="text-red-500 ml-1">*</span>
        @endif
    </label>

    <!-- Field Input Based on Type -->
    @switch($fieldType)
        @case('text')
        @case('email')
        @case('tel')
        @case('url')
        @case('number')
        @case('date')
            <input type="{{ $fieldType }}" 
                   id="{{ $fieldId }}" 
                   name="{{ $name }}" 
                   value="{{ old($name, $value) }}"
                   placeholder="{{ $fieldPlaceholder }}"
                   class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 {{ $hasError ? 'border-red-500 ring-red-200' : '' }}"
                   {{ $isRequired ? 'required' : '' }}
                   @if($fieldType === 'number' && isset($field['min'])) min="{{ $field['min'] }}" @endif
                   @if($fieldType === 'number' && isset($field['max'])) max="{{ $field['max'] }}" @endif
                   @if(isset($field['max_length'])) maxlength="{{ $field['max_length'] }}" @endif>
            @break

        @case('textarea')
            <textarea id="{{ $fieldId }}" 
                      name="{{ $name }}" 
                      placeholder="{{ $fieldPlaceholder }}"
                      rows="{{ $field['rows'] ?? 4 }}"
                      class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 resize-none {{ $hasError ? 'border-red-500 ring-red-200' : '' }}"
                      {{ $isRequired ? 'required' : '' }}
                      @if(isset($field['max_length'])) maxlength="{{ $field['max_length'] }}" @endif>{{ old($name, $value) }}</textarea>
            @break

        @case('select')
            <select id="{{ $fieldId }}" 
                    name="{{ $name }}" 
                    class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 {{ $hasError ? 'border-red-500 ring-red-200' : '' }}"
                    {{ $isRequired ? 'required' : '' }}
                    @if($conditional) onchange="handleConditionalFields(this)" @endif>
                @if(!$isRequired)
                    <option value="">-- Select {{ $fieldLabel }} --</option>
                @endif
                @foreach($fieldOptions as $optionValue => $optionLabel)
                    <option value="{{ $optionValue }}" {{ old($name, $value) == $optionValue ? 'selected' : '' }}>
                        {{ $optionLabel }}
                    </option>
                @endforeach
            </select>
            @break

        @case('checkbox')
            <div class="flex items-center">
                <input type="checkbox" 
                       id="{{ $fieldId }}" 
                       name="{{ $name }}" 
                       value="1"
                       class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500 focus:ring-2"
                       {{ old($name, $value) ? 'checked' : '' }}
                       {{ $isRequired ? 'required' : '' }}>
                <label for="{{ $fieldId }}" class="ml-3 text-sm text-gray-700">
                    {{ $fieldPlaceholder ?: $fieldLabel }}
                </label>
            </div>
            @break

        @case('radio')
            <div class="space-y-3">
                @foreach($fieldOptions as $optionValue => $optionLabel)
                    <div class="flex items-center">
                        <input type="radio" 
                               id="{{ $fieldId }}_{{ $optionValue }}" 
                               name="{{ $name }}" 
                               value="{{ $optionValue }}"
                               class="w-5 h-5 text-purple-600 border-gray-300 focus:ring-purple-500 focus:ring-2"
                               {{ old($name, $value) == $optionValue ? 'checked' : '' }}
                               {{ $isRequired ? 'required' : '' }}>
                        <label for="{{ $fieldId }}_{{ $optionValue }}" class="ml-3 text-sm text-gray-700">
                            {{ $optionLabel }}
                        </label>
                    </div>
                @endforeach
            </div>
            @break

        @case('file')
            <input type="file" 
                   id="{{ $fieldId }}" 
                   name="{{ $name }}{{ isset($field['multiple']) && $field['multiple'] ? '[]' : '' }}" 
                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 transition-all duration-200"
                   {{ $isRequired ? 'required' : '' }}
                   @if(isset($field['accept'])) accept="{{ $field['accept'] }}" @endif
                   @if(isset($field['multiple']) && $field['multiple']) multiple @endif>
            @if(isset($field['help_text']))
                <p class="mt-2 text-xs text-gray-500">{{ $field['help_text'] }}</p>
            @endif
            @break

        @default
            <input type="text" 
                   id="{{ $fieldId }}" 
                   name="{{ $name }}" 
                   value="{{ old($name, $value) }}"
                   placeholder="{{ $fieldPlaceholder }}"
                   class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 {{ $hasError ? 'border-red-500 ring-red-200' : '' }}"
                   {{ $isRequired ? 'required' : '' }}>
    @endswitch

    <!-- Field Help Text -->
    @if(isset($field['help_text']) && $fieldType !== 'file')
        <p class="mt-2 text-sm text-gray-500">{{ $field['help_text'] }}</p>
    @endif

    <!-- Error Message -->
    @if($hasError)
        <p class="mt-2 text-sm text-red-600 flex items-center">
            <i class="fas fa-exclamation-circle mr-1"></i>
            {{ $errorMessage }}
        </p>
    @endif
</div>

<!-- JavaScript for Conditional Fields -->
@once
<script>
function handleConditionalFields(selectElement) {
    const selectedValue = selectElement.value;
    const fieldName = selectElement.name;
    
    // Find all conditional fields that depend on this field
    document.querySelectorAll('.conditional-field').forEach(function(conditionalField) {
        const conditionalData = JSON.parse(conditionalField.dataset.conditional || '{}');
        
        if (conditionalData[fieldName]) {
            if (conditionalData[fieldName] === selectedValue) {
                conditionalField.style.display = 'block';
                // Make required if it was originally required
                const input = conditionalField.querySelector('input, select, textarea');
                if (input && input.hasAttribute('data-originally-required')) {
                    input.setAttribute('required', 'required');
                }
            } else {
                conditionalField.style.display = 'none';
                // Remove required attribute when hidden
                const input = conditionalField.querySelector('input, select, textarea');
                if (input) {
                    if (input.hasAttribute('required')) {
                        input.setAttribute('data-originally-required', 'true');
                        input.removeAttribute('required');
                    }
                    input.value = ''; // Clear the value when hidden
                }
            }
        }
    });
}

// Initialize conditional fields on page load
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('select[onchange*="handleConditionalFields"]').forEach(function(select) {
        handleConditionalFields(select);
    });
});
</script>
@endonce
