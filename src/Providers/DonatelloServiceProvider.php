<?php

namespace Azuriom\Plugin\Donatello\Providers;

use Azuriom\Extensions\Plugin\BasePluginServiceProvider;
use Azuriom\Plugin\Donatello\DonatelloMethod;

class DonatelloServiceProvider extends BasePluginServiceProvider
{
    /**
     * Register any plugin services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any plugin services.
     *
     * @return void
     */
    public function boot()
    {
        if (! plugins()->isEnabled('shop')) {
            logger()->warning('Donatello нужен плагин Shop для работы !');

            return;
        }

        $this->loadViews();

        $this->loadTranslations();

        payment_manager()->registerPaymentMethod('donatello', DonatelloMethod::class);
    }
}
