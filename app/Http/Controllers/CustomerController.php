<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Entire;
use App\Models\Balance;

class CustomerController extends Controller
{

    function apply_balance($customerid) {        
        $customer = Customer::find($customerid);
        $entires = DB::table('entires')
        ->leftJoin('tickets', 'tickets.id', '=', 'entires.ticketid')
        ->select(
                DB::raw('IFNULL(SUM(((entires.entires * tickets.fractions) + entires.fractions) * tickets.price), 0) as amount')
                )
        ->where('entires.customerid', $customerid)->first();        
        $balances = DB::table('balances')          
            ->select(
                DB::raw('IFNULL(SUM((CASE type WHEN 1 THEN amount ELSE 0 END)), 0) as pay, IFNULL(SUM((CASE type WHEN 2 THEN amount ELSE 0 END)), 0) as receipt'
            ))
            ->where('appliedtype', 'customers')
            ->where('appliedid', $customerid)->first();
        $amount = ($entires->amount - ($entires->amount * $customer->utility / 100)) + $balances->pay - $balances->receipt;
        
        DB::table('customers')
        ->where('id', $customerid)
        ->update(['balance' => $amount]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = DB::table('customers')            
            ->select('customers.*', 
            DB::raw('(SELECT IFNULL(SUM(e.entires + (e.fractions / t.fractions)), 0) FROM tickets t LEFT JOIN entires e ON t.id=e.ticketid WHERE e.customerid=customers.id) as entires'),
            DB::raw('(SELECT IFNULL(SUM(e.entires * t.fractions), 0) FROM tickets t LEFT JOIN entires e ON t.id=e.ticketid WHERE e.customerid=customers.id) as entires_fractions'),
            DB::raw('(SELECT IFNULL(SUM(fractions), 0) FROM entires WHERE customerid=customers.id) as fractions'),
            DB::raw('(SELECT IFNULL(SUM(t.price * e.entires * t.fractions), 0) FROM tickets t LEFT JOIN entires e ON t.id=e.ticketid WHERE e.customerid=customers.id) as total_entires'),
            DB::raw('(SELECT IFNULL(SUM(t.price * e.fractions), 0) FROM tickets t LEFT JOIN entires e ON t.id=e.ticketid WHERE e.customerid=customers.id) as total_fractions'),
            DB::raw('(SELECT IFNULL(SUM((e.entires * t.fractions) + e.fractions), 0) FROM tickets t LEFT JOIN entires e ON t.id=e.ticketid WHERE e.customerid=customers.id) as all_fractions'))
            ->where('customers.active', 1)
            ->get();
        // $customers = Customer::all();
        return view('customers.index')->with(['customers' => $customers]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customers.create');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function give(Request $request)
    {
        $customers = DB::table('customers')->where('active', 1)->get();
        $tickets = DB::table('tickets')
            ->leftJoin('suppliers', 'tickets.supplierid', '=', 'suppliers.id')
            ->leftJoin('lotteries', 'tickets.lotteryid', '=', 'lotteries.id')            
            ->select(
                'tickets.*', 
                'suppliers.name as supplier_name', 
                'suppliers.utility as supplier_utility', 
                'lotteries.name as lottery_name',
                'lotteries.date as lottery_date',
                DB::raw('(SELECT IFNULL(SUM(entires), 0) FROM entires WHERE ticketid=tickets.id) as entires_count'
            ))
            ->where('tickets.active', 1)
            ->get();
        return view('customers.give')->with([
            'customers' => $customers,
            'tickets' => $tickets,
            'c' => $request->get('c')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function give_store(Request $request)
    {
        $rules = [
            'ticketid' => 'required',
            'customerid' => 'required'
        ];
        $messages = [
            'ticketid.required' => 'Seleccione un ticket válido...',
            'customerid.required' => 'Seleccione un cliente...'
        ];
        $this->validate($request, $rules, $messages);
        
        $ticket_entires = DB::table('tickets')
        ->select('entires')
        ->where('id', $request->ticketid)
        ->first();
        $ticket_entires = $ticket_entires->entires;

        if(empty($request->entires)) {
            $request->entires = 0;
        } else if(!is_numeric($request->entires) || $request->entires < 0 || $request->entires > $ticket_entires) {
            return redirect('/customers/give')->withErrors([
                'entires' => 'Los enteros deben ser un número entre 0 a '.$ticket_entires.'...'
            ])->withInput();
        }
        $request->entires = floor($request->entires);
        
        if(!empty($request->fractions) && (!is_numeric($request->fractions) || $request->fractions < 0)) {
            return redirect('/customers/give')->withErrors([
                'fractions' => 'La cantidad de fracciones debe ser un número mayor a 0...'
            ])->withInput();
        }
        $request->fractions = floor($request->fractions);

        if($request->entires + $request->fractions == 0) {
            return redirect('/customers/give')->withErrors([
                'entires' => 'Ingrese una cantidad de enteros o fracciones válidas...'
            ])->withInput();
        }

        $ticket = DB::table('tickets')
            ->select('fractions')
            ->where('id', $request->ticketid)
            ->first();
        
        if($request->fractions > $ticket->fractions) {
            return redirect('/customers/give')->withErrors([
                'fractions' => 'La cantidad de fracciones no puede ser mayor a '.$ticket->fractions.' ...'
            ])->withInput();
        }  

        $entires = DB::table('tickets')
            ->leftJoin('entires', 'tickets.id', '=', 'entires.ticketid')
            ->select(DB::raw('IFNULL(SUM(entires.entires), 0) as entires, IFNULL(SUM(entires.fractions), 0) as fractions,  IFNULL(MAX(tickets.fractions), 0) as num_fractions'))
            ->where('tickets.id', $request->ticketid)
            ->first();

        $total_entregados = $entires->entires + ($entires->fractions / $entires->num_fractions);
        $total_nuevo = $request->entires + ($request->fractions / $entires->num_fractions);

        if($total_entregados + $total_nuevo > $ticket_entires) {
            if($request->entires > ($ticket_entires - $total_entregados)) {
                return redirect('/customers/give')->withErrors([
                    'entires' => 'Cantidad de enteros disponibles: '.
                    ($ticket_entires - $total_entregados).' ...'
                ])->withInput();
            } else {
                return redirect('/customers/give')->withErrors([
                    'fractions' => 'Cantidad de fracciones disponibles: '.((($ticket_entires - $total_entregados) * 5) - ($request->entires * 5)).' ...'
                ])->withInput();
            }
        }
        
        $ticket = Entire::create([
            'ticketid' => $request->ticketid,
            'customerid' => $request->customerid,
            'entires' => $request->entires,
            'fractions' => $request->fractions,
            'action' => 'add',
            'active' => 1, //$request->has('active'),
            'description' => $request->description,
            'created_by' => Auth()->user()->username
        ]);
        
        $this->apply_balance($request->customerid);

        return redirect('customers/'.$request->customerid)->with([
            'message' => 'Cuota entregada correctamente...',
            'type' => 'success'
        ]);
    }

    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function return(Request $request)
    {
        $customers = DB::table('customers')->where('active', 1)->get();
        $tickets = DB::table('tickets')
            ->leftJoin('suppliers', 'tickets.supplierid', '=', 'suppliers.id')
            ->leftJoin('lotteries', 'tickets.lotteryid', '=', 'lotteries.id')            
            ->select(
                'tickets.*', 
                'suppliers.name as supplier_name', 
                'suppliers.utility as supplier_utility', 
                'lotteries.name as lottery_name',
                'lotteries.date as lottery_date',
                DB::raw('(SELECT IFNULL(SUM(entires), 0) FROM entires WHERE ticketid=tickets.id) as entires_count'
            ))
            ->where('tickets.active', 1)
            ->get();
        return view('customers.return')->with([
            'customers' => $customers,
            'tickets' => $tickets,
            'c' => $request->get('c')
        ]);
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function return_store(Request $request)
    {
        $rules = [
            'ticketid' => 'required',
            'customerid' => 'required|'
        ];
        $messages = [
            'ticketid.required' => 'Seleccione un ticket válido...',
            'customerid.required' => 'Seleccione un cliente...'
        ];
        $this->validate($request, $rules, $messages);
                   
         $ticket_entires = DB::table('tickets')
        ->select('entires')
        ->where('id', $request->ticketid)
        ->first();
        $ticket_entires = $ticket_entires->entires;
        
        if(empty($request->entires)) {
            $request->entires = 0;
        } else if(!is_numeric($request->entires) || $request->entires < 0 || $request->entires > $ticket_entires) {
            return redirect('/customers/give')->withErrors([
                'entires' => 'Los enteros deben ser un número entre 0 a '.$ticket_entires.'...'
            ])->withInput();
        }
        $request->entires = floor($request->entires);
        
        if(!empty($request->fractions) && (!is_numeric($request->fractions) || $request->fractions < 0)) {
            return redirect('/customers/give')->withErrors([
                'fractions' => 'La cantidad de fracciones debe ser un número mayor a 0...'
            ])->withInput();
        }
        $request->fractions = floor($request->fractions);

        if($request->entires + $request->fractions == 0) {            
            return redirect('/customers/return')->withErrors([
                'entires' => 'Ingrese una cantidad de enteros o fracciones válidas...'
            ])->withInput();
        }

        $ticket = DB::table('tickets')
            ->select('fractions')
            ->where('id', $request->ticketid)
            ->first();
        
        if($request->fractions > $ticket->fractions) {
            return redirect('/customers/return')->withErrors([
                'fractions' => 'La cantidad de fracciones no puede ser mayor a '.$ticket->fractions.' ...'
            ])->withInput();
        }    

        $entires = DB::table('tickets')
            ->leftJoin('entires', 'tickets.id', '=', 'entires.ticketid')
            ->select(DB::raw('IFNULL(SUM(entires.entires), 0) as entires, IFNULL(SUM(entires.fractions), 0) as fractions,  IFNULL(MAX(tickets.fractions), 0) as num_fractions'))
            ->where('tickets.id', $request->ticketid)
            ->first();

        $total_entregados = $entires->entires + ($entires->fractions / $entires->num_fractions);
        $total_nuevo = $request->entires + ($request->fractions / $entires->num_fractions);

        if($total_entregados < $total_nuevo) {
            if(floor($total_entregados) < $request->entires) {
                return redirect('/customers/return')->withErrors([
                    'entires' => 'Cantidad de enteros disponibles para devolver: '.floor($total_entregados).' ...'
                ])->withInput();
            } else {
                return redirect('/customers/return')->withErrors([
                    'fractions' => 'Cantidad de fracciones disponibles para devolver: '.((($entires->entires * 5) + $entires->fractions) - ($request->entires * 5)).' ...'
                ])->withInput();
            }
        }
        
        $ticket = Entire::create([
            'ticketid' => $request->ticketid,
            'customerid' => $request->customerid,
            'entires' => $request->entires * -1,
            'fractions' => $request->fractions * -1,
            'action' => 'del',
            'active' => 1, //$request->has('active'),
            'description' => $request->description,
            'created_by' => Auth()->user()->username
        ]);
        
        $this->apply_balance($request->customerid);
        
        return redirect('customers/'.$request->customerid)->with([
            'message' => 'Cuota devuelta correctamente...',
            'type' => 'success'
        ]);
    }


    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pay(Request $request)
    {
        if(empty($request->get('c'))) {
            return redirect('customers');
        }
        $customers = DB::table('customers')->where('id', $request->get('c'))->where('active', 1)->get();
        $tickets = DB::table('tickets')
            ->leftJoin('suppliers', 'tickets.supplierid', '=', 'suppliers.id')
            ->leftJoin('lotteries', 'tickets.lotteryid', '=', 'lotteries.id')            
            ->select(
                'tickets.*', 
                'suppliers.name as supplier_name', 
                'suppliers.utility as supplier_utility', 
                'lotteries.name as lottery_name',
                'lotteries.date as lottery_date',
                DB::raw('(SELECT IFNULL(SUM(entires), 0) FROM entires WHERE ticketid=tickets.id) as entires'
            ))
            ->where('active', 1)
            ->get();
        return view('customers.pay')->with([
            'customers' => $customers,
            'tickets' => $tickets,
            'c' => $request->get('c')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pay_store(Request $request)
    {
        $rules = [
            'customerid' => 'required',
            'type' => 'required|numeric|between:1,2',
            'amount' => 'required|numeric|min:1'
        ];
        $messages = [
            'customerid.required' => 'Seleccione un cliente...',
            'type.required' => 'Seleccione un tipo de aplicación...',
            'type.numeric' => 'Seleccione un tipo de aplicación válida...',
            'type.between' => 'Seleccione un tipo de aplicación válida...',
            'amount.required' => 'Ingrese un monto a aplicar...',
            'amount.numeric' => 'Ingrese un monto válido...',
            'amount.min' => 'Ingrese un monto mayor a 0...'
        ];
        $this->validate($request, $rules, $messages);
                    
        $balance = Balance::create([
            'appliedtype' => 'customers',
            'appliedid' => $request->customerid,
            'date' => date('Y-m-d H:m:s'),
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
            'created_by' => Auth()->user()->username
        ]);

        $this->apply_balance($request->customerid);
        
        return redirect('customers/'.$request->customerid)->with([
            'message' => 'Saldos aplicados correctamente...',
            'type' => 'success'
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
            'name' => 'required|max:255|unique:customers,name,0,active',
            'utility' => 'required|numeric|between:1,100',
        ];
        $messages = [
            'name.required' => 'Agrega el nombre del cliente...',
            'name.max' =>'El nombre del cliente no puede ser mayor a :max caracteres...',
            'name.unique' =>'Ya existe un cliente con ese nombre...',
            'utility.required' => 'Agrega la utilidad del cliente...',
            'utility.numeric' => 'La utilidad debe ser un número...',
            'utility.between' => 'La utilidad debe estar entre :min y :max...'
        ];
        $this->validate($request, $rules, $messages);
        
        $customer = Customer::create([
            'name' => $request->name,
            'utility' => $request->utility,
            'active' => 1, //$request->has('active'),
            'balance' => 0,
            'created_by' => Auth()->user()->username
        ]);
        
        return redirect('customers')->with([
            'message' => 'Cliente creado correctamente...',
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
        $customer = Customer::find($id);        
        $tickets = DB::table('entires')
            ->leftJoin('tickets', 'tickets.id', '=', 'entires.ticketid')
            ->leftJoin('suppliers', 'tickets.supplierid', '=', 'suppliers.id')
            ->leftJoin('lotteries', 'tickets.lotteryid', '=', 'lotteries.id')            
            ->select(
                DB::raw("'ticket' as type"),
                'entires.entires',
                'entires.fractions',
                'entires.created_at',
                'entires.created_by',
                'lotteries.name as lottery_name',
                'lotteries.date as lottery_date',
                'tickets.price as ticket_price',
                'tickets.fractions as tickets_fractions',
                DB::raw('(SELECT IFNULL(SUM(entires), 0) FROM entires WHERE ticketid=tickets.id) as total_entires'),
                DB::raw('NULL as typebalance'), 
                DB::raw('NULL as amount'), 
            )
            ->where('entires.customerid', $id);            

            
        $balances2 = DB::table('balances')          
            ->select(
                DB::raw("'balance' as type"),
                DB::raw('NULL as entires'), 
                DB::raw('NULL as fractions'),
                'created_at',
                'created_by',
                DB::raw('NULL as lottery_name'),
                DB::raw('NULL as lottery_date'),
                DB::raw('NULL as ticket_price'),
                DB::raw('NULL as tickets_fractions'),
                DB::raw('NULL as total_entires'),
                'type as typebalance',
                'amount'
            )
            ->where('appliedtype', 'customers')
            ->where('appliedid', $id);

        $tickets = $tickets->union($balances2)->orderBy('created_at', 'asc')->get();

        $balances = DB::table('balances')          
            ->select(
                DB::raw('IFNULL(SUM((CASE type WHEN 1 THEN amount ELSE 0 END)), 0) as pay, IFNULL(SUM((CASE type WHEN 2 THEN amount ELSE 0 END)), 0) as receipt'
            ))
            ->where('appliedtype', 'customers')
            ->where('appliedid', $id)->first();

        return view('customers.show', [
            'customer' => $customer,
            'tickets' => $tickets,
            'balances' => $balances,
            'id' => $id
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
        $customer = Customer::find($id);
        return view('customers.delete')->with(['customer' => $customer]);
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
        DB::table('customers')
        ->where('id', $id)
        ->update(['active' => 0]);

        return redirect('/customers')->with([
            'message' => 'Cliente eliminado correctamente...',
            'type' => 'success'
        ]);
    }
}