<?php

namespace App\Http\Controllers;

use App\Models\FeedbackLink;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        return view('AdminFeedback.AdminPageFeedback', ['feedbackLink' => FeedbackLink::all()->first()]);
    }

    public function edit()
    {
        return view('AdminFeedback.AdminPageEditFeedback', ['feedbackLink' => FeedbackLink::all()->first()]);
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'new_feedback_link' => 'required|url|active_url',
        ]);

        $feedbackLink = FeedbackLink::all()->first();
        $feedbackLink->link = $validatedData['new_feedback_link'];
        $feedbackLink->save();

        request()->session()->flash('success', 'Feedback link updated successfully!');
        return redirect()->route('feedback.index');
    }
}
