<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImpostazioniController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-impostazioni');
    }

    public function index()
    {
        return view('admin.impostazioni.index');
    }
}