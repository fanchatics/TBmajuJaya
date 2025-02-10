<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\History;
use Carbon\Carbon;
class historyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $histories = History::all(); // Fetch all records from the histories table
        return view('history.showfull', ['histories' => $histories]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function search(Request $request)
    {
        $name = $request->input('name');
    
        // Use where clause to filter records based on the name
        $histories = History::where('name', 'like', "%$name%")->get();
    
        if ($histories->isEmpty()) {
            return view('history.search_result', ['message' => 'No records found for the given name', 'name' => $name]);
        } else {
            return view('history.search_result', ['histories' => $histories,'name' => $name]);
        }
    }
    
    public function search_date(Request $request)
    {
        $inputDate = $request->input('date');
    
        $histories = History::whereDate('created_at', '=', $inputDate)->get();
    
        if ($histories->isEmpty()) {
            return view('history.search_result_date', ['message' => 'No records found for the given date', 'date' => $inputDate]);
        } else {
            return view('history.search_result_date', ['histories' => $histories, 'date' => $inputDate]);
        }
    }

    public function last(Request $request)
    {
        $action = $request->query('action');
    
        $numberOfDays = 30;
    
        if ($action === 'action1') {
            $numberOfDays = 30;
        } elseif ($action === 'action2') {
            $numberOfDays = 90;
        } elseif ($action === 'action3'){
            $histories = History::all();
            return view('history.showfull', ['histories' => $histories]);

        }
    

        $startDate = Carbon::now()->subDays($numberOfDays)->toDateString();
    
        // Query the database with the calculated start date
        $histories = History::where('created_at', '>=', $startDate)->get();
    
        return view('history.show_date', ['histories' => $histories, 'startDate' => $startDate, 'numberofDays' => $numberOfDays]);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
