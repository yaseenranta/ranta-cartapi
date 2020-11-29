<?php

use Ranta\CartApi\Core\CartApi;

if (! function_exists('cartApi')) {
    /**
     * Returns an instance of the Cart class.
     */
    function cartApi()
    {
        return app(CartApi::class);
    }
}