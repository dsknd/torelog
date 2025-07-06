<?php

namespace Tests\Unit\Repositories;

use App\Models\User;
use App\Repositories\Eloquent\UserQueryRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserQueryRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private UserQueryRepository $repository;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new UserQueryRepository();
    }

    public function test_find_by_id(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $result = $this->repository->findById($user->id);

        $this->assertNotNull($result);
        $this->assertEquals($user->id, $result->id);
        $this->assertEquals('Test User', $result->name);
        $this->assertEquals('test@example.com', $result->email);
    }

    public function test_find_by_id_returns_null_when_not_found(): void
    {
        $result = $this->repository->findById(99999);

        $this->assertNull($result);
    }

    public function test_find_by_email(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $result = $this->repository->findByEmail('test@example.com');

        $this->assertNotNull($result);
        $this->assertEquals($user->id, $result->id);
        $this->assertEquals('Test User', $result->name);
        $this->assertEquals('test@example.com', $result->email);
    }

    public function test_find_by_email_returns_null_when_not_found(): void
    {
        $result = $this->repository->findByEmail('nonexistent@example.com');

        $this->assertNull($result);
    }

    public function test_find_by_email_is_case_sensitive(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $result = $this->repository->findByEmail('TEST@EXAMPLE.COM');

        $this->assertNull($result);
    }

    public function test_find_by_email_with_multiple_users(): void
    {
        $targetUser = User::factory()->create([
            'name' => 'Target User',
            'email' => 'target@example.com',
        ]);
        
        User::factory()->count(3)->create(); // Other users

        $result = $this->repository->findByEmail('target@example.com');

        $this->assertNotNull($result);
        $this->assertEquals($targetUser->id, $result->id);
        $this->assertEquals('Target User', $result->name);
    }
}