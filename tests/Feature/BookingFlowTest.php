<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingFlowTest extends TestCase
{
    use RefreshDatabase;



    public function test_client_can_create_booking(): void
    {
        $client = $this->makeUser('client');
        $product = $this->makeActiveProduct();

        $response = $this->actingAs($client)->post('/client/bookings', [
            'product_id' => $product->id,
            'total_amount' => 500,
        ]);

        $response->assertRedirect(route('client.bookings.index'));
        $this->assertDatabaseHas('bookings', [
            'user_id' => $client->id,
            'product_id' => $product->id,
            'status' => 'reserved',
            'is_confirmed' => false,
            'is_paid' => false,
        ]);
    }

    public function test_booking_rejects_unverified_product(): void
    {
        $client = $this->makeUser('client');
        $product = $this->makeActiveProduct();
        $product->forceFill(['is_verified' => false])->save();

        $response = $this->actingAs($client)->from('/client/bookings')->post('/client/bookings', [
            'product_id' => $product->id,
            'total_amount' => 500,
        ]);

        $response->assertRedirect('/client/bookings');
        $this->assertDatabaseMissing('bookings', ['product_id' => $product->id]);
    }

    public function test_booking_requires_valid_product(): void
    {
        $client = $this->makeUser('client');

        $response = $this->actingAs($client)->post('/client/bookings', [
            'product_id' => 999999,
            'total_amount' => 500,
        ]);

        $response->assertSessionHasErrors('product_id');
        $this->assertSame(0, Booking::count());
    }

    public function test_guest_cannot_create_booking(): void
    {
        $product = $this->makeActiveProduct();

        $response = $this->post('/client/bookings', [
            'product_id' => $product->id,
            'total_amount' => 500,
        ]);

        $this->assertTrue($response->isRedirect());
        $this->assertSame(0, Booking::count());
    }

    public function test_client_bookings_index_lists_own_booking(): void
    {
        $client = $this->makeUser('client');
        $product = $this->makeActiveProduct();

        $this->actingAs($client)->post('/client/bookings', [
            'product_id' => $product->id,
            'total_amount' => 750,
        ]);

        $this->actingAs($client)
            ->get(route('client.bookings.index'))
            ->assertOk();
    }

    public function test_facility_bookings_page_renders(): void
    {
        $user = $this->makeFacilityUser();

        $this->actingAs($user)
            ->get(route('facility.bookings.index'))
            ->assertOk();
    }

    public function test_client_contracts_page_renders(): void
    {
        $client = $this->makeUser('client');

        $this->actingAs($client)
            ->get(route('client.contracts.index'))
            ->assertOk();
    }
}
