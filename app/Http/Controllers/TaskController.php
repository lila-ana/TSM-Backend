<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ProgressController;
use App\Models\User;
// use Illuminate\Support\Carbon;
use \Carbon\Carbon;



class TaskController extends Controller
{

    public function index()
    {
        $tasks = Task::with('user')->get();
        return response()->json(['tasks' => $tasks]);
    }

    //CRUD

    public function show($id)
    {
        $task = Task::where('user_id', $id)->with('user')->get();
        return response()->json(['tasks' => $task]);
    }
    public function showTask($id)
    {
        $task = Task::where('id', $id)->with('user')->first();
        return response()->json(['tasks' => $task]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255|unique:tasks',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'planned_progress' => 'required|int',
            'assigned_to' => 'required|array',

            // 'status' => 'string',
        ]);

        unset($validatedData['assigned_to']);
        // return $validatedData;
        $userIds = $request->input('assigned_to');
        foreach ($userIds as $key => $id) {
            // return $id;

            # code...
            $user = User::where('id', $id)->first();
            if (!$user) {
                return response()->json(['error' => 'User with id = '  . $id .  'not found'], 404);
            }
        }


        $task = Task::create($validatedData);

        $task->user()->attach($userIds);

        return response()->json(['message' => 'Task created successfully', 'task' => $task], 201);
    }


    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $validatedData = $request->validate([
            'title' => 'string|max:255',
            'description' => 'string',
            'start_date' => 'date',
            'end_date' => 'date',
            'planned_progress' => 'int',
            'assigned_to' => 'string|exists:users,id',

        ]);


        $changes = false;

        if (isset($validatedData['title']) && $validatedData['title'] !== $task->title) {
            $task->title = $validatedData['title'];
            $changes = true;
        }

        if (isset($validatedData['description']) && $validatedData['description'] !== $task->progress) {
            $task->description = $validatedData['description'];
            $changes = true;
        }
        if (isset($validatedData['start_date']) && $validatedData['start_date'] !== $task->progress) {
            $task->start_date = $validatedData['start_date'];
            $changes = true;
        }
        if (isset($validatedData['end_date']) && $validatedData['end_date'] !== $task->progress) {
            $task->end_date = $validatedData['end_date'];
            $changes = true;
        }
        if (isset($validatedData['planned_progress']) && $validatedData['planned_progress'] !== $task->progress) {
            $task->planned_progress = $validatedData['planned_progress'];
            $changes = true;
        }
        if (isset($validatedData['assigned_to']) && $validatedData['progress'] !== $task->progress) {
            $task->assigned_to = $validatedData['assigned_to'];
            $changes = true;
        }
        if (isset($validatedData['status']) && $validatedData['status'] !== $task->progress) {
            $task->status = $validatedData['status'];
            $changes = true;
        }

        if ($changes) {
            $task->save();
            return response()->json(['message' => 'Task updated successfully', 'task' => $task], 200);
        } else {
            return response()->json(['message' => 'No changes detected for the task'], 200);
        }
    }


    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }


    // Assigned Calculation 

    public function calculateAssignedDate(Task $task)
    {
        $startDate = DateTime::createFromFormat('Y-m-d', $task->start_date, new DateTimeZone('UTC'));
        $endDate = DateTime::createFromFormat('Y-m-d', $task->end_date, new DateTimeZone('UTC'));

        if (!$startDate || !$endDate) {
            return response()->json(['message' => 'Task start date or end date is missing. Cannot calculate Assigned date.'], 400);
        }

        $interval = $endDate->diff($startDate);
        $AssignedDate = $interval->days;

        return response()->json(['Assigned_date' => $AssignedDate]);
    }

    // Compare Progress

    public function compareProgress(Request $request, $taskId)
    {
        $task = Task::find($taskId);

        $validator = Validator::make($request->all(), [
            'planned_progress' => 'integer|between:0,100',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 400);
        }

        $start_date = $task->start_date;
        $end_date = $task->end_date;
        $plannedProgress = $task->planned_progress;
        $current_date = Carbon::now();

        // Convert the date strings to DateTime objects
        $start_date = new DateTime($start_date);
        $end_date = new DateTime($end_date);
        $current_date = new DateTime($current_date);

        $total_duration = $start_date->diff($end_date)->days;
        $elapsed_duration = $start_date->diff($current_date)->days;
        $progressPercentage = round(($elapsed_duration / $total_duration) * 100, 2);


        $actualProgress = number_format($progressPercentage, 2);

        $deviation = number_format(($plannedProgress - $actualProgress), 2);


        return response()->json([
            'message' => 'Progress comparison result',
            'actual_progress' => $actualProgress,
            'planned_progress' => $plannedProgress,
            'deviation' => $deviation,
            'progress_percentage' => $progressPercentage . '%', // Include progressPercentage here

        ]);
    }

    //Flag

    public function getStatus($taskId)
    {

        $task = Task::find($taskId);

        $start_date = $task->start_date;
        $end_date = $task->end_date;
        $current_date = Carbon::now();


        $start_date = new DateTime($start_date);
        $end_date = new DateTime($end_date);
        $current_date = new DateTime($current_date);

        $total_duration = $start_date->diff($end_date)->days;
        $elapsed_duration = $start_date->diff($current_date)->days;
        $progress = round(($elapsed_duration / $total_duration) * 100, 2);

        if ($current_date < $start_date) {
            return "Not Started";
        } elseif ($current_date >= $start_date && $current_date <= $end_date) {

            if ($progress < 50) {
                return "In Progress";
            } elseif ($progress >= 50 && $progress < 100) {
                return "Almost Complete";
            } else {
                return "Complete";
            }
        } else {
            return "Complete";
        }
    }


    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}
