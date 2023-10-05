<?php

namespace App\Http\Controllers;

use App\Models\Layout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LayoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['status' => 'Ok', 'layouts' => Layout::all()], 200);
    }

    public function activate(Request $request)
    {
        $activate = DB::table('configs')->update(['layout' => $request->name]);
        return response()->json(['status' => "Ok", 'layout' => $activate], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Layout $layout)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Layout $layout)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Layout $layout)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Layout $layout)
    {
        //
    }
}
