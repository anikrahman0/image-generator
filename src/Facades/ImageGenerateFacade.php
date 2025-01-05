<?php

namespace Noobtrader\Imagegenerator\Facades;

use Illuminate\Support\Facades\Facade;

class ImageGenerateFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'image-generate'; // Matches the service container binding key.
    }
}
