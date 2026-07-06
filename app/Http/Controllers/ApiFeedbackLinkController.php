<?php

namespace App\Http\Controllers;

use App\Models\FeedbackLink;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiFeedbackLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        if ($user) {
            $feedback_link = FeedbackLink::all()->first();
            return response()->json([
                'success' => true,
                'feedback_link' => $feedback_link
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Please login first'
            ], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        if($user){
            $validatedData = $request->validate([ //validasi input
                'feedback_link' => 'required | url | active_url'
            ]);
            $feedback_link = FeedbackLink::all()->first();
            $feedback_link->link = $validatedData['feedback_link'];
            $feedback_link->save();
            return response()->json([
                'success' => true,
                'new_feedback_link' => $feedback_link
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Please login first'
            ], 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(FeedbackLink $feedbackLink)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FeedbackLink $feedbackLink)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FeedbackLink $feedbackLink)
    {
        //
    }
}
