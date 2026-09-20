<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ReservationLink;
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
            'reservationLink' => ReservationLink::configured()->first(),
        ]);
    }
}