<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class AboutController extends Controller
{
    /**
     * Display the public About page.
     */
    public function show(): View
    {
        $profile = User::query()->firstOrFail();

        return view('about', compact('profile'));
    }
}
