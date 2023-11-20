<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function getEdit($id){

        if ($id > 10){ $id = 10; }
        return view('catalog.edit', ['id' => $id]);

    }
}
