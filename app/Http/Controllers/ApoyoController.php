<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApoyoController extends Controller
{
    /**
     * Mostrar la vista de herramientas de apoyo.
     */
    public function index()
    {
        // Preparar datos si es necesario
        return view('apoyo');
    }
}
