<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function hadi()
    {
        $file= public_path(). "/uploads/manager/default/image/kamkar.pdf";

        $headers = array(
            'Content-Type: application/pdf',
        );

        return Response::download($file, 'factor.pdf', $headers);
    }
}
