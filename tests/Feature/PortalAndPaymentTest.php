<?php

namespace Tests\Feature;

use App\Models\Contract;
use App\Models\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class PortalAndPaymentTest extends TestCase
{
    use RefreshDatabase;

    private function makeContract(): array
    {
        $product = $this->makeActiveProduct();
        $client = $this->makeUser('client');

        $contract = new Contract();
        $contract->forceFill([
            'contract_type' => 'rent',
            'owner_id' => $product->owner_user_id,
            'product_id' => $product->id,
            'user_id' => $client->id,
            'facility_id' => $product->facility_id,
            'status' => 'active',
            'total_amount' => 12000,
        ]);
        $contract->save();

        return [$contract, $client, $product];
    }

    private function makeInvoice(Contract $contract, int $createdBy): Invoice
    {
        $invoice = new Invoice();
        $invoice->forceFill([
            'contract_id' => $contract->id,
            'created_by' => $createdBy,
            'invoice_number' => 'INV-' . uniqid(),
            'invoice_type' => 'rent',
            'amount' => 1000,
            'remaining_amount' => 1000,
            'status' => 'sent',
            'due_date' => now()->addMonth(),
        ]);
        $invoice->save();

        return $invoice;
    }

    public function test_tenant_portal_rejects_unsigned_access(): void
    {
        [$contract] = $this->makeContract();

        $this->get("/tenant-portal/contract/{$contract->id}")
            ->assertForbidden();
    }

    public function test_tenant_portal_renders_with_valid_signature(): void
    {
        [$contract] = $this->makeContract();

        $url = URL::signedRoute('tenant-portal.contract', ['contract' => $contract->id]);

        $this->get($url)->assertOk();
    }

    public function test_tenant_pay_is_blocked_without_gateway_keys(): void
    {
        [$contract, $client, $product] = $this->makeContract();
        $invoice = $this->makeInvoice($contract, $product->owner_user_id);

        $url = URL::signedRoute('tenant-portal.pay', [
            'contract' => $contract->id,
            'invoice' => $invoice->id,
        ]);

        // Moyasar keys are empty in the test env → feature must refuse cleanly
        $this->get($url)->assertForbidden();
    }

    public function test_notifications_count_endpoint_returns_json(): void
    {
        $user = $this->makeUser('client');

        $this->actingAs($user)
            ->getJson('/dashboard/notifications-count')
            ->assertOk()
            ->assertJsonStructure(['count']);
    }

    public function test_notifications_count_requires_auth(): void
    {
        $response = $this->getJson('/dashboard/notifications-count');
        $this->assertContains($response->status(), [401, 403]);
    }

    public function test_admin_bookings_export_returns_file(): void
    {
        $admin = $this->makeUser('admin');

        $this->actingAs($admin)
            ->get('/admin/bookings/export')
            ->assertOk();
    }
}
