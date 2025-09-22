# プロジェクト概要

- アプリ名 : coachtech フリマ
  ユーザー登録を行い、商品を出品・購入・コメント・いいねができます。

## 実装機能一覧

- 会員登録 / ログイン機能
- 商品出品
- 商品検索
- 商品詳細ページ（コメント・いいね機能）
- 商品購入（カード決済 / コンビニ決済）
- 配送先住所登録・変更
- プロフィール編集

## 補足（機能要件外の実装）

- マイページ・購入した商品の詳細ページに遷移することはできますが、再購入とコメント送信をできないようにしています。
- マイページ・出品した商品は、商品画像と商品名の表示のみにして詳細ページ等へアクセスできないようにしています。
- 購入済み商品の商品詳細ページでは購入するボタンの代わりに sold を表示し、コメントも不可にしています。

## 環境構築

**Docker ビルド**

1. `git clone git@github.com:pokimaru3/-Tokaji-Suzuka--mogi-test1.git`
2. DockerDesktop アプリを立ち上げる
   > cd が効かない場合は、cd ./-Tokaji-Suzuka--mogi-test1/を入力
3. `docker-compose up -d --build`

> _Mac の M1・M2 チップの PC の場合、`no matching manifest for linux/arm64/v8 in the manifest list entries`のメッセージが表示されビルドができないことがあります。
> エラーが発生する場合は、docker-compose.yml ファイルの「mysql」内に「platform」の項目を追加で記載してください_

```bash
mysql:
    platform: linux/x86_64(この文追加)
    image: mysql:8.0.26
    environment:
```

**Laravel 環境構築**

1. `docker-compose exec php bash`
2. `composer install`
3. 「.env.example」ファイルを 「.env」ファイルに命名を変更。または、新しく.env ファイルを作成
4. .env に以下の環境変数を追加

```text
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```

5. アプリケーションキーの作成

```bash
php artisan key:generate
```

6. マイグレーションの実行

```bash
php artisan migrate
```

7. シーディングの実行

```bash
php artisan db:seed
```

## 使用技術（実行環境）

- PHP8.4.6
- Laravel8.83.29
- MySQL9.3.0
- Docker / docker-compose

## ER 図

## URL

- 商品一覧画面：http://localhost/
- ログイン画面：http://localhost/login
- 会員登録画面：http://localhost/register
- プロフィール設定画面：http://localhost/setting
- 商品詳細画面：http://localhost/item/(item_id)
- 商品購入画面：http://localhost/purchase/(item_id)
- 配送先住所変更画面：http://localhost/purchase/address/(item_id)
- マイページ：http://localhost/mypage
- プロフィール編集画面：http://localhost/mypage/profile
- 商品出品画面：http://localhost/sell
- phpMyAdmin：http://localhost:8080/

## 一般ユーザーのログイン情報

1.  ユーザー名：テスト太郎
    メールアドレス：test@example.com
    パスワード：test12345
2.  ユーザー名：テスト花子
    メールアドレス：test2@example.com
    パスワード：test12345

## テストの実行方法

```bash
php artisan test
```
