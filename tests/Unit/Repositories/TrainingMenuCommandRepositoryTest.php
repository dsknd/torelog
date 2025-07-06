<?php

namespace Tests\Unit\Repositories;

use App\Models\Exercise;
use App\Models\TrainingMenu;
use App\Models\User;
use App\Repositories\Eloquent\TrainingMenuCommandRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainingMenuCommandRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private TrainingMenuCommandRepository $repository;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new TrainingMenuCommandRepository();
        
        // シードデータの準備
        $this->seed(\Database\Seeders\MuscleGroupCategorySeeder::class);
        $this->seed(\Database\Seeders\MuscleSeeder::class);
        $this->seed(\Database\Seeders\ExerciseSeeder::class);
    }

    public function test_create_training_menu(): void
    {
        $user = User::factory()->create();
        
        $data = [
            'user_id' => $user->id,
            'name' => 'Test Training Menu',
            'description' => 'Test description',
        ];

        $trainingMenu = $this->repository->create($data);

        $this->assertInstanceOf(TrainingMenu::class, $trainingMenu);
        $this->assertEquals($user->id, $trainingMenu->user_id);
        $this->assertEquals('Test Training Menu', $trainingMenu->name);
        $this->assertEquals('Test description', $trainingMenu->description);
        $this->assertDatabaseHas('training_menus', $data);
    }

    public function test_update_training_menu(): void
    {
        $trainingMenu = TrainingMenu::factory()->create([
            'name' => 'Original Menu',
            'description' => 'Original description',
        ]);

        $updatedData = [
            'name' => 'Updated Menu',
            'description' => 'Updated description',
        ];

        $updatedMenu = $this->repository->update($trainingMenu->id, $updatedData);

        $this->assertInstanceOf(TrainingMenu::class, $updatedMenu);
        $this->assertEquals('Updated Menu', $updatedMenu->name);
        $this->assertEquals('Updated description', $updatedMenu->description);
        $this->assertDatabaseHas('training_menus', [
            'id' => $trainingMenu->id,
            'name' => 'Updated Menu',
            'description' => 'Updated description',
        ]);
    }

    public function test_update_nonexistent_training_menu_throws_exception(): void
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->repository->update(99999, ['name' => 'test']);
    }

    public function test_delete_training_menu(): void
    {
        $trainingMenu = TrainingMenu::factory()->create();

        $result = $this->repository->delete($trainingMenu->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('training_menus', [
            'id' => $trainingMenu->id,
        ]);
    }

    public function test_delete_nonexistent_training_menu_throws_exception(): void
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->repository->delete(99999);
    }

    public function test_attach_exercises(): void
    {
        $trainingMenu = TrainingMenu::factory()->create();
        $exercise1 = Exercise::first();
        $exercise2 = Exercise::skip(1)->first();

        $exerciseData = [
            $exercise1->id => ['order' => 1],
            $exercise2->id => ['order' => 2],
        ];

        $this->repository->attachExercises($trainingMenu->id, $exerciseData);

        $this->assertDatabaseHas('training_menu_exercises', [
            'training_menu_id' => $trainingMenu->id,
            'exercise_id' => $exercise1->id,
            'order' => 1,
        ]);
        $this->assertDatabaseHas('training_menu_exercises', [
            'training_menu_id' => $trainingMenu->id,
            'exercise_id' => $exercise2->id,
            'order' => 2,
        ]);
    }

    public function test_sync_exercises(): void
    {
        $trainingMenu = TrainingMenu::factory()->create();
        $exercise1 = Exercise::first();
        $exercise2 = Exercise::skip(1)->first();
        $exercise3 = Exercise::skip(2)->first();

        // 既存の関連を作成
        $trainingMenu->exercises()->attach($exercise1->id, ['order' => 1]);
        $trainingMenu->exercises()->attach($exercise2->id, ['order' => 2]);

        // 新しいデータでsync
        $newExerciseData = [
            $exercise2->id => ['order' => 1], // 順序更新
            $exercise3->id => ['order' => 2], // 新規追加
            // exercise1は削除される
        ];

        $this->repository->syncExercises($trainingMenu->id, $newExerciseData);

        // exercise1は削除されている
        $this->assertDatabaseMissing('training_menu_exercises', [
            'training_menu_id' => $trainingMenu->id,
            'exercise_id' => $exercise1->id,
        ]);
        
        // exercise2の順序が更新されている
        $this->assertDatabaseHas('training_menu_exercises', [
            'training_menu_id' => $trainingMenu->id,
            'exercise_id' => $exercise2->id,
            'order' => 1,
        ]);
        
        // exercise3が新規追加されている
        $this->assertDatabaseHas('training_menu_exercises', [
            'training_menu_id' => $trainingMenu->id,
            'exercise_id' => $exercise3->id,
            'order' => 2,
        ]);
    }

    public function test_attach_exercises_with_nonexistent_menu_throws_exception(): void
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $exercise = Exercise::first();
        $this->repository->attachExercises(99999, [$exercise->id => ['order' => 1]]);
    }

    public function test_sync_exercises_with_nonexistent_menu_throws_exception(): void
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $exercise = Exercise::first();
        $this->repository->syncExercises(99999, [$exercise->id => ['order' => 1]]);
    }
}