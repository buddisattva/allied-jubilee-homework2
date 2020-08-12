# ALLIED JUBILEE

## Homework 2

### Version
- Laravel 5.5

### Installation
```bash
# 建立你的環境設置
$ cp .env.example .env
$ vi .env

# 安裝相依套件，需要先安裝完成 composer，再執行 composer install
$ composer install

# migration
$ php artisan migrate --force
```

### Task Scheduling
> 新增一筆 cronjob
```bash
* * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1
```