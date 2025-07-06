<?php

namespace Tests\Unit\UseCases\TrainingRecord;

use App\Dto\TrainingRecord\CreateTrainingRecordInput;
use App\Dto\TrainingRecord\CreateTrainingRecordOutput;
use App\Dto\TrainingRecord\ExerciseLogData;
use App\Models\Exercise;
use App\Models\ExerciseLog;
use App\Models\TrainingRecord;
use App\Models\User;
use App\Models\WeightUnit;
use App\Repositories\Interfaces\TrainingRecordCommandRepositoryInterface;
use App\UseCases\TrainingRecord\CreateTrainingRecord\CreateTrainingRecordUseCase;
use App\UseCases\TrainingRecord\CreateTrainingRecord\CreateTrainingRecordUseCaseInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class CreateTrainingRecordUseCaseTest extends TestCase
{
    use RefreshDatabase;

    private CreateTrainingRecordUseCaseInterface $useCase;
    private TrainingRecordCommandRepositoryInterface $repository;
    private User $user;
    private Exercise $exercise;
    private WeightUnit $weightUnit;

    protected function setUp(): void
    {
        parent::setUp();

        // 実際のリポジトリを使用
        $this->repository = app(TrainingRecordCommandRepositoryInterface::class);
        $this->useCase = app(CreateTrainingRecordUseCaseInterface::class);

        // テストデータを準備
        $this->user = User::factory()->create();
        $this->exercise = Exercise::factory()->create();
        $this->weightUnit = WeightUnit::factory()->create();
    }

    public function test_execute_successfully_creates_training_record(): void
    {
        $exerciseLogData = new ExerciseLogData(
            exerciseId: $this->exercise->id,
            weightUnitId: $this->weightUnit->id,
            setNumber: 1,
            weight: 50.0,
            reps: 10,
            memo: 'Good set'
        );

        $input = new CreateTrainingRecordInput(
            userId: $this->user->id,
            trainingMenuId: null,
            date: '2025-01-06',
            memo: 'Good workout',
            exerciseLogs: [$exerciseLogData]
        );

        $output = $this->useCase->execute($input);

        $this->assertInstanceOf(CreateTrainingRecordOutput::class, $output);
        $this->assertNotNull($output->trainingRecordId);
        $this->assertEquals(1, $output->exerciseLogCount);
        $this->assertNotEmpty($output->createdAt);

        // TrainingRecordが作成されたことを確認
        $this->assertDatabaseHas('training_records', [
            'id' => $output->trainingRecordId,
            'user_id' => $this->user->id,
            'training_menu_id' => null,
            'date' => '2025-01-06',
            'memo' => 'Good workout',
        ]);

        // ExerciseLogが作成されたことを確認
        $this->assertDatabaseHas('exercise_logs', [
            'training_record_id' => $output->trainingRecordId,
            'exercise_id' => $this->exercise->id,
            'weight_unit_id' => $this->weightUnit->id,
            'set_number' => 1,
            'weight' => 50.0,
            'reps' => 10,
            'memo' => 'Good set',
        ]);
    }

    public function test_execute_with_multiple_exercise_logs(): void
    {
        $exerciseLog1 = new ExerciseLogData(
            exerciseId: $this->exercise->id,
            weightUnitId: $this->weightUnit->id,
            setNumber: 1,
            weight: 50.0,
            reps: 10
        );

        $exerciseLog2 = new ExerciseLogData(
            exerciseId: $this->exercise->id,
            weightUnitId: $this->weightUnit->id,
            setNumber: 2,
            weight: 52.5,
            reps: 8
        );

        $input = new CreateTrainingRecordInput(
            userId: $this->user->id,
            trainingMenuId: null,
            date: '2025-01-06',
            memo: null,
            exerciseLogs: [$exerciseLog1, $exerciseLog2]
        );

        $output = $this->useCase->execute($input);

        $this->assertEquals(2, $output->exerciseLogCount);
        $this->assertEquals(2, ExerciseLog::count());
        
        // 両方のエクササイズログが作成されたことを確認
        $this->assertDatabaseHas('exercise_logs', [
            'training_record_id' => $output->trainingRecordId,
            'set_number' => 1,
            'weight' => 50.0,
            'reps' => 10,
        ]);
        
        $this->assertDatabaseHas('exercise_logs', [
            'training_record_id' => $output->trainingRecordId,
            'set_number' => 2,
            'weight' => 52.5,
            'reps' => 8,
        ]);
    }

    public function test_execute_throws_exception_when_user_not_found(): void
    {
        $exerciseLogData = new ExerciseLogData(
            exerciseId: $this->exercise->id,
            weightUnitId: $this->weightUnit->id,
            setNumber: 1,
            weight: 50.0,
            reps: 10
        );

        $input = new CreateTrainingRecordInput(
            userId: 99999, // 存在しないユーザー
            trainingMenuId: null,
            date: '2025-01-06',
            memo: null,
            exerciseLogs: [$exerciseLogData]
        );

        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage('User with id 99999 not found');

        $this->useCase->execute($input);
    }

    public function test_execute_throws_exception_when_exercise_logs_empty(): void
    {
        $input = new CreateTrainingRecordInput(
            userId: $this->user->id,
            trainingMenuId: null,
            date: '2025-01-06',
            memo: null,
            exerciseLogs: [] // 空のエクササイズログ
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Exercise logs cannot be empty');

        $this->useCase->execute($input);
    }

    public function test_execute_throws_exception_when_exercise_not_found(): void
    {
        $exerciseLogData = new ExerciseLogData(
            exerciseId: 99999, // 存在しないエクササイズ
            weightUnitId: $this->weightUnit->id,
            setNumber: 1,
            weight: 50.0,
            reps: 10
        );

        $input = new CreateTrainingRecordInput(
            userId: $this->user->id,
            trainingMenuId: null,
            date: '2025-01-06',
            memo: null,
            exerciseLogs: [$exerciseLogData]
        );

        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage('Exercises not found: 99999');

        $this->useCase->execute($input);
    }

    public function test_execute_throws_exception_when_weight_unit_not_found(): void
    {
        $exerciseLogData = new ExerciseLogData(
            exerciseId: $this->exercise->id,
            weightUnitId: 99999, // 存在しない重量単位
            setNumber: 1,
            weight: 50.0,
            reps: 10
        );

        $input = new CreateTrainingRecordInput(
            userId: $this->user->id,
            trainingMenuId: null,
            date: '2025-01-06',
            memo: null,
            exerciseLogs: [$exerciseLogData]
        );

        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage('Weight units not found: 99999');

        $this->useCase->execute($input);
    }

    public function test_execute_throws_exception_with_invalid_set_number(): void
    {
        $exerciseLogData = new ExerciseLogData(
            exerciseId: $this->exercise->id,
            weightUnitId: $this->weightUnit->id,
            setNumber: 0, // 無効なセット番号
            weight: 50.0,
            reps: 10
        );

        $input = new CreateTrainingRecordInput(
            userId: $this->user->id,
            trainingMenuId: null,
            date: '2025-01-06',
            memo: null,
            exerciseLogs: [$exerciseLogData]
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Set number must be 1 or greater');

        $this->useCase->execute($input);
    }

    public function test_execute_throws_exception_with_negative_weight(): void
    {
        $exerciseLogData = new ExerciseLogData(
            exerciseId: $this->exercise->id,
            weightUnitId: $this->weightUnit->id,
            setNumber: 1,
            weight: -10.0, // 負の重量
            reps: 10
        );

        $input = new CreateTrainingRecordInput(
            userId: $this->user->id,
            trainingMenuId: null,
            date: '2025-01-06',
            memo: null,
            exerciseLogs: [$exerciseLogData]
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Weight must be 0 or greater');

        $this->useCase->execute($input);
    }

    public function test_execute_throws_exception_with_invalid_reps(): void
    {
        $exerciseLogData = new ExerciseLogData(
            exerciseId: $this->exercise->id,
            weightUnitId: $this->weightUnit->id,
            setNumber: 1,
            weight: 50.0,
            reps: 0 // 無効なレップ数
        );

        $input = new CreateTrainingRecordInput(
            userId: $this->user->id,
            trainingMenuId: null,
            date: '2025-01-06',
            memo: null,
            exerciseLogs: [$exerciseLogData]
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Reps must be 1 or greater');

        $this->useCase->execute($input);
    }

    public function test_execute_throws_exception_with_invalid_date_format(): void
    {
        $exerciseLogData = new ExerciseLogData(
            exerciseId: $this->exercise->id,
            weightUnitId: $this->weightUnit->id,
            setNumber: 1,
            weight: 50.0,
            reps: 10
        );

        $input = new CreateTrainingRecordInput(
            userId: $this->user->id,
            trainingMenuId: null,
            date: 'invalid-date', // 無効な日付形式
            memo: null,
            exerciseLogs: [$exerciseLogData]
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Date must be in Y-m-d format');

        $this->useCase->execute($input);
    }
}