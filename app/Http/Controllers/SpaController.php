<?php

namespace App\Http\Controllers;


class SpaController extends Controller
{
    /**
     * Get the SPA view.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $user = auth()->user();

        return view('spa');
    }
}
