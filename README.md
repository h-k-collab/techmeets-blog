# techmeets-blog

## 概要
Docker上でLaravelを動かす開発環境です。Week 7以降、このリポジトリにブログアプリの機能を追加していきます。

## 使用技術
- PHP 8.x / Laravel
- MySQL 8.0
- Nginx
- phpMyAdmin
- Docker / Docker Compose

## セットアップ手順
```bash
git clone https://github.com/h-k-collab/techmeets-blog.git
cd techmeets-blog

# ポート番号の設定ファイルを作る(必要なら番号を変更)
cp .env.example .env

# コンテナを起動
docker compose up -d

# Laravelの準備
docker compose exec app composer install
cp src/.env.example src/.env
docker compose exec app php artisan key:generate
```

`src/.env` のDB設定を次のように書き換えます。

```
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=secret
```

マイグレーションを実行します。

```bash
docker compose exec app php artisan migrate
```

## アクセス先
- アプリケーション: http://localhost
- phpMyAdmin: http://localhost:8080

## 機能一覧
- Laravelのウェルカムページの表示
- phpMyAdminでのDB確認
- マイグレーションの実行
