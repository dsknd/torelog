# UseCase Design

## 概要

TorelogプロジェクトでのUseCase層の設計方針とアプリケーションサービスの実装について記載します。

## 基本方針

### UseCase層の役割

- **ビジネスロジックの調整**: 複数のRepositoryやドメインオブジェクトを組み合わせて業務処理を実現
- **トランザクション管理**: データの整合性を保つためのトランザクション境界を定義
- **外部サービス連携**: 必要に応じて外部APIやサービスとの連携を管理
- **入力値検証**: ビジネスルール違反をチェック

### 設計原則

- **単一責任の原則**: 1つのUseCaseは1つの業務処理のみを担当
- **依存性の逆転**: インターフェースに依存し、具象クラスに依存しない
- **ドメイン知識の集約**: ビジネスルールをUseCaseに集約

## ディレクトリ構造

```
app/
├── UseCases/
│   ├── Commands/             # Command用DTOクラス
│   │   ├── CreateTrainingRecordCommand.php
│   │   ├── UpdateTrainingRecordCommand.php
│   │   ├── CreateExerciseCommand.php
│   │   └── ...
│   ├── TrainingRecord/       # トレーニング記録関連
│   │   ├── CreateTrainingRecordUseCase.php
│   │   ├── UpdateTrainingRecordUseCase.php
│   │   ├── DeleteTrainingRecordUseCase.php
│   │   └── GetTrainingRecordUseCase.php
│   ├── Exercise/             # エクササイズ関連
│   │   ├── CreateExerciseUseCase.php
│   │   ├── SearchExerciseUseCase.php
│   │   └── GetExerciseDetailsUseCase.php
│   ├── TrainingMenu/         # トレーニングメニュー関連
│   │   ├── CreateTrainingMenuUseCase.php
│   │   ├── UpdateTrainingMenuUseCase.php
│   │   └── GetTrainingMenuUseCase.php
│   └── User/                 # ユーザー関連
│       ├── CreateUserUseCase.php
│       └── GetUserProfileUseCase.php
```

## Command/Query パターン

### Commandオブジェクト

入力データを構造化し、バリデーションを行うためのDTOクラス。

```php
class CreateTrainingRecordCommand
{
    public function __construct(
        public readonly int $userId,
        public readonly TrainingDate $date,
        public readonly ?int $trainingMenuId,
        public readonly ?string $memo,
        public readonly array $exerciseLogs
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            userId: $data['user_id'],
            date: new TrainingDate($data['date']),
            trainingMenuId: $data['training_menu_id'] ?? null,
            memo: $data['memo'] ?? null,
            exerciseLogs: $data['exercise_logs'] ?? []
        );
    }
}
```

### UseCase実装例

```php
class CreateTrainingRecordUseCase
{
    public function __construct(
        private TrainingRecordCommandRepositoryInterface $commandRepository,
        private TrainingRecordQueryRepositoryInterface $queryRepository,
        private ExerciseQueryRepositoryInterface $exerciseRepository
    ) {}

    public function execute(CreateTrainingRecordCommand $command): TrainingRecord
    {
        DB::beginTransaction();
        
        try {
            // ビジネスルール検証
            $this->validateCommand($command);
            
            // トレーニング記録作成
            $trainingRecord = $this->commandRepository->create([
                'user_id' => $command->userId,
                'training_menu_id' => $command->trainingMenuId,
                'date' => $command->date,
                'memo' => $command->memo,
            ]);
            
            // エクササイズログ作成
            $this->createExerciseLogs($trainingRecord, $command->exerciseLogs);
            
            DB::commit();
            
            return $this->queryRepository->findById($trainingRecord->id);
            
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    private function validateCommand(CreateTrainingRecordCommand $command): void
    {
        // ビジネスルール検証
        if ($command->date->isFuture()) {
            throw new InvalidArgumentException('トレーニング日は未来の日付にできません');
        }
    }
}
```

## 実装予定UseCase

### 優先度：高

