<?php

use App\Models\User;
use App\Models\Task;
use App\Models\ActivityLog;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can register', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(201)
             ->assertJsonStructure(['user', 'token']);
    
    $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
});

test('user can login', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertStatus(200)
             ->assertJsonStructure(['token']);
});

test('authenticated user can create a task and it logs activity', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->postJson('/api/tasks', [
        'title' => 'Test Task',
        'description' => 'Test Description',
        'priority' => 'high',
        'status' => 'pending',
    ]);

    $response->assertStatus(201);
    $this->assertDatabaseHas('tasks', ['title' => 'Test Task']);
    
    // Verify activity log
    $this->assertDatabaseHas('activity_logs', [
        'user_id' => $user->id,
        'action' => 'created'
    ]);
});

test('authenticated user can update their task', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user);

    $response = $this->putJson("/api/tasks/{$task->id}", [
        'title' => 'Updated Title',
        'status' => 'completed',
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('tasks', ['id' => $task->id, 'title' => 'Updated Title', 'status' => 'completed']);
    
    // Verify activity log
    $this->assertDatabaseHas('activity_logs', [
        'user_id' => $user->id,
        'task_id' => $task->id,
        'action' => 'updated'
    ]);
});

test('authenticated user can delete their task', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user);

    $response = $this->deleteJson("/api/tasks/{$task->id}");

    $response->assertStatus(200);
    $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    
    // Verify activity log
    $this->assertDatabaseHas('activity_logs', [
        'user_id' => $user->id,
        'task_id' => $task->id,
        'action' => 'deleted'
    ]);
});

test('user cannot access other users tasks', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $task = Task::factory()->create(['user_id' => $user1->id]);

    $this->actingAs($user2);

    $response = $this->getJson("/api/tasks/{$task->id}");
    $response->assertStatus(403);
});

test('user can filter tasks', function () {
    $user = User::factory()->create();
    Task::factory()->create(['user_id' => $user->id, 'status' => 'completed', 'priority' => 'high']);
    Task::factory()->create(['user_id' => $user->id, 'status' => 'pending', 'priority' => 'low']);
    
    $this->actingAs($user);

    $response = $this->getJson('/api/tasks/filter?status=completed');
    $response->assertStatus(200)
             ->assertJsonCount(1);
    
    $response = $this->getJson('/api/tasks/filter?priority=low');
    $response->assertStatus(200)
             ->assertJsonCount(1);
});
