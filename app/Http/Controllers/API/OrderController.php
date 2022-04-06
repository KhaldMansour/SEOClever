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
    public function massOrder(Request $request)
    {
        $user = auth('user')->user();

        // $orders =  $request->orders;

        $orders = preg_split("/\r\n|\n|\r/", $request->orders);

        $total = 0;

        $order_items = [];

        for ($i = 0; $i < count($orders); $i++) 
        {
            $orders[$i] = explode('|', $orders[$i]);

            $service = Service::findOrFail($orders[$i][0]);

            $url = $orders[$i][1];

            $quantity = $orders[$i][2];

            $validation = "/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i";

            $valid_url =preg_match($validation, $url);

            $valid_quantity = is_numeric($quantity);

            if(!$service && $valid_url && $valid_quantity)
            {
                return response()->json(['message' => "You have an error in line" . $i+1], 400); 
            }

            $total += ($service->rate * $quantity);

            $order_item = [$service , $quantity , $url]; 

            array_push($order_items , $order_item);
        }

        foreach ($order_items as $order_item)
        {
            $this->createOrder($order_item);
        }

        $this->calculateBalance();

        return response()->json([
            'message' => "Orders added successfully"
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function createOrder($order_item)
    {
        $user = auth('user')->user();

        $amount = $order_item[0]['rate'] * $order_item[1];

        $data = [
            'service_id' => $order_item[0]['id'],
            'quantity' => $order_item[1],
            'total' => $amount,
            'user_id' => $user->id,
            'rate' => $order_item[0]['rate'],
            'url' => $order_item[2]
        ];

        $order = Order::create($data);

        $this->addToLogs($order);
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

        $user = $this->calculateLevel($user);

        $user->save();
    }

    public function calculateLevel($user)
    {
        $total = $user->orders->sum('total');

        if( $total >= 0 && $total <= 100 )
        {
            $user->level = 'new';
        }
        elseif( $total > 100 && $total <= 1000 )
        {
            $user->level = 'beginner';
        }
        elseif( $total > 1000 && $total <= 2500 )
        {
            $user->level = 'active';
        }
        elseif( $total > 2500 && $total <= 10000 )
        {
            $user->level = 'special';
        }
        elseif( $total > 10000 && $total <= 25000 )
        {
            $user->level = 'vip';
        }
        else{
            $user->level = 'royal';
        }

        return $user;
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
