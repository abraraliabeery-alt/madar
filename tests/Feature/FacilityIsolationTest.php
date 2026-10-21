<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FacilityIsolationTest extends TestCase
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

    private function makePayment(Contract $contract, int $createdBy): Payment
    {
        $payment = new Payment();
        $payment->forceFill([
            'contract_id' => $contract->id,
            'facility_id' => $contract->facility_id,
            'payment_method' => 'bank_transfer',
            'amount' => 500,
            'payment_date' => now()->toDateString(),
            'status' => 'pending',
            'created_by' => $createdBy,
        ]);
        $payment->save();

        return $payment;
    }

    private function assertDenied($response): void
    {
        // أي رد غير 200 يعني عدم تسريب البيانات (403 سياسة، 404 نطاق، 302 تحويل)
        $this->assertNotSame(200, $response->status());
    }

    public function test_facility_cannot_view_or_update_other_facility_product(): void
    {
        $product = $this->makeActiveProduct();
        $otherFacility = $this->makeFacilityUser();

        $this->assertDenied($this->actingAs($otherFacility)
            ->get(route('facility.products.show', $product)));

        $this->assertDenied($this->actingAs($otherFacility)
            ->put(route('facility.products.update', $product), ['title' => 'hijacked']));
    }

    public function test_facility_cannot_view_or_confirm_other_facility_booking(): void
    {
        $product = $this->makeActiveProduct();
        $client = $this->makeUser('client');

        $booking = new Booking();
        $booking->forceFill([
            'product_id' => $product->id,
            'user_id' => $client->id,
        ]);
        $booking->save();

        $otherFacility = $this->makeFacilityUser();

        $this->assertDenied($this->actingAs($otherFacility)
            ->get(route('facility.bookings.show', $booking)));

        $this->assertDenied($this->actingAs($otherFacility)
            ->post(route('facility.bookings.confirm', $booking)));

        // حالة الحجز علاقة polymorphic — التأكيد كان سيُنشئ سجل status
        $this->assertSame(0, $booking->statuses()->count());
    }

    public function test_facility_cannot_view_or_update_other_facility_invoice(): void
    {
        [$contract] = $this->makeContract();
        $invoice = $this->makeInvoice($contract, $contract->owner_id);
        $otherFacility = $this->makeFacilityUser();

        $this->assertDenied($this->actingAs($otherFacility)
            ->get(route('facility.invoices.show', $invoice)));

        $this->assertDenied($this->actingAs($otherFacility)
            ->put(route('facility.invoices.update', $invoice), ['amount' => 1]));

        $this->assertEquals(1000, (int) $invoice->fresh()->amount);
    }

    public function test_facility_cannot_view_or_confirm_other_facility_payment(): void
    {
        [$contract] = $this->makeContract();
        $payment = $this->makePayment($contract, $contract->owner_id);
        $otherFacility = $this->makeFacilityUser();

        $this->assertDenied($this->actingAs($otherFacility)
            ->get(route('facility.payments.show', $payment)));

        // تأكيد الدفعة عبر المسارين يجب أن يفشل
        $this->assertDenied($this->actingAs($otherFacility)
            ->post(route('facility.payments.confirm', $payment)));

        $response = $this->actingAs($otherFacility)
            ->post(route('facility.financial.confirm-payment', $payment->id));
        $this->assertNotSame(200, $response->status());

        $this->assertSame('pending', $payment->fresh()->status);
    }

    public function test_facility_payments_index_does_not_leak_other_facility_payments(): void
    {
        [$contract] = $this->makeContract();
        $payment = $this->makePayment($contract, $contract->owner_id);
        $marker = 'PAY-LEAK-' . uniqid();
        $payment->forceFill(['reference_number' => $marker])->save();

        $otherFacility = $this->makeFacilityUser();
        $response = $this->actingAs($otherFacility)
            ->get(route('facility.financial.payments'));

        $response->assertOk();
        $this->assertStringNotContainsString($marker, $response->getContent());
    }
}
