<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Contract;
use App\Models\ExecutionBid;
use App\Models\ExecutionRequest;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;

class EtimadFlowSeeder extends Seeder
{
    public function run(): void
    {
        $requests = ExecutionRequest::with('bids')->get();
        $client = User::where('primary_role', 'client')->first() ?? User::first();

        if ($requests->isEmpty() || !$client) {
            $this->command?->warn('No execution requests or client user found.');
            return;
        }

        $createdContracts = 0;
        $createdInvoices = 0;
        $createdPayments = 0;

        foreach ($requests as $request) {
            $bids = $request->bids;
            if ($bids->isEmpty()) {
                continue;
            }

            $winningBid = $bids->sortByDesc(function ($b) {
                return $b->score ?? 0;
            })->first();

            foreach ($bids as $bid) {
                $bid->status = ($bid->id === $winningBid->id) ? 'accepted' : 'rejected';
                $bid->save();
            }

            $request->status = 'awarded';
            $request->save();

            $product = Product::where('facility_id', $request->facility_id)->inRandomOrder()->first();
            if (!$product) {
                continue;
            }

            $owner = User::where('primary_role', 'facility')->where('facility_id', $request->facility_id)->first()
                ?? User::where('primary_role', 'facility')->first();

            $contract = Contract::create([
                'product_id' => $product->id,
                'user_id' => $client->id,
                'owner_id' => $owner?->id ?? $client->id,
                'facility_id' => $request->facility_id,
                'start_date' => now()->toDateString(),
                'end_date' => now()->addMonths(6)->toDateString(),
            ]);

            $createdContracts++;

            $amount = (float) ($winningBid->price_total ?? 10000);

            $invoice = Invoice::create([
                'contract_id' => $contract->id,
                'invoice_number' => 'INV-' . now()->format('Ymd') . '-' . Str::padLeft((string) $contract->id, 4, '0'),
                'invoice_type' => 'sale',
                'amount' => $amount,
                'due_date' => now()->addDays(10)->toDateString(),
                'paid_amount' => 0,
                'remaining_amount' => $amount,
                'status' => 'sent',
                'payment_terms' => 'سداد خلال 10 أيام',
                'notes' => 'فاتورة تجريبية مرتبطة بدورة اعتماد (ترسية -> عقد -> فاتورة).',
                'facility_id' => $request->facility_id,
                'created_by' => $owner?->id ?? $client->id,
            ]);

            $createdInvoices++;

            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'contract_id' => $contract->id,
                'payment_method' => 'bank_transfer',
                'amount' => $amount,
                'payment_date' => now()->toDateString(),
                'reference_number' => 'PAY-' . now()->format('Ymd') . '-' . Str::padLeft((string) $invoice->id, 4, '0'),
                'bank_name' => 'بنك تجريبي',
                'notes' => 'دفعة تجريبية مرتبطة بالفاتورة.',
                'status' => 'pending',
                'facility_id' => $request->facility_id,
                'created_by' => $owner?->id ?? $client->id,
            ]);

            $payment->confirm();

            $createdPayments++;
        }

        $this->command?->info("Seeded Etimad flow: {$createdContracts} contracts, {$createdInvoices} invoices, {$createdPayments} payments.");
    }
}
