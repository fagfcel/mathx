<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    //
    public function home()
    {
        echo 'Apresenta página inicial';
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
