<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\FortifyServiceProvider::class,
    App\Providers\JetstreamServiceProvider::class,
    // Only load Telescope in the local environment
    app()->environment('local') ? App\Providers\TelescopeServiceProvider::class : null,
];
