<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PieceController extends Controller
{
    public function index() {
        return response()->json(DB::table('pieces')->get());
    }

    public function store(Request $request) {
        $id = DB::table('pieces')->insertGetId($request->all());
        return response()->json(['id' => $id, 'message' => 'Pièce ajoutée']);
    }

    public function update(Request $request, $id) {
        DB::table('pieces')->where('id', $id)->update($request->all());
        return response()->json(['message' => 'Stock mis à jour']);
    }

    public function destroy($id) {
        DB::table('pieces')->where('id', $id)->delete();
        return response()->json(['message' => 'Supprimé']);
    }
}
