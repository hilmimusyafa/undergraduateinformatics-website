<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReservationSchedule;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ApiReservationScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schedules = ReservationSchedule::all();
        return response()->json([
            'status' => 'success',
            'data' => $schedules
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $day = Carbon::parse($value)->dayOfWeekIso;
                    if (!in_array($day, [1, 2, 4, 5])) {
                        $fail('The '.$attribute.' must be a Monday, Tuesday, Thursday, or Friday.');
                    }
                }
            ],
            'shift' => 'required|in:09:00,13:00,15:00,09:00:00,13:00:00,15:00:00',
            'requested_by' => 'required|string|max:255',
            'document_link' => 'nullable|string|url|max:255',
            'meeting_room' => 'nullable|string|max:255',
            'study_program' => 'nullable|string|max:255',
            'participants' => 'nullable|string|max:255',
            'agenda' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'prodi_signature_name' => 'nullable|string|max:255',
            'prodi_signature_position' => 'nullable|string|max:255',
            'related_party_signature_name' => 'nullable|string|max:255',
            'related_party_signature_position' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        
        $shift = $data['shift'];
        if (strlen($shift) === 5) {
            $data['shift'] = $shift . ':00';
        }

        $isConflict = ReservationSchedule::where('date', $data['date'])
            ->where('shift', $data['shift'])
            ->exists();

        if ($isConflict) {
            return response()->json([
                'status' => 'error',
                'message' => 'The schedule is already full.',
                'errors' => [
                    'date' => ['The schedule on this date and session is already full. Please select another date or session.']
                ]
            ], 422);
        }

        $schedule = ReservationSchedule::create($data);

        try {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.berita_acara', ['schedule' => $schedule]);
            
            $fileName = 'berita_acara_' . $schedule->id . '_' . time() . '.pdf';
            $directory = public_path('beritaacara');
            
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            $pdfPath = $directory . '/' . $fileName;
            $pdf->save($pdfPath);
            
            $schedule->document_link = url('beritaacara/' . $fileName);
            $schedule->save();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('PDF Generation failed: ' . $e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Reservation schedule created successfully',
            'data' => $schedule
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $schedule = ReservationSchedule::find($id);

        if (!$schedule) {
            return response()->json([
                'status' => 'error',
                'message' => 'Reservation schedule not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $schedule
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $schedule = ReservationSchedule::find($id);

        if (!$schedule) {
            return response()->json([
                'status' => 'error',
                'message' => 'Reservation schedule not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'date' => [
                'sometimes',
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $day = Carbon::parse($value)->dayOfWeekIso;
                    if (!in_array($day, [1, 2, 4, 5])) {
                        $fail('The '.$attribute.' must be a Monday, Tuesday, Thursday, or Friday.');
                    }
                }
            ],
            'shift' => 'sometimes|required|in:09:00,13:00,15:00,09:00:00,13:00:00,15:00:00',
            'requested_by' => 'sometimes|required|string|max:255',
            'document_link' => 'nullable|string|url|max:255',
            'meeting_room' => 'nullable|string|max:255',
            'study_program' => 'nullable|string|max:255',
            'participants' => 'nullable|string|max:255',
            'agenda' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'prodi_signature_name' => 'nullable|string|max:255',
            'prodi_signature_position' => 'nullable|string|max:255',
            'related_party_signature_name' => 'nullable|string|max:255',
            'related_party_signature_position' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        if (isset($data['shift'])) {
            $shift = $data['shift'];
            if (strlen($shift) === 5) {
                $data['shift'] = $shift . ':00';
            }
        }

        $checkDate = $data['date'] ?? $schedule->date;
        $checkShift = $data['shift'] ?? $schedule->shift;

        $isConflict = ReservationSchedule::where('date', $checkDate)
            ->where('shift', $checkShift)
            ->where('id', '!=', $id)
            ->exists();

        if ($isConflict) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jadwal sudah terisi',
                'errors' => [
                    'date' => ['Jadwal pada tanggal dan sesi ini sudah terisi oleh reservasi lain. Silakan pilih tanggal atau sesi lain.']
                ]
            ], 422);
        }

        $oldDocumentLink = $schedule->document_link;
        $schedule->update($data);

        try {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.berita_acara', ['schedule' => $schedule]);
            
            $fileName = 'berita_acara_' . $schedule->id . '_' . time() . '.pdf';
            $directory = public_path('beritaacara');
            
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            $pdfPath = $directory . '/' . $fileName;
            $pdf->save($pdfPath);
            
            if ($oldDocumentLink) {
                $urlPath = parse_url($oldDocumentLink, PHP_URL_PATH);
                if ($urlPath) {
                    $oldFileName = basename($urlPath);
                    $oldPath = public_path('beritaacara/' . $oldFileName);
                    if (file_exists($oldPath) && is_file($oldPath)) {
                        unlink($oldPath);
                    }
                }
            }
            
            $schedule->document_link = url('beritaacara/' . $fileName);
            $schedule->save();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('PDF Generation failed on update: ' . $e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Reservation schedule updated successfully',
            'data' => $schedule
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $schedule = ReservationSchedule::find($id);

        if (!$schedule) {
            return response()->json([
                'status' => 'error',
                'message' => 'Reservation schedule not found'
            ], 404);
        }

        if ($schedule->document_link) {
            $urlPath = parse_url($schedule->document_link, PHP_URL_PATH);
            if ($urlPath) {
                $oldFileName = basename($urlPath);
                $oldPath = public_path('beritaacara/' . $oldFileName);
                if (file_exists($oldPath) && is_file($oldPath)) {
                    unlink($oldPath);
                }
            }
        }

        $schedule->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Reservation schedule deleted successfully'
        ]);
    }
}
