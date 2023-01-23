<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Supplier;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suppliers = DB::table('suppliers')            
            ->select('suppliers.*', 
            DB::raw('(SELECT IFNULL(COUNT(id), 0) FROM tickets WHERE supplierid=suppliers.id) as count'),
            DB::raw('(SELECT IFNULL(SUM(fractions * price), 0) FROM tickets WHERE supplierid=suppliers.id) as count_price'),
            DB::raw('(SELECT IFNULL(SUM(e.entires), 0) FROM entires e LEFT JOIN tickets t ON e.ticketid=t.id WHERE t.supplierid=suppliers.id) as entires'),
            DB::raw('(SELECT IFNULL(SUM(e.fractions / t.fractions), 0) FROM entires e LEFT JOIN tickets t ON e.ticketid=t.id WHERE t.supplierid=suppliers.id) as fractions'),

            DB::raw('(SELECT IFNULL(SUM(t.price * (e.entires + (e.fractions / t.fractions))), 0) FROM tickets t LEFT JOIN entires e ON t.id=e.ticketid WHERE t.supplierid=suppliers.id) as total_entires'),
            DB::raw('(SELECT IFNULL(SUM(t.price * e.fractions), 0) FROM tickets t LEFT JOIN entires e ON t.id=e.ticketid WHERE t.supplierid=suppliers.id) as total_fractions'),
            DB::raw('(SELECT IFNULL(SUM((e.entires * t.fractions) + e.fractions), 0) FROM tickets t LEFT JOIN entires e ON t.id=e.ticketid WHERE t.supplierid=suppliers.id) as all_fractions'))
            ->get();
        // $suppliers = Supplier::all();
        return view('suppliers.index')->with('suppliers', $suppliers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('suppliers.create');
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
            'name' => 'required|max:255|unique:suppliers',
            'utility' => 'required|numeric|between:1,100',
        ];
        $messages = [
            'name.required' => 'Agrega el nombre del proveedor...',
            'name.max' =>'El nombre del proveedor no puede ser mayor a :max caracteres...',
            'name.unique' =>'Ya existe un proveedor con ese nombre...',
            'utility.required' => 'Agrega la utilidad del proveedor...',
            'utility.numeric' => 'La utilidad debe ser un número...',
            'utility.between' => 'La utilidad debe estar entre :min y :max...'
        ];
        $this->validate($request, $rules, $messages);
        
        $supplier = Supplier::create([
            'name' => $request->name,
            'utility' => $request->utility,
            'active' => $request->has('active'),
            'description' => $request->description,
            'created_by' => Auth()->user()->username
        ]);  
        
        return redirect('suppliers')->with([
            'message' => 'Proveedor creado correctamente...',
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
        $supplier = Supplier::find($id);        
        $tickets = DB::table('tickets')
            ->leftJoin('suppliers', 'tickets.supplierid', '=', 'suppliers.id')
            ->leftJoin('lotteries', 'tickets.lotteryid', '=', 'lotteries.id')            
            ->select(
                'tickets.*', 
                'suppliers.name as supplier_name', 
                'suppliers.utility as supplier_utility', 
                'lotteries.name as lottery_name',
                'lotteries.date as lottery_date',
                DB::raw('(SELECT IFNULL(SUM(entires), 0) FROM entires WHERE ticketid=tickets.id) as total_entires'),
                DB::raw('(SELECT IFNULL(SUM(fractions / tickets.fractions), 0) FROM entires WHERE ticketid=tickets.id) as total_fractions')
                )
            ->where('suppliers.id', $id)->get();
        return view('suppliers.show', [
            'supplier' => $supplier,
            'tickets' => $tickets
    ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }
}