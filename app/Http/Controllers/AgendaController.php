<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            // dd($request->user()->name);
            // if($request->user() === 'admin') {
            //     $agendas = Agenda::all();
            // } else {
            //     $month = $request->query('month') ?? date('m');
            //     $agendas = Agenda::whereMonth('start', $month)->with('user.userable')->get();
            // }
            $agendas = Agenda::with('user.userable')->get();
            return response()->json(['status' => 'success', 'agendas' => $agendas], 200);
        } catch (\Exception $e) {
             return response()->json(['status' => 'error','message' => $e->getMessage()], 500);
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
        $agenda  = json_decode($request->agenda);
        // dd($agenda);
        // return $agenda;
        try {
            $simpan = Agenda::updateOrCreate(
                [
                    'id' => $agenda->id ?? null,

                ],
                [
                    'name' => $agenda->name,
                    'description' => $agenda->description,
                    'start' => $agenda->start,
                    'end' => $agenda->end,
                    'location' => $agenda->location ?? null,
                    'color' => $agenda->color ?? null,
                    'user_id' => $request->user()->name,
                    'is_done' => "0",
                    'is_active' => "1",
                    'status' => $agenda->status
                ]
            );
            return response()->json(['status' => 'success', 'data' => $simpan], 200);
        } catch (\Exception $e) {
            dd($e);
            return response()->json(['status' => 'error','message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Agenda  $agenda
     * @return \Illuminate\Http\Response
     */
    public function show(Agenda $agenda)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Agenda  $agenda
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Agenda $agenda)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Agenda  $agenda
     * @return \Illuminate\Http\Response
     */
    public function destroy(Agenda $agenda)
    {
        try {
            // dd($id);
            $hapus = $agenda->delete();
            return response()->json(['status' => 'success', 'data' => $hapus], 200);
        } catch(\Exception $e) {
            return response()->json(['status' => 'error',' message' => $e->getMessage()], 500);
        }

    }
}
