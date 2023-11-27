<?php

namespace App\Http\Controllers;

use App\Http\Resources\DeviceResource;
use App\Models\Device;
use App\Models\Track;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $track = $request->has('end') ? Track::whereBetween('datetime', [Carbon::parse($request->start), Carbon::parse($request->end)->addDay()])->get('imei') : Track::whereDate('datetime', Carbon::parse($request->start))->get('imei');
        return auth()->user()->role == 'admin' ? DeviceResource::collection(Device::whereIn('imei', $track)->where('category', $request->device)->with('user')->get()) : DeviceResource::collection(auth()->user()->devices()->whereIn('imei', $track)->where('category', $request->device)->with('user')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $this->authorize('create');
            $request->validate([
                'imei' => 'required|max:15|unique:devices,imei',
                'phone' => 'required|max:16',
                'afd' => 'required|max:5',
                'blok' => 'required|max:5',
                'category' => 'required|in:spreader,tracker'
            ]);
            $device = Device::create([
                'user_id' => $request->user,
                'imei' => $request->imei,
                'phone' => $request->phone,
                'afd' => $request->afd,
                'blok' => $request->blok,
                'category' => $request->category,
                'created_by' => auth()->id
            ]);
            return DeviceResource::make($device);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()]);
        }
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
    public function update(Request $request, Device $device)
    {
        try {
            $this->authorize('update', $device);
            $request->validate([
                'imei' => 'required|max:15|unique:devices,imei',
                'phone' => 'required|max:16',
                'afd' => 'required|max:5',
                'blok' => 'required|max:5',
                'category' => 'required|in:spreader,tracker'
            ]);
            $device->update([
                'user_id' => $request->user,
                'imei' => $request->imei,
                'phone' => $request->phone,
                'afd' => $request->afd,
                'blok' => $request->blok,
                'category' => $request->category,
                'updated_by' => auth()->id
            ]);
            return DeviceResource::make($device);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Device $device)
    {
        try {
            $this->authorize('delete', $device);
            $device->delete();
            return response()->json(['success' => 'Device deleted successfully']);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()]);
        }
    }

    public function spreaderCount(Request $request)
    {
        $track = Track::whereDate('datetime', Carbon::parse($request->date))->get('imei');
        return auth()->user()->role == 'admin' ? Device::whereIn('imei', $track)->where('category', 'spreader')->distinct('imei')->count() : auth()->user()->devices()->whereIn('imei', $track)->where('category', 'spreader')->distinct('imei')->count();
    }

    public function trackerCount(Request $request)
    {
        $track = Track::whereDate('datetime', Carbon::parse($request->date))->get('imei');
        return auth()->user()->role == 'admin' ? Device::whereIn('imei', $track)->where('category', 'tracker')->distinct('imei')->count() : auth()->user()->devices()->whereIn('imei', $track)->where('category', 'tracker')->distinct('imei')->count();
    }
}
