<?php

namespace App\Http\Controllers;

use App\Http\Resources\TrackResource;
use App\Models\Device;
use App\Models\Track;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TrackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $device = auth()->user()->role == 'admin' ? Device::where('category', $request->device)->get('imei') : auth()->user()->devices()->where('category', $request->device)->get('imei');
        return TrackResource::collection($request->has('end') ? Track::whereIn('imei', $device)->whereBetween('datetime', [Carbon::parse($request->start), Carbon::parse($request->end)->addDay()])->get()->groupBy('imei') : Track::whereIn('imei', $device)->whereDate('datetime', Carbon::parse($request->start))->get()->groupBy('imei'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function imei(Request $request)
    {
        return TrackResource::collection($request->has('end') ? Track::where('imei', $request->imei)->whereBetween('datetime', [Carbon::parse($request->start), Carbon::parse($request->end)->addDay()])->get() : Track::where('imei', $request->imei)->whereDate('datetime', Carbon::parse($request->start))->get());
    }
}
