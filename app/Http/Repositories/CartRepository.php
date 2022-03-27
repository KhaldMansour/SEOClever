<?php

namespace App\Http\Repositories;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;


class CartRepository{

    public function getCart()
    {
        $user = auth('user')->user();

        $cart = Cart::with('cart_items')->where('user_id' , $user->id)->first();
        
        if ($cart)
        {
            return $cart;
        }

        $cart = new Cart();

        $cart = $user->cart()->save($cart);

        $cart->refresh();
        
        return $cart; 
    }

    public function addToCart($cart , $data , $service)
    {
        $cart = $this->getCart();

        foreach ($cart->cart_items as $cart_item)
        {
            if ($cart_item->service_id == $service->id)
            {
                return $this->updateCartItem($cart_item , $data , $service);
            }
        }

        $total = $service->rate * $data['quantity'];

        $rate = $service->rate;
        
        $data += ['total' => $total];

        $data += ['rate' => $rate];

        $cart_item = new CartItem($data);

        $cart_item->service()->associate($service);

        $cart->cart_items()->save($cart_item);

        $this->collectTotal($cart);
    }

    public function updateCartItem($cart_item , $data , $service)
    {
        $total = $service->rate * $data['quantity'];

        $data += ['total' => $total];

        $cart_item->update($data);

        $cart = $cart_item->cart;

        $this->collectTotal($cart);
    }

    public function collectTotal($cart)
    {
        $total = $cart->withSum('cart_items' , 'cart_items.total')->where('id', $cart->id)->first()->cart_items_sum_cart_itemstotal;

        $total = $total ? $total : 0;

        $cart->update(['total' => $total]);
    } 

    public function removeCartItem($cart_item_id)
    {
        $cart_item = CartItem::findOrFail($cart_item_id);

        $cart_item->delete();

        $cart = $this->getCart();

        $this->collectTotal($cart);
    }
}