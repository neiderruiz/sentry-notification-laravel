# sentry-notification-laravel

## Requirements

| requirements | README |
| ------ | ------ |
| php | ^7.4 / ^8.0 |
| install package | *"sentry/sentry-laravel"* |
| laravel | ^7 |

### install package

```sh
composer require neiderruiz/sentry-notification-laravel
```

## Steps

- [**one**] - install package
```sh
    composer require neiderruiz/sentry-notification-laravel
```
- [**two**] - register provider package
redirect to route **config/app.php**
inner providers insert
```sh  
    //sentry notification
    Neiderruiz\SentryNotificationLaravel\Providers\SentryNotificationLaravelServiceProvider::class,
```
- [**three**] - publish provider
```sh  
php artisan vendor:publish --provider="Neiderruiz\SentryNotificationLaravel\Providers\SentryNotificationLaravelServiceProvider"
```

- [**Four**] - create table, run command in terminal
```sh
php artisan queue:table  
```

- [**Six**] - run migration
```sh
php artisan migrate
```
- [**Seven**] - config enviroment variables
```sh
    #sentry
    SENTRY_LARAVEL_DSN=https://ba58cc597a7b4be1b0769052ef156fab@o1162423.ingest.sentry.io/6262441
    SENTRY_TRACES_SAMPLE_RATE=1.0
    SENTRY_SERVER_NAME=localhost
    SENTRY_TOKEN=b2bd1fb414f4445a8f39c69961f26f97efcf780db2f24463a20e893ffe09e44f
    SENTRY_URL_API=https://sentry.io/api/0/projects
    SENTRY_ORGANIZATION=flash-swunschk
    SENTRY_PROJECT=laravel-slack-dev-api
```




### send message slack
- [**one**] - config enviroment variables
```sh
    #slack
    SLACK_SEND_MESSAGE=true
    SLACK_WEBHOOK_ENDPOINT=https://hooks.slack.com/services/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
```

run command *php artisan queue:table*
variable de entorno *SENTRY_SLACK* bool

## License

MIT
