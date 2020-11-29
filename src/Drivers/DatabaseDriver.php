<?php
namespace Ranta\CartApi\Drivers;

use Freshbitsweb\LaravelCartManager\Drivers\DatabaseDriver as DbCart;
use Freshbitsweb\LaravelCartManager\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DatabaseDriver extends DbCart
{
    /**
     * Returns current cart data.
     *
     * @return array
     */
    public function getCartData()
    {
        $selectColumns = ['id', 'subtotal', 'discount', 'discount_percentage', 'coupon_id', 'shipping_charges', 'net_total', 'tax', 'total', 'round_off', 'payable'];

        $cartData = Cart::with($this->cartItemsQuery())
            ->where($this->cartIdentifier())
            ->first($selectColumns);

        if (! $cartData) {
            return [];
        }

        return $cartData->toArray();
    }

    public function storeNewCartData($cartData)
    {
        $cartItems = $cartData['items'];
        unset($cartData['items']);
        $cartId = $this->storeCartDetails($cartData);

        foreach ($cartItems as $cartItem) {
            $this->addCartItem($cartId, $cartItem);
        }
        return $cartId;
    }

    /**
     * Updates the cart record with the new data.
     *
     * @param int Id of the cart
     * @param array Cart data
     * @return void
     */
    public function updateCart($cartId, $cartData)
    {
        $cartData = $this->arraySnakeCase($cartData);

        Cart::where('id', $cartId)->update($cartData);
    }


    /**
     * Stores the cart data in the database table and returns the id of the record.
     *
     * @param array Cart data
     * @return int
     */
    protected function storeCartDetails($cartData)
    {
        $cartData = $this->arraySnakeCase($cartData);

        $cart = Cart::updateOrCreate(
            $this->cartIdentifier(),
            array_merge($cartData, $this->getCookieElement())
        );

        return $cart->id;
    }

    /**
     * Returns the cart identifier.
     *
     * @return array
     */
    protected function cartIdentifier()
    {
        // If Cart ID is set manually, use it
        if (app()->offsetExists('cart_id')) {
            return ['id' => resolve('cart_id')];
        }

        // If customer is logged in, use his identifier
        if (Auth::guard(config('cart_manager.auth_guard'))->check()) {
            return ['auth_user' => Auth::guard(config('cart_manager.auth_guard'))->id()];
        }

        return $this->getCookieElement();
    }

    /**
     * Converts the keys of an array into snake case.
     *
     * @param array
     * @return array
     */
    private function arraySnakeCase($array)
    {
        $newArray = [];

        foreach ($array as $key => $value) {
            $newArray[Str::snake($key)] = $value;
        }

        return $newArray;
    }

}
