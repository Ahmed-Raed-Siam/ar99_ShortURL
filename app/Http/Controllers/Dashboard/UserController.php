<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\user;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * UserController constructor.
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
        //
//        $users = DB::table('users')->paginate(10);
        $users = DB::table('users')->whereNull('deleted_at')->paginate(10);
//        $users = User::withTrashed();
//        $users = User::withoutTrashed()->paginate(10);
        return response()->view('dashboard.users.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return RedirectResponse|Response
     */
    public function create()
    {
//        if (Gate::denies('creat-edit-delete_users')) {
//            return redirect()->route('dashboard.users.index');
//        }
        $roles = Role::all();
        return response()->view('dashboard.users.create', ['roles' => $roles]);
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
            'name' => 'required|unique:users',
            'roles' => 'exists:roles,id',
            'email' => 'required|unique:users',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required|same:password'
        ]);

        // Create instance from user model
        $user = new user();
        $user_name = ucwords($input['name']);
        $email = $input['email'];
        $password = $input['password'];
        $user->name = strtolower($user_name);
        $user->email = $email;
//        $user->password = $password;
//        $user->password = Hash::make($password) . 'seeYou';
        $user->password = Hash::make($password);

        //print_r($input);
        //dd($input);

        // Add a role to a user
//        $user->roles()->attach($role_id);
        // Insert in Users Table--Database
        $user->save();

        // Check is there is any role for this user
        if ($request->has('roles') && $request->filled('roles')) {
            $roles_checkbox_array = $input['roles'];
        } else {
            $roles_checkbox_array = [];
        }

        $count_roles_checkbox = count($roles_checkbox_array);
        $user_roles = '';
        $roles_can_save = 0;
        if ($count_roles_checkbox >= 0) {
            $roles_can_save = 1;
            foreach ($roles_checkbox_array as $role_element) {
                $role_model = Role::where('id', (int)$role_element)->firstOrFail();
                $user_roles .= ucfirst($role_model->name) . ' , ';
            }
            $user_roles = substr($user_roles, 0, -3);
        }

        // Save User Roles if founded
        if ($roles_can_save === 1) {
            $user->roles()->attach($roles_checkbox_array);
        }

        // Get Last ID || User ID
        $user_id = User::all()->last();

        // Status for Adding the New User To The System!
        $alert_status = 'alert-success';
        // Msg
        $msg = 'New User Added Successfully.';
        // Pref
        $pref = "You Add $user_name As New User To The System!<br>His ID : {$user_id['id']} ,His Email : $email . User roles : $user_roles . ";
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
        // Find User ID
        $user = User::withoutTrashed()->findOrFail($id);
//        $user_roles = User::withoutTrashed()->whereHas('roles')->where('users.id', $id)->firstOrFail()->roles()->get();
//        $user_roles = User::withoutTrashed()->whereHas('roles')->firstOrFail()->roles()->get();
//        $user_roles = $user->whereHas('roles')->find($id)->roles()->get();
        $user_roles = $user->whereHas('roles')->find($id);
        /*        $count_roles_array = count($roles_array);
                $user_roles = '';
                $roles_can_save = 0;
                if ($count_roles_array >= 0) {
                    $roles_can_save = 1;
                    foreach ($roles_array as $role_element) {
                        $role_model = Role::where('id', (int)$role_element)->firstOrFail();
                        $user_roles .= ucfirst($role_model->name) . ' , ';
                    }
                    $user_roles = substr($user_roles, 0, -3);
                }*/
        if (is_null($user_roles)) {

            //echo "<hr>";
            //echo "<h1>Null</h1>";
            //echo "<hr>";
            return response()->view('dashboard.users.show', ['user' => $user]);

        }
        $user_roles = $user_roles->roles()->get();
        //echo "<hr>";
        //echo "<h1>Exists</h1>";
        //echo "<hr>";
        return response()->view('dashboard.users.show', ['user' => $user, 'user_roles' => $user_roles]);
