<?php
namespace Ranta\CartApi\Core;

use Freshbitsweb\LaravelCartManager\Contracts\CartDriver;
use Freshbitsweb\LaravelCartManager\Core\Cart;
use Freshbitsweb\LaravelCartManager\Events\CartCreated;

class CartApi extends Cart{

    public function __construct(CartDriver $cartDriver)
    {
        parent::__construct($cartDriver);

    }

    public function setCartId($cartId)
    {
        app()->singleton('cart_id', function () use ($cartId) {
            return $cartId;
        });
    }

    protected function cartUpdates($isNewItem = false, $keepDiscount = false)
    {
        $this->updateTotals($keepDiscount);
        
        $this->storeCartData($isNewItem);

        return $this->toArray();
    }

    protected function storeCartData($isNewItem = false)
    {
        if ($this->id) {
            $this->cartDriver->updateCart($this->id, $this->data());

            if ($isNewItem) {
                $this->cartDriver->addCartItem($this->id, $this->items->last()->toArray());
            }

            return;
        }
         
        event(new CartCreated($this->toArray()));
        $cartId = $this->cartDriver->storeNewCartData($this->toArray());

        $this->id = $cartId;
    }

    public function toArray($withItems = true)
    {
        $cartData = [
            'id'        => $this->id,
            'subtotal'  => $this->subtotal,
            'discount'  => $this->discount,
            'couponId'  => $this->couponId,
            'netTotal'  => $this->netTotal,
            'tax'       => $this->tax,
            'total'     => $this->total,
            'roundOff'  => $this->roundOff,
            'payable'   => $this->payable,
            'discountPercentage'    => $this->discountPercentage,
            'shippingCharges'       => $this->shippingCharges,
        ];

        if ($withItems) {
            $cartData['items'] = $this->items();
        }

        return $cartData;
    }
}


?>