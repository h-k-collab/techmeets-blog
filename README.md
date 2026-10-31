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

## ブログシステム(Laravel)

Laravel(MVC)で作った、ブログ・商品管理・イベント予約の Web アプリです。

### 機能

**ブログ(`/posts`)**
- 投稿一覧(10件ずつページネーション)
- 投稿の詳細表示・作成・編集・削除
- 入力チェック(バリデーション)
- Blade のレイアウト継承

**商品管理(`/products`)**
- 商品の一覧・詳細・登録・編集・削除(CRUD)

**イベント予約(`/events`)**
- イベント一覧・イベント詳細
- 予約の作成(名前・メール・人数・予約日時)
- 予約一覧(`/reservations`)・予約のキャンセル

### テーブル定義

**posts(投稿)**

| カラム | 型 | 説明 |
|---|---|---|
| id | bigint | 主キー |
| title | varchar(200) | タイトル |
| content | text | 本文 |
| category | varchar(50) | カテゴリ |
| created_at / updated_at | timestamp | 作成・更新日時 |

**products(商品)**

| カラム | 型 | 説明 |
|---|---|---|
| id | bigint | 主キー |
| name | varchar(100) | 商品名 |
| price | unsigned int | 価格 |
| description | text (null可) | 説明 |
| stock | unsigned int | 在庫(初期値0) |
| category | varchar(50) | カテゴリ |
| created_at / updated_at | timestamp | 作成・更新日時 |

**events(イベント)**

| カラム | 型 | 説明 |
|---|---|---|
| id | bigint | 主キー |
| title | varchar(100) | タイトル |
| description | text (null可) | 説明 |
| location | varchar(100) | 場所 |
| event_date | datetime | 開催日時 |
| created_at / updated_at | timestamp | 作成・更新日時 |

**reservations(予約)**

| カラム | 型 | 説明 |
|---|---|---|
| id | bigint | 主キー |
| event_id | bigint | events.id への外部キー(イベント削除時に予約も削除) |
| name | varchar(100) | 名前 |
| email | varchar(255) | メールアドレス |
| people | unsigned int | 人数 |
| reserved_at | datetime | 予約日時 |
| created_at / updated_at | timestamp | 作成・更新日時 |

### 画面

**投稿一覧**

![投稿一覧](docs/images/posts.png)

**商品一覧**

![商品一覧](docs/images/products.png)

**イベント一覧**

![イベント一覧](docs/images/events.png)

**予約一覧**

![予約一覧](docs/images/reservations.png)