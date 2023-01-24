<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Ticket;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        $tickets = DB::table('tickets')
            ->leftJoin('suppliers', 'tickets.supplierid', '=', 'suppliers.id')
            ->leftJoin('lotteries', 'tickets.lotteryid', '=', 'lotteries.id')            
            ->select(
                'tickets.*', 
                'suppliers.name as supplier_name', 
                'suppliers.utility as supplier_utility', 
                'lotteries.name as lottery_name',
                DB::raw('(SELECT IFNULL(SUM(entires), 0) FROM entires WHERE ticketid=tickets.id) as total_entires'),
                DB::raw('(SELECT IFNULL(SUM(fractions / tickets.fractions), 0) FROM entires WHERE ticketid=tickets.id) as total_fractions')
            )
            ->where('tickets.active', 1)
            ->get();
        return view('tickets.index')->with([
            'tickets' => $tickets
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $suppliers = DB::table('suppliers')->where('active', 1)->get();
        $lotteries = DB::table('lotteries')->where('active', 1)->get();
        $customers = DB::table('customers')->where('active', 1)->get();
        return view('tickets.create')->with([
            'suppliers' => $suppliers,
            'lotteries' => $lotteries,
            'customers' => $customers
        ]);
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
            'supplierid' => 'required',
            'lotteryid' => 'required',
            'price' => 'required|numeric|min:1',
            'entires' => 'required|numeric|min:1',
            'fractions' => 'required|numeric|min:1'
        ];
        $messages = [
            'supplierid.required' => 'Seleccione un proveedor...',
            'lotteryid.required' => 'Seleccione una lotería...',
            'price.required' => 'Agrega un precio de fracción...',
            'price.min' => 'Ingrese un precio válido...',
            'price.numeric' => 'El precio debe ser un número...',
            'entires.required' => 'Agrega el número de enteros...',
            'entires.min' => 'Ingrese un número de enteros válido...',
            'entires.numeric' => 'Los enteros debe ser un número...',
            'fractions.required' => 'Agrega el número de fracciones...',
            'fractions.min' => 'Ingrese un número de fracción válido...',
            'fractions.numeric' => 'Las fracciones debe ser un número...'
        ];
        $this->validate($request, $rules, $messages);
        
        $ticket = Ticket::create([
            'supplierid' => $request->supplierid,
            'lotteryid' => $request->lotteryid,
            'price' => $request->price,
            'entires' => $request->entires,
            'fractions' => $request->fractions,
            'serie' => $request->serie,
            'emission' => $request->emission,
            'active' => 1, //$request->has('active'),
            'description' => $request->description,
            'created_by' => Auth()->user()->username
        ]); 

        return redirect('tickets')->with([
            'message' => 'Cuota agregada correctamente...',
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ticket = Ticket::find($id);
        return view('tickets.delete')->with(['ticket' => $ticket]);
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
        DB::table('tickets')
        ->where('id', $id)
        ->update(['active' => 0]);

        return redirect('/tickets')->with([
            'message' => 'Cuota eliminada correctamente...',
            'type' => 'success'
        ]);
    }
}