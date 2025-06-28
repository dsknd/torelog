# テスト設計ドキュメント

## 概要

Torelogプロジェクトにおけるテスト戦略とテスト実装のガイドライン。

## テスト戦略

### テスト構成

```
tests/
├── Unit/                         # ユニットテスト
│   ├── ValueObjects/            # Value Objectのテスト
│   │   ├── WeightTest.php
│   │   ├── RepsTest.php
│   │   ├── SetNumberTest.php
│   │   └── TrainingDateTest.php
│   ├── Repositories/            # Repository（モック使用）
│   │   ├── TrainingRecordCommandRepositoryTest.php
│   │   └── TrainingRecordQueryRepositoryTest.php
│   ├── UseCases/                # UseCase（モック使用）
│   │   └── TrainingRecord/
│   │       └── CreateTrainingRecordUseCaseTest.php
│   ├── Models/                  # Eloquentモデル
│   │   ├── UserTest.php
│   │   ├── ExerciseTest.php
│   │   └── TrainingRecordTest.php
│   └── Enums/                   # Enumクラス
│       ├── WeightUnitEnumTest.php
│       ├── MuscleEnumTest.php
│       └── ExerciseEnumTest.php
└── Feature/                      # 統合テスト
    ├── Repositories/            # Repository（実DB使用）
    │   ├── TrainingRecordCommandRepositoryIntegrationTest.php
    │   └── TrainingRecordQueryRepositoryIntegrationTest.php
    ├── UseCases/                # UseCase（実Repository使用）
    │   └── TrainingRecord/
    │       └── CreateTrainingRecordUseCaseIntegrationTest.php
    └── Database/                # DB関連
        ├── Seeders/
        │   ├── WeightUnitSeederTest.php
        │   └── ExerciseSeederTest.php
        └── Migrations/
            └── DatabaseMigrationTest.php
```

## テスト基盤

### BaseTestCase

```php
<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class BaseTestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(); // 基本的なマスターデータをシード
    }
}
```

### IntegrationTestCase

```php
<?php

namespace Tests\Feature;

use Tests\BaseTestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class IntegrationTestCase extends BaseTestCase
{
    use DatabaseTransactions;
    
    protected function setUp(): void
    {
        parent::setUp();
        // 統合テスト用の追加設定
    }
}
```

## Value Object テスト

### テスト対象

- **バリデーション**: 不正値の検証
- **ビジネスルール**: ドメインロジックの検証
- **型変換**: Cast動作の検証
- **等価性**: Value Objectの同値性

### 実装例

```php
class WeightTest extends TestCase
{
    public function test_有効な重量値で作成できる(): void
    {
        $weightUnit = WeightUnit::factory()->create();
        $weight = new Weight(100.5, $weightUnit);
        
        $this->assertEquals(100.5, $weight->getValue());
        $this->assertEquals($weightUnit->id, $weight->getUnit()->id);
    }

    public function test_負の重量値で例外が発生する(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $weightUnit = WeightUnit::factory()->create();
        new Weight(-10, $weightUnit);
    }

    public function test_重量単位変換が正しく動作する(): void
    {
        $kg = WeightUnit::factory()->create(['symbol' => 'kg', 'conversion_rate' => 1.0]);
        $lb = WeightUnit::factory()->create(['symbol' => 'lb', 'conversion_rate' => 0.453592]);
        
        $weight = new Weight(100, $kg);
        $convertedWeight = $weight->convertTo($lb);
        
        $this->assertEqualsWithDelta(220.46, $convertedWeight->getValue(), 0.1);
    }
}
```

## Repository テスト

### Unit Test（モック使用）

- **インターフェース準拠**: 戻り値型の検証
- **メソッド呼び出し**: 引数の正確性
- **例外処理**: エラーハンドリング

### Integration Test（実DB使用）

- **CRUD操作**: 実際のDB操作
- **リレーション**: Eloquent関係の検証
- **トランザクション**: データ整合性
- **パフォーマンス**: N+1問題の回避

### 実装例

```php
// Unit Test
class TrainingRecordCommandRepositoryTest extends TestCase
{
    public function test_create_メソッドがTrainingRecordを返す(): void
    {
        $repository = Mockery::mock(TrainingRecordCommandRepositoryInterface::class);
        $trainingRecord = TrainingRecord::factory()->make();
        
        $repository->shouldReceive('create')
            ->once()
            ->with(Mockery::type('array'))
            ->andReturn($trainingRecord);
            
        $result = $repository->create([
            'user_id' => 1,
            'date' => '2025-06-28',
        ]);
        
        $this->assertInstanceOf(TrainingRecord::class, $result);
    }
}

// Integration Test
class TrainingRecordCommandRepositoryIntegrationTest extends IntegrationTestCase
{
    public function test_create_で実際にDBにレコードが作成される(): void
    {
        $user = User::factory()->create();
        $trainingMenu = TrainingMenu::factory()->create(['user_id' => $user->id]);
        
        $repository = app(TrainingRecordCommandRepositoryInterface::class);
        
        $result = $repository->create([
            'user_id' => $user->id,
            'training_menu_id' => $trainingMenu->id,
            'date' => '2025-06-28',
            'memo' => 'テストメモ'
        ]);
        
        $this->assertDatabaseHas('training_records', [
            'id' => $result->id,
            'user_id' => $user->id,
            'training_menu_id' => $trainingMenu->id,
        ]);
    }
}
```

## UseCase テスト

### Unit Test（モック使用）

- **ビジネスロジック**: 業務ルールの検証
- **依存性注入**: Repository呼び出しの検証
- **例外処理**: エラーケースの検証

### Integration Test（実Repository使用）

