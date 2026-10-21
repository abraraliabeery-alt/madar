<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CriticalFlowsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_panels(): void
    {
        $this->assertTrue($this->get('/admin')->isRedirect());
        $this->assertTrue($this->get('/client')->isRedirect());
    }

    public function test_phone_password_login_logs_in_and_redirects(): void
    {
        $user = $this->makeUser('client', ['phone_number' => '+966555123456']);

        $response = $this->post('/login/phone', [
            'phone_number' => '0555123456',
            'password' => 'password123',
        ]);

        $this->assertTrue($response->isRedirect());
        $this->assertAuthenticatedAs($user);
    }

    public function test_phone_login_rejects_wrong_password(): void
    {
        $this->makeUser('client', ['phone_number' => '+966555654321']);

        $response = $this->from('/login/phone')->post('/login/phone', [
            'phone_number' => '0555654321',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertGuest();
    }

    public function test_admin_login_redirects_to_admin_dashboard(): void
    {
        $this->makeUser('admin', ['phone_number' => '+966555000111']);

        $response = $this->post('/login/phone', [
            'phone_number' => '0555000111',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_dashboard_renders_for_admin(): void
    {
        $admin = $this->makeUser('admin');

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk();
    }

    public function test_client_cannot_access_admin_panel(): void
    {
        $client = $this->makeUser('client');

        $response = $this->actingAs($client)->get(route('admin.dashboard'));

        $this->assertContains($response->status(), [302, 403]);
    }

    public function test_client_dashboard_renders(): void
    {
        $client = $this->makeUser('client');

        $this->actingAs($client)
            ->get('/client')
            ->assertOk();
    }

    public function test_facility_dashboard_renders(): void
    {
        $user = $this->makeFacilityUser();

        $this->actingAs($user)
            ->get('/facility')
            ->assertOk();
    }

    public function test_admin_global_search_returns_json_results(): void
    {
        $admin = $this->makeUser('admin');
        $this->makeUser('client', ['name' => 'Unique Searchable Person', 'email' => 'uniq@test.local']);

        $response = $this->actingAs($admin)
            ->getJson('/admin/search/global?q=Unique%20Searchable');

        $response->assertOk()->assertJsonStructure(['results']);
        $this->assertNotEmpty($response->json('results'));
    }

    public function test_admin_global_search_does_not_crash_on_empty_query(): void
    {
        $admin = $this->makeUser('admin');

        $this->actingAs($admin)
            ->getJson('/admin/search/global?q=')
            ->assertOk();
    }

    public function test_admin_list_pages_render_with_empty_state(): void
    {
        $admin = $this->makeUser('admin');

        foreach (['/admin/users', '/admin/bookings', '/admin/contracts', '/admin/products', '/admin/facilities'] as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }

        // Bookings table is empty in a fresh DB → empty state must render
        $response = $this->actingAs($admin)->get('/admin/bookings');
        $this->assertStringContainsString(
            __('admin.messages.no_records'),
            $response->getContent()
        );
    }
}
