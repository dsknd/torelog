<?php

namespace Tests\Unit\Repositories;

use App\Models\Exercise;
use App\Models\Muscle;
use App\Models\MuscleGroupCategory;
use App\Repositories\Eloquent\ExerciseQueryRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExerciseQueryRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private ExerciseQueryRepository $repository;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new ExerciseQueryRepository();
        
        // シードデータの準備
        $this->seed(\Database\Seeders\MuscleGroupCategorySeeder::class);
        $this->seed(\Database\Seeders\MuscleSeeder::class);
        $this->seed(\Database\Seeders\ExerciseSeeder::class);
    }

    public function test_find_by_id(): void
    {
        $exercise = Exercise::first();

        $result = $this->repository->findById($exercise->id);

        $this->assertNotNull($result);
        $this->assertEquals($exercise->id, $result->id);
        $this->assertEquals($exercise->name, $result->name);
        $this->assertTrue($result->relationLoaded('muscles'));
        $this->assertTrue($result->relationLoaded('muscleGroupCategories'));
        
        // 筋肉の関連も読み込まれているか確認
        if ($result->muscles->count() > 0) {
            $this->assertTrue($result->muscles->first()->relationLoaded('muscleGroupCategory'));
        }
    }

    public function test_find_by_id_returns_null_when_not_found(): void
    {
        $result = $this->repository->findById(99999);

        $this->assertNull($result);
    }

    public function test_find_all(): void
    {
        $result = $this->repository->findAll();

        $this->assertGreaterThan(0, $result->count());
        $this->assertTrue($result->first()->relationLoaded('muscleGroupCategories'));
        
        // 特定のエクササイズを追加してソート順を確認
        Exercise::factory()->create(['name' => 'AAA Test Exercise']);
        Exercise::factory()->create(['name' => 'ZZZ Test Exercise']);
        
        $sortedResult = $this->repository->findAll();
        $names = $sortedResult->pluck('name')->toArray();
        
        // 最初と最後の要素でソートを確認
        $this->assertEquals('AAA Test Exercise', $names[0]);
        $this->assertEquals('ZZZ Test Exercise', $names[count($names) - 1]);
    }

    public function test_find_by_muscle_group_category_id(): void
    {
        $category = MuscleGroupCategory::first();
        
        // カテゴリに関連するエクササイズを作成
        $exercise = Exercise::factory()->create(['name' => 'Test Exercise']);
        $exercise->muscleGroupCategories()->attach($category->id);

        $result = $this->repository->findByMuscleGroupCategoryId($category->id);

        $this->assertGreaterThan(0, $result->count());
        $this->assertTrue($result->contains('id', $exercise->id));
        $this->assertTrue($result->first()->relationLoaded('muscleGroupCategories'));
        
        // 全ての結果が指定されたカテゴリに属しているか確認
        foreach ($result as $exerciseResult) {
            $this->assertTrue(
                $exerciseResult->muscleGroupCategories->contains('id', $category->id)
            );
        }
    }

    public function test_find_by_muscle_id(): void
    {
        $muscle = Muscle::first();
        
        // 筋肉に関連するエクササイズを作成
        $exercise = Exercise::factory()->create(['name' => 'Test Exercise']);
        $exercise->muscles()->attach($muscle->id, ['is_primary' => true]);

        $result = $this->repository->findByMuscleId($muscle->id);

        $this->assertGreaterThan(0, $result->count());
        $this->assertTrue($result->contains('id', $exercise->id));
        $this->assertTrue($result->first()->relationLoaded('muscles'));
        
        // 筋肉の関連も読み込まれているか確認
        if ($result->first()->muscles->count() > 0) {
            $this->assertTrue($result->first()->muscles->first()->relationLoaded('muscleGroupCategory'));
        }
        
        // 全ての結果が指定された筋肉をターゲットにしているか確認
        foreach ($result as $exerciseResult) {
            $this->assertTrue(
                $exerciseResult->muscles->contains('id', $muscle->id)
            );
        }
    }

    public function test_search_by_name(): void
    {
        // テスト用のユニークなエクササイズを作成
        Exercise::factory()->create(['name' => 'Unique Bench Press Test']);
        Exercise::factory()->create(['name' => 'Unique Dumbbell Press Test']);
        Exercise::factory()->create(['name' => 'Unique Squat Test']);

        $result = $this->repository->searchByName('Press Test');

        $this->assertEquals(2, $result->count());
        $this->assertTrue($result->contains('name', 'Unique Bench Press Test'));
        $this->assertTrue($result->contains('name', 'Unique Dumbbell Press Test'));
        $this->assertFalse($result->contains('name', 'Unique Squat Test'));
        $this->assertTrue($result->first()->relationLoaded('muscleGroupCategories'));
    }

    public function test_search_by_name_case_insensitive(): void
    {
        Exercise::factory()->create(['name' => 'Unique Case Test Exercise']);

        $result = $this->repository->searchByName('unique case test');

        $this->assertEquals(1, $result->count());
        $this->assertTrue($result->contains('name', 'Unique Case Test Exercise'));
    }

    public function test_search_by_name_partial_match(): void
    {
        Exercise::factory()->create(['name' => 'Unique Partial Match Test']);

        $result = $this->repository->searchByName('Partial Match');

        $this->assertEquals(1, $result->count());
        $this->assertTrue($result->contains('name', 'Unique Partial Match Test'));
    }

    public function test_search_by_name_returns_empty_when_no_match(): void
    {
        Exercise::factory()->create(['name' => 'Some Exercise']);

        $result = $this->repository->searchByName('NonExistentUniqueString');

        $this->assertEquals(0, $result->count());
    }

    public function test_eager_loading_reduces_queries(): void
    {
        $exercise = Exercise::first();
        
        // クエリ数をカウント
        $queries = 0;
        \DB::listen(function ($query) use (&$queries) {
            $queries++;
        });

        $result = $this->repository->findById($exercise->id);
        
        // Eager loadingにより少数のクエリで関連データを取得
        $this->assertLessThanOrEqual(5, $queries);
        
        // 関連データへのアクセスで追加クエリが発生しないことを確認
        $queriesBefore = $queries;
        if ($result->muscles->count() > 0) {
            $result->muscles->first()->muscleGroupCategory->name;
        }
        if ($result->muscleGroupCategories->count() > 0) {
            $result->muscleGroupCategories->first()->name;
        }
        
        $this->assertEquals($queriesBefore, $queries);
    }
}