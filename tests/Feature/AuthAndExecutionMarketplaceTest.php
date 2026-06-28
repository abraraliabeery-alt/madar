<?php

namespace Tests\Feature;

use App\Models\ExecutionBid;
use App\Models\ExecutionRequest;
use App\Models\Facility;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthAndExecutionMarketplaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_otp_verification_assigns_client_role_and_returns_token(): void
    {
        $phone = '966500000001';

        $this->postJson('/api/v1/otp/request', [
            'phone_number' => $phone,
            'login_intent' => 'client',
        ])->assertOk()
            ->assertJsonPath('success', true);

        $user = User::query()->where('phone_number', $phone)->firstOrFail();

        $this->postJson('/api/v1/otp/verify', [
            'phone_number' => $phone,
            'otp' => $user->otp_code,
            'login_intent' => 'client',
            'device_name' => 'feature-test',
        ])->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['data' => ['token']]);

        $user->refresh();

        $this->assertSame('client', $user->primary_role);
        $this->assertTrue($user->hasRole('client'));
    }

    public function test_client_marketplace_request_can_receive_facility_bid(): void
    {
        $client = User::query()->create([
            'name' => 'Client',
            'email' => 'client@example.test',
            'password' => Hash::make('password'),
            'primary_role' => 'client',
        ]);

        $executor = User::query()->create([
            'name' => 'Executor',
            'email' => 'executor@example.test',
            'password' => Hash::make('password'),
            'primary_role' => 'facility',
        ]);

        $facility = Facility::query()->create([
            'name' => 'Execution Facility',
            'owner_user_id' => $executor->id,
            'is_active' => true,
        ]);

        $executor->facilities()->attach($facility->id);

        $project = Project::query()->create([
            'client_user_id' => $client->id,
            'status' => 'open_for_bids',
        ]);

        $executionRequest = ExecutionRequest::query()->create([
            'facility_id' => null,
            'client_user_id' => $client->id,
            'project_id' => $project->id,
            'type' => 'construction',
            'status' => 'open',
            'priority' => 'normal',
        ]);

        $bid = ExecutionBid::query()->create([
            'execution_request_id' => $executionRequest->id,
            'executor_facility_id' => $facility->id,
            'executor_user_id' => $executor->id,
            'price_total' => 100000,
            'currency' => 'SAR',
            'status' => 'pending',
        ]);

        $this->assertNull($executionRequest->facility_id);
        $this->assertSame($client->id, $executionRequest->client_user_id);
        $this->assertSame($facility->id, $bid->executor_facility_id);
    }
}
