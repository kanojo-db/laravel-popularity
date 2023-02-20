# Laravel Popularity

[![MIT licensed](https://img.shields.io/badge/license-MIT-blue.svg)](license.md)

Laravel Popularity allows you to sort model entries by a fully customizable popularity metric, while being **fully GDPR-compliant** by avoiding the storage of personal identifying information such as IP addresses.

Clients are identified using anonymized hashes, in order to prevent abuse when counting visits. The popularity of model entries is computed through a scheduled command, and fully customizable by implementing PopularityContract.

## Usage

Use the `HasPopularity` trait on the model you intend to track, and implement `PopularityContract`:
``` php
use KanojoDb\LaravelPopularity\Traits\HasPopularity;
use KanojoDb\LaravelPopularity\PopularityContract;

class Post extends Model, PopularityContract
{
    use HasPopularity;
    ...

    /**
     * Returns the popularity score for the current entry.
     */
    public function getPopularity(): float
    {
        return $this->visitsDay();
    }

    ...
}
```

Schedule the `popularity:refresh` command in App\Console\Kernel. The interval of this schedules

```php
class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('popularity:refresh')->daily();
    }

    ...
}
```

Some utility functions are provided to work with visits in your popularity metrics:

```php
// Register a visit for the current model entry.
$post->visit();

// Retrieve the number of visits in a timeframe (The timeframe used should usually coincide with the schedule of the popularity:refresh command).
$post->visitsDay();
$post->visitsWeek();
$post->visitsMonth();
$post->visitsBetween($from, $to);
$post->visitsForever();
```

To retrieve the entries ordered by their popularity score, use the following:

```php
Posts::popular()->get();
```

## Install

### Via Composer

``` bash
$ composer require kanojo-db/laravel-popular
$ php artisan migrate
```

## Credits

Original Laravel Popular module by [Jordan Miguel](https://www.linkedin.com/in/joordanmiguel/).

## License

This project is licensed under the MIT License (MIT). Please see the [license file](LICENSE.md) for more information.
