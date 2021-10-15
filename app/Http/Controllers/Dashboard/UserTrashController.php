<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\user;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserTrashController extends Controller
{
    /**
     * UserTrashController constructor.
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
        $users = User::onlyTrashed()->paginate(10);
//        $users = DB::table('users')->whereNotNull('deleted_at')->paginate(10);

        return response()->view('dashboard.users.trash.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(): ?Response
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): ?Response
    {
        //
    }

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function restore(int $id): RedirectResponse
    {
        // Find User ID
        $user = User::onlyTrashed()->findOrFail($id);
        $user_id = $id;
        $user_name = $user->name;
        $user_email = $user->email;
        $alert_status = 'alert-success';
        // Msg
        $msg = "Restore User $user_name Successfully.";
        // Pref
        $pref = "You Restore $user_name User from The System!<br>His ID : {$user_id} ,His Email : $user_email . ";
        $status = ['alert_status' => $alert_status, 'msg' => $msg, 'pref' => $pref];

        /*Restore*/
        $user->restore();
        // Restore in one line
        //User::withTrashed()->find($id)->restore();

        return redirect()->route('dashboard.users.trash.index')->with('status', $status);
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
        $user = User::onlyTrashed()->findOrFail($id);
        return response()->view('dashboard.users.show', ['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit(int $id): ?Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param user $user
     * @return Response
     */
    public function update(Request $request, user $user): ?Response
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        // Find User ID in the trashed
        $user = User::onlyTrashed()->findOrFail($id);
        $user_id = $id;
        $user_name = $user->name;
        $user_email = $user->email;
        $alert_status = 'alert-success';
        // Msg
        $msg = 'Delete User ' . $user_name . ' From The Trashed Successfully.';
        // Pref
        $pref = "You Delete $user_name User from The System!<br>His ID : {$user_id} ,His Email : $user_email . ";
        $status = ['alert_status' => $alert_status, 'msg' => $msg, 'pref' => $pref];

        /* Force Delete (Permanently) To delete from soft-deleted (trashed) data */
        $user->forceDelete();

        // Redirect to User Table
        return redirect()->route('dashboard.users.trash.index')->with('status', $status);
    }
}
