<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Lottery;

class LotteryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lotteries = DB::table('lotteries')            
            ->select('lotteries.*', 
            DB::raw('(SELECT IFNULL(COUNT(id), 0) FROM tickets WHERE lotteryid=lotteries.id) as count'),
            DB::raw('(SELECT IFNULL(SUM(entires), 0) FROM entires WHERE ticketid=lotteries.id) as entires'),
            DB::raw('(SELECT IFNULL(SUM(e.fractions / t.fractions), 0) FROM entires e LEFT JOIN tickets t ON t.id=e.ticketid WHERE e.ticketid=lotteries.id) as fractions')
            )
            ->where('active', 1)
            ->get();
        // $lotteries = Lottery::all();
        return view('lotteries.index')->with('lotteries', $lotteries);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('lotteries.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'date' => 'required|date',
        ];
        $messages = [
            'name.required' => 'Agrega el nombre de la lotería...',
            'name.max' =>'El nombre de la lotería no puede ser mayor a :max caracteres...',
            'date.required' => 'Agrega la fecha de la lotería...',
            'date.date' => 'Ingrasa una fecha válida...'
        ];
        $this->validate($request, $rules, $messages);
        $supplier = Lottery::create([
            'name' => $request->name,
            'date' => $request->date,
            'active' => 1, //$request->has('active'),
            'created_by' => Auth()->user()->username
        ]);
        
        return redirect('/lotteries')->with([
            'message' => 'Lotería creada correctamente...',
            'type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $lottery = Lottery::find($id);
        return view('lotteries.delete')->with(['lottery' => $lottery]);        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('lotteries')
        ->where('id', $id)
        ->update(['active' => 0]);

        return redirect('/lotteries')->with([
            'message' => 'Lotería eliminada correctamente...',
            'type' => 'success'
        ]);
    }
}