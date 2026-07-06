<?php

namespace App\Http\Controllers;

use App\Models\FeedbackLink;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Return admin feedback link page
        return view('AdminFeedback.AdminPageFeedback', ['feedbackLink' => FeedbackLink::all()->first()]);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        // Return feedback link for users
        return view('FeedbackPage', ['feedbackLink' => FeedbackLink::all()->first()]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        // Return form for changing feedback link
        return view('AdminFeedback.AdminPageEditFeedback', ['feedbackLink' => FeedbackLink::all()->first()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // Check if input URL is valid
        $validatedData = $request->validate([
            'new_feedback_link' => 'required | url | active_url',
        ]);

        // Update feedback URL
        $feedbackLink = FeedbackLink::all()->first();
        $feedbackLink->link = $validatedData['new_feedback_link'];
        $feedbackLink->save();

        // Redirect to admin feedback link page
        request()->session()->flash('success', 'Feedback link updated successfully!');
        return redirect()->route('feedback.index');
    }
}
