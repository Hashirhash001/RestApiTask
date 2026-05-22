<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Projects;

class ProjectController extends Controller
{
    public function show(Projects $id) {
        // Logic to retrieve a project by ID
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $project = Projects::where('id', $id)->where('user_id', $user->id)->first();
        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Project retrieved successfully',
            'data' => $project
        ]);
    }

    public function store(Request $request) {
        // Logic to create a new project
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,archived',
        ]);

        $project = Projects::create([
            'user_id' => $user->id,
            'name' => $validatedData['name'],
            'description' => $validatedData['description'] ?? null,
            'status' => $validatedData['status'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Project created successfully',
            'data' => $project
        ], 201);
    }

    public function update(Request $request, $id) {
        // Logic to update an existing project
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $project = Projects::where('id', $id)->where('user_id', $user->id)->first();
        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }
        if ($project->user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,archived',
        ]);

        $project->update([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'] ?? null,
            'status' => $validatedData['status'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Project updated successfully',
            'data' => $project
        ]);
    }

    public function destroy($id) {
        // Logic to delete a project
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $project = Projects::where('id', $id)->where('user_id', $user->id)->first();
        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }
        if ($project->user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $project->delete();
        return response()->json([
            'success' => true,
            'message' => 'Project deleted successfully'
        ]);
    }
}
