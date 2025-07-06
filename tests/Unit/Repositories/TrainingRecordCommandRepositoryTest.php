<?php

namespace Tests\Unit\Repositories;

use App\Models\TrainingMenu;
use App\Models\TrainingRecord;
use App\Models\User;
use App\Repositories\Eloquent\TrainingRecordCommandRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainingRecordCommandRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private TrainingRecordCommandRepository $repository;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new TrainingRecordCommandRepository();
    }

    public function test_create_training_record(): void
    {
        $user = User::factory()->create();
        $trainingMenu = TrainingMenu::factory()->create(['user_id' => $user->id]);
        
        $data = [
            'user_id' => $user->id,
            'training_menu_id' => $trainingMenu->id,
            'date' => '2025-01-06',
            'memo' => 'Good workout',
        ];

        $trainingRecord = $this->repository->create($data);

        $this->assertInstanceOf(TrainingRecord::class, $trainingRecord);
        $this->assertEquals($user->id, $trainingRecord->user_id);
        $this->assertEquals($trainingMenu->id, $trainingRecord->training_menu_id);
        $this->assertEquals('2025-01-06', $trainingRecord->date->format('Y-m-d'));
        $this->assertEquals('Good workout', $trainingRecord->memo);
        $this->assertDatabaseHas('training_records', $data);
    }

    public function test_create_training_record_without_menu(): void
    {
        $user = User::factory()->create();
        
        $data = [
            'user_id' => $user->id,
            'training_menu_id' => null,
            'date' => '2025-01-06',
            'memo' => null,
        ];

        $trainingRecord = $this->repository->create($data);

        $this->assertInstanceOf(TrainingRecord::class, $trainingRecord);
        $this->assertEquals($user->id, $trainingRecord->user_id);
        $this->assertNull($trainingRecord->training_menu_id);
        $this->assertNull($trainingRecord->memo);
    }

    public function test_update_training_record(): void
    {
        $trainingRecord = TrainingRecord::factory()->create([
            'date' => '2025-01-06',
            'memo' => 'Initial memo',
        ]);

        $updatedData = [
            'date' => '2025-01-07',
            'memo' => 'Updated memo',
        ];

        $updatedRecord = $this->repository->update($trainingRecord->id, $updatedData);

        $this->assertInstanceOf(TrainingRecord::class, $updatedRecord);
        $this->assertEquals('2025-01-07', $updatedRecord->date->format('Y-m-d'));
        $this->assertEquals('Updated memo', $updatedRecord->memo);
        $this->assertDatabaseHas('training_records', [
            'id' => $trainingRecord->id,
            'date' => '2025-01-07',
            'memo' => 'Updated memo',
        ]);
    }

    public function test_update_nonexistent_record_throws_exception(): void
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->repository->update(99999, ['memo' => 'test']);
    }

    public function test_delete_training_record(): void
    {
        $trainingRecord = TrainingRecord::factory()->create();

        $result = $this->repository->delete($trainingRecord->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('training_records', [
            'id' => $trainingRecord->id,
        ]);
    }

    public function test_delete_nonexistent_record_throws_exception(): void
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->repository->delete(99999);
    }
}