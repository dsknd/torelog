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
4. **training_menu_exercises** - メニューとエクササイズの中間テーブル
5. **training_records** - トレーニング記録
6. **exercise_logs** - 各エクササイズの実施記録（セット、重量、回数）

### リレーション
- ユーザーは複数のトレーニングメニューを持つ
- トレーニングメニューは複数のエクササイズを含む
- トレーニング記録は複数のエクササイズログを持つ
- エクササイズログは特定のエクササイズと紐づく

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
- ✅ 基本的なデータベース構造（マイグレーション）作成済み
- ✅ Eloquentモデル作成済み（ただし、リレーションなどは未実装）
- ⬜ APIエンドポイント未実装
- ⬜ ビジネスロジック未実装
- ⬜ テスト未実装

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