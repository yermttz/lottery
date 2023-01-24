<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index() {
        if(!Auth::check()) {
            return redirect()->to('/login');
        }

        $tickets = [];

        $tickets["count"] = DB::table('tickets')
            ->select(                
            DB::raw('IFNULL(COUNT(tickets.id), 0) as tickets'),
            DB::raw('IFNULL(SUM(tickets.fractions), 0) as fractions'),
            DB::raw('IFNULL(SUM(tickets.entires), 0) as entires')
            )
            ->where('active', 1)
            ->first();

        $tickets["info"] = DB::table('tickets')      
            ->leftJoin('entires', 'tickets.id', '=', 'entires.ticketid')      
            ->select(
            DB::raw('IFNULL(SUM(tickets.fractions), 0) as fractions_count'),
            DB::raw('IFNULL(SUM(entires.entires + (entires.fractions / tickets.fractions)), 0) as all_entires'),
            DB::raw('IFNULL(SUM((entires.entires + (entires.fractions / tickets.fractions)) * tickets.fractions * tickets.price), 0) as all_entires_price')
            )
            ->first();

           $tickets = json_decode(json_encode($tickets), FALSE);

        // $lotteries = Lottery::all();
        return view('home.index')->with('tickets', $tickets);
    }
}