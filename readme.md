# HOW TO RUN
```
docker-compose build
```
- create Laravel project in container
LINUX:
```
docker run --rm -v $(pwd)/src:/app composer create-project laravel/laravel /app
```
WINDOWS:
```
docker run --rm -v ${PWD}/src:/app composer create-project laravel/laravel /app
```


```
docker compose up -d
```

```
docker-compose exec app php artisan migrate
```

## IF ERROR WITH PERMISSION
```
sudo chmod -R 777 src/storage src/bootstrap/cache
```

```
php artisan migrate:fresh
```

```
docker compose restart
```

- generate key in container
```
docker-compose exec app php artisan key:generate
```

```
docker compose exec app composer install
```