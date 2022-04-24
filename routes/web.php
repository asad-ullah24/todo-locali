<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // $todo_list = [
    //     [
    //         "title" => "Apply for job",
    //         "deadline" => '2022-04-28T00:30:00Z'
    //     ],
    //     [
    //         "title" => "Create a TODO List Application",
    //         "deadline" => '2022-04-24T18:59:59Z'
    //     ],
    //     [
    //         "title" => "Interview For Senior Software Engineer",
    //         "deadline" => '2022-05-01T10:00:00Z'
    //     ],
    // ];

    $todo_list = [];
    $string = file_get_contents(__DIR__."/../tasks.json");
    if ($string !== false) {
        $json_a = json_decode($string, true);
        if (!empty($json_a)) {
            $todo_list = $json_a;
        }
    }
    
    return view('welcome', ['todo_list' => $todo_list]);
});

Route::post('/addTask', function(Request $request) {
    try {
        $tasks = [];
        $string = file_get_contents(__DIR__."/../tasks.json");
        if ($string !== false) {
            $json_a = json_decode($string, true);
            if (!empty($json_a)) {
                $tasks = $json_a;
            }
        }

        $data = $request->all();

        array_push($tasks, $data);

        $fp = fopen(__DIR__.'/../tasks.json', 'w');
        fwrite($fp, json_encode($tasks, JSON_PRETTY_PRINT));   // here it will print the array pretty
        fclose($fp);

        return ["success" => true];
    } catch(Exception $e) {
        return ["success" => false, "error" => $e->getMessage()];
    }
});