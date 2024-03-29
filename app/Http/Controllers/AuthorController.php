<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class AuthorController extends Controller
{
    public function list(): View
    {
        return view('layouts.authors.list')->with('authors', User::where('roles', 'LIKE', '%WRITER%')->orderBy('username', 'ASC')->paginate(50));
    }
}
