<?php

namespace Tests\Unit\Repositories;

use App\Models\Exercise;
use App\Models\TrainingMenu;
use App\Models\User;
use App\Repositories\Eloquent\TrainingMenuQueryRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainingMenuQueryRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private TrainingMenuQueryRepository $repository;
    private User $user;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new TrainingMenuQueryRepository();
        $this->user = User::factory()->create();
        
        // シードデータの準備
        $this->seed(\Database\Seeders\MuscleGroupCategorySeeder::class);
        $this->seed(\Database\Seeders\MuscleSeeder::class);
        $this->seed(\Database\Seeders\ExerciseSeeder::class);
    }

    public function test_find_by_id(): void
    {
        $trainingMenu = TrainingMenu::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test Menu',
        ]);
        
        // エクササイズを追加
        $exercise = Exercise::first();
        $trainingMenu->exercises()->attach($exercise->id, ['order' => 1]);

        $result = $this->repository->findById($trainingMenu->id);

        $this->assertNotNull($result);
        $this->assertEquals($trainingMenu->id, $result->id);
        $this->assertEquals('Test Menu', $result->name);
        $this->assertTrue($result->relationLoaded('user'));
        $this->assertTrue($result->relationLoaded('exercises'));
        
        // エクササイズの関連も読み込まれているか確認
        if ($result->exercises->count() > 0) {
            $this->assertTrue($result->exercises->first()->relationLoaded('muscleGroupCategories'));
        }
    }

    public function test_find_by_id_returns_null_when_not_found(): void
    {
        $result = $this->repository->findById(99999);

        $this->assertNull($result);
    }

    public function test_find_by_user_id(): void
    {
        $anotherUser = User::factory()->create();
        
        // 対象ユーザーのメニュー
        TrainingMenu::factory()->count(3)->create([
            'user_id' => $this->user->id,
        ]);
        
        // 別ユーザーのメニュー
        TrainingMenu::factory()->count(2)->create([
            'user_id' => $anotherUser->id,
        ]);

        $result = $this->repository->findByUserId($this->user->id);

        $this->assertCount(3, $result);
        $this->assertTrue($result->every(fn($menu) => $menu->user_id === $this->user->id));
    }

    public function test_find_by_user_id_returns_sorted_by_name(): void
    {
        TrainingMenu::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'ZZZ Menu',
        ]);
        TrainingMenu::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'AAA Menu',
        ]);
        TrainingMenu::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'MMM Menu',
        ]);

        $result = $this->repository->findByUserId($this->user->id);

        $names = $result->pluck('name')->toArray();
        $this->assertEquals(['AAA Menu', 'MMM Menu', 'ZZZ Menu'], $names);
    }

    public function test_find_by_user_id_with_exercises(): void
    {
        $trainingMenu = TrainingMenu::factory()->create([
            'user_id' => $this->user->id,
        ]);
        
        // エクササイズを追加
        $exercise1 = Exercise::first();
        $exercise2 = Exercise::skip(1)->first();
        $trainingMenu->exercises()->attach([
            $exercise1->id => ['order' => 1],
            $exercise2->id => ['order' => 2],
        ]);

        $result = $this->repository->findByUserIdWithExercises($this->user->id);

        $this->assertCount(1, $result);
        $menu = $result->first();
        $this->assertTrue($menu->relationLoaded('exercises'));
        $this->assertCount(2, $menu->exercises);
        
        // エクササイズの関連も読み込まれているか確認
        $this->assertTrue($menu->exercises->first()->relationLoaded('muscleGroupCategories'));
    }

    public function test_find_by_user_id_with_exercises_returns_sorted_by_name(): void
    {
        TrainingMenu::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'ZZZ Menu with Exercises',
        ]);
        TrainingMenu::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'AAA Menu with Exercises',
        ]);

        $result = $this->repository->findByUserIdWithExercises($this->user->id);

        $names = $result->pluck('name')->toArray();
        $this->assertEquals(['AAA Menu with Exercises', 'ZZZ Menu with Exercises'], $names);
    }

    public function test_find_by_user_id_returns_empty_when_no_menus(): void
    {
        $result = $this->repository->findByUserId($this->user->id);

        $this->assertCount(0, $result);
    }

    public function test_find_by_user_id_with_exercises_returns_empty_when_no_menus(): void
    {
        $result = $this->repository->findByUserIdWithExercises($this->user->id);

        $this->assertCount(0, $result);
    }

    public function test_eager_loading_reduces_queries(): void
    {
        $trainingMenu = TrainingMenu::factory()->create([
            'user_id' => $this->user->id,
        ]);
        
        $exercise = Exercise::first();
        $trainingMenu->exercises()->attach($exercise->id, ['order' => 1]);
        
        // クエリ数をカウント
        $queries = 0;
        \DB::listen(function ($query) use (&$queries) {
            $queries++;
        });

        $result = $this->repository->findById($trainingMenu->id);
        
        // Eager loadingにより少数のクエリで関連データを取得
        $this->assertLessThanOrEqual(5, $queries);
        
        // 関連データへのアクセスで追加クエリが発生しないことを確認
        $queriesBefore = $queries;
        $result->user->name;
        if ($result->exercises->count() > 0) {
            $result->exercises->first()->muscleGroupCategories->first()->name ?? null;
        }
        
        $this->assertEquals($queriesBefore, $queries);
    }
}