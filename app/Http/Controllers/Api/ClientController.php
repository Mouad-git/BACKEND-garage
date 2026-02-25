<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Important

class ClientController extends Controller
{
    // LIRE (READ)
    public function index() {
        $clients = DB::table('clients')->orderBy('id', 'desc')->get();
        return response()->json($clients);
    }

    // CRÉER (CREATE)
    public function store(Request $request) {
        $id = DB::table('clients')->insertGetId([
            'nom_complet' => $request->nom_complet,
            'type_client' => $request->type_client,
            'adresse'     => $request->adresse,
            'utilisateur_id' => $request->utilisateur_id,
            'date_enregistrement' => now()
        ]);
        return response()->json(['id' => $id, 'message' => 'Client créé']);
    }

    // MODIFIER (UPDATE)
    public function update(Request $request, $id) {
        DB::table('clients')->where('id', $id)->update([
            'nom_complet' => $request->nom_complet,
            'type_client' => $request->type_client,
            'adresse'     => $request->adresse
        ]);
        return response()->json(['message' => 'Client mis à jour']);
    }

    // SUPPRIMER (DELETE)
    public function destroy($id) {
        DB::table('clients')->where('id', $id)->delete();
        return response()->json(['message' => 'Client supprimé']);
    }
}
