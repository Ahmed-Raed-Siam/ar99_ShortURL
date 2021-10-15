<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class UserRoleController extends Controller
{
    /**
     * UserRoleController constructor.
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

        $users_roles = User::withoutTrashed()->whereHas('roles')->paginate(10);
        return response()->view('dashboard.users.roles.index', compact("users_roles"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(): Response
    {
        // Get all users roles from role_users table;
        $roles = Role::all();
        $users = User::withoutTrashed()->whereDoesntHave('roles')->get();
//        dd($users_roles,$users);
        return response()->view('dashboard.users.roles.create', compact('users', 'roles'));
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
            'username' => 'required',
            'roles' => 'required|exists:roles,id', // validating role
        ]);
        // Create instance from RoleUser model
        $user_id = (int)$input['username'];
        $user = User::withoutTrashed()->where('id', $user_id)->firstOrFail();
        $user_name = $user->name;
        // Check is there is any role for this user
        if ($request->has('roles') && $request->filled('roles')) {
            $roles_checkbox_array = $input['roles'];
        } else {
            $roles_checkbox_array = [];
        }

        $count_roles_checkbox = count($roles_checkbox_array);
        $user_roles = '';
        if ($count_roles_checkbox >= 0) {
            foreach ($roles_checkbox_array as $role_element) {
                $role_model = Role::where('id', (int)$role_element)->firstOrFail();
                $user_roles .= ucfirst($role_model->name) . ' , ';
            }
            $user_roles = substr($user_roles, 0, -3);
            $user->save();
        }

//        dd($input, $roles_checkbox_array);

        //Save roles for this user
        $user->roles()->attach($roles_checkbox_array);
//        $user->roles()->sync($roles_checkbox_array, true);

        // Status for Adding the New Role To exist User!
        $alert_status = 'alert-success';
        // Msg
        $msg = 'New Roles Added Successfully.';
        // Pref
        $pref = "You Add Roles to $user_name User in The System!<br>User ID : {$user_id} ,User roles : $user_roles . ";
        $status = ['alert_status' => $alert_status, 'msg' => $msg, 'pref' => $pref];

        return redirect()->route('dashboard.users.roles.index')->with('status', $status);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id): Response
    {
        // Find User ID
        $user = User::withoutTrashed()->findOrFail($id);
        $user_roles = $user->whereHas('roles')->find($id)->roles()->get();
//        dd($id, $user, $user_roles);
        return response()->view('dashboard.users.roles.show', compact('user', 'user_roles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit(int $id): Response
    {
        // Get user roles from role_users table
        // User ID
        $user = User::withoutTrashed()->where('id', $id)->firstOrFail();
        
        // Get all roles from roles table
        $roles = Role::all();
        $user_roles = RoleUser::where('user_id', $id)->get();
        return response()->view('dashboard.users.roles.edit', compact('user_roles', 'user', 'roles'));
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
            'username' => 'required',
            'roles' => 'exists:roles,id', // validating role
        ]);
        $user_id = $input['username'];
        $user = User::withoutTrashed()->where('id', $user_id)->firstOrFail();
        $user_name = $user->name;
        //$old_user_roles = RoleUser::where('user_id', $id)->pluck('role_id')->toArray();
        // OR
        $old_user_roles = DB::table('role_users')->where('user_id', $id)->pluck('role_id')->toArray();

        $user_roles = '';
        if ($request->has('roles') && $request->filled('roles')) {
            $roles_checkbox_array = $input['roles'];
//            dd('user_id is not empty.');
        } else {
            $roles_checkbox_array = [];
//            dd('user_id is empty.');
        }
//        $roles_checkbox_array = $input['roles'];
        $count_roles_checkbox = count($roles_checkbox_array);
        if ($count_roles_checkbox >= 0) {
            foreach ($roles_checkbox_array as $role_element) {
                $role_model = Role::where('id', (int)$role_element)->firstOrFail();
                $user_roles .= ucfirst($role_model->name) . ' , ';
            }
            $user_roles = substr($user_roles, 0, -3);
        }

        // Print Data
        //dd($input, $roles_checkbox_array, $user_roles, $old_user_roles);

        //Save roles for this user
        $user->roles()->sync($roles_checkbox_array, true);

        // Status for Adding the New Role To exist User!
        $alert_status = 'alert-success';
        // Msg
        $msg = 'New User Added Successfully.';
        // Pref
        $pref = "You Edit $user_name User in The System!<br>User ID : {$user_id} ,User roles : $user_roles . ";
        $status = ['alert_status' => $alert_status, 'msg' => $msg, 'pref' => $pref];

        return redirect()->route('dashboard.users.roles.index')->with('status', $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        // User Roles ID
        $user_id = $id;
        $user = User::withoutTrashed()->where('id', $user_id)->firstOrFail();
        $user_name = $user->name;
        $user_roles = "This user doesn't have roles";

        // Status for Deleting all roles for user!
        $alert_status = 'alert-warning';
        // Msg
        $msg = "Delete All Roles for $user_name Successfully.";
        // Pref
        $pref = "You Delete All Roles for User $user_name from the System!<br>User ID : {$user_id} ,User roles : $user_roles . ";
        $status = ['alert_status' => $alert_status, 'msg' => $msg, 'pref' => $pref];

        $user->roles()->detach();

        return redirect()->route('dashboard.users.roles.index')->with('status', $status);
    }
}
