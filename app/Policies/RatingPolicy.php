<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class RatingPolicy
{
    public function create(User $user, Project $project): bool
    {
        return $user->role === 'klien'
            && $project->client_id === $user->id
            && $project->status === Project::STATUS_CLOSED
            && $project->winningBid()->exists()
            && ! $project->rating()->exists();
    }
}
