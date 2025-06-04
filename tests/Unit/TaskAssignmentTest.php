<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskAssignmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_only_assign_to_staff()
    {
        // dd(config('database.default'));

        $manager = User::factory()->state(['role' => 'manager'])->create();
        $staff = User::factory()->create(['role' => 'staff']);
        $otherManager = User::factory()->create(['role' => 'manager']);

        // dd([
        //     'manager' => $manager->toArray(),
        //     'staff' => $staff->toArray(),
        //     'otherManager' => $otherManager->toArray(),
        // ]);
        
        // $this->actingAs($manager, 'sanctum');
        $this->actingAs($manager);

        // Simulasi assign ke staff (boleh)
        $this->postJson('/api/tasks', [
            'title' => 'Valid Task',
            'description' => 'Assign to staff',
            'assigned_to' => $staff->id,
            'status' => 'pending',
            'due_date' => now()->addDays(1)->toDateString(),
        ])->assertStatus(201);

        // Simulasi assign ke manager (ditolak)
        $this->postJson('/api/tasks', [
            'title' => 'Invalid Task',
            'description' => 'Assign to manager',
            'assigned_to' => $otherManager->id,
            'status' => 'pending',
            'due_date' => now()->addDays(1)->toDateString(),
        ])->assertStatus(403);


    }
}
