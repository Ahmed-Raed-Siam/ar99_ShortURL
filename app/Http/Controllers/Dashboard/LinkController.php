<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\link;
use App\Models\User;
use App\Models\UserHits;
use App\Models\VisitorHits;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use function Sodium\increment;

class LinkController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $user_id = Auth::id();
//        $get_links = link::where('user_id', $user_id)->get();
//        $links = link::with('user')->where('user_id', Auth::id())->paginate(10);
        /*OR This*/
        $links = link::where('user_id', Auth::id())->paginate(10);
        /*OR This*/
//        $links2 = link::with(array('user' => function ($query) use ($user_id) {
//            $query->where('users.id', $user_id);
//        }))->get();

//        dd(
//            $user_id,
//            $get_links,
//            $links,
//            $links2,
//        );
        return response()->view('dashboard.links.index', ['links' => $links]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return response()->view('dashboard.links.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        // Get all inputs from the form
        $input = $request->all();

        // Validate inputs
        $validatedData = $request->validate([
            'link' => ['url', 'required', 'unique:links'],
        ]);

        $user_id = Auth::id();
        $link = new link();
        $link->user_id = $user_id;
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
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
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
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
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
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function public_shorten_link($code, Request $request)
    {
        $find = link::where('code', $code)->first();
        /* Prepare data to insert in Visitor_hits table*/
        $visitor_id = session()->getId();
        $session = $request->getSession(true);
        $session->put('hits', +1);
        $visited_page_id = $find->id; //$request->getUri()
        $hits = $session->get('hits');
        $ip = $request->getClientIp();
        $user_agent = $request->userAgent();
        $visited_at = Carbon::now()->toString();
        $logs_data = [
            'visitor_id' => $visitor_id,
            'visited_page_id' => $visited_page_id,
            'hits' => $hits,
            'ip' => $ip,
            'user_agent' => $user_agent,
            'visited_at' => $visited_at,
        ];

        // Save Data To visitor_hits Table
        $visitor_hits_model = VisitorHits::create($logs_data);

//        dd(
//            $request,
//            $request->userAgent(),
//            $request->getUserInfo(),
//            $request->getUser(),
//            $request->ip(),
//            $request->getClientIp(),
//            $request->getSession(true),
//            $visit_date,
//            $request->getUri(),
//            $find->link,
//            $logs_data,
//            Session::all(),
//            $session,
//            $user_hits,
//        );

//        $user = User::findOrFail($find->user_id);

//        $userLogs = $user->userLogs()
//            ->with('user')
//            ->latest()->get();

//        $visitor_hits_links = $visitor_hits_model->links()->get();

//        $user_hits = $user->userLogs()
//            ->with('user')
//            ->where('visited_page_id', '=', $visited_page_id)
//            ->select('hits')
//            ->latest()
//            ->get()
//            ->count();

//        $visitor_hits_logs = $visitor_hits_model
//            ->with('links')
//            ->where('visited_page_id', '=', $visited_page_id)
//            ->latest()
//            ->get();

        $visitor_hits = $visitor_hits_model
            ->with('links')
            ->where('visited_page_id', '=', $visited_page_id)
            ->select('hits')
            ->latest()
            ->get()
            ->count();

        // Update total_hits Data on Links Table
        $find->total_hits = $visitor_hits;
        $find->save();

//        dd(
//            $visitor_hits_logs,
//            $visitor_hits,
//            $visitor_hits_links,
//            $request,
//            $find,
//            $find->total_hits,
//            $user,
//            $userLogs,
//            $user_hits,
//        );

        return redirect($find->link);
    }

    /**
     * @param $code
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function shortenLink($code, Request $request)
    {
//        $find = link::all()->where('code', $code)->first();
        $find = link::where('code', $code)->first();

        $user = $request->user();

        $session = $request->getSession(true);
        $session->put('hits', +1);
        $user_id = $user->id;
        $visited_page_id = $find->id; //$request->getUri()
        $hits = $session->get('hits');
        $ip = $request->getClientIp();
        $user_agent = $request->userAgent();
        $visited_at = Carbon::now()->toString();
        $logs_data = [
            'user_id' => $user_id,
            'visited_page_id' => $visited_page_id,
            'hits' => $hits,
            'ip' => $ip,
            'user_agent' => $user_agent,
            'visited_at' => $visited_at,
        ];

//        dd(
//            $logs_data,
//            $user->userLogs(),
//            $hits,
//            $user->userLogs,
//            $user->userLogs()->orderBy('visited_at')->get(),
//            $find,
//        );

        /* Check Using UserHits Model to find the Error */
//        $userHits = new UserHits();
//        $userHits->user_id = $user_id;
//        $userHits->visited_page_id = $visited_page_id;
//        $userHits->hits = $hits;
//        $userHits->ip = $ip;
//        $userHits->user_agent = $user_agent;
//        $userHits->visited_at = $visited_at;
//
//        $userHits->save();


        // Save Data to user_hits table
//        $user_hits = $user->userLogs()->create($logs_data);

        // Specify Sql Data to insert
//        $user->userLogs()->create([
//            'user_id' => $user_id,
//            'visited_page_id' => $visited_page_id,
//            'hits' => $hits,
//            'ip' => $ip,
//            'user_agent' => $user_agent,
//            'visited_at' => $visited_at,
//        ]);
//        $find->update(['total_hits' => $hits += $hits]);

        //OR
//        dd(
//            $find,
//            $find->total_hits,
//            $logs_data,
//            $find,
//        );

        // Save Data To user_hits Table
        $user->userLogs()->create($logs_data);

//        dd(
//            $request,
//            $request->userAgent(),
//            $request->getUserInfo(),
//            $request->getUser(),
//            $request->ip(),
//            $request->getClientIp(),
//            $request->getSession(true),
//            $visit_date,
//            $request->getUri(),
//            $find->link,
//            $logs_data,
//            Session::all(),
//            $session,
//            $user_hits,
////            $user_hits->user()->first(),
//        );

        $userLogs = $user->userLogs()
            ->with('user')
            ->latest()
            ->paginate(10);

        $user_hits = $user->userLogs()
            ->with('user')
            ->where('visited_page_id', '=', $visited_page_id)
            ->select('hits')
            ->latest()
            ->get()
            ->count();
//
//        $userLogs3 = $user->userLogs()
//            ->with(
//                ['userLogs' => function ($q) use ($visited_page_id) {
//                    $q->where('visited_page_id', '=', $visited_page_id);
//                }]
//            )
//            ->get();

        // Update total_hits Data on Links Table
        $find->total_hits = $user_hits;
        $find->save();

//        dd(
//            $user,
//            $user->userLogs()->get(),
//            $userLogs,
//            $user_hits,
//            $userLogs3,
//            $find,
//            $find->total_hits,
//        );
        return redirect($find->link);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function userLogs(Request $request)
    {
        $user = $request->user();
        $userLogs = $user->userLogs()
            ->with('user')
            ->Join('links', 'user_hits.visited_page_id', '=', 'links.id')
            ->latest('visited_at')
            ->paginate(10);

//        $visitor_hits = VisitorHits::with('links')
//            ->Join('links', 'visitor_hits.visited_page_id', '=', 'links.id')
//            ->latest('visited_at')
//            ->paginate(10);

//        dd(
//            $userLogs,
//            $userLogs->first(),
//        );
        return response()->view('dashboard.users.logs.index', ['userLogs' => $userLogs]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function clearLogs(Request $request): RedirectResponse
    {
        $user = $request->user();
//        $userLogs = $user->userLogs()
//            ->with('user')
//            ->delete()
//        ;

        if ($user->userLogs()->exists() === false && VisitorHits::with('links')->exists() === false) {

//            $userLogs = $user->userLogs()
//                ->with('user')
//                ->Join('links', 'user_hits.visited_page_id', '=', 'links.id')
//                ->latest('visited_at')
//                ->paginate(10);
            // Status for Clearing User Logs From The System!
            $alert_status = 'alert-warning';
            // Msg
            $msg = "There No Logs To Clear.";
            // Pref
            $pref = "There No Logs To Clear !<br>";
            $status = ['alert_status' => $alert_status, 'msg' => $msg, 'pref' => $pref];

//            $find = link::where('code', $code)->first();

//            dd(
//                $user,
//                $userLogs,
//                'Fails'
//            );

            return redirect()->route('dashboard.user.logs')->with('status', $status);
        }

        // Status for Clearing User Logs From The System!
        $alert_status = 'alert-danger';
        // Msg
        $msg = "You Clear all Your Logs.";
        // Pref
        $pref = "You Clear all Your Logs !<br>";
        $status = ['alert_status' => $alert_status, 'msg' => $msg, 'pref' => $pref];

        $userLogs = $user->userLogs()
            ->with('user')
            ->Join('links', 'user_hits.visited_page_id', '=', 'links.id')
            ->latest('visited_at')
            ->update(['links.total_hits' => 0]);

        $visitor_hits = VisitorHits::with('links')
            ->Join('links', 'visitor_hits.visited_page_id', '=', 'links.id')
            ->latest()
            ->update(['links.total_hits' => 0]);


        if ($user->userLogs()->exists()) {
            $user->userLogs()->delete();
        }

        if (VisitorHits::with('links')->exists()) {
            VisitorHits::with('links')->delete();
        }

//        dd(
//            $user,
//            $userLogs,
//            'Success'
//        );
        return redirect()->route('dashboard.user.logs')->with('status', $status);


    }

}
