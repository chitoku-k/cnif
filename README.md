# cnif

## セットアップ

```sh
$ docker-compose build
$ docker-compose up -d

$ docker-compose exec client bash
root@container:/# npm install
root@container:/# exit

$ docker-compose exec api bash
root@container:/# composer install
root@container:/# composer install
root@container:/# mv Plugin/LampagerCakephp2 app/Plugin/Lampager
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

![Image](https://user-images.githubusercontent.com/6535425/37680556-98ecd62a-2cc7-11e8-9183-808975d88c78.gif)
