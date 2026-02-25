<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceptionController extends Controller
{
    // 1. Rechercher un véhicule par immatriculation ou nom de client
    public function searchVehicules(Request $request)
    {
        $q = $request->query('q');
        
        $results = DB::table('vehicules')
            ->join('clients', 'vehicules.client_id', '=', 'clients.id')
            ->select('vehicules.*', 'clients.nom_complet as client_nom')
            ->where('vehicules.immatriculation', 'LIKE', "%$q%")
            ->orWhere('clients.nom_complet', 'LIKE', "%$q%")
            ->limit(5)
            ->get();

        return response()->json($results);
    }

    // 2. Enregistrer la réception
    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            // A. Insérer la réception
            DB::table('receptions')->insert([
                'vehicule_id'    => $request->vehicule_id,
                'utilisateur_id' => $request->utilisateur_id,
                'motif_visite'   => $request->motif_visite,
                'statut'         => 'en_attente',
                // On stocke les dommages et photos en JSON
                'photos_json'    => json_encode([
                    'damages' => $request->damages,
                    'fuel'    => $request->fuel,
                    'photos'  => $request->photos // Ici on stockerait les noms de fichiers
                ]),
                'date_reception' => now()
            ]);

            // B. Mettre à jour le kilométrage du véhicule
            DB::table('vehicules')
                ->where('id', $request->vehicule_id)
                ->update(['kilometrage_actuel' => $request->mileage]);
        });

        return response()->json(['message' => 'Réception enregistrée avec succès']);
    }
}