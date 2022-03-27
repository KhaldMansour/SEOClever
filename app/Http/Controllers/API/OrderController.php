<?php

namespace App\Http\Controllers\API;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Repositories\CartRepository;
use App\Http\Repositories\OrderRepository;

class OrderController extends Controller
{
    public $cartRepository;

    public $orderRepository;

    public function __construct(CartRepository $cartRepository , OrderRepository $orderRepository)
    {
        $this->middleware('auth:user');

        $this->cartRepository = $cartRepository;

        $this->orderRepository = $orderRepository;
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
        $data = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'required|regex:/(01)[0-9]/',
            'city' => 'required|string',
            'address' => 'required|string',
            'country' => 'required|string',
        ]);

        $cart_items_count = $this->cartRepository->getCart()->withCount('cart_items')->first()->cart_items_count;

        if ($cart_items_count <= 0)
        {
            return response()->json([
                'message' => "Your cart is empty",
            ] , 400);
        }

        return $this->orderRepository->placeOrder($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
