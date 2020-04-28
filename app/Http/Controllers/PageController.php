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
        $user = null;
        if (Auth::check()){
            $user = Auth::user();
        }
        return view('pages.about', ['user' => $user]);
    }

}
