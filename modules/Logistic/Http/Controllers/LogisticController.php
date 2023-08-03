<?php

namespace Modules\Logistic\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Tenant\Configuration;

class LogisticController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $configuration = Configuration::first();

        return view('logistic::logistics.index', compact('configuration'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create($id = null)
    {
        if(auth()->user()->type == 'client') return redirect('/logistics');

        $quotationId = null;
        $showPayments = true;

        return view('logistic::logistics.form', compact('id', 'quotationId', 'showPayments'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('logistic::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('logistic::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
