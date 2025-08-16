<!DOCTYPE html>
<html lang="{{ $currentLanguage }}" dir="@direction">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('public.home.title') - Language Demo</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- RTL CSS for Arabic -->
    @if($isRTL)
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    @endif
    
    <style>
        .demo-section {
            margin: 2rem 0;
            padding: 2rem;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
        }
        
        .rtl-demo {
            direction: rtl;
            text-align: right;
        }
        
        .ltr-demo {
            direction: ltr;
            text-align: left;
        }
        
        .language-info {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 0.375rem;
            margin-bottom: 1rem;
        }
        
        .translation-example {
            background-color: #e9ecef;
            padding: 1rem;
            border-radius: 0.375rem;
            margin: 1rem 0;
        }
        
        .utility-demo {
            background-color: #fff3cd;
            padding: 1rem;
            border-radius: 0.375rem;
            margin: 1rem 0;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>@lang('public.home.title')</h1>
                    <x-language-switcher />
                </div>
            </div>
        </div>

        <!-- Language Information -->
        <div class="demo-section">
            <h2>Language Information</h2>
            <div class="language-info">
                <p><strong>Current Language:</strong> {{ $currentLanguage }}</p>
                <p><strong>Language Name:</strong> {{ $currentLanguageData['name'] }}</p>
                <p><strong>Native Name:</strong> {{ $currentLanguageData['native'] }}</p>
                <p><strong>Flag:</strong> {{ $currentLanguageData['flag'] }}</p>
                <p><strong>Direction:</strong> {{ $isRTL ? 'RTL (Right-to-Left)' : 'LTR (Left-to-Right)' }}</p>
                <p><strong>HTML Dir Attribute:</strong> <code>dir="@direction"</code></p>
            </div>
        </div>

        <!-- Translation Examples -->
        <div class="demo-section">
            <h2>Translation Examples</h2>
            
            <div class="translation-example">
                <h4>Basic Translation</h4>
                <p><strong>Home Title:</strong> @lang('public.home.title')</p>
                <p><strong>Home Subtitle:</strong> @lang('public.home.subtitle')</p>
                <p><strong>Search Button:</strong> @lang('public.home.search_button')</p>
            </div>

            <div class="translation-example">
                <h4>Navigation</h4>
                <p><strong>Home:</strong> @lang('public.navigation.home')</p>
                <p><strong>Facilities:</strong> @lang('public.navigation.facilities')</p>
                <p><strong>Products:</strong> @lang('public.navigation.products')</p>
                <p><strong>About:</strong> @lang('public.navigation.about')</p>
                <p><strong>Contact:</strong> @lang('public.navigation.contact')</p>
            </div>

            <div class="translation-example">
                <h4>Common Actions</h4>
                <p><strong>View Details:</strong> @lang('public.common.view')</p>
                <p><strong>Edit:</strong> @lang('public.common.edit')</p>
                <p><strong>Delete:</strong> @lang('public.common.delete')</p>
                <p><strong>Save:</strong> @lang('public.common.save')</p>
                <p><strong>Cancel:</strong> @lang('public.common.cancel')</p>
            </div>
        </div>

        <!-- RTL/LTR Utility Examples -->
        <div class="demo-section">
            <h2>RTL/LTR Utility Examples</h2>
            
            <div class="utility-demo">
                <h4>Text Alignment</h4>
                <p class="@textAlign">This text is aligned using @textAlign directive</p>
                <p class="@textAlign">هذا النص محاذي باستخدام توجيه @textAlign</p>
            </div>

            <div class="utility-demo">
                <h4>Float Positioning</h4>
                <div class="clearfix">
                    <div class="@float('left') p-3 bg-primary text-white">Left Float</div>
                    <div class="@float('right') p-3 bg-success text-white">Right Float</div>
                </div>
            </div>

            <div class="utility-demo">
                <h4>Margin and Padding</h4>
                <div class="row">
                    <div class="col-6">
                        <div class="@margin('left') p-3 bg-info text-white">Left Margin</div>
                    </div>
                    <div class="col-6">
                        <div class="@margin('right') p-3 bg-warning text-white">Right Margin</div>
                    </div>
                </div>
            </div>

            <div class="utility-demo">
                <h4>Flexbox Utilities</h4>
                <div class="d-flex @flexDirection">
                    <div class="p-2 bg-primary text-white">Item 1</div>
                    <div class="p-2 bg-secondary text-white">Item 2</div>
                    <div class="p-2 bg-success text-white">Item 3</div>
                </div>
            </div>
        </div>

        <!-- Language Switcher Examples -->
        <div class="demo-section">
            <h2>Language Switcher Examples</h2>
            
            <div class="row">
                <div class="col-md-6">
                    <h4>Dropdown Style</h4>
                    <x-language-switcher />
                </div>
                <div class="col-md-6">
                    <h4>Inline Style</h4>
                    <x-language-switcher :dropdown="false" />
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <h4>Without Flags</h4>
                    <x-language-switcher :show-flags="false" />
                </div>
                <div class="col-md-6">
                    <h4>Without Names</h4>
                    <x-language-switcher :show-names="false" />
                </div>
            </div>
        </div>

        <!-- Form Examples -->
        <div class="demo-section">
            <h2>Form Examples</h2>
            
            <form>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">@lang('public.contact.name')</label>
                            <input type="text" class="form-control" id="name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">@lang('public.contact.email')</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="subject" class="form-label">@lang('public.contact.subject')</label>
                    <input type="text" class="form-control" id="subject" required>
                </div>
                
                <div class="mb-3">
                    <label for="message" class="form-label">@lang('public.contact.message')</label>
                    <textarea class="form-control" id="message" rows="4" required></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">@lang('public.contact.submit')</button>
            </form>
        </div>

        <!-- Footer -->
        <div class="demo-section">
            <h2>Footer Examples</h2>
            
            <div class="row">
                <div class="col-md-3">
                    <h5>@lang('public.footer.about')</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">@lang('public.footer.services')</a></li>
                        <li><a href="#">@lang('public.footer.contact')</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>@lang('public.footer.services')</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">@lang('public.facilities.title')</a></li>
                        <li><a href="#">@lang('public.products.title')</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>@lang('public.footer.contact')</h5>
                    <ul class="list-unstyled">
                        <li>@lang('public.contact.address')</li>
                        <li>@lang('public.contact.phone')</li>
                        <li>@lang('public.contact.email')</li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>@lang('public.footer.follow_us')</h5>
                    <p>@lang('public.footer.newsletter')</p>
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="@lang('public.footer.email_placeholder')">
                        <button class="btn btn-outline-primary" type="button">@lang('public.footer.subscribe')</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Language switching demo
        document.addEventListener('DOMContentLoaded', function() {
            // Get language info
            fetch('/language-info')
                .then(response => response.json())
                .then(data => {
                    console.log('Language System Info:', data);
                });
        });
    </script>
</body>
</html>
