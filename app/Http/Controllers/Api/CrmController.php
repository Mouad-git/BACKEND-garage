<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CrmController extends Controller
{
     // 1. Historique des communications
    public function index()
    {
        $history = DB::table('communications')
            ->leftJoin('clients', 'communications.client_id', '=', 'clients.id')
            ->select('communications.*', 'clients.nom_complet as client_nom')
            ->orderBy('date_envoi', 'desc')
            ->get();
        return response()->json($history);
    }

    // 2. Récupérer les clients pour relance CT (Dernière visite > 2 ans)
    public function getCtReminders()
    {
        $twoYearsAgo = now()->subYears(2);

        $reminders = DB::table('receptions')
            ->join('clients', 'receptions.utilisateur_id', '=', 'clients.id') // Ajuste selon tes clés
            ->join('vehicules', 'receptions.vehicule_id', '=', 'vehicules.id')
            ->select('clients.nom_complet', 'vehicules.immatriculation', 'vehicules.marque', 'vehicules.modele', 'receptions.date_reception')
            ->where('receptions.date_reception', '<=', $twoYearsAgo)
            ->where('receptions.statut', '=', 'termine')
            ->distinct()
            ->get();

        return response()->json($reminders);
    }

    // 3. Enregistrer un message
    public function store(Request $request)
    {
        $id = DB::table('communications')->insertGetId([
            'client_id' => $request->client_id,
            'type'      => $request->type,
            'contenu'   => $request->contenu,
            'date_envoi' => now()
        ]);

        return response()->json(['id' => $id, 'message' => 'Message enregistré']);
    }
}
