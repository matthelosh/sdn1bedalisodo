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
    public function index()
    {
        try {
            $agendas = Agenda::with('user')->get();
            return response()->json(['status' => 'success', 'data' => $agendas], 200);
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
        try {
            $simpan = Agenda::updateOrCreate(
                [
                    'id' => $request->id ?? null,

                ],
                [
                    'name' => $request->name,
                    'description' => $request->description,
                    'start' => $request->start,
                    'end' => $request->end,
                    'location' => $request->location ?? null,
                    'color' => $request->color ?? null,
                    'user_id' => $request->user()->name,
                    'is_done' => 0,
                    'is_active' => 1
                ]
            );
            return response()->json(['status' => 'success', 'data' => $simpan], 200);
        } catch (\Exception $e) {
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