#### トレーニング記録管理
- **CreateTrainingRecordUseCase**: トレーニング記録とエクササイズログを一括作成
- **UpdateTrainingRecordUseCase**: 既存のトレーニング記録を更新
- **DeleteTrainingRecordUseCase**: トレーニング記録とエクササイズログを一括削除
- **GetTrainingRecordUseCase**: 詳細なトレーニング記録を取得
- **GetTrainingRecordListUseCase**: ユーザーのトレーニング記録一覧を取得

#### エクササイズ管理
- **SearchExerciseUseCase**: エクササイズを検索（名前、筋肉グループ別）
- **GetExerciseDetailsUseCase**: エクササイズの詳細情報を取得
- **CreateExerciseUseCase**: 新規エクササイズを作成

### 優先度：中

#### トレーニングメニュー管理
- **CreateTrainingMenuUseCase**: トレーニングメニューを作成
- **UpdateTrainingMenuUseCase**: トレーニングメニューを更新
- **GetTrainingMenuListUseCase**: ユーザーのトレーニングメニュー一覧を取得

#### ユーザー管理
- **CreateUserUseCase**: ユーザー登録
- **GetUserProfileUseCase**: ユーザープロフィール取得

### 優先度：低（将来実装）

#### 統計・分析
- **GetTrainingStatsUseCase**: トレーニング統計を取得
- **GetProgressAnalysisUseCase**: 進捗分析を取得

## エラーハンドリング

### ビジネス例外クラス

```php
namespace App\Exceptions;

class BusinessRuleViolationException extends Exception {}
class EntityNotFoundException extends Exception {}
class DuplicateEntityException extends Exception {}
```

### UseCase内でのエラーハンドリング

- **バリデーションエラー**: `InvalidArgumentException`または専用の例外クラス
- **エンティティ未発見**: `EntityNotFoundException`
- **ビジネスルール違反**: `BusinessRuleViolationException`

## トランザクション管理

### 基本方針

- **UseCase単位でトランザクション**: 1つのUseCaseが1つのトランザクション境界
- **例外時の自動ロールバック**: 例外発生時は自動的にロールバック
- **Repositoryはトランザクション非依存**: Repository層ではトランザクション管理しない

### 実装パターン

```php
public function execute(SomeCommand $command): Result
{
    DB::beginTransaction();
    
    try {
        // ビジネスロジック実行
        $result = $this->performBusinessLogic($command);
        
        DB::commit();
        return $result;
        
    } catch (Exception $e) {
        DB::rollback();
        throw $e;
    }
}
```

## Value Object活用

### 入力値のValue Object化

```php
class CreateTrainingRecordCommand
{
    public function __construct(
        public readonly int $userId,
        public readonly TrainingDate $date,        // Value Object
        public readonly ?int $trainingMenuId,
        public readonly ?string $memo,
        public readonly array $exerciseLogs        // ExerciseLogCommandの配列
    ) {}
}

class ExerciseLogCommand
{
    public function __construct(
        public readonly int $exerciseId,
        public readonly SetNumber $setNumber,      // Value Object
        public readonly Weight $weight,            // Value Object
        public readonly Reps $reps,               // Value Object
        public readonly ?string $memo
    ) {}
}
```

## テスト戦略

### Unit Test

- **各UseCase単体**: モックしたRepositoryを使用
- **Commandオブジェクト**: バリデーションロジックのテスト
- **Value Object**: ビジネスルールのテスト

### Integration Test

- **実際のDB**: 実データベースを使用した統合テスト
- **トランザクション**: ロールバック機能のテスト

## 今後の検討事項

### イベント駆動アーキテクチャ

将来的にドメインイベントを導入する可能性：

```php
class TrainingRecordCreatedEvent
{
    public function __construct(
        public readonly int $trainingRecordId,
        public readonly int $userId,
        public readonly TrainingDate $date
    ) {}
}
```

### CQRS拡張

読み取り専用のQueryUseCaseの導入：

```php
class GetTrainingRecordListQueryUseCase
{
    // 読み取り専用、複雑なビューロジック
}
```

### 非同期処理

重い処理の非同期化：

```php
class GenerateTrainingStatsUseCase
{
    // Queueを使用した非同期処理
}
```