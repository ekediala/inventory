<?php

namespace App\Http\Controllers;

use App\Http\Requests\InventoryRequest;
use App\Inventory;

class InventoryController extends Controller
{
    /**
     * Create a new InventoryController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->authorizeResource(Inventory::class, 'inventory');
    }

    /**
     * Display a listing of the inventories created by the user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inventories = auth()->user()->inventories;
        return response()->json(['data' => $inventories], 200);
    }

    /**
     * Show given resource
     */

     public function show(Inventory $inventory){
        return response()->json($inventory, 200);
     }


    /**
     * Store a newly created inventory.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InventoryRequest $request)
    {

        $inventory = Inventory::create($request->validated());

        if ($inventory) {
            auth()->user()->inventories()->save($inventory);
        }

        $inventory = true;

        $response = $inventory ? 'Created successfully' : 'Something went wrong. Try again';

        return response()->json(['response' => $response], 200);
    }

    /**
     * Update the specified inventory in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function update(InventoryRequest $request, Inventory $inventory)
    {
        $input = $request->validated();
        $response = $inventory->update($input);
        $response = $response ? 'Updated successfully' : 'Something went wrong. Try again';
        return response()->json(['response' => $response], 200);
    }

    /**
     * Remove the specified inventory from storage.
     *
     * @param  \App\Inventory  $inventory
     * @return \Illuminate\Http\Response
     *
     */
    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return response()->json(['response' => 'Successfully Deleted'], 200);
    }
}
