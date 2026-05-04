<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_manage_projects(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('projects.store'), [
            'name' => 'Website Redesign',
            'description' => 'Refresh the customer-facing website.',
            'color' => '#2563eb',
        ]);

        $project = Project::query()->firstOrFail();

        $response->assertRedirect(route('projects.show', $project));
        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'user_id' => $user->id,
            'name' => 'Website Redesign',
        ]);

        $this->actingAs($user)->put(route('projects.update', $project), [
            'name' => 'Website Relaunch',
            'description' => 'Ship the refreshed customer-facing website.',
            'color' => '#059669',
        ])->assertRedirect(route('projects.show', $project));

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'Website Relaunch',
            'color' => '#059669',
        ]);

        $this->actingAs($user)->delete(route('projects.destroy', $project))
            ->assertRedirect(route('projects.index'));

        $this->assertDatabaseMissing('projects', [
            'id' => $project->id,
        ]);
    }

    public function test_user_cannot_view_another_users_project(): void
    {
        $owner = User::factory()->create();
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'user_id' => $owner->id,
        ]);

        $this->actingAs($user)->get(route('projects.show', $project))
            ->assertForbidden();
    }
}
