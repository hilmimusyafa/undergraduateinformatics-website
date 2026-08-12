<?php

namespace App\Http\Controllers;

use App\Models\ReservationLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiReservationLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $links = ReservationLink::all();
        return response()->json([
            'status' => 'success',
            'data' => $links
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'link' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $link = ReservationLink::create($validator->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Reservation link created successfully',
            'data' => $link
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $link = ReservationLink::find($id);

        if (!$link) {
            return response()->json([
                'status' => 'error',
                'message' => 'Reservation link not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $link
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $link = ReservationLink::find($id);

        if (!$link) {
            return response()->json([
                'status' => 'error',
                'message' => 'Reservation link not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'link' => 'sometimes|required|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $link->update($validator->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Reservation link updated successfully',
            'data' => $link
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $link = ReservationLink::find($id);

        if (!$link) {
            return response()->json([
                'status' => 'error',
                'message' => 'Reservation link not found'
            ], 404);
        }

        $link->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Reservation link deleted successfully'
        ]);
    }
}

