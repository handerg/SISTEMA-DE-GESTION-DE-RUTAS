<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth; 

trait Authorizable
{
    protected function authorizeAdmin()
    {
        // Cambia auth()->user() por Auth::user()
        if (! Auth::user()?->isAdmin()) { 
            abort(403);
        }
    }
}