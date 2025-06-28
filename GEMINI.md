# Torelog API

これは、トレーニング記録を管理するためのAPIアプリケーションです。

## 開発環境

- **フレームワーク:** Laravel 12
- **データベース:** PostgreSQL
- **開発環境:** Laravel Sail (Docker)

## 開発環境の構築手順

1. **Docker Desktopを起動します。**
2. **コンテナを起動します。**
   ```bash
   ./vendor/bin/sail up -d
   ```
3. **データベースのマイグレーションを実行します。**
   ```bash
   ./vendor/bin/sail artisan migrate
   ```

## 設計方針

このプロジェクトでは、以下の設計方針を採用します。

- **アーキテクチャ:** クリーンアーキテクチャ
- **設計思想:** ドメイン駆動設計 (DDD)
- **パターン:** CQRS (Command Query Responsibility Segregation)
  - 書き込み処理と読み取り処理を分離します。
  - Joinを含むような複雑な参照処理は、専用のReadModelを返すリポジリを実装します。
- **開発手法:** テスト駆動開発 (TDD)
  - 機能実装の前に必ずテストコードを記述します。

## APIの利用

（今後、APIのエンドポイントなどをここに記述していきます）