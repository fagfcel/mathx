<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class MainController extends Controller
{
    //
    public function home(): View
    {
        return view('home');
    }
    public function generetionExercises(Request $request): View
    {
        //form validade
        $request->validate([
            'check_sum' => 'required_without_all:check_subtraction,check_multiplication,check_division',
            'check_subtraction' => 'required_without_all:check_sum,check_multiplication,check_division',
            'check_multiplication' => 'required_without_all:check_subtraction,check_sum,check_division',
            'check_division' => 'required_without_all:check_subtraction,check_multiplication,check_sum',
            'number_one' => 'required | integer | min:0 | max:999 |lt:number_two',
            'number_two' => 'required |integer | min:0 | max:999',
            'number_exercises' => 'required | integer | min:5 | max:50 ',

        ]);
        //get selected operations 
        $operations = [];

        if ($request->chek_sum) {
            $operations[] = 'sum';
        }
        if ($request->check_subtraction) {
            $operations[] = 'subtraction';
        }
        if ($request->check_multiplication) {
            $operations[] = 'multiplication';
        }
        if ($request->check_division) {
            $operations[] = 'division';
        }
        //get number (min and max)
        $min = $request->number_one;
        $max = $request->number_two;

        //get number of exercises
        $numberExercises = $request->number_exercises;

        //generate exercises
        $exercises = [];
        for ($index = 1; $index <= $numberExercises; $index++) {
            $exercises[] = $this->generetionExercise($index, $operations, $min, $max);
        }

        //place exercises in session
        session(['exercises' => $exercises]);

        // dd(session('exercises'));
        return view('operations', ['exercises' => $exercises]);
    }

    public function printExercises()
    {
        if(!session()->has('exercises')){
            return redirect()->route('home');
        }
       
        $exercises = session('exercises');
        // dd($exercises);
        echo '<!DOCTYPE html>';
        echo '<pre>';
        echo '<h1>Exercícios de Matemática ('. env('APP_NAME') .')</h1>';
        echo '<hr>';

        foreach($exercises as $exercise)
        {
            echo "<h2><small>". $exercise['exercise_number'] ." >> </small>".$exercise['exercise']."</h2>";
        }

        echo "<hr>";
        echo '<small>Soluções</small><br>';

        foreach($exercises as $exercise) {
            echo "<small>". $exercise['exercise_number']." >> ".$exercise['sollution']."</small><br>";
        }

    }

    public function exportExercises()
    {
        if(!session()->has('exercises'))
        {
            return redirect->route('home');
        }
        
        //creat file to download with exercises
        $exercises = session('exercises');

        $filename = 'exercicios_'. env('APP_NAME') . '_' . date('YmdHis').'.txt';

        $content ="Exercícios de Matemática (". env('APP_NAME').") \n    \n";

        foreach ($exercises as $exercise) {
            $content .= $exercise['exercise_number'] . ' > ' . $exercise['exercise'] . "\n";
        }
        
        //sollucions
        $content .= "\n Soluções \n". str_repeat('-', 20)."\n";
        foreach ($exercises as $exercise) {
            $content .= $exercise['exercise_number'] . ' > ' . $exercise['sollution'] . " \n";
        }

        return response($content)
                ->header('Content-Type', 'text/plain')
                ->header('Content-Disposition', 'attachment; filename ="'. $filename .'"');



    }

    private function generetionExercise($index, $operations, $min, $max): array
    {

        $operation = $operations[array_rand($operations)];
        $number1 = rand($min, $max);
        $number2 = rand($min, $max);

        $exercise = '';
        $sollution = '';

        switch ($operation) {
            case 'sum':
                $exercise = "$number1 + $number2 = ";
                $sollution = $number1 + $number2;
                break;
            case 'subtraction':
                $exercise = "$number1 - $number2 = ";
                $sollution = $number1 - $number2;
                break;
            case 'multiplication':
                $exercise = "$number1 x $number2 = ";
                $sollution = $number1 * $number2;
                break;
            case 'division':
                //avoid division  by zero
                if ($number2 == 0) {
                    $number2 = 1;
                }
                $exercise = "$number1 : $number2 = ";
                $sollution = $number1 / $number2;
                break;
        }
        //if $sollution is a float number, round it to 2 decimal places
        if (is_float($sollution)) {
            $sollution = round($sollution, 2);
        }
        return [
            'operation' => $operation,
            'exercise_number' => str_pad($index, 2, '0',STR_PAD_LEFT),
            'exercise' => $exercise,
            'sollution' => "$exercise $sollution",

        ];
    }
}
