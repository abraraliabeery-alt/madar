# Dynamic Attributes Implementation

## Overview
This implementation makes the "تفاصيل العقار" (Property Details) section dynamic based on the selected category and its linked attributes. The attributes are loaded dynamically via AJAX when a category is selected.

## What Was Implemented

### 1. API Endpoint
- **File**: `app/Http/Controllers/Api/ApiAttributeController.php`
- **Route**: `/api/v1/attributes/by-category`
- **Functionality**: Returns attributes for a specific category with translations

### 2. Updated Controllers
- **AdminProductController**: Removed static attributes loading
- **FacilityProductController**: Removed static attributes loading
- Both controllers now rely on dynamic loading via AJAX

### 3. Updated Views

#### Admin Views
- **Create**: `resources/views/admin/products/create.blade.php`
- **Edit**: `resources/views/admin/products/edit.blade.php`
- **Changes**: 
  - Replaced static attributes loop with dynamic container
  - Added JavaScript for AJAX loading
  - Handles both create and edit scenarios

#### Facility Views
- **Create**: `resources/views/facility/products/create.blade.php`
- **Edit**: `resources/views/facility/products/edit.blade.php`
- **Changes**:
  - Added attributes section (was missing)
  - Added JavaScript for AJAX loading
  - Consistent with admin interface

### 4. Translation Files
- **Arabic**: `resources/lang/ar/facility.php`
- **English**: `resources/lang/en/facility.php`
- **Added Keys**:
  - `attributes` - "الخصائص" / "Attributes"
  - `select_category_for_attributes` - "اختر فئة أولاً لعرض الخصائص المتاحة" / "Select a category first to show available attributes"
  - `no_attributes_available` - "لا توجد خصائص متاحة لهذه الفئة" / "No attributes available for this category"
  - `error_loading_attributes` - "حدث خطأ في تحميل الخصائص" / "Error loading attributes"

### 5. Routes
- **File**: `routes/api.php`
- **Added**:
  - `GET /api/v1/attributes` - Get all attributes
  - `GET /api/v1/attributes/by-category` - Get attributes by category

## How It Works

### 1. Category Selection
When a user selects a category in the dropdown:
1. JavaScript event listener triggers
2. AJAX request sent to `/api/v1/attributes/by-category`
3. API returns attributes for that category with translations

### 2. Dynamic Rendering
The JavaScript dynamically creates form fields for each attribute:
- Shows attribute name with translation
- Displays required indicator (*) if needed
- Shows icon if available
- Pre-fills values for edit mode
- Maintains old input values on validation errors

### 3. Data Persistence
- Attributes are saved via the existing `ProductAttributeValue` model
- Values are stored in the `product_attribute_values` table
- Edit mode preserves existing attribute values

## Database Structure

### Existing Tables
- `attributes` - Stores attribute definitions
- `attribute_translations` - Stores attribute names in different languages
- `product_attribute_values` - Stores attribute values for products
- `categories` - Categories that can have attributes

### Relationships
- `Category` has many `Attribute`
- `Attribute` belongs to `Category`
- `Product` belongs to many `Attribute` through `ProductAttributeValue`

## Features

### 1. Dynamic Loading
- Attributes load only when category is selected
- Reduces initial page load time
- Provides better user experience

### 2. Translation Support
- Attributes display in the current locale
- Supports Arabic and English
- Fallback to original name if translation missing

### 3. Validation
- Required attributes are marked with asterisk (*)
- Form validation works with dynamic fields
- Error handling for AJAX failures

### 4. Edit Mode Support
- Pre-fills existing attribute values
- Preserves data when switching categories
- Handles both create and edit scenarios

## Usage

### For Admins
1. Go to Admin → Products → Create/Edit
2. Select a category
3. Attributes section will populate automatically
4. Fill in attribute values
5. Save the product

### For Facility Users
1. Go to Facility → Products → Create/Edit
2. Select a category
3. Attributes section will populate automatically
4. Fill in attribute values
5. Save the product

## Testing

### API Testing
```bash
# Test the API endpoint
curl "http://localhost:8000/api/v1/attributes/by-category?category_id=1"
```

### Database Seeding
```bash
# Run the attribute seeder
php artisan db:seed --class=AttributeSeeder
```

## Future Enhancements

1. **Attribute Types**: Support different input types (number, text, select, boolean)
2. **Validation Rules**: Custom validation rules per attribute
3. **Default Values**: Set default values for attributes
4. **Bulk Operations**: Bulk edit attributes for multiple products
5. **Search/Filter**: Search and filter products by attribute values

## Files Modified

### New Files
- `app/Http/Controllers/Api/ApiAttributeController.php`

### Modified Files
- `routes/api.php`
- `app/Http/Controllers/Admin/AdminProductController.php`
- `app/Http/Controllers/Facility/FacilityProductController.php`
- `resources/views/admin/products/create.blade.php`
- `resources/views/admin/products/edit.blade.php`
- `resources/views/facility/products/create.blade.php`
- `resources/views/facility/products/edit.blade.php`
- `resources/lang/ar/facility.php`
- `resources/lang/en/facility.php`

### Existing Files (No Changes)
- `app/Models/Category.php` (already had attributes relationship)
- `app/Models/Attribute.php` (already had translations)
- `app/Models/Product.php` (already had attributes relationship)
- `database/seeders/AttributeSeeder.php` (already existed)
