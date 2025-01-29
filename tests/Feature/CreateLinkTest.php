<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class CreateLinkTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_create_link(): void
    {
        $user = User::factory()->create(['id' => 1]);

        $response = $this->post('/api/manage/new', [
            'userId' => $user->id,
            'user_url' => 'https://www.example.com',
            'metadatas' => [
                [
                    'meta_key' => 'description',
                    'meta_value' => 'Example description',
                ],
                [
                    'meta_key' => 'author',
                    'meta_value' => 'Example author',
                ],
            ],
            'custom_short_code' => 'example',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('shortlinks', [
            'user_id' => 1,
            'user_url' => 'https://www.example.com',
            'short_code' => 'example',
            'short_url' => 'http://localhost/example',
            'is_active' => false,
            'is_premium' => true,
        ]);
    }
}
