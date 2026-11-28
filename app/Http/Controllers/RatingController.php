<?php

namespace App\Http\Controllers;

use App\Http\Requests\RatingRequest;
use App\Models\Project;
use App\Models\Rating;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class RatingController extends Controller
{
    public function store(RatingRequest $request, Project $project): RedirectResponse
    {
        Gate::authorize('create', [Rating::class, $project]);

        $rating = new Rating($request->validated());
        $rating->project()->associate($project);
        $rating->client()->associate($request->user());
        $rating->developer()->associate($project->winningBid->developer_id);

        try {
            $rating->save();
        } catch (UniqueConstraintViolationException) {
            return back()->with('err', 'Proyek ini sudah diberi rating.');
        }

        return redirect()->route('projects.show', $project)->with('ok', 'Terima kasih, rating berhasil dikirim.');
    }
}
