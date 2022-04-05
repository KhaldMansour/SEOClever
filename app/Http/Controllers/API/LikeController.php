<?php

namespace App\Http\Controllers\API;

use App\Models\Like;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth('user')->user();

        return $user->likes;
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
    public function store($id)
    {
        $service = Service::findOrFail($id);

        $user = auth('user')->user();

        if($user->likes->contains($service->id))
        {
            return response()->json(['messages' => "This service is already in your likes"], 400);
        }

        $user->likes()->attach($service);

        return response()->json(['message' => "Service added to your likes"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function show(Like $like)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function edit(Like $like)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Like $like)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $service = Service::findOrFail($id);

        $user = auth('user')->user();

        if(!$user->likes->contains($service->id))
        {
            return response()->json(['messages' => "This service isn't in your likes"], 400);
        }

        $user->likes()->detach($service->id);

        return response()->json(['message' => "Service unliked"]);
    }
}
