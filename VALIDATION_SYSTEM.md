# Global Validation Error Handling System

This document explains how to use the new global validation error handling system implemented in the Aqar application.

## Overview

The system provides a centralized way to handle validation errors across the entire application, with both server-side and client-side error display capabilities.

## Features

1. **Global Error Display**: A fixed notification that appears at the top of the page for validation errors
2. **Form Components**: Reusable Blade components that automatically handle validation styling
3. **Multiple Message Types**: Support for error, success, and info messages
4. **Auto-hide**: Messages automatically disappear after a set time
5. **Manual Control**: Ability to show/hide messages programmatically

## Components

### 1. Validation Errors Component

Use this component to display validation errors for a specific field or all form errors:

```blade
{{-- Display errors for a specific field --}}
<x-validation-errors field="email" />

{{-- Display all form errors --}}
<x-validation-errors />
```

### 2. Form Input Component

Automatically handles validation styling and error display:

```blade
<x-form-input 
    name="email"
    label="Email Address"
    type="email"
    required="true"
    placeholder="Enter your email"
    helpText="We'll never share your email"
/>
```

### 3. Form Textarea Component

```blade
<x-form-textarea 
    name="description"
    label="Description"
    rows="4"
    required="true"
/>
```

### 4. Form Select Component

```blade
<x-form-select 
    name="category_id"
    label="Category"
    :options="$categories->pluck('name', 'id')->toArray()"
    :selected="$product->category_id"
    placeholder="Select a category"
    required="true"
/>
```

### 5. Form File Component

```blade
<x-form-file 
    name="image"
    label="Upload Image"
    accept="image/*"
    :helpText="__('facility.form.image_help')"
/>
```

## JavaScript Functions

### Show Global Validation Errors

```javascript
// Show a single error message
showGlobalValidationErrors('This field is required');

// Show multiple error messages
showGlobalValidationErrors(['Name is required', 'Email is invalid']);

// Show object with field-specific errors
showGlobalValidationErrors({
    'name': ['Name is required'],
    'email': ['Email is invalid', 'Email must be unique']
});
```

### Show Success Message

```javascript
showGlobalSuccessMessage('Product created successfully!');
```

### Show Info Message

```javascript
showGlobalInfoMessage('Please check your email for verification.');
```

### Hide Messages

```javascript
hideGlobalValidationErrors();
```

## Usage Examples

### Basic Form with Validation

```blade
<form method="POST" action="{{ route('products.store') }}">
    @csrf
    
    {{-- Display all form errors at the top --}}
    <x-validation-errors />
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <x-form-input 
            name="name"
            label="Product Name"
            required="true"
        />
        
        <x-form-select 
            name="category_id"
            label="Category"
            :options="$categories->pluck('name', 'id')->toArray()"
            placeholder="Select category"
            required="true"
        />
    </div>
    
    <x-form-textarea 
        name="description"
        label="Description"
        rows="4"
    />
    
    <button type="submit">Create Product</button>
</form>
```

### AJAX Form Submission

```javascript
document.getElementById('product-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/api/products', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showGlobalSuccessMessage('Product created successfully!');
            // Redirect or update UI
        } else if (data.errors) {
            showGlobalValidationErrors(data.errors);
        }
    })
    .catch(error => {
        showGlobalValidationErrors('An error occurred. Please try again.');
    });
});
```

### Controller Response

```php
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'category_id' => 'required|exists:categories,id',
    ]);

    try {
        $product = Product::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'product' => $product
        ]);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to create product'
        ], 500);
    }
}
```

## Configuration

### Translation Keys

Add these keys to your language files:

```php
// resources/lang/en/validation.php
'errors' => [
    'title' => 'Validation Error',
    'close' => 'Close',
],

'success' => [
    'title' => 'Success',
],

'info' => [
    'title' => 'Information',
],
```

```php
// resources/lang/ar/validation.php
'errors' => [
    'title' => 'خطأ في التحقق من صحة البيانات',
    'close' => 'إغلاق',
],

'success' => [
    'title' => 'تم بنجاح',
],

'info' => [
    'title' => 'معلومات',
],
```

## Benefits

1. **Consistency**: All forms use the same validation styling and error display
2. **Maintainability**: Changes to validation UI only need to be made in one place
3. **User Experience**: Clear, consistent error messages across the application
4. **Developer Experience**: Simple, reusable components reduce boilerplate code
5. **Accessibility**: Proper ARIA labels and semantic HTML structure

## Migration from Old System

To migrate existing forms:

1. Replace individual `@error` directives with form components
2. Remove manual validation styling classes
3. Add `<x-validation-errors />` at the top of forms for general error display
4. Update JavaScript to use the new global functions

## Best Practices

1. Always use the form components for new forms
2. Display general form errors at the top of the form
3. Use specific field error display for complex forms
4. Provide helpful error messages in your validation rules
5. Test both server-side and client-side validation
6. Consider using AJAX for better user experience on complex forms
7. **Handle old input values properly for dynamic content** (see section below)

## Handling Dynamic Content with Old Input Values

When working with dynamically loaded content (like attributes based on category selection), you need to handle old input values properly to maintain user input after validation failures.

### For Create Forms

```javascript
document.addEventListener('DOMContentLoaded', function() {
    // Store old input values from Laravel session
    const oldInputs = @json(old('attributes', []));
    
    // Load attributes based on selected category
    const categorySelect = document.getElementById('category_id');
    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            const categoryId = this.value;
            if (categoryId) {
                loadAttributesByCategory(categoryId);
            }
        });
        
        // Load attributes on page load if category is selected and there are old inputs
        const initialCategoryId = categorySelect.value;
        if (initialCategoryId && Object.keys(oldInputs).length > 0) {
            loadAttributesByCategory(initialCategoryId);
        }
    }

    function getOldAttributeValue(attributeId) {
        // First try to get from old inputs (Laravel session)
        if (oldInputs[attributeId] && oldInputs[attributeId].value) {
            return oldInputs[attributeId].value;
        }
        
        // Fallback to existing input element (if any)
        const input = document.querySelector(`input[name="attributes[${attributeId}][value]"]`);
        return input ? input.value : '';
    }
});
```

### For Edit Forms

```javascript
document.addEventListener('DOMContentLoaded', function() {
    // Store old input values from Laravel session and existing data
    const oldInputs = @json(old('attributes', []));
    const productAttributes = @json($product->attributes->keyBy('id')->map(function($attr) { 
        return $attr->pivot->value; 
    }));
    
    function getCurrentAttributeValue(attributeId) {
        // First try to get from old inputs (Laravel session) - highest priority
        if (oldInputs[attributeId] && oldInputs[attributeId].value) {
            return oldInputs[attributeId].value;
        }
        
        // Then try to get from existing input element
        const input = document.querySelector(`input[name="attributes[${attributeId}][value]"]`);
        if (input && input.value) {
            return input.value;
        }
        
        // Finally, try to get from product's existing attributes
        return productAttributes[attributeId] || '';
    }
});
```

### Priority Order for Value Retrieval

1. **Old input values** (from Laravel session) - highest priority
2. **Existing input elements** (if any)
3. **Database values** (for edit forms)
4. **Empty string** - fallback

This ensures that user input is preserved across form submissions and validation failures.
