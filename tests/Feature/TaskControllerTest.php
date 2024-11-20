<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    public function setUp(): void
    {
        parent::setUp();

        // Create a user with admin privileges
        $this->admin = User::factory()->create([
            'role' => 'admin', // Assuming you have a 'role' field for admin
        ]);

        // Acting as the admin user for all requests
        $this->actingAs($this->admin);
    }

    public function test_can_create_task()
    {
        $payload = [
            'title' => 'Test Task',
            'description' => 'Task description.',
            'due_date' => now()->addDays(3)->toDateString(),
            'status' => 'pending',
        ];

        $response = $this->postJson('/api/admin/tasks', $payload);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'task' => ['id', 'title', 'description', 'due_date', 'status'],
                 ]);
    }

    public function test_can_update_task()
    {
        $task = Task::factory()->create();

        $response = $this->putJson('/api/admin/tasks/' . $task->id, [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'due_date' => now()->addDays(5)->toDateString(),
            'status' => 'completed',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Task updated successfully',
                 ]);
    }

    public function test_can_delete_task()
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson('/api/admin/tasks/' . $task->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Task deleted successfully',
                 ]);
    }

    public function test_can_retrieve_tasks_with_filter()
    {
        Task::factory()->count(3)->create();

        $response = $this->getJson('/api/admin/tasks?status=pending');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'result' => [
                        'data'=> [
                            '*' => ['id', 'title', 'description', 'status'],
                        ]
                     ]
                 ]);
    }

    public function test_can_assign_users_to_task()
    {
        $task = Task::factory()->create();
        $user = User::factory()->create();

        $response = $this->postJson('/api/admin/tasks/assign_users/' . $task->id , [
            'user_emails' => [$user->email] ,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Users assigned successfully.',
                 ]);
    }

    public function test_task_creation_requires_valid_data()
    {
        $payload = [
            'title' => '',
            'description' => 'Task description.',
            'due_date' => now()->addDays(3)->toDateString(),
            'status' => 'pending',
        ];

        $response = $this->postJson('/api/admin/tasks', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title']);
    }

    public function test_user_assignment_requires_valid_emails()
    {
        $task = Task::factory()->create();

        $response = $this->postJson('/api/admin/tasks/assign_users/' . $task->id , [
            'user_emails' => null ,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['user_emails']);
    }

    public function test_cannot_update_nonexistent_task()
    {
        $response = $this->putJson('/api/admin/tasks/9999', [
            'title' => 'Nonexistent Task',
        ]);

        $response->assertStatus(404)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Task not found.',
                 ]);
    }

    public function test_prevent_duplicate_user_assignment()
    {
        $task = Task::factory()->create();
        $user = User::factory()->create();

        // First assignment
        $this->postJson('/api/admin/tasks/assign_users/' . $task->id , [
            'user_emails' => [$user->email] ,
        ]);

        // Try assigning the same user again
        $response = $this->postJson('/api/admin/tasks/assign_users/' . $task->id , [
            'user_emails' => [$user->email] ,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'No new users to assign.',
                 ]);
    }
}
