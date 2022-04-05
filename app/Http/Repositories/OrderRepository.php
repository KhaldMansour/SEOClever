<?php

namespace App\Http\Repositories;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderRepository{

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

    public function placeOrder($data)
    {
        $order = new Order($data);
 
        $user = auth('user')->user();
        
        $user->orders()->save($order);
        
        $cart = $this->getCart();
        
        foreach($cart->cart_items as $cart_item)
        {
            $cart_item = $cart_item->toArray();
            
            unset($cart_item['cart_id'] , $cart_item['id']);
            
            $order_item = new OrderItem($cart_item);
            
            $order->order_items()->save($order_item);
        }

        DB::table('cart_items')->where('cart_id', '=', $cart->id)->delete();

        $this->collectTotal($order);
    }

    public function collectTotal($order)
    {
        $total = $order->withSum('order_items' , 'order_items.total')->where('id', $order->id)->first()->order_items_sum_order_itemstotal;

        $total = $total ? $total : 0;

        $order->update(['total' => $total]);
    } 
}