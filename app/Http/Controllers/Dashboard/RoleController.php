<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * RoleController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        $roles = DB::table('roles')->paginate(10);
        return response()->view('dashboard.roles.index', ['roles' => $roles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(): Response
    {
        return response()->view('dashboard.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // Get all inputs from the form
        $input = $request->all();
        // Validate inputs
        $request->validate([
            'role_name' => 'required',
        ]);

        // Create instance from role model
        $role = new Role();
        $role_name = $input['role_name'];
        $role->name = strtolower($role_name);
        //print_r($input);
//        dd($input);

        // Insert in roles Table--Database
        $role->save();

        // Get Last ID || Role ID
        $role_id = Role::all()->last();

        // Status for Adding the New Role To The System!
        $alert_status = 'alert-success';
        // Msg
        $msg = 'New User Added Successfully.';
        // Pref
        $pref = "You Add $role_name As New User Role To The System!<br>Role ID : {$role_id['id']} . ";
        $status = ['alert_status' => $alert_status, 'msg' => $msg, 'pref' => $pref];

        return redirect()->back()->with('status', $status);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id): Response
    {
        // Role ID
        $role = Role::findOrFail($id);
        return response()->view('dashboard.roles.show', ['role' => $role]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit(int $id): Response
    {
        // Role ID
        $role = Role::findOrFail($id);
        return response()->view('dashboard.roles.edit', ['role' => $role]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        // Get all inputs from the form
        $input = $request->all();
        // Validate inputs
        $request->validate([
            'role_name' => 'required',
        ]);

        // Role ID
        $role = Role::findOrFail($id);
        $role_name = $input['role_name'];
        $role->name = strtolower($role_name);

        // Insert in roles Table--Database
        $role->save();

        // Status for Editing the User in The System!
        $alert_status = 'alert-success';
        // Msg
        $msg = "Edit Role $role_name Successfully.";
        // Pref
        $pref = "You Edit $role_name User Role in The System!<br>Role ID : {$id} . ";
        $status = ['alert_status' => $alert_status, 'msg' => $msg, 'pref' => $pref];

        return redirect()->route('dashboard.roles.index')->with('status', $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        // Role ID
        $role = Role::findOrFail($id);
        $role_id = $id;
        $role_name = $role->name;

        // Status for Adding the New Role To The System!
        $alert_status = 'alert-warning';
        // Msg
        $msg = "Delete User Role $role_name Successfully.";
        // Pref
        $pref = "You Delete $role_name User Role From The System!<br>Role ID : {$role_id} . ";
        $status = ['alert_status' => $alert_status, 'msg' => $msg, 'pref' => $pref];

        $role->delete();

        return redirect()->route('dashboard.roles.index')->with('status', $status);
    }
}
