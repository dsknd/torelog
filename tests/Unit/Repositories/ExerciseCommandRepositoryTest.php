<?php

namespace Tests\Unit\Repositories;

use App\Models\Exercise;
use App\Models\Muscle;
use App\Models\MuscleGroupCategory;
use App\Repositories\Eloquent\ExerciseCommandRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExerciseCommandRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private ExerciseCommandRepository $repository;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new ExerciseCommandRepository();
        
        // シードデータの準備
        $this->seed(\Database\Seeders\MuscleGroupCategorySeeder::class);
        $this->seed(\Database\Seeders\MuscleSeeder::class);
    }

    public function test_create_exercise(): void
    {
        $data = [
            'name' => 'Test Exercise',
            'description' => 'Test description',
        ];

        $exercise = $this->repository->create($data);

        $this->assertInstanceOf(Exercise::class, $exercise);
        $this->assertEquals('Test Exercise', $exercise->name);
        $this->assertEquals('Test description', $exercise->description);
        $this->assertDatabaseHas('exercises', $data);
    }

    public function test_update_exercise(): void
    {
        $exercise = Exercise::factory()->create([
            'name' => 'Original Exercise',
            'description' => 'Original description',
        ]);

        $updatedData = [
            'name' => 'Updated Exercise',
            'description' => 'Updated description',
        ];

        $updatedExercise = $this->repository->update($exercise->id, $updatedData);

        $this->assertInstanceOf(Exercise::class, $updatedExercise);
        $this->assertEquals('Updated Exercise', $updatedExercise->name);
        $this->assertEquals('Updated description', $updatedExercise->description);
        $this->assertDatabaseHas('exercises', [
            'id' => $exercise->id,
            'name' => 'Updated Exercise',
            'description' => 'Updated description',
        ]);
    }

    public function test_update_nonexistent_exercise_throws_exception(): void
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->repository->update(99999, ['name' => 'test']);
    }

    public function test_delete_exercise(): void
    {
        $exercise = Exercise::factory()->create();

        $result = $this->repository->delete($exercise->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('exercises', [
            'id' => $exercise->id,
        ]);
    }

    public function test_delete_nonexistent_exercise_throws_exception(): void
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->repository->delete(99999);
    }

    public function test_attach_muscles(): void
    {
        $exercise = Exercise::factory()->create();
        $muscle1 = Muscle::first();
        $muscle2 = Muscle::skip(1)->first();

        $muscleData = [
            $muscle1->id => ['is_primary' => true],
            $muscle2->id => ['is_primary' => false],
        ];

        $this->repository->attachMuscles($exercise->id, $muscleData);

        $this->assertDatabaseHas('exercise_target_muscles', [
            'exercise_id' => $exercise->id,
            'muscle_id' => $muscle1->id,
            'is_primary' => true,
        ]);
        $this->assertDatabaseHas('exercise_target_muscles', [
            'exercise_id' => $exercise->id,
            'muscle_id' => $muscle2->id,
            'is_primary' => false,
        ]);
    }

    public function test_sync_muscles(): void
    {
        $exercise = Exercise::factory()->create();
        $muscle1 = Muscle::first();
        $muscle2 = Muscle::skip(1)->first();
        $muscle3 = Muscle::skip(2)->first();

        // 既存の関連を作成
        $exercise->muscles()->attach($muscle1->id, ['is_primary' => true]);
        $exercise->muscles()->attach($muscle2->id, ['is_primary' => false]);

        // 新しいデータでsync
        $newMuscleData = [
            $muscle2->id => ['is_primary' => true], // 更新
            $muscle3->id => ['is_primary' => false], // 新規
            // muscle1は削除される
        ];

        $this->repository->syncMuscles($exercise->id, $newMuscleData);

        // muscle1は削除されている
        $this->assertDatabaseMissing('exercise_target_muscles', [
            'exercise_id' => $exercise->id,
            'muscle_id' => $muscle1->id,
        ]);
        
        // muscle2は更新されている
        $this->assertDatabaseHas('exercise_target_muscles', [
            'exercise_id' => $exercise->id,
            'muscle_id' => $muscle2->id,
            'is_primary' => true,
        ]);
        
        // muscle3は新規追加されている
        $this->assertDatabaseHas('exercise_target_muscles', [
            'exercise_id' => $exercise->id,
            'muscle_id' => $muscle3->id,
            'is_primary' => false,
        ]);
    }

    public function test_attach_muscle_group_categories(): void
    {
        $exercise = Exercise::factory()->create();
        $category1 = MuscleGroupCategory::first();
        $category2 = MuscleGroupCategory::skip(1)->first();

        $categoryIds = [$category1->id, $category2->id];

        $this->repository->attachMuscleGroupCategories($exercise->id, $categoryIds);

        $this->assertDatabaseHas('exercise_muscle_group_categories', [
            'exercise_id' => $exercise->id,
            'muscle_group_category_id' => $category1->id,
        ]);
        $this->assertDatabaseHas('exercise_muscle_group_categories', [
            'exercise_id' => $exercise->id,
            'muscle_group_category_id' => $category2->id,
        ]);
    }

    public function test_sync_muscle_group_categories(): void
    {
        $exercise = Exercise::factory()->create();
        $category1 = MuscleGroupCategory::first();
        $category2 = MuscleGroupCategory::skip(1)->first();
        $category3 = MuscleGroupCategory::skip(2)->first();

        // 既存の関連を作成
        $exercise->muscleGroupCategories()->attach([$category1->id, $category2->id]);

        // 新しいデータでsync
        $newCategoryIds = [$category2->id, $category3->id];

        $this->repository->syncMuscleGroupCategories($exercise->id, $newCategoryIds);

        // category1は削除されている
        $this->assertDatabaseMissing('exercise_muscle_group_categories', [
            'exercise_id' => $exercise->id,
            'muscle_group_category_id' => $category1->id,
        ]);
        
        // category2は残っている
        $this->assertDatabaseHas('exercise_muscle_group_categories', [
            'exercise_id' => $exercise->id,
            'muscle_group_category_id' => $category2->id,
        ]);
        
        // category3は新規追加されている
        $this->assertDatabaseHas('exercise_muscle_group_categories', [
            'exercise_id' => $exercise->id,
            'muscle_group_category_id' => $category3->id,
        ]);
    }
}