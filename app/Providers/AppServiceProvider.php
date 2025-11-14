<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Carbon;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Blade::directive('prettyDate', function ($expression) {
            return "<?php echo \\App\\Providers\\AppServiceProvider::formatPrettyDate($expression); ?>";
        });
    }

    public static function formatPrettyDate($date, $withTime = false)
    {
        if (empty($date)) {
            return 'N/A';
        }

        try {
            $dt = $date instanceof \Illuminate\Support\Carbon ? $date : Carbon::parse($date);
            if ($withTime) {
                return $dt->format('F j, Y H:i');
            }
            return $dt->format('F j, Y');
        } catch (\Throwable $e) {
            return (string) $date;
        }
    }
}