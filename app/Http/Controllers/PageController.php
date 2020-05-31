<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function about()
    {
        return view('pages.about');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function notFound404()
    {
        return view('errors.404');
    }

}
