<?php

namespace Tests\Feature;

use App\Models\Bid;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RatingTest extends TestCase
{
    use RefreshDatabase;

    private function closedProject(): Bid
    {
        $bid = Bid::factory()->create();
        $bid->forceFill(['is_winner' => true])->save();
        $bid->project->forceFill(['status' => Project::STATUS_CLOSED])->save();

        return $bid;
    }

    public function test_klien_pemilik_bisa_memberi_rating(): void
    {
        $bid = $this->closedProject();
        $klien = $bid->project->client;

        $this->actingAs($klien)->post("/projects/{$bid->project_id}/rating", ['nilai' => 5, 'komentar' => 'Mantap'])
            ->assertRedirect("/projects/{$bid->project_id}");

        $this->assertDatabaseHas('ratings', [
            'project_id' => $bid->project_id,
            'client_id' => $klien->id,
            'developer_id' => $bid->developer_id,
            'nilai' => 5,
        ]);
    }

    public function test_tidak_bisa_rating_sebelum_ditutup(): void
    {
        $bid = Bid::factory()->create();

        $this->actingAs($bid->project->client)->post("/projects/{$bid->project_id}/rating", ['nilai' => 4])
            ->assertForbidden();
        $this->assertDatabaseCount('ratings', 0);
    }

    public function test_klien_lain_dan_developer_ditolak(): void
    {
        $bid = $this->closedProject();

        $this->actingAs(User::factory()->klien()->create())
            ->post("/projects/{$bid->project_id}/rating", ['nilai' => 1])->assertForbidden();
        $this->actingAs($bid->developer)
            ->post("/projects/{$bid->project_id}/rating", ['nilai' => 5])->assertForbidden();
        $this->assertDatabaseCount('ratings', 0);
    }

    public function test_rating_hanya_sekali_dan_nilai_divalidasi(): void
    {
        $bid = $this->closedProject();
        $klien = $bid->project->client;
        $url = "/projects/{$bid->project_id}/rating";

        $this->actingAs($klien)->post($url, ['nilai' => 9])->assertSessionHasErrors('nilai');
        $this->actingAs($klien)->post($url, ['nilai' => 4])->assertRedirect();
        $this->actingAs($klien)->post($url, ['nilai' => 1])->assertForbidden();
        $this->assertDatabaseCount('ratings', 1);
    }

    public function test_komentar_rating_di_escape(): void
    {
        $bid = $this->closedProject();
        $this->actingAs($bid->project->client)
            ->post("/projects/{$bid->project_id}/rating", ['nilai' => 3, 'komentar' => '<script>alert(1)</script>']);

        $this->get("/projects/{$bid->project_id}")
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee('&lt;script&gt;', false);
    }
}
