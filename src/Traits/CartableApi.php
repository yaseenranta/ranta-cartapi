<?php

namespace Ranta\CartApi\Traits;

use Ranta\CartApi\Core\CartApi;

trait CartableApi
{
    /**
     * Adds an item to the cart.
     *
     * @param int Identifier
     * @param int quantity
     * @return
     */
    public static function addToCartApi($id, $quantity = 1)
    {
        $class = static::class;

        return app(CartApi::class)->add($class::findOrFail($id), $quantity);
    }
}
