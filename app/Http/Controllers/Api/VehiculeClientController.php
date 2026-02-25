<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehiculeClientController extends Controller
{
    
    public function getClientVehicles($clientId)
    {
        $vehicules = DB::select(
            "SELECT * FROM vehicules WHERE client_id = ? ORDER BY id DESC",
            [$clientId]
        );

        return response()->json($vehicules);
    }

    
    public function store(Request $request)
    {
        $id = DB::insert(
            "INSERT INTO vehicules 
            (client_id, immatriculation, marque, modele, kilometrage_actuel, vin) 
            VALUES (?, ?, ?, ?, ?, ?)",
            [
                $request->client_id,
                $request->immatriculation,
                $request->marque,
                $request->modele,
                $request->kilometrage_actuel ?? 0,
                $request->vin ?? null
            ]
        );

        // Pour récupérer le dernier ID inséré
        $lastId = DB::getPdo()->lastInsertId();

        return response()->json([
            'id' => $lastId,
            'message' => 'Véhicule enregistré avec succès'
        ]);
    }

    
    public function destroy($id)
    {
        DB::delete(
            "DELETE FROM vehicules WHERE id = ?",
            [$id]
        );

        return response()->json([
            'message' => 'Véhicule supprimé'
        ]);
    }
}
