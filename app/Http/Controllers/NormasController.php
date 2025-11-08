<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NormasController extends Controller
{
    /**
     * Mostrar la vista de normas.
     */
    public function index()
    {
        // Preparar datos si es necesario
        return view('normas');
    }
}
