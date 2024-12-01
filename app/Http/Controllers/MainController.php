<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class MainController extends Controller
{
    //
    public function home():View
    {
        return view('home');
    }
    public function generetionExercises(Request $request)
    {
        echo 'Gerar exercicios!';
    }

    public function printExercises()
    {
        echo 'Imprimi exercicios no navegador';
    }

    public function exportExercises()
    {
        echo 'Exporta exercicios para um arquivo de texto!';
    }
}
