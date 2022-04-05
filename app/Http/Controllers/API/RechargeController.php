<?php

namespace App\Http\Controllers\API;

use App\Models\Recharge;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class RechargeController extends Controller
{


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

        return $user->recharges;
    }

    public function store(Request $request)
    {
        $user = auth('user')->user();
        
        $data = $request->validate([
            'method' => 'required|string',
            'amount' =>'required|numeric'
        ]);

        $recharge = new Recharge($data);

        $user->recharges()->save($recharge);

        $this->addToLogs($recharge);

        $this->calculateBalance($recharge);

        return response()->json(['message' => "Balance Recharged"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Recharge  $recharge
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $recharge = Recharge::findOrFail($id);

        if ($recharge->user_id != auth('user')->user()->id)
        {
            return response()->json([
                'message' => "Unauthorized"
            ] , 400);
        }

        return $recharge;
    }

    public function addToLogs($recharge)
    {
        $user = auth('user')->user();

        $log = $recharge->toArray();

        unset($log['method']);

        $log = $recharge->log()->create($log);

        $user->logs()->save($log);
    }

    public function calculateBalance($recharge)
    {
        $user = auth('user')->user();

        $user_balance = $user->balance + $recharge->amount;

        $user->balance = $user_balance;

        $user->save();
    }
}
