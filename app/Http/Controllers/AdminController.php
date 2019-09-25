<?php

namespace App\Http\Controllers;

class AdminController extends Controller
{
    /**
     *  Get collection of all users.
     *
     * @return \Illuminate\Http\Response
     */
    public function users()
    {
        $this->authorize('admin', auth()->user());
        $users =  \App\User::simplePaginate(20);
        return response()->json(['users' => $users], 200);
    }

    /**
     * Get all inventories
     *
     * @return \Illuminate\Http\Response
     */
    public function inventories()
    {
        $this->authorize('admin', auth()->user());
        $inventories = \App\Inventory::simplePaginate(20);
        return response()->json(['inventories' => $inventories], 200);

    }


}
