<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Pricing Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used in the pricing-related views
    | for various messages, labels, and interface elements.
    |
    */

    'title' => 'Pricing Plans',
    'subtitle' => 'Choose the perfect plan for your real estate needs',
    
    'plans' => [
        'basic' => [
            'name' => 'Basic',
            'price' => 'month',
            'features' => [
                '5 property listings',
                'Basic search filters',
                'Email support',
                'Standard templates',
            ],
        ],
        'professional' => [
            'name' => 'Professional',
            'badge' => 'Most Popular',
            'features' => [
                'Unlimited property listings',
                'Advanced search filters',
                'Priority email support',
                'Premium templates',
                'Basic analytics',
                'Booking management',
            ],
            'cta' => 'Choose Plan',
        ],
        'enterprise' => [
            'name' => 'Enterprise',
            'description' => 'Everything in Professional',
            'features' => [
                'Advanced analytics dashboard',
                '24/7 phone support',
                'Custom branding',
                'API access',
                'Dedicated account manager',
            ],
            'cta' => 'Contact Sales',
        ],
    ],
    
    'common_features' => [
        'title' => 'All Plans Include',
        'features' => [
            'Secure hosting',
            'Mobile responsive',
            'Multi-language support',
            'Regular updates',
            'Data backup',
            'SSL security',
        ],
    ],
    
    'faq' => [
        'title' => 'Have questions about our pricing?',
    ],
    
    'cta' => [
        'get_started' => 'Get Started',
    ],
];
