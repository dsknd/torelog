# Torelog プロジェクト概要

## プロジェクト基本情報
- **プロジェクト名**: Torelog API
- **目的**: トレーニング記録を管理するためのAPIアプリケーション
- **フレームワーク**: Laravel 12
- **PHP バージョン**: 8.2以上
- **データベース**: PostgreSQL 17
- **開発環境**: Laravel Sail (Docker)

## アーキテクチャと設計方針
- **アーキテクチャ**: クリーンアーキテクチャ
- **設計思想**: ドメイン駆動設計 (DDD)
- **パターン**: CQRS (Command Query Responsibility Segregation)
  - 書き込み処理と読み取り処理を分離
  - Joinを含む複雑な参照処理は専用のReadModelを実装
- **開発手法**: テスト駆動開発 (TDD)

## データベース構造

### 主要テーブル
1. **users** - ユーザー情報
2. **training_menus** - トレーニングメニュー（ユーザーが作成）
3. **exercises** - エクササイズマスタ
4. **muscle_group_categories** - 筋肉グループカテゴリ（胸、背中、脚など）
5. **muscles** - 筋肉マスタ（大胸筋、広背筋、大腿四頭筋など）
6. **exercise_target_muscles** - エクササイズと筋肉の関係（主働筋/補助筋）
7. **exercise_muscle_group_categories** - エクササイズとカテゴリの関係（高速検索用）
8. **weight_units** - 重量単位マスタ（kg、lb、など）
9. **training_menu_exercises** - メニューとエクササイズの中間テーブル
10. **training_records** - トレーニング記録
11. **exercise_logs** - 各エクササイズの実施記録（セット、重量、回数）

### リレーション
- ユーザーは複数のトレーニングメニューを持つ
- トレーニングメニューは複数のエクササイズを含む
- トレーニング記録は複数のエクササイズログを持つ
- エクササイズログは特定のエクササイズと紐づく
- エクササイズは複数の筋肉をターゲットとする（主働筋/補助筋の区別あり）
- エクササイズは複数の筋肉グループカテゴリに属する（高速検索用）
- エクササイズログは重量単位を参照する

## 開発環境セットアップ
```bash
# Docker Desktop を起動
# コンテナを起動
./vendor/bin/sail up -d

# データベースマイグレーション
./vendor/bin/sail artisan migrate
```

## 開発用コマンド
```bash
# 開発サーバー起動（Laravel + Vite + Queue + Logs）
composer run dev

# テスト実行
./vendor/bin/sail artisan test

# コードフォーマット
./vendor/bin/sail artisan pint

# ログ確認
./vendor/bin/sail artisan pail

# Tinker（対話型シェル）
./vendor/bin/sail artisan tinker

# キャッシュクリア
./vendor/bin/sail artisan cache:clear
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan route:clear
./vendor/bin/sail artisan view:clear

# マイグレーションロールバック
./vendor/bin/sail artisan migrate:rollback

# マイグレーションリフレッシュ（全テーブル削除して再作成）
./vendor/bin/sail artisan migrate:fresh

# シーダー実行
./vendor/bin/sail artisan db:seed
```

## 現在の実装状況
- ✅ データベース構造設計完了
- ✅ マイグレーションファイル作成済み
  - 重量単位マスター追加
  - 筋肉グループカテゴリ・筋肉マスター追加
  - エクササイズと筋肉の関係テーブル追加
- ✅ Eloquentモデル作成済み（ただし、リレーションなどは未実装）
- ⬜ APIエンドポイント未実装
- ⬜ ビジネスロジック未実装
- ⬜ テスト未実装

## ビジネスルール

### エクササイズ（マスターデータ）
- エクササイズテーブルには「ベンチプレス」「スクワット」などの種目名を登録
- 各エクササイズは複数の筋肉グループカテゴリに属することができる
- 詳細な筋肉ターゲット情報も管理（主働筋・補助筋の区別あり）

### トレーニング記録
- 1日に複数のトレーニング記録を作成可能
- トレーニング記録は必ずユーザーに紐付く
- トレーニングメニューを使用せずに記録することも可能（training_menu_idはnullable）

### エクササイズログ（実際のトレーニング記録）
- 各セットごとに1レコード作成（例：ベンチプレス3セットなら3レコード）
- セット番号（set_number）はエクササイズごとに1から始まる連番
- 重量の単位は必須（weight_unit_id）
- 重量は0以上の値（0は自重トレーニング等を想定）
- レップ数は1以上の整数

### 筋肉とエクササイズの関係
- 1つのエクササイズは複数の筋肉をターゲットにできる
- 主働筋（is_primary = true）と補助筋（is_primary = false）を区別
- 筋肉グループカテゴリ（胸、背中、脚など）で大まかな分類
- 個別の筋肉（大胸筋、広背筋など）で詳細な管理

### トレーニングメニュー
- ユーザーごとに複数のメニューを作成可能
- メニュー内のエクササイズには順序（order）がある
- 同じエクササイズを複数回含むことも可能

## 注意事項
- モデルファイルは作成されているが、まだ空の状態
- CQRSパターンに従って、コマンドとクエリを分離する必要がある
- TDDアプローチに従い、実装前にテストを書く必要がある
- DB_DESIGN.mdにER図があるので、データベース設計の参考に使用する
- 全てのArtisanコマンドは`./vendor/bin/sail artisan`を使用する

## 今後の実装予定
1. モデルのリレーション定義
2. Repository層の実装
3. UseCase層の実装
4. APIエンドポイントの実装
5. 認証・認可の実装
6. テストの実装