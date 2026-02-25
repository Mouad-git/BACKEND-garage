<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TechnicienController extends Controller
{
    public function index() {
        return response()->json(DB::table('techniciens')->where('statut_actif', 1)->get());
    }

    public function store(Request $request) {
        $id = DB::table('techniciens')->insertGetId($request->all());
        return response()->json(['id' => $id, 'message' => 'Technicien créé']);
    }

    public function update(Request $request, $id) {
        DB::table('techniciens')->where('id', $id)->update($request->all());
        return response()->json(['message' => 'Mise à jour réussie']);
    }

    public function destroy($id) {
        // Option : suppression logique (statut_actif = 0)
        DB::table('techniciens')->where('id', $id)->update(['statut_actif' => 0]);
        return response()->json(['message' => 'Technicien archivé']);
    }
}