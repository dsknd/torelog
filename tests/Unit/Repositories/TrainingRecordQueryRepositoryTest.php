<?php

namespace Tests\Unit\Repositories;

use App\Models\Exercise;
use App\Models\ExerciseLog;
use App\Models\TrainingMenu;
use App\Models\TrainingRecord;
use App\Models\User;
use App\Models\WeightUnit;
use App\Repositories\Eloquent\TrainingRecordQueryRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainingRecordQueryRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private TrainingRecordQueryRepository $repository;
    private User $user;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new TrainingRecordQueryRepository();
        $this->user = User::factory()->create();
        
        // シードデータの準備
        $this->seed(\Database\Seeders\WeightUnitSeeder::class);
        $this->seed(\Database\Seeders\MuscleGroupCategorySeeder::class);
        $this->seed(\Database\Seeders\MuscleSeeder::class);
        $this->seed(\Database\Seeders\ExerciseSeeder::class);
    }

    public function test_find_by_id(): void
    {
        $trainingMenu = TrainingMenu::factory()->create(['user_id' => $this->user->id]);
        $trainingRecord = TrainingRecord::factory()->create([
            'user_id' => $this->user->id,
            'training_menu_id' => $trainingMenu->id,
        ]);
        
        $exercise = Exercise::first();
        $weightUnit = WeightUnit::first();
        
        ExerciseLog::factory()->count(3)->create([
            'training_record_id' => $trainingRecord->id,
            'exercise_id' => $exercise->id,
            'weight_unit_id' => $weightUnit->id,
        ]);

        $result = $this->repository->findById($trainingRecord->id);

        $this->assertNotNull($result);
        $this->assertEquals($trainingRecord->id, $result->id);
        $this->assertTrue($result->relationLoaded('user'));
        $this->assertTrue($result->relationLoaded('trainingMenu'));
        $this->assertTrue($result->relationLoaded('exerciseLogs'));
        $this->assertCount(3, $result->exerciseLogs);
        $this->assertTrue($result->exerciseLogs->first()->relationLoaded('exercise'));
        $this->assertTrue($result->exerciseLogs->first()->relationLoaded('weightUnit'));
    }

    public function test_find_by_id_returns_null_when_not_found(): void
    {
        $result = $this->repository->findById(99999);

        $this->assertNull($result);
    }

    public function test_find_by_user_id(): void
    {
        $anotherUser = User::factory()->create();
        
        // 対象ユーザーの記録
        TrainingRecord::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'date' => '2025-01-06',
        ]);
        
        // 別ユーザーの記録
        TrainingRecord::factory()->count(2)->create([
            'user_id' => $anotherUser->id,
        ]);

        $result = $this->repository->findByUserId($this->user->id);

        $this->assertCount(3, $result);
        $this->assertTrue($result->every(fn($record) => $record->user_id === $this->user->id));
        $this->assertTrue($result->first()->relationLoaded('trainingMenu'));
        $this->assertTrue($result->first()->relationLoaded('exerciseLogs'));
    }

    public function test_find_by_user_id_and_date(): void
    {
        TrainingRecord::factory()->count(2)->create([
            'user_id' => $this->user->id,
            'date' => '2025-01-06',
        ]);
        
        TrainingRecord::factory()->create([
            'user_id' => $this->user->id,
            'date' => '2025-01-07',
        ]);

        $result = $this->repository->findByUserIdAndDate($this->user->id, '2025-01-06');

        $this->assertCount(2, $result);
        $this->assertTrue($result->every(fn($record) => 
            $record->date->format('Y-m-d') === '2025-01-06'
        ));
    }

    public function test_find_by_user_id_with_date_range(): void
    {
        TrainingRecord::factory()->create([
            'user_id' => $this->user->id,
            'date' => '2025-01-01',
        ]);
        
        TrainingRecord::factory()->create([
            'user_id' => $this->user->id,
            'date' => '2025-01-05',
        ]);
        
        TrainingRecord::factory()->create([
            'user_id' => $this->user->id,
            'date' => '2025-01-10',
        ]);
        
        TrainingRecord::factory()->create([
            'user_id' => $this->user->id,
            'date' => '2025-01-15',
        ]);

        $result = $this->repository->findByUserIdWithDateRange(
            $this->user->id,
            '2025-01-05',
            '2025-01-10'
        );

        $this->assertCount(2, $result);
        $dates = $result->pluck('date')->map(fn($date) => $date->format('Y-m-d'))->toArray();
        $this->assertEquals(['2025-01-10', '2025-01-05'], $dates); // desc order
    }

    public function test_eager_loading_relationships(): void
    {
        $trainingRecord = TrainingRecord::factory()->create([
            'user_id' => $this->user->id,
        ]);
        
        $exercise = Exercise::first();
        $weightUnit = WeightUnit::first();
        
        ExerciseLog::factory()->create([
            'training_record_id' => $trainingRecord->id,
            'exercise_id' => $exercise->id,
            'weight_unit_id' => $weightUnit->id,
        ]);

        // クエリ数をカウント
        $queries = 0;
        \DB::listen(function ($query) use (&$queries) {
            $queries++;
        });

        $result = $this->repository->findById($trainingRecord->id);
        
        // Eager loadingにより少数のクエリで全ての関連データを取得
        $this->assertLessThanOrEqual(10, $queries); // Seederとファクトリによりクエリが多くなるため余裕を持たせる
        
        // 関連データへのアクセスで追加クエリが発生しないことを確認
        $queriesBefore = $queries;
        $result->user->name;
        $result->trainingMenu;
        $result->exerciseLogs->first()->exercise->name;
        $result->exerciseLogs->first()->weightUnit->symbol;
        
        $this->assertEquals($queriesBefore, $queries);
    }
}