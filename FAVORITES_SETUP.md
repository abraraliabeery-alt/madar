# Favorites Functionality Setup Guide

This guide explains how to set up and use the favorites functionality in the Aqar application.

## Overview

The favorites system allows users to:
- Add/remove products to/from their favorites
- Add/remove facilities to/from their favorites
- View all their favorite items in a dedicated page
- Manage favorites through the client dashboard

## Database Setup

### 1. Run the Migration

The favorites table uses a polymorphic relationship to store favorites for different types of items:

```bash
php artisan migrate
```

This will create the `favorites` table with the following structure:
- `id` - Primary key
- `user_id` - Foreign key to users table
- `favoritable_id` - ID of the favorited item
- `favoritable_type` - Class name of the favorited item (e.g., App\Models\Product)
- `created_at` - Timestamp when item was added to favorites
- `updated_at` - Timestamp when item was last updated

### 2. Seed Sample Data (Optional)

To populate the favorites table with sample data:

```bash
php artisan db:seed --class=FavoriteSeeder
```

## Models and Relationships

### User Model

The User model has the following relationships:

```php
// Favorite products (polymorphic)
public function favoriteProducts()
{
    return $this->morphedByMany(Product::class, 'favoritable', 'favorites', 'user_id', 'favoritable_id');
}

// Favorite facilities (polymorphic)
public function favoriteFacilities()
{
    return $this->morphedByMany(Facility::class, 'favoritable', 'favorites', 'user_id', 'favoritable_id');
}
```

### Product Model

```php
// Users who favorited this product
public function favoritedBy()
{
    return $this->morphToMany(User::class, 'favoritable', 'favorites', 'favoritable_id', 'user_id');
}
```

### Facility Model

```php
// Users who favorited this facility
public function favoritedBy()
{
    return $this->morphToMany(User::class, 'favoritable', 'favorites', 'favoritable_id', 'user_id');
}
```

## Controllers

### ClientController

The main controller for managing favorites in the client panel:

- `favorites()` - Display all favorites (products and facilities)
- `favoriteProducts()` - Display only favorite products
- `favoriteFacilities()` - Display only favorite facilities
- `addToFavorites(Product $product)` - Add a product to favorites
- `removeFromFavorites(Product $product)` - Remove a product from favorites
- `addFacilityToFavorites(Facility $facility)` - Add a facility to favorites
- `removeFacilityFromFavorites(Facility $facility)` - Remove a facility from favorites

### Public Controllers

Public controllers for adding/removing favorites from public pages:

- `ProductController::addToFavorites()` - Add product to favorites from public page
- `ProductController::removeFromFavorites()` - Remove product from favorites from public page
- `FacilityController::addToFavorites()` - Add facility to favorites from public page
- `FacilityController::removeFromFavorites()` - Remove facility from favorites from public page

## Routes

### Client Routes

```php
// Favorites management
Route::get('/favorites', [ClientController::class, 'favorites'])->name('favorites');
Route::get('/favorites/products', [ClientController::class, 'favoriteProducts'])->name('favorites.products');
Route::get('/favorites/facilities', [ClientController::class, 'favoriteFacilities'])->name('favorites.facilities');
Route::post('/favorites/products/{product}', [ClientController::class, 'addToFavorites'])->name('favorites.add-product');
Route::delete('/favorites/products/{product}', [ClientController::class, 'removeFromFavorites'])->name('favorites.remove-product');
Route::post('/favorites/facilities/{facility}', [ClientController::class, 'addFacilityToFavorites'])->name('favorites.add-facility');
Route::delete('/favorites/facilities/{facility}', [ClientController::class, 'removeFacilityFromFavorites'])->name('favorites.remove-facility');
```

### Public Routes

```php
// Public favorites (for non-authenticated users)
Route::post('/products/{product}/favorite', [ProductController::class, 'addToFavorites'])->name('products.favorite.add');
Route::delete('/products/{product}/favorite', [ProductController::class, 'removeFromFavorites'])->name('products.favorite.remove');
Route::post('/facilities/{facility}/favorite', [FacilityController::class, 'addToFavorites'])->name('facilities.favorite.add');
Route::delete('/facilities/{facility}/favorite', [FacilityController::class, 'removeFromFavorites'])->name('facilities.favorite.remove');
```

## Views

### Client Favorites Page

The main favorites page is located at `resources/views/client/favorites.blade.php` and includes:

- Tabbed interface for products and facilities
- Grid layout for displaying favorite items
- Remove from favorites functionality
- Links to view details and book items
- Empty state messages when no favorites exist
- Pagination support

### Dashboard Integration

The favorites are integrated into the client dashboard at `resources/views/client/dashboard.blade.php`:

- Quick access to favorites page
- Count of favorite products
- Recent favorites display

## Usage Examples

### Adding a Product to Favorites

```php
// In a controller
$user = Auth::user();
$product = Product::find($id);

if (!$user->favoriteProducts()->where('products.id', $product->id)->exists()) {
    $user->favoriteProducts()->attach($product->id);
    return redirect()->back()->with('success', 'Product added to favorites');
}
```

### Removing a Product from Favorites

```php
$user = Auth::user();
$product = Product::find($id);
$user->favoriteProducts()->detach($product->id);

return redirect()->back()->with('success', 'Product removed from favorites');
```

### Checking if an Item is Favorited

```php
$user = Auth::user();
$isFavorited = $user->favoriteProducts()->where('products.id', $product->id)->exists();
```

### Getting User's Favorite Products

```php
$user = Auth::user();
$favoriteProducts = $user->favoriteProducts()
    ->with(['facility', 'category', 'status'])
    ->paginate(12);
```

## Testing

### Manual Testing

1. Run the test file:
   ```bash
   php test_favorites.php
   ```

2. Test through the web interface:
   - Log in as a client user
   - Navigate to `/client/favorites`
   - Add/remove products and facilities
   - Verify the changes are reflected

### Automated Testing

Create feature tests for the favorites functionality:

```php
public function test_user_can_add_product_to_favorites()
{
    $user = User::factory()->create();
    $product = Product::factory()->create();
    
    $this->actingAs($user)
         ->post(route('client.favorites.add-product', $product))
         ->assertRedirect();
    
    $this->assertTrue($user->favoriteProducts()->where('products.id', $product->id)->exists());
}
```

## Troubleshooting

### Common Issues

1. **Migration fails**: Ensure the database connection is working and you have proper permissions
2. **Favorites not showing**: Check if the relationships are properly defined in the models
3. **Polymorphic relationship errors**: Verify the table structure matches the migration

### Debug Commands

```bash
# Check if favorites table exists
php artisan tinker
>>> Schema::hasTable('favorites')

# Check table structure
php artisan tinker
>>> Schema::getColumnListing('favorites')

# Test relationships
php artisan tinker
>>> $user = App\Models\User::first();
>>> $user->favoriteProducts()->count();
```

## Security Considerations

- All favorite operations require authentication
- Users can only manage their own favorites
- CSRF protection is enabled for all form submissions
- Input validation is implemented for all user inputs

## Performance Optimization

- Use eager loading (`with()`) when fetching favorites with related data
- Implement pagination for large lists of favorites
- Consider caching frequently accessed favorites data
- Use database indexes on frequently queried columns

## Future Enhancements

- Add favorite categories/tags
- Implement favorite sharing
- Add favorite notifications
- Create favorite analytics and insights
- Support for favorite collections
