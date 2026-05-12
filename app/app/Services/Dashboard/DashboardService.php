<?php

namespace App\Services\Dashboard;

use App\Models\User;
use App\Repositories\ConversationRepository;

class DashboardService
{
    public function __construct(
        private readonly ConversationRepository $conversations,
    ) {
    }

    public function forUser(User $user): array
    {
        return $this->conversations->statsForUser($user);
    }
}
