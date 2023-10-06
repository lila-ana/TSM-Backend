<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $role = Role::all();
        $permissions = Permission::all();

        return response()->json([
            "message" => "List of Role and permissions",
            "Roles" => $role,
            "Permissions" => $permissions
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $roleId = $request->input("role_id");
        $permissions = $request->input("permission_id");

        // Retrieve the role by its ID
        $role = Role::findById($roleId);

        if (!$role) {
            return response()->json([
                "message" => "Role not found",
            ], 404);
        }

        // Assign permissions to the role
        $role->givePermissionTo($permissions);

        return response()->json([
            "message" => "List of Role and permissions",
            "Assigned Permissions to Role" => $role,
        ], 200);
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
