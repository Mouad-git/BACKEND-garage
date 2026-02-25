<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ClientDashboarController extends Controller
{
    public function index($clientId)
    {
        // 1. Prochain RDV (uniquement le plus proche à venir)
        $nextRdv = DB::table('rendez_vous')
            ->where('client_id', $clientId)
            ->where('date_heure', '>=', now())
            ->where('statut', '!=', 'annule')
            ->orderBy('date_heure', 'asc')
            ->first();

        // 2. Nombre de véhicules possédés
        $vehiculesCount = DB::table('vehicules')
        ->join('clients', 'vehicules.client_id', '=', 'clients.id')
        ->where('clients.utilisateur_id', $userId)
        ->count();


        // 3. Suivi Atelier (Dernière réception en cours pour le stepper)
        $lastWork = DB::table('receptions')
        ->join('vehicules', 'receptions.vehicule_id', '=', 'vehicules.id')
        ->join('clients', 'vehicules.client_id', '=', 'clients.id')
        ->where('clients.utilisateur_id', $userId)
        ->select('vehicules.marque', 'vehicules.modele', 'vehicules.immatriculation', 'receptions.statut')
        ->orderBy('receptions.date_reception', 'desc')
        ->first();

        return response()->json([
        'vehicules_count' => $vehiculesCount,
        'last_reception' => $lastWork,
        'next_rdv' => null // Ajoutez votre logique de RDV ici
    ]);
    }
}