- **フローテスト**: 処理全体の検証
- **トランザクション**: 整合性の検証
- **データベース状態**: 最終的なデータ状態

### 実装例

```php
// Unit Test
class CreateTrainingRecordUseCaseTest extends TestCase
{
    public function test_正常なコマンドでトレーニング記録が作成される(): void
    {
        $commandRepo = Mockery::mock(TrainingRecordCommandRepositoryInterface::class);
        $queryRepo = Mockery::mock(TrainingRecordQueryRepositoryInterface::class);
        $exerciseRepo = Mockery::mock(ExerciseQueryRepositoryInterface::class);
        
        $trainingRecord = TrainingRecord::factory()->make();
        
        $commandRepo->shouldReceive('create')->once()->andReturn($trainingRecord);
        $queryRepo->shouldReceive('findById')->once()->andReturn($trainingRecord);
        
        $useCase = new CreateTrainingRecordUseCase($commandRepo, $queryRepo, $exerciseRepo);
        $command = new CreateTrainingRecordCommand(
            userId: 1,
            date: new TrainingDate('2025-06-28'),
            trainingMenuId: null,
            memo: null,
            exerciseLogs: []
        );
        
        $result = $useCase->execute($command);
        
        $this->assertInstanceOf(TrainingRecord::class, $result);
    }

    public function test_未来の日付でビジネス例外が発生する(): void
    {
        $commandRepo = Mockery::mock(TrainingRecordCommandRepositoryInterface::class);
        $queryRepo = Mockery::mock(TrainingRecordQueryRepositoryInterface::class);
        $exerciseRepo = Mockery::mock(ExerciseQueryRepositoryInterface::class);
        
        $useCase = new CreateTrainingRecordUseCase($commandRepo, $queryRepo, $exerciseRepo);
        $command = new CreateTrainingRecordCommand(
            userId: 1,
            date: new TrainingDate(Carbon::tomorrow()->format('Y-m-d')),
            trainingMenuId: null,
            memo: null,
            exerciseLogs: []
        );
        
        $this->expectException(InvalidArgumentException::class);
        $useCase->execute($command);
    }
}
```

## Factory 設計

### 基本Factory

```php
class ExerciseFactory extends Factory
{
    protected $model = Exercise::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'ベンチプレス', 'スクワット', 'デッドリフト', '懸垂'
            ]),
            'description' => $this->faker->sentence(),
        ];
    }

    public function withMuscles(array $muscles = []): static
    {
        return $this->afterCreating(function (Exercise $exercise) use ($muscles) {
            if (empty($muscles)) {
                $muscles = Muscle::factory(3)->create();
            }
            
            foreach ($muscles as $muscle) {
                $exercise->muscles()->attach($muscle->id, ['is_primary' => true]);
            }
        });
    }
}
```

## テスト実行戦略

### 実行コマンド

```bash
# 全テスト実行
./vendor/bin/sail artisan test

# ユニットテストのみ
./vendor/bin/sail artisan test --testsuite=Unit

# 統合テストのみ
./vendor/bin/sail artisan test --testsuite=Feature

# カバレッジ付き実行
./vendor/bin/sail artisan test --coverage

# 特定クラスのテスト
./vendor/bin/sail artisan test tests/Unit/ValueObjects/WeightTest.php
```

### CI/CD統合

```yaml
# GitHub Actions例
test:
  runs-on: ubuntu-latest
  steps:
    - uses: actions/checkout@v2
    - name: Setup Laravel
      run: |
        cp .env.testing .env
        ./vendor/bin/sail up -d
    - name: Run tests
      run: ./vendor/bin/sail artisan test --coverage
```

## パフォーマンステスト

### 大量データテスト

```php
class TrainingRecordPerformanceTest extends IntegrationTestCase
{
    public function test_大量データでのクエリパフォーマンス(): void
    {
        $user = User::factory()->create();
        TrainingRecord::factory(1000)->create(['user_id' => $user->id]);
        
        $start = microtime(true);
        
        $repository = app(TrainingRecordQueryRepositoryInterface::class);
        $records = $repository->findByUserId($user->id);
        
        $end = microtime(true);
        $executionTime = $end - $start;
        
        $this->assertLessThan(1.0, $executionTime); // 1秒以内
        $this->assertCount(1000, $records);
    }
}
```

## テストデータ管理

### Seederテスト

```php
class WeightUnitSeederTest extends IntegrationTestCase
{
    public function test_WeightUnitSeederが正しくデータを投入する(): void
    {
        $this->artisan('db:seed', ['--class' => 'WeightUnitSeeder']);
        
        $this->assertDatabaseHas('weight_units', ['symbol' => 'kg']);
        $this->assertDatabaseHas('weight_units', ['symbol' => 'lb']);
        $this->assertDatabaseHas('weight_units', ['symbol' => 'g']);
        
        $this->assertEquals(3, WeightUnit::count());
    }
}
```

## テスト実装優先度

### 第1フェーズ（高優先度）
1. Value Objectテスト
2. Enumテスト
3. Repository統合テスト
4. 主要UseCaseテスト

### 第2フェーズ（中優先度）
1. Modelテスト
2. Repository単体テスト
3. UseCase単体テスト
4. Seederテスト

### 第3フェーズ（低優先度）
1. パフォーマンステスト
2. エッジケーステスト
3. APIテスト（将来実装）

## 品質指標

### カバレッジ目標
- **全体**: 80%以上
- **ValueObject**: 95%以上
- **UseCase**: 90%以上
- **Repository**: 85%以上

### テスト実行時間目標
- **ユニットテスト**: 30秒以内
- **統合テスト**: 2分以内
- **全テスト**: 3分以内