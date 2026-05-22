<?php

namespace App\Http\Controllers;

use App\Models\Tasks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function show(Tasks $id) {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        // Logic to retrieve a task by ID
        $task = Tasks::find($id);
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Task retrieved successfully',
            'data' => $task
        ]);
    }

    public function store(Request $request) {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        // Logic to create a new task
        $validatedData = $request->validate([
            'project_id' => 'required|integer|exists:projects,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'assigned_to' => 'nullable|integer|exists:users,id',
        ]);

        $task = Tasks::create([
            'project_id' => $validatedData['project_id'],
            'name' => $validatedData['name'],
            'description' => $validatedData['description'] ?? null,
            'status' => $validatedData['status'],
            'assigned_to' => $validatedData['assigned_to'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task created successfully',
            'data' => $task
        ], 201);
    }

    public function update(Request $request, $id) {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        // Logic to update an existing task
        $task = Tasks::find($id);
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'assigned_to' => 'nullable|integer|exists:users,id',
        ]);

        $task->update([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'] ?? null,
            'status' => $validatedData['status'],
            'assigned_to' => $validatedData['assigned_to'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully',
            'data' => $task
        ]);
    }

    public function destroy($id) {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        // Logic to delete a task
        $task = Tasks::find($id);
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }
        $task->delete();
        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully'
        ]);
    }
}
