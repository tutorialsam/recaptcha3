<?php

namespace Tutorials\Recaptcha3;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class RecaptchaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // define custom config file
        $this->mergeConfigFrom(__DIR__.'/../config/recaptcha.php', 'recaptcha');

        // define the route middleware for recaptcha
        $this->app['router']->aliasMiddleware('recaptcha', Http\Middleware\VerifyRecaptchaToken::class);

        // define the blade directive
        Blade::directive('recaptcha', static function () {
            return <<<'HTML'
                <?php if (config('recaptcha.enabled')) : ?>
                    <script src="https://www.google.com/recaptcha/api.js?render={{ config('recaptcha.key') }}"></script>
                    <script>window.recaptchaEnabled = true;</script>
                <?php endif; ?>

                <script>
                    window.recaptcha = function (action) {
                        if (window.recaptchaEnabled) {
                            return new Promise((resolve) => {
                                grecaptcha.ready(function () {
                                    grecaptcha.execute('{{ config('recaptcha.key') }}', { action: action }).then(function (token) {
                                        resolve(token)
                                    })
                                })
                            })
                        }

                        return Promise.resolve('')
                    }
                </script>
            HTML;
        });
    }
}
