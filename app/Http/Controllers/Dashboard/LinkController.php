<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\link;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LinkController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $links = link::paginate(10);
//        $users = User::withTrashed();
//        $users = User::withoutTrashed()->paginate(10);
        return response()->view('dashboard.links.index', ['links' => $links]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->view('dashboard.links.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Get all inputs from the form
        $input = $request->all();

        // Validate inputs
        $validatedData = $request->validate([
            'link' => ['url', 'required', 'unique:links'],
        ]);

        $link=new link();
        $link->link = $validatedData['link'];
        $link->code = Str::random(6);
        $link->short_link = $link->code;
        $link->save();

        // Status for Adding the New User To The System!
        $alert_status = 'alert-success';
        // Msg
        $msg = 'New User Added Successfully.';
        // Pref
        $pref = "You Generate new short link $link->short_link As New User To The System!<br>His Code : $link->code ,His Orginal : $link->link .  ";
        $status = ['alert_status' => $alert_status, 'msg' => $msg, 'pref' => $pref];

//        dd($validatedData);
        return redirect()->back()->with('status', $status);


    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Link ID
        $link = link::findOrFail($id);
        $link_id = $id;
        $short_link = $link->short_link;
        $original_link = $link->link;

        // Status for Adding the New Role To The System!
        $alert_status = 'alert-warning';
        // Msg
        $msg = "Delete Short Link $short_link Successfully.";
        // Pref
        $pref = "You Delete the $short_link Link From The System!<br>His Orginal link : {$original_link} . ";
        $status = ['alert_status' => $alert_status, 'msg' => $msg, 'pref' => $pref];

        $link->delete();

        return redirect()->route('dashboard.links.index')->with('status', $status);
    }

    /**
     * @param $code
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function shortenLink($code)
    {
//        $find = link::all()->where('code', $code)->first();
        $find = link::where('code', $code)->first();
//        dd($find->link);

        return redirect($find->link);
    }

}
