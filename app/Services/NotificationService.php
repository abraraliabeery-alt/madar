<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    /**
     * Send notification to user
     *
     * @param User $user
     * @param mixed $notification
     * @return void
     */
    public function sendToUser(User $user, $notification): void
    {
        $user->notify($notification);
    }

    /**
     * Send notification to multiple users
     *
     * @param array $users
     * @param mixed $notification
     * @return void
     */
    public function sendToUsers(array $users, $notification): void
    {
        Notification::send($users, $notification);
    }

    /**
     * Send notification to users with specific role
     *
     * @param string $role
     * @param mixed $notification
     * @return void
     */
    public function sendToRole(string $role, $notification): void
    {
        $users = User::where('primary_role', $role)->get();
        $this->sendToUsers($users->toArray(), $notification);
    }

    /**
     * Send notification to facility users
     *
     * @param int $facilityId
     * @param mixed $notification
     * @return void
     */
    public function sendToFacility(int $facilityId, $notification): void
    {
        $users = User::whereHas('facilities', function($query) use ($facilityId) {
            $query->where('facility_id', $facilityId);
        })->get();

        $this->sendToUsers($users->toArray(), $notification);
    }
}
