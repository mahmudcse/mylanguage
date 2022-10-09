<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class LoadModalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function addModal(){
        $response = View::make('addModal')->render();
        echo json_encode($response);
    }
}