//        dd($user, $user_roles);
//        dd($user, $user_roles, count($user_roles));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit(int $id): Response
    {
        // Find User ID
        $user = User::withoutTrashed()->findOrFail($id);
        $roles = Role::all();
        $user_roles = RoleUser::where('user_id', $id)->get();
        return response()->view('dashboard.users.edit', ['user' => $user, 'roles' => $roles, 'user_roles' => $user_roles]);
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
        // Get All Inputs From the form
        $inputs = $request->all();

        // Validate inputs
        $request->validate([
            'username' => 'required',
            'roles' => 'exists:roles,id',
            'email' => 'required',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required|same:password'
        ]);

        // Find User ID
        $user = User::withoutTrashed()->findOrFail($id);
        $user_name = ucwords($inputs['username']);
        $email = $inputs['email'];
        $password = $inputs['password'];
        $user->name = strtolower($user_name);
        $user->email = $email;
//        $user->password = $password;
        $user->password = Hash::make($password);
//        $user->password = Hash::make($password) . 'seeYou';

//        dd($input);

        // Insert in Database
        $user->save();

        // Check is there is any role for this user
        if ($request->has('roles') && $request->filled('roles')) {
            $roles_checkbox_array = $inputs['roles'];
        } else {
            $roles_checkbox_array = [];
        }

        $count_roles_checkbox = count($roles_checkbox_array);
        $user_roles = '';
        $roles_can_save = 0;
        if ($count_roles_checkbox >= 0) {
            $roles_can_save = 1;
            foreach ($roles_checkbox_array as $role_element) {
                $role_model = Role::where('id', (int)$role_element)->firstOrFail();
                $user_roles .= ucfirst($role_model->name) . ' , ';
            }
            $user_roles = substr($user_roles, 0, -3);
        }

        // Save User Roles if founded
        if ($roles_can_save === 1) {
            $user->roles()->sync($roles_checkbox_array, true);
        }

        // Status for Editing the User in The System!
        $alert_status = 'alert-success';
        // Msg
        $msg = "Edit User $user_name Successfully.";
        // Pref
        $pref = "You Edit $user_name User in The System!<br>His ID : {$id} ,His Email : $email . User roles : $user_roles . ";
        $status = ['alert_status' => $alert_status, 'msg' => $msg, 'pref' => $pref];

        return redirect()->route('dashboard.users.index')->with('status', $status);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        // Find User ID
        $user = User::findOrFail($id);
        $user_id = $id;
        $user_name = ucwords($user->name);
        $user_email = $user->email;

        // Status for Deleting This User from The System!
        $alert_status = 'alert-warning';
        // Msg
        $msg = "Delete User $user_name Successfully.";
        // Pref
        $pref = "You Delete $user_name User from The System!<br>His ID : {$user_id} ,His Email : $user_email . ";
        $status = ['alert_status' => $alert_status, 'msg' => $msg, 'pref' => $pref];

        $user->delete();
        // or
//        $user->destory();

        return redirect()->route('dashboard.users.index')->with('status', $status);
    }

    /*Trash*/
//    /**
//     * Display a listing of the resource.
//     *
//     * @return Response
//     */
//    public function users_trash_index(): Response
//    {
//        $users = User::onlyTrashed()->paginate(10);
////        $users = DB::table('users')->whereNotNull('deleted_at')->paginate(10);
//
//        return response()->view('dashboard.users.trash.index', ['users' => $users]);
//    }
//
//    /**
//     * @param int $id
//     * @return RedirectResponse
//     */
//    public function restore_user(int $id): RedirectResponse
//    {
//        // Find User ID
//        $user = User::onlyTrashed()->findOrFail($id);
//        $user_id = $id;
//        $user_name = $user->name;
//        $user_email = $user->email;
//        $alert_status = 'alert-success';
//        // Msg
//        $msg = "Restore User $user_name Successfully.";
//        // Pref
//        $pref = "You Restore $user_name User from The System!<br>His ID : {$user_id} ,His Email : $user_email . ";
//        $status = ['alert_status' => $alert_status, 'msg' => $msg, 'pref' => $pref];
//
//        /*Restore*/
//        $user->restore();
//        // Restore in one line
//        //User::withTrashed()->find($id)->restore();
//
//        return redirect()->route('dashboard.users.trash.index')->with('status', $status);
//    }
//
//    /**
//     * Display the specified resource.
//     *
//     * @param int $id
//     * @return Response
//     */
//    public function view_user_in_trash(int $id): Response
//    {
//        // Find User ID
//        $user = User::onlyTrashed()->findOrFail($id);
//        return response()->view('dashboard.users.trash.show', ['user' => $user]);
//    }
//
//    /**
//     * Remove the specified resource from storage.
//     *
//     * @param int $id
//     * @return RedirectResponse
//     */
//    public function delete_from_trash(int $id): RedirectResponse
//    {
//        // Find User ID in the trashed
//        $user = User::onlyTrashed()->findOrFail($id);
//        $user_id = $id;
//        $user_name = $user->name;
//        $user_email = $user->email;
//        $alert_status = 'alert-success';
//        // Msg
//        $msg = 'Delete User ' . $user_name . ' From The Trashed Successfully.';
//        // Pref
//        $pref = "You Delete $user_name User from The System!<br>His ID : {$user_id} ,His Email : $user_email . ";
//        $status = ['alert_status' => $alert_status, 'msg' => $msg, 'pref' => $pref];
//
//        /* Force Delete (Permanently) To delete from soft-deleted (trashed) data */
//        $user->forceDelete();
//
//        // Redirect to User Table
//        return redirect()->route('dashboard.users.trash..index')->with('status', $status);
//    }

}
