<?php

namespace Tests\Unit\UseCases\Exercise;

use App\Dto\Exercise\SearchExerciseInput;
use App\Dto\Exercise\SearchExerciseOutput;
use App\Models\Exercise;
use App\Models\Muscle;
use App\Models\MuscleGroupCategory;
use App\UseCases\Exercise\SearchExercise\SearchExerciseUseCaseInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class SearchExerciseUseCaseTest extends TestCase
{
    use RefreshDatabase;

    private SearchExerciseUseCaseInterface $useCase;
    private Exercise $exercise1;
    private Exercise $exercise2;
    private MuscleGroupCategory $muscleGroupCategory;
    private Muscle $muscle;

    protected function setUp(): void
    {
        parent::setUp();

        $this->useCase = app(SearchExerciseUseCaseInterface::class);

        // テストデータを準備
        $this->muscleGroupCategory = MuscleGroupCategory::factory()->create(['name' => 'Chest']);
        $this->muscle = Muscle::factory()->create(['name' => 'Pectoralis Major']);
        
        $this->exercise1 = Exercise::factory()->create(['name' => 'Bench Press']);
        $this->exercise2 = Exercise::factory()->create(['name' => 'Push Up']);
        
        // エクササイズと筋肉グループカテゴリを関連付け
        $this->exercise1->muscleGroupCategories()->attach($this->muscleGroupCategory->id);
        $this->exercise2->muscleGroupCategories()->attach($this->muscleGroupCategory->id);
        
        // エクササイズと筋肉を関連付け
        $this->exercise1->muscles()->attach($this->muscle->id, ['is_primary' => true]);
        $this->exercise2->muscles()->attach($this->muscle->id, ['is_primary' => false]);
    }

    public function test_execute_returns_all_exercises_without_filters(): void
    {
        $input = new SearchExerciseInput();
        
        $output = $this->useCase->execute($input);
        
        $this->assertInstanceOf(SearchExerciseOutput::class, $output);
        $this->assertGreaterThanOrEqual(2, $output->getExerciseCount());
        $this->assertArrayHasKey('total', $output->pagination);
        $this->assertArrayHasKey('per_page', $output->pagination);
        $this->assertArrayHasKey('current_page', $output->pagination);
    }

    public function test_execute_filters_by_name(): void
    {
        $input = new SearchExerciseInput(name: 'Bench');
        
        $output = $this->useCase->execute($input);
        
        $this->assertGreaterThanOrEqual(1, $output->getExerciseCount());
        
        // 結果にBenchが含まれることを確認
        $found = false;
        foreach ($output->exercises as $exercise) {
            if (stripos($exercise['name'], 'Bench') !== false) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, 'Bench exercise should be found');
    }

    public function test_execute_filters_by_muscle_group_category(): void
    {
        $input = new SearchExerciseInput(
            muscleGroupCategoryIds: [$this->muscleGroupCategory->id]
        );
        
        $output = $this->useCase->execute($input);
        
        $this->assertGreaterThanOrEqual(2, $output->getExerciseCount());
    }

    public function test_execute_filters_by_muscle_ids(): void
    {
        $input = new SearchExerciseInput(
            muscleIds: [$this->muscle->id]
        );
        
        $output = $this->useCase->execute($input);
        
        $this->assertGreaterThanOrEqual(2, $output->getExerciseCount());
    }

    public function test_execute_with_pagination(): void
    {
        // 追加のエクササイズを作成してページネーションをテスト
        Exercise::factory()->count(25)->create();
        
        $input = new SearchExerciseInput(page: 1, perPage: 10);
        
        $output = $this->useCase->execute($input);
        
        $this->assertLessThanOrEqual(10, $output->getExerciseCount());
        $this->assertEquals(1, $output->pagination['current_page']);
        $this->assertEquals(10, $output->pagination['per_page']);
    }

    public function test_execute_with_multiple_filters(): void
    {
        $input = new SearchExerciseInput(
            name: 'Press',
            muscleGroupCategoryIds: [$this->muscleGroupCategory->id],
            page: 1,
            perPage: 5
        );
        
        $output = $this->useCase->execute($input);
        
        $this->assertInstanceOf(SearchExerciseOutput::class, $output);
        $this->assertLessThanOrEqual(5, $output->getExerciseCount());
    }

    public function test_execute_throws_exception_with_invalid_page_number(): void
    {
        $input = new SearchExerciseInput(page: 0);
        
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Page number must be 1 or greater');
        
        $this->useCase->execute($input);
    }

    public function test_execute_throws_exception_with_invalid_per_page(): void
    {
        $input = new SearchExerciseInput(perPage: 0);
        
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Per page must be between 1 and 100');
        
        $this->useCase->execute($input);
    }

    public function test_execute_throws_exception_with_per_page_too_large(): void
    {
        $input = new SearchExerciseInput(perPage: 101);
        
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Per page must be between 1 and 100');
        
        $this->useCase->execute($input);
    }

    public function test_execute_throws_exception_with_empty_name(): void
    {
        $input = new SearchExerciseInput(name: '   ');
        
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Name must not be empty when provided');
        
        $this->useCase->execute($input);
    }

    public function test_execute_throws_exception_with_empty_muscle_group_category_ids(): void
    {
        $input = new SearchExerciseInput(muscleGroupCategoryIds: []);
        
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Muscle group category IDs must not be empty when provided');
        
        $this->useCase->execute($input);
    }

    public function test_execute_throws_exception_with_empty_muscle_ids(): void
    {
        $input = new SearchExerciseInput(muscleIds: []);
        
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Muscle IDs must not be empty when provided');
        
        $this->useCase->execute($input);
    }

    public function test_has_filters_returns_true_when_name_provided(): void
    {
        $input = new SearchExerciseInput(name: 'Bench');
        
        $this->assertTrue($input->hasFilters());
    }

    public function test_has_filters_returns_true_when_muscle_group_category_ids_provided(): void
    {
        $input = new SearchExerciseInput(muscleGroupCategoryIds: [1]);
        
        $this->assertTrue($input->hasFilters());
    }

    public function test_has_filters_returns_true_when_muscle_ids_provided(): void
    {
        $input = new SearchExerciseInput(muscleIds: [1]);
        
        $this->assertTrue($input->hasFilters());
    }

    public function test_has_filters_returns_false_when_no_filters(): void
    {
        $input = new SearchExerciseInput();
        
        $this->assertFalse($input->hasFilters());
    }

    public function test_input_to_array_returns_correct_structure(): void
    {
        $input = new SearchExerciseInput(
            name: 'Bench',
            muscleGroupCategoryIds: [1, 2],
            muscleIds: [3, 4],
            page: 2,
            perPage: 15
        );
        
        $expected = [
            'name' => 'Bench',
            'muscle_group_category_ids' => [1, 2],
            'muscle_ids' => [3, 4],
            'page' => 2,
            'per_page' => 15,
        ];
        
        $this->assertEquals($expected, $input->toArray());
    }

    public function test_output_to_array_returns_correct_structure(): void
    {
        $exercises = [
            ['id' => 1, 'name' => 'Bench Press'],
            ['id' => 2, 'name' => 'Push Up'],
        ];
        
        $pagination = [
            'total' => 2,
            'per_page' => 20,
            'current_page' => 1,
        ];
        
        $output = new SearchExerciseOutput($exercises, $pagination);
        
        $expected = [
            'exercises' => $exercises,
            'pagination' => $pagination,
        ];
        
        $this->assertEquals($expected, $output->toArray());
        $this->assertEquals(2, $output->getExerciseCount());
    }
}