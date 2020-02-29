<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TournamentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tournaments = DB::table('tournament')
            ->where('type', 1)
            ->get();

        return view('tournament.index')->with(['tournaments' => $tournaments]);
    }

    public function personal()
    {
        $tournaments = DB::table('tournament')
            ->where('user_iduser', $_SESSION['user_session']['iduser'])
            ->orWhere('type', 2)
            ->get();

        return view('tournament.personal')->with(['tournaments' => $tournaments]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($_SESSION['user_session']['iduser']) {
            $deports = DB::table('deport')->get();

            return view('tournament.create')->with(['deports' => $deports]);
        } else {
            return view('home');
        }
    }

    public function typeByDeport(Request $request)
    {
        if ($request->ajax()) {

            $typeTournaments = DB::table('tournament_type')
                ->where('deport_iddeport', (int) $request->deportId)
                ->get();

            return response()->json([
                'data' => $typeTournaments,
            ], 200);
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // dd($request);
            $response = DB::table('tournament')->insert([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'date_init' => $request->input('date-init'),
                'date_end' => $request->input('date-end'),
                'tournament_type_idtournament_type' => $request->input('type-tournament'),
                'user_iduser' => $_SESSION['user_session']['iduser'],
                'type' => $request->input('type'),
            ]);

            if ($response) {
                return redirect(route('tournament.personal'));
            } else {
                return redirect(route('tournament.create'))->withErrors(['Error al registrar torneo.']);
            }
        } catch (\Exception $ex) {
            return \back()->withErrors([$ex->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('tournament.info');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
