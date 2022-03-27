<?php

namespace App\Http\Controllers\API;

use App\Models\Cart;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Repositories\CartRepository;

class CartController extends Controller
{
    public $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->middleware('auth:user');

        $this->cartRepository = $cartRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $cart = $this->cartRepository->getCart();

        return $cart;
    }

    public function addToCart($service_id , Request $request)
    {        
        $service = Service::findOrFail($service_id);
        
        $data = $request->validate([
            'quantity' => 'required|numeric|gt:0',
            'url'   => 'required|url'
        ]);
            
        $data = request()->all();

        $cart = $this->cartRepository->getCart();

        return $this->cartRepository->addToCart($cart , $data , $service);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        //
    }

    public function removeCartItem($cart_item_id)
    {
        $cart = $this->cartRepository->getCart();

        $in_cart = false;

        foreach($cart->cart_items as $cart_item)
        {
            if ($cart_item->id == $cart_item_id)
            {
                $in_cart = true;
            }
        };


        if (!$in_cart)
        {
            return response()->json([
                'message' => "You don't have this item in your cart.",
            ] , 400);
        }

        $this->cartRepository->removeCartItem($cart_item_id);

        $cart->refresh();

        return response()->json([
            'message' => 'Item removed successfully.',
            'cart' => $cart
        ] , 200);
    }
}
