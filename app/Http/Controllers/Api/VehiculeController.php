<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Très important pour éviter l'erreur 500

class VehiculeController extends Controller
{
    public function index()
    {
        // On récupère les véhicules avec le nom du client en faisant une jointure (facultatif mais utile)
        $vehicules = DB::table('vehicules')
            ->leftJoin('clients', 'vehicules.client_id', '=', 'clients.id')
            ->select('vehicules.*', 'clients.nom_complet as proprietaire_nom')
            ->orderBy('vehicules.id', 'desc')
            ->get();

        return response()->json($vehicules);
    }

    public function store(Request $request)
    {
        $id = DB::table('vehicules')->insertGetId([
            'client_id'          => $request->client_id,
            'immatriculation'    => $request->immatriculation,
            'vin'                => $request->vin,
            'marque'             => $request->marque,
            'modele'             => $request->modele,
            'annee'              => $request->annee,
            'kilometrage_actuel' => $request->kilometrage_actuel,
        ]);

        return response()->json(['id' => $id, 'message' => 'Véhicule ajouté']);
    }

    public function update(Request $request, $id)
    {
        DB::table('vehicules')->where('id', $id)->update([
            'client_id'          => $request->client_id,
            'immatriculation'    => $request->immatriculation,
            'vin'                => $request->vin,
            'marque'             => $request->marque,
            'modele'             => $request->modele,
            'annee'              => $request->annee,
            'kilometrage_actuel' => $request->kilometrage_actuel,
        ]);

        return response()->json(['message' => 'Véhicule mis à jour']);
    }

    public function destroy($id)
    {
        DB::table('vehicules')->where('id', $id)->delete();
        return response()->json(['message' => 'Véhicule supprimé']);
    }
}
