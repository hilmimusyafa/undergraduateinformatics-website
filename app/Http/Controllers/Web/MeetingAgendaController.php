<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ReservationSchedule;
use App\Support\PageMeta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MeetingAgendaController extends Controller
{
    public function show(): View
    {
        $page = PageMeta::page('reservation');

        return view('MeetingAgendaPage', [
            'title' => 'FORM AGENDA PERTEMUAN DENGAN PRODI',
            'description' => 'Form agenda pertemuan dengan Program Studi Sarjana Informatika Telkom University.',
            'page' => $page,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'organization' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'agenda' => ['required', 'string'],
        ]);

        ReservationSchedule::create([
            'date' => Carbon::today()->toDateString(),
            'shift' => '09:00:00',
            'requested_by' => $validated['name'],
            'study_program' => $validated['organization'],
            'meeting_room' => $validated['position'],
            'participants' => $validated['phone'],
            'agenda' => $validated['agenda'],
        ]);

        return redirect()->route('meeting.agenda.show')->with('success', 'Form agenda pertemuan berhasil dikirim. Data Anda akan tampil di halaman admin reservasi.');
    }
}