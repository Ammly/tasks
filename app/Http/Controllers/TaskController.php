<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::where(['user_id' => Auth::user()->id])->get();

        return response()->json([

            'tasks' => $tasks,

        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'status' => 'required',
            'due_date' => 'required'
        ]);

        $task = Task::create([
            'title' => $request->title,
            'status' => $request->status,
            'due_date' => $request->due_date,
            'user_id' => Auth::user()->id
        ]);

        return response()->json([
            'task' => $task,
            'message' => 'Success'
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'status' => 'required',
            'due_date' => 'required'
        ]);

        $task->title = request('title');

        $task->status = request('status');

        $task->due_date = request('due_date');

        $task->save();

        return response()->json([
            'message' => 'Task updated successfully!'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json([

            'message' => 'Task deleted successfully!'

        ], 200);
    }
}
