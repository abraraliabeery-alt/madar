<?php

namespace Tests\Feature;

use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContractLifecycleTest extends TestCase
{
    use RefreshDatabase;

    private function makeActiveContract(): array
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

    public function test_full_payment_lifecycle(): void
    {
        [$contract, $client, $product] = $this->makeActiveContract();
        $facilityUser = User::find($product->owner_user_id);

        $invoice = new Invoice();
        $invoice->forceFill([
            'contract_id' => $contract->id,
            'created_by' => $facilityUser->id,
            'invoice_number' => 'INV-' . uniqid(),
            'invoice_type' => 'rent',
            'amount' => 1000,
            'remaining_amount' => 1000,
            'status' => 'sent',
            'due_date' => now()->addMonth(),
        ]);
        $invoice->save();

        // 1. العميل يسجّل الدفع — يجب أن يبقى معلّقاً
        $response = $this->actingAs($client)->postJson(
            route('client.contracts.pay-invoice', $contract),
            [
                'invoice_id' => $invoice->id,
                'payment_method' => 'bank_transfer',
                'amount' => 1000,
                'payment_date' => now()->toDateString(),
            ]
        );

        $response->assertOk()->assertJson(['success' => true]);

        $payment = Payment::latest()->first();
        $this->assertNotNull($payment);
        $this->assertSame('pending', $payment->status);
        $this->assertEquals(1000, (int) $invoice->fresh()->remaining_amount);
        $this->assertEquals(12000, $contract->fresh()->getRemainingAmount());

        // 2. المنشأة تؤكد الدفعة — تُحتسب على العقد والفاتورة
        $confirm = $this->actingAs($facilityUser)->post(
            route('facility.financial.confirm-payment', $payment->id)
        );
        if ($confirm->status() !== 200) {
            $this->fail('confirm-payment returned ' . $confirm->status() . ': ' . $confirm->content());
        }

        $this->assertSame('confirmed', $payment->fresh()->status);
        $this->assertEquals(1000, (int) $invoice->fresh()->paid_amount);
        $this->assertSame('paid', $invoice->fresh()->status);
        $this->assertEquals(11000, $contract->fresh()->getRemainingAmount());
    }

    public function test_client_cannot_pay_other_clients_invoice(): void
    {
        [$contract] = $this->makeActiveContract();
        $otherClient = $this->makeUser('client');

        $invoice = new Invoice();
        $invoice->forceFill([
            'contract_id' => $contract->id,
            'created_by' => $contract->owner_id,
            'invoice_number' => 'INV-' . uniqid(),
            'invoice_type' => 'rent',
            'amount' => 500,
            'remaining_amount' => 500,
            'status' => 'sent',
            'due_date' => now()->addMonth(),
        ]);
        $invoice->save();

        $response = $this->actingAs($otherClient)->postJson(
            route('client.contracts.pay-invoice', $contract),
            [
                'invoice_id' => $invoice->id,
                'payment_method' => 'cash',
                'amount' => 500,
                'payment_date' => now()->toDateString(),
            ]
        );

        $response->assertForbidden();
        $this->assertSame(0, Payment::count());
    }

    public function test_facility_cannot_view_other_facility_contract(): void
    {
        [$contract] = $this->makeActiveContract();
        $otherFacilityUser = $this->makeFacilityUser();

        $response = $this->actingAs($otherFacilityUser)
            ->get(route('facility.contracts.show', $contract));

        $this->assertContains($response->status(), [403, 404]);
    }

    public function test_client_cannot_view_other_clients_contract(): void
    {
        [$contract] = $this->makeActiveContract();
        $otherClient = $this->makeUser('client');

        $response = $this->actingAs($otherClient)
            ->get(route('client.contracts.show', $contract));

        $this->assertContains($response->status(), [403, 404]);
    }
}
