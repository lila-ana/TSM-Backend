<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Progress;
use App\Models\Task;
use DateTime;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ProgressController extends Controller
{
    public function calculateProgress(Request $request, $taskId)
    {
        $task = Task::find($taskId);

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'current_date' => 'required|date|after_or_equal:start_date|before_or_equal:end_date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Get the validated data from the request
        $start_date = $task->start_date;
        $end_date = $task->end_date;
        $current_date = $request->input('current_date');

        // Convert the date strings to DateTime objects
        $start_date = new DateTime($start_date);
        $end_date = new DateTime($end_date);
        $current_date = new DateTime($current_date);

        $total_duration = $start_date->diff($end_date)->days;

        $elapsed_duration = $start_date->diff($current_date)->days;

        $progressPercentage = ($elapsed_duration / $total_duration) * 100;


        return response()->json(['progress' => number_format($progressPercentage, 2) . '%']);
    }
}
