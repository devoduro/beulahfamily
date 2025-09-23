<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ProgramType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'registration_fields',
        'display_settings',
        'allow_file_uploads',
        'allowed_file_types',
        'max_file_size',
        'max_files',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'registration_fields' => 'array',
        'display_settings' => 'array',
        'allowed_file_types' => 'array',
        'allow_file_uploads' => 'boolean',
        'is_active' => 'boolean',
        'max_file_size' => 'integer',
        'max_files' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Get all programs of this type
     */
    public function programs(): HasMany
    {
        return $this->hasMany(Program::class);
    }

    /**
     * Scope for active program types
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope ordered by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Auto-generate slug from name
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($programType) {
            if (empty($programType->slug)) {
                $programType->slug = Str::slug($programType->name);
            }
        });

        static::updating(function ($programType) {
            if ($programType->isDirty('name') && empty($programType->slug)) {
                $programType->slug = Str::slug($programType->name);
            }
        });
    }

    /**
     * Get display color
     */
    public function getDisplayColorAttribute(): string
    {
        return $this->display_settings['color'] ?? 'blue';
    }

    /**
     * Get display icon
     */
    public function getDisplayIconAttribute(): string
    {
        return $this->display_settings['icon'] ?? 'fas fa-calendar';
    }

    /**
     * Get gradient colors for display
     */
    public function getGradientColorsAttribute(): array
    {
        return $this->display_settings['gradient'] ?? ['from-blue-500', 'to-purple-500'];
    }

    /**
     * Get validation rules for registration fields
     */
    public function getValidationRules(): array
    {
        $rules = [];
        
        foreach ($this->registration_fields as $fieldName => $fieldConfig) {
            $fieldRules = [];
            
            if ($fieldConfig['required'] ?? false) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }
            
            switch ($fieldConfig['type']) {
                case 'email':
                    $fieldRules[] = 'email';
                    break;
                case 'tel':
                    $fieldRules[] = 'string';
                    break;
                case 'number':
                    $fieldRules[] = 'numeric';
                    break;
                case 'date':
                    $fieldRules[] = 'date';
                    break;
                case 'select':
                    if (isset($fieldConfig['options'])) {
                        $fieldRules[] = 'in:' . implode(',', array_keys($fieldConfig['options']));
                    }
                    break;
                default:
                    $fieldRules[] = 'string';
            }
            
            if (isset($fieldConfig['max_length'])) {
                $fieldRules[] = 'max:' . $fieldConfig['max_length'];
            }
            
            $rules[$fieldName] = implode('|', $fieldRules);
        }
        
        return $rules;
    }

    /**
     * Predefined program types
     */
    public static function getDefaultTypes(): array
    {
        return [
            [
                'name' => 'Beulah Family Annual',
                'slug' => 'beulah_family_annual',
                'description' => 'Annual family gathering and spiritual retreat for all church members',
                'registration_fields' => [
                    'full_name' => [
                        'type' => 'text',
                        'label' => 'Full Name',
                        'required' => true,
                        'placeholder' => 'Enter your full name'
                    ],
                    'phone_number' => [
                        'type' => 'tel',
                        'label' => 'Phone Number',
                        'required' => true,
                        'placeholder' => 'Enter your phone number'
                    ],
                    'email' => [
                        'type' => 'email',
                        'label' => 'Email Address',
                        'required' => true,
                        'placeholder' => 'Enter your email address'
                    ],
                    'residential_address' => [
                        'type' => 'textarea',
                        'label' => 'Residential Address',
                        'required' => true,
                        'placeholder' => 'Enter your full residential address'
                    ],
                    'date_of_birth' => [
                        'type' => 'date',
                        'label' => 'Date of Birth',
                        'required' => false
                    ],
                    'occupation' => [
                        'type' => 'text',
                        'label' => 'Occupation',
                        'required' => false,
                        'placeholder' => 'What is your occupation?'
                    ],
                    'emergency_contact_name' => [
                        'type' => 'text',
                        'label' => 'Emergency Contact Name',
                        'required' => true,
                        'placeholder' => 'Emergency contact person'
                    ],
                    'emergency_contact_phone' => [
                        'type' => 'tel',
                        'label' => 'Emergency Contact Phone',
                        'required' => true,
                        'placeholder' => 'Emergency contact phone number'
                    ],
                    'dietary_requirements' => [
                        'type' => 'textarea',
                        'label' => 'Dietary Requirements',
                        'required' => false,
                        'placeholder' => 'Any special dietary needs or allergies?'
                    ],
                    'how_heard_about' => [
                        'type' => 'select',
                        'label' => 'How did you hear about this program?',
                        'required' => true,
                        'options' => [
                            'church_announcement' => 'Church Announcement',
                            'pastor' => 'Pastor/Church Leader',
                            'friend' => 'Friend/Family Member',
                            'social_media' => 'Social Media',
                            'website' => 'Church Website',
                            'flyer' => 'Flyer/Poster',
                            'other' => 'Other'
                        ]
                    ],
                    'special_needs' => [
                        'type' => 'textarea',
                        'label' => 'Special Needs or Accommodations',
                        'required' => false,
                        'placeholder' => 'Any special accommodations needed?'
                    ],
                    'additional_info' => [
                        'type' => 'textarea',
                        'label' => 'Additional Information',
                        'required' => false,
                        'placeholder' => 'Any other information you would like to share?'
                    ]
                ],
                'display_settings' => [
                    'color' => 'green',
                    'icon' => 'fas fa-users',
                    'gradient' => ['from-green-500', 'to-blue-500']
                ],
                'allow_file_uploads' => false,
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'ERGATES Conference',
                'slug' => 'ergates_conference',
                'description' => 'Business networking and entrepreneurship conference',
                'registration_fields' => [
                    'business_name' => [
                        'type' => 'text',
                        'label' => 'Business Name',
                        'required' => true,
                        'placeholder' => 'Enter your business name'
                    ],
                    'business_type' => [
                        'type' => 'select',
                        'label' => 'Business Type',
                        'required' => true,
                        'options' => [
                            'retail' => 'Retail (e.g. stores, shops)',
                            'wholesale' => 'Wholesale (e.g. distributors)',
                            'manufacturing' => 'Manufacturing (e.g. factories, producers)',
                            'service' => 'Service (e.g. consulting, accounting)',
                            'hospitality' => 'Hospitality (e.g. hotels, restaurants)',
                            'healthcare' => 'Healthcare (e.g. hospitals, clinics)',
                            'technology' => 'Technology (e.g. software, hardware)',
                            'finance' => 'Finance (e.g. banking, insurance)',
                            'nonprofit' => 'Nonprofit (e.g. charitable organizations)',
                            'transportation' => 'Transportation (e.g. logistics, shipping)',
                            'education' => 'Education (e.g. schools, universities)',
                            'construction' => 'Construction (e.g. general contractors)',
                            'agriculture' => 'Agriculture (e.g. farming, ranching)',
                            'energy' => 'Energy (e.g. oil, gas, renewable energy)',
                            'media' => 'Media (e.g. publishing, broadcasting)',
                            'legal' => 'Legal (e.g. law firms, legal services)',
                            'real_estate' => 'Real Estate (e.g. brokerage, development)',
                            'arts_entertainment' => 'Arts and Entertainment',
                            'professional_services' => 'Professional Services',
                            'other' => 'Other'
                        ]
                    ],
                    'business_type_other' => [
                        'type' => 'text',
                        'label' => 'Other Business Type',
                        'required' => false,
                        'placeholder' => 'Please specify if you selected "Other"',
                        'conditional' => ['business_type' => 'other']
                    ],
                    'services_offered' => [
                        'type' => 'textarea',
                        'label' => 'Services/Products Offered',
                        'required' => true,
                        'placeholder' => 'Describe the services or products your business offers'
                    ],
                    'business_address' => [
                        'type' => 'textarea',
                        'label' => 'Business Address',
                        'required' => true,
                        'placeholder' => 'Enter your business address'
                    ],
                    'contact_name' => [
                        'type' => 'text',
                        'label' => 'Contact Person Name',
                        'required' => true,
                        'placeholder' => 'Primary contact person'
                    ],
                    'business_phone' => [
                        'type' => 'tel',
                        'label' => 'Business Phone',
                        'required' => true,
                        'placeholder' => 'Business phone number'
                    ],
                    'whatsapp_number' => [
                        'type' => 'tel',
                        'label' => 'WhatsApp Number',
                        'required' => false,
                        'placeholder' => 'WhatsApp contact number'
                    ],
                    'email' => [
                        'type' => 'email',
                        'label' => 'Business Email',
                        'required' => true,
                        'placeholder' => 'Business email address'
                    ],
                    'website' => [
                        'type' => 'url',
                        'label' => 'Website',
                        'required' => false,
                        'placeholder' => 'Business website (if any)'
                    ],
                    'years_in_business' => [
                        'type' => 'number',
                        'label' => 'Years in Business',
                        'required' => false,
                        'placeholder' => 'How many years has your business been operating?'
                    ],
                    'number_of_employees' => [
                        'type' => 'select',
                        'label' => 'Number of Employees',
                        'required' => false,
                        'options' => [
                            '1' => 'Just me (1)',
                            '2-5' => '2-5 employees',
                            '6-10' => '6-10 employees',
                            '11-25' => '11-25 employees',
                            '26-50' => '26-50 employees',
                            '51-100' => '51-100 employees',
                            '100+' => 'More than 100 employees'
                        ]
                    ],
                    'special_offers' => [
                        'type' => 'textarea',
                        'label' => 'Special Offers for Conference Attendees',
                        'required' => false,
                        'placeholder' => 'Any special discounts or offers for conference participants?'
                    ],
                    'networking_goals' => [
                        'type' => 'textarea',
                        'label' => 'Networking Goals',
                        'required' => false,
                        'placeholder' => 'What do you hope to achieve from this conference?'
                    ],
                    'additional_info' => [
                        'type' => 'textarea',
                        'label' => 'Additional Information',
                        'required' => false,
                        'placeholder' => 'Any other information you would like to share?'
                    ]
                ],
                'display_settings' => [
                    'color' => 'orange',
                    'icon' => 'fas fa-briefcase',
                    'gradient' => ['from-orange-500', 'to-red-500']
                ],
                'allow_file_uploads' => true,
                'allowed_file_types' => ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'mp4', 'mov', 'avi', 'mp3', 'wav'],
                'max_file_size' => 102400, // 100MB in KB
                'max_files' => 5,
                'is_active' => true,
                'sort_order' => 2
            ]
        ];
    }
}
