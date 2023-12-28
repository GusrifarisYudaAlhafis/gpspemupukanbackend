<?php

namespace App\Http\Controllers;

use App\Http\Resources\UnitResource;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return UnitResource::make(auth()->user()->units()->first());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $this->authorize('create');
            $request->validate([
                'kode' => 'required|max:5',
                'nama' => 'required|max:48',
                'afd' => 'required|max:5',
                'blok' => 'required|max:5',
                'longitude' => 'required',
                'latitude' => 'required',
                'polygon' => 'required',
            ]);
            $unit = Unit::create([
                'user_id' => $request->user,
                'kode' => $request->kode,
                'nama' => $request->nama,
                'afd' => $request->afd,
                'blok' => $request->blok,
                'longitude' => $request->longitude,
                'latitude' => $request->latitude,
                'polygon' => $request->polygon,
                'created_by' => auth()->id,
            ]);

            return UnitResource::make($unit);
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
    public function update(Request $request, Unit $unit)
    {
        try {
            $this->authorize('update', $unit);
            $request->validate([
                'kode' => 'required|max:5',
                'nama' => 'required|max:48',
                'afd' => 'required|max:5',
                'blok' => 'required|max:5',
                'longitude' => 'required',
                'latitude' => 'required',
                'polygon' => 'required',
            ]);
            $unit->update([
                'user_id' => $request->user,
                'kode' => $request->kode,
                'nama' => $request->nama,
                'afd' => $request->afd,
                'blok' => $request->blok,
                'longitude' => $request->longitude,
                'latitude' => $request->latitude,
                'polygon' => $request->polygon,
                'updated_by' => auth()->id,
            ]);

            return UnitResource::make($unit);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {
        try {
            $this->authorize('delete', $unit);
            $unit->delete();

            return response()->json(['success' => 'Unit deleted successfully']);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()]);
        }
    }

    public function polygon(Request $request)
    {
        $data = auth()->user()->units()->pluck('polygon')->map(function ($item) {
            $polygonObject = json_decode($item);

            return collect($polygonObject->coordinates[0])->map(function ($i) {
                return (object) [
                    'lat' => $i[1] ?? null,
                    'lng' => $i[0] ?? null,
                ];
            })->toArray() ?? null;
        })->toArray();

        return response()->json($data);
    }
}
