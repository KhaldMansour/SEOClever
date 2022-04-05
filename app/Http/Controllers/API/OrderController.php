<?php

namespace App\Http\Controllers\API;

use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public $orderRepository;

    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth('user')->user();

        return $user->orders;
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
            'service_id' => 'required|numeric',
            'quantity' =>'required|numeric',
            'url' =>'required|url'
        ]);

        $service = Service::findOrFail($data['service_id']);

        $user = auth('user')->user();

        $amount = $data['quantity'] * $service['rate'];

        if ($user->balance < $amount)
        {
            return response()->json(['message' => "You don't have enough balance , Pleace recharge"], 400);
        }

        $data = array_merge($data ,[
            'total' => $amount,
            'user_id' => $user->id,
            'rate' => $service->rate
        ]);

        $order = Order::create($data);

        $this->addToLogs($order);

        $this->calculateBalance();

        return response()->json([
            'message' => "Order added successfully"
        ]);
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

    public function calculateBalance()
    {
        $user = auth()->user();

        $user_balance = $user->recharges->sum('amount') - $user->orders->sum('total');

        $user->balance = $user_balance;

        $user->save();
    }

    public function addToLogs($order)
    {
        $user = auth('user')->user();

        $log = $order->toArray();
        
        $log = array_merge($log ,[
            'amount' => $order->total,
        ]);

        $log = $order->log()->create($log);

        $user->logs()->save($log);
    }
}
