<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FournisseurController extends Controller
{
    public function index() {
        return response()->json(DB::table('fournisseurs')->orderBy('id', 'desc')->get());
    }

    public function store(Request $request) {
        $id = DB::table('fournisseurs')->insertGetId($request->all());
        return response()->json(['id' => $id, 'message' => 'Fournisseur ajouté']);
    }

    public function update(Request $request, $id) {
        DB::table('fournisseurs')->where('id', $id)->update($request->all());
        return response()->json(['message' => 'Fournisseur mis à jour']);
    }

    public function destroy($id) {
        DB::table('fournisseurs')->where('id', $id)->delete();
        return response()->json(['message' => 'Fournisseur supprimé']);
    }
}