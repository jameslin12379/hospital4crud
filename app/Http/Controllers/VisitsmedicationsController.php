<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class VisitsmedicationsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $visitsmedications = DB::select('SELECT * FROM visitsmedications ORDER BY date_created DESC');
        $count = DB::table('visitsmedications')->count();
        $data = array(
            'visitsmedications' => $visitsmedications,
            'count' => $count,
            'title' => 'Visitsmedications'
        );
        return view('visitsmedications/index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $level = Auth::user()->level;
        if ($level === 1){
            $data = array(
                'title' => 'Create'
            );
            return view('visitsmedications/new')->with($data);
        } else {
            return redirect('/');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $level = Auth::user()->level;
        if ($level === 1){
            $validatedData = $request->validate([
                'visit_id' => 'required',
                'medication_id' => 'required'
            ]);
            $visit_id = $request->input('visit_id');
            $medication_id = $request->input('medication_id');
            DB::table('visitsmedications')->insert(
                ['visit_id' => $visit_id,'medication_id' => $medication_id]
            );
            return redirect('/visitsmedications')->with('success', 'Visitmedication created.');
        } else {
            return redirect('/');
        }

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
        $level = Auth::user()->level;
        if ($level === 1){
            $visitmedication = DB::select('select * from visitsmedications where id = ?', array($id));
            if (empty($visitmedication)) {
                return view('404');
            }
            $data = array(
                'title' => 'Edit',
                'visitmedication' => $visitmedication
            );
            return view('visitsmedications/edit')->with($data);
        } else {
            return redirect('/');
        }
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
        $level = Auth::user()->level;
        if ($level === 1){
            $visitmedication = DB::select('select * from visitsmedications where id = ?', array($id));
            if (empty($visitmedication)) {
                return view('404');
            } else {
                $validatedData = $request->validate([
                    'visit_id' => 'required',
                    'medication_id' => 'required'
                ]);
                $visit_id = $request->input('visit_id');
                $medication_id = $request->input('medication_id');
                DB::table('visitsmedications')
                    ->where('id', $id)
                    ->update(['visit_id' => $visit_id, 'medication_id' => $medication_id]);
                return redirect('/visitsmedications')->with('success', 'Visitmedication edited.');

            }
        } else {
            return redirect('/');
        }
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
        $level = Auth::user()->level;
        if ($level === 1){
            $visitmedication = DB::select('select * from visitsmedications where id = ?', array($id));
            if (empty($visitmedication)) {
                return view('404');
            } else {
                DB::table('visitsmedications')->where('id', '=', $id)->delete();
                return redirect('/visitsmedications')->with('success', 'Visitmedication deleted.');
            }
        } else {
            return redirect('/');
        }
    }
}
