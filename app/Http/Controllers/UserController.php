<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index()
    {
        // Query the users table to retrieve all registered users
        $users = User::all();
        // Return the users as a JSON response
        return response()->json(['users' => $users]);
    }


    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'string|max:255',
            'email' => 'string'

        ]);


        $changes = false;

        if (isset($validatedData['name']) && $validatedData['name'] !== $user->name) {
            $user->name = $validatedData['name'];
            $changes = true;
        }

        if ($changes) {
            $user->save();
            return response()->json(['message' => 'User updated successfully', 'user' => $user], 200);
        } else {
            return response()->json(['message' => 'No changes detected for the user'], 200);
        }
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
