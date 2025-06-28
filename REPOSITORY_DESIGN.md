# Repository Design

## 概要

TorelogプロジェクトでのRepository層の設計方針とCQRSパターンの実装について記載します。

## 基本方針

### CQRS (Command Query Responsibility Segregation)

書き込み処理と読み取り処理を分離し、それぞれに最適化されたRepositoryを提供します。

- **CommandRepository**: 書き込み専用（Create, Update, Delete）
- **QueryRepository**: 読み取り専用（Find, Search）

### 戻り値の方針

- **CommandRepository**: Laravelモデル（Eloquent）を返す
- **QueryRepository**: 
  - シンプルな取得: Laravelモデルを返す
  - 複雑なJOIN: 将来的にQueryModel/ReadModelを返す（現在は未実装）

## ディレクトリ構造

```
app/
├── Repositories/
│   ├── Interfaces/           # Repository インターフェース
│   │   ├── UserCommandRepositoryInterface.php
│   │   ├── UserQueryRepositoryInterface.php
│   │   ├── ExerciseCommandRepositoryInterface.php
│   │   ├── ExerciseQueryRepositoryInterface.php
│   │   ├── TrainingMenuCommandRepositoryInterface.php
│   │   ├── TrainingMenuQueryRepositoryInterface.php
│   │   ├── TrainingRecordCommandRepositoryInterface.php
│   │   └── TrainingRecordQueryRepositoryInterface.php
│   └── Eloquent/             # Repository 実装
│       ├── UserCommandRepository.php
│       ├── UserQueryRepository.php
│       ├── ExerciseCommandRepository.php
│       ├── ExerciseQueryRepository.php
│       ├── TrainingMenuCommandRepository.php
│       ├── TrainingMenuQueryRepository.php
│       ├── TrainingRecordCommandRepository.php
│       └── TrainingRecordQueryRepository.php
└── QueryModels/              # 複雑なクエリ用（将来実装予定）
```

## 実装済みRepository

### 1. UserRepository

#### CommandRepository
- `create(array $data): User`
- `update(int $id, array $data): User`
- `delete(int $id): bool`

#### QueryRepository
- `findById(int $id): ?User`
- `findByEmail(string $email): ?User`

### 2. ExerciseRepository

#### CommandRepository
- `create(array $data): Exercise`
- `update(int $id, array $data): Exercise`
- `delete(int $id): bool`
- `attachMuscles(int $exerciseId, array $muscleData): void`
- `syncMuscles(int $exerciseId, array $muscleData): void`
- `attachMuscleGroupCategories(int $exerciseId, array $categoryIds): void`
- `syncMuscleGroupCategories(int $exerciseId, array $categoryIds): void`

#### QueryRepository
- `findById(int $id): ?Exercise`
- `findAll(): Collection`
- `findByMuscleGroupCategoryId(int $categoryId): Collection`
- `findByMuscleId(int $muscleId): Collection`
- `searchByName(string $name): Collection`

### 3. TrainingMenuRepository

#### CommandRepository
- `create(array $data): TrainingMenu`
- `update(int $id, array $data): TrainingMenu`
- `delete(int $id): bool`
- `attachExercises(int $menuId, array $exerciseData): void`
- `syncExercises(int $menuId, array $exerciseData): void`

#### QueryRepository
- `findById(int $id): ?TrainingMenu`
- `findByUserId(int $userId): Collection`
- `findByUserIdWithExercises(int $userId): Collection`

### 4. TrainingRecordRepository

#### CommandRepository
- `create(array $data): TrainingRecord`
- `update(int $id, array $data): TrainingRecord`
- `delete(int $id): bool`

#### QueryRepository
- `findById(int $id): ?TrainingRecord`
- `findByUserId(int $userId): Collection`
- `findByUserIdAndDate(int $userId, string $date): Collection`
- `findByUserIdWithDateRange(int $userId, string $startDate, string $endDate): Collection`

## Eager Loading戦略

### QueryRepositoryでの最適化

各QueryRepositoryでは、用途に応じて適切なEager Loadingを実装しています。

#### 例：TrainingRecordQueryRepository

```php
// 詳細表示用（全ての関連データが必要）
public function findById(int $id): ?TrainingRecord
{
    return TrainingRecord::with([
        'user', 
        'trainingMenu', 
        'exerciseLogs.exercise', 
        'exerciseLogs.weightUnit'
    ])->find($id);
}

// 一覧表示用（最小限のデータのみ）
public function findByUserId(int $userId): Collection
{
    return TrainingRecord::with(['trainingMenu', 'exerciseLogs.exercise'])
        ->where('user_id', $userId)
        ->orderBy('date', 'desc')
        ->get();
}
```

## PostgreSQL対応

検索機能では、PostgreSQLの`ILIKE`を使用して大文字小文字を区別しない検索を実装しています。

```php
public function searchByName(string $name): Collection
{
    return Exercise::where('name', 'ILIKE', "%{$name}%")
        ->with(['muscleGroupCategories'])
        ->orderBy('name')
        ->get();
}
```

## DI (Dependency Injection) 設定

`RepositoryServiceProvider`でインターフェースと実装をバインドしています。

```php
// User Repositories
$this->app->bind(
    \App\Repositories\Interfaces\UserCommandRepositoryInterface::class,
    \App\Repositories\Eloquent\UserCommandRepository::class
);
$this->app->bind(
    \App\Repositories\Interfaces\UserQueryRepositoryInterface::class,
    \App\Repositories\Eloquent\UserQueryRepository::class
);
```

## 使用例

### UseCase層での利用

```php
class CreateTrainingRecordUseCase
{
    public function __construct(
        private TrainingRecordCommandRepositoryInterface $commandRepository,
        private TrainingRecordQueryRepositoryInterface $queryRepository
    ) {}

    public function execute(CreateTrainingRecordCommand $command): TrainingRecord
    {
        // Command用Repositoryで作成
        $trainingRecord = $this->commandRepository->create($command->toArray());
        
        // Query用Repositoryで詳細取得
        return $this->queryRepository->findById($trainingRecord->id);
    }
}
```

## 今後の拡張予定

### QueryModel/ReadModel

複雑なJOINクエリが必要になった場合、専用のQueryModelを作成する予定です。

```php
// 将来的な実装例
class TrainingRecordWithDetailsQueryModel
{
    public function __construct(
        public int $id,
        public string $date,
        public string $menuName,
        public int $totalSets,
        public array $exercises
    ) {}
}
```

### パフォーマンス最適化

- クエリのパフォーマンス分析
- インデックスの最適化
- キャッシュ戦略の検討

## 注意事項

- 全てのRepositoryメソッドは例外安全
- 外部キー制約エラーは適切に処理
- トランザクション管理はUseCase層で実施