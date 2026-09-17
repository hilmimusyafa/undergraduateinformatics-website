<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\MsForms\MsFormsException;
use App\Services\Reservation\ReservationFormService;
use App\Services\Reservation\ReservationFormUnavailableException;
use App\Services\Reservation\ReservationMetadata;
use App\Support\PageMeta;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function show(Request $request): View
    {
        $page = PageMeta::page('reservation');

        return view('ReservationPage', [
            'title' => $page['title'],
            'description' => $page['description'],
        ]);
    }
}