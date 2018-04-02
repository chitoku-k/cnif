# cnif

拙作の CakePHP プラグイン [lampager/lampager-cakephp2](https://github.com/lampager/lampager-cakephp2) の動作を示すサンプル アプリケーションです。

Docker Compose 上に PHP-FPM + MySQL で動作する CakePHP の API 側と、Vue.js で動作するクライアントサイドによって構成されています。

サンプルのため画面を下にスクロールすると最後の要素 ID をカーソルとして次のアイテムを取得していく機能のみが実装されています。

## セットアップ

```sh
$ docker-compose build
$ docker-compose up -d

$ docker-compose exec client bash
root@container:/# npm install
root@container:/# exit

$ docker-compose exec api bash
root@container:/# composer install
root@container:/# exit
```

## 実行

```sh
$ docker-compose up -d
```

## 確認

```
http://localhost:8080
```

## イメージ

![Image](https://user-images.githubusercontent.com/6535425/37680556-98ecd62a-2cc7-11e8-9183-808975d88c78.gif)
