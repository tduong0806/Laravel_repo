<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SysadminController extends Controller
{
    public function index()
    {
        return view('sysadmin.index'); 
    }
}
