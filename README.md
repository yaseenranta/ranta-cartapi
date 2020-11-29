## Ranta CartApi extended version of freshbitsweb/laravel-cart-manager

## Installation

1) Install the package by running this command in your terminal/cmd:
```
composer require ranta/cart-api
```

2) Add a trait to the model(s) of cart items:

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Ranta\CartApi\Traits\CartableApi;

class Product extends Model
{
    use CartableApi;
    // ...
}
```

### Add to cart
```
/**
 * Add to cart
 *
 * @return json
 */
 public function addToCart()
{
    cartApi()->setCartId(request('cartId'));
    return Product::addToCartApi(request('productId'));
}
```

### Remove from cart
```
/**
 * Remove from cart
 *
 * @return json
 */
public function removeFromCart()
{
    cartApi()->setCartId(request('cartId'));
    return cartApi()->removeAt(request('cartItemIndex'));
}
```

### Increment/decrement quantity of a cart item
```
/**
 * Increment cart item quantity
 *
 * @return json
 */
public function incrementCartItem()
{
    cartApi()->setCartId(request('cartId'));
    return cartApi()->incrementQuantityAt(request('cartItemIndex'));
}

/**
 * Decrement cart item quantity
 *
 * @return json
 */
public function decrementCartItem()
{
    cartApi()->setCartId(request('cartId'));
    return cartApi()->decrementQuantityAt(request('cartItemIndex'));
}
```

### Clear cart
```
/**
 * Clear Cart
 *
 * @return json
 */
public function clearCart()
{
    cartApi()->setCartId(request('cartId'));
    return cartApi()->clear();
}
```

### Get complete cart details
```
cartApi()->setCartId(request('cartId'));
$cart = cartApi()->toArray();
```

