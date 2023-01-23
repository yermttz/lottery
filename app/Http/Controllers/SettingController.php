<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        $settings = Setting::all();
        return view('settings.index')->with('settings', $settings);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $arr = [];
        foreach($request->all() as $req => $val) {
            if(strpos($req, ":") !== false){
                list($type, $key) = explode(":", $req);

                switch($type) {
                    case "text":
                        if ($key == "password" && !empty(trim($val)))
                        {
                            DB::table('users')
                            ->where('username', Auth()->user()->username)
                            ->update(['password' => bcrypt($val)]);
                        }
                        if(!$val) $val = '';
                        DB::table('settings')
                        ->where('key', $key)
                        ->update(['value' => $val]);
                    break;
                    case "file":
                        $file = $request->file($req);
                        $array_valid = ["image/jpeg", "image/png"];
                        if(!in_array($file->getMimeType(), $array_valid)) {
                            return redirect('/settings')->withErrors([
                                'logo' => 'La imagen debe tener el formato: jpeg | png ...'
                            ])->withInput();
                        }
                        /*
                        //Display File Name
                        echo 'File Name: '.$file->getClientOriginalName();
                        echo '<br>';
                    
                        //Display File Extension
                        echo 'File Extension: '.$file->getClientOriginalExtension();
                        echo '<br>';
                    
                        //Display File Real Path
                        echo 'File Real Path: '.$file->getRealPath();
                        echo '<br>';
                    
                        //Display File Size
                        echo 'File Size: '.$file->getSize();
                        echo '<br>';
                    
                        //Display File Mime Type
                        echo 'File Mime Type: '.$file->getMimeType();
                    
                        return redirect('settings')->with([
                            'message' => 'Ajustes guardados correctamente...'.$file->getMimeType(),
                            'type' => 'success'
                        ]);
                        */
                        //Move Uploaded File
                        $destinationPath = 'uploads';
                        $ext = "png";
                        if($file->getMimeType() == "image/jpeg") $ext = "jpg";
                        $fileNa = $key.'.'.$ext;
                        $file->move($destinationPath, $fileNa);
                        
                        if(!$val) $val = '';
                        DB::table('settings')
                        ->where('key', $key)
                        ->update(['value' => $fileNa]);

                        //$request->file->move(public_path('uploads'), $fileName);
                    break;
                }
            }            
        }
        
        return redirect('settings')->with([
            'message' => 'Ajustes guardados correctamente...',
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