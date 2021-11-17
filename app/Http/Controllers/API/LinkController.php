<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\LinksResource;
use App\Models\link;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $user_id = Auth::guard('sanctum')->id();
        $links = link::with('user')->where('user_id', $user_id)->paginate(10);
        /*OR This*/
//        $links = link::where('user_id', Auth::id())->paginate(10);

        return Response::json([
            'links' => $links,
        ], 200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $input = $request->all();

        // Validate inputs
        $validatedData = $request->validate([
            'link' => ['url', 'required', 'unique:links'],
        ]);

        $user_id = Auth::guard('sanctum')->id();
        $link = new link();
        $link->user_id = $user_id;
        $link->link = $validatedData['link'];
        $link->code = Str::random(6);
        $link->short_link = $link->code;
        $link->save();

        // Msg
        $msg = 'New User Added Successfully.';
        // Pref
        $pref = "You Generate new short link $link->short_link As New User To The System! His Code : $link->code ,His Original : $link->link .  ";

        return Response::json([
            'message' => $msg,
            'pref' => $pref,
            'link' => new LinksResource($link),
        ], 200);

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
     * Update the specified resource in storage.
     *
     * @param Request $request
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
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
