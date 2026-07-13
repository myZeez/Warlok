<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class StaticPageController extends Controller
{
    public function about(): View
    {
        return view('pages.about');
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function profile(): View
    {
        return view('pages.profile');
    }
}
