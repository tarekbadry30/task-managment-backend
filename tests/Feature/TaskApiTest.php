<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    public function test_can_create_task()
    {
        $response = $this->actingAs(User::first(),'api')->postJson('/api/tasks', [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'status' => 'pending',
        ]);

        $response->assertStatus(201);
    }

    public function test_can_list_tasks()
    {
        $response = $this->actingAs(User::first(),'api')->getJson('/api/tasks');
        $response->assertStatus(200);
    }
}
