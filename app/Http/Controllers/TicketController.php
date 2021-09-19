<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\table01;
use App\Models\table03;
use App\Models\table04;
use App\Models\table05;
use App\Models\table06;

class TicketController extends Controller
{
    //
    public function index(Request $request){
        return view("index_ticket");
    }

    public function create(){
        return view("create_ticket");
    }
    public function store(Request $request){
        dump($request);

        return view("index_ticket");
    }
}
