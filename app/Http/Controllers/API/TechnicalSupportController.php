<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\TechnicalSupport;
use App\Http\Controllers\Controller;


class TechnicalSupportController extends Controller
{
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
            'topic' => 'required|string',
            'title' => 'required|string',
            'message' => 'required|string',
            'order_id' => 'required',   
        ]);

        $technical_support = new TechnicalSupport($data);

        $user = auth()->user();

        $user->technical_supports()->save($technical_support);

        return $technical_support;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TechnicalSupport  $technicalSupport
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $user = auth('user')->user();

        return $user->technical_supports;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TechnicalSupport  $technicalSupport
     * @return \Illuminate\Http\Response
     */
    public function edit(TechnicalSupport $technicalSupport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TechnicalSupport  $technicalSupport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TechnicalSupport $technicalSupport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TechnicalSupport  $technicalSupport
     * @return \Illuminate\Http\Response
     */
    public function destroy(TechnicalSupport $technicalSupport)
    {
        //
    }
}
