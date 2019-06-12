<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;

class TaskController extends Controller
{
    public function index()
    {
        return Task::with('priority')->where('userId', auth()->user()->id)->get();
    }

    public function create(Request $request)
    {
        $data = array_merge($request->all(), ['userId' => auth()->user()->id]);

        return Task::create($data)->load('priority');
    }

    public function edit(Request $request, $id)
    {
        $task = Task::findorFail($id);

        if ($task->userId != auth()->user()->id){
            return response()->json('Unauthorized', 401);
        }

        $task->update($request->all());

        return $task->load('priority');
    }

    public function delete($id)
    {
        $task = Task::findorFail($id);

        if ($task->userId != auth()->user()->id){
            return response()->json('Unauthorized', 401);
        }
        
        $task->delete();

        return 204;
    }
}
