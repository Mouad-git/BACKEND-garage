<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TachedController extends Controller
{
    // 1. Récupérer les ordres et leurs tâches pour le Kanban
    public function getKanban()
{
    $orders = DB::table('ordres_reparations')
        ->leftJoin('vehicules', 'ordres_reparations.vehicule_id', '=', 'vehicules.id')
        ->leftJoin('clients', 'vehicules.client_id', '=', 'clients.id') // Jointure pour le nom du client
        ->select(
            'ordres_reparations.id',
            'ordres_reparations.statut',
            'vehicules.immatriculation as plaque',
            'clients.nom_complet as client' // On récupère le nom du client ici
        )
        ->orderBy('ordres_reparations.id', 'desc')
        ->get();

    foreach ($orders as $order) {
        $order->taches = DB::table('taches')->where('ordre_id', $order->id)->get();
    }

    return response()->json($orders);
}

    // 2. Mettre à jour le statut de l'OR (Quand on glisse une carte dans une autre colonne)
    public function updateOrderStatus(Request $request, $id)
    {
        // On vérifie que le statut envoyé correspond aux colonnes du Kanban React
        // ('en_attente', 'en_cours', 'controle_qualite', 'termine')
        DB::table('ordres_reparations')
            ->where('id', $id)
            ->update(['statut' => $request->statut]);

        return response()->json(['message' => 'Statut OR mis à jour']);
    }

    // 3. Cocher/Décocher une tâche (Case à cocher dans la carte)
    public function toggleTask(Request $request, $id)
    {
        $current = DB::table('taches')->where('id', $id)->first();
        
        if (!$current) {
            return response()->json(['error' => 'Tâche introuvable'], 404);
        }

        // Si le statut est 'termine', on repasse en 'en_attente', et inversement
        $newStatut = ($current->statut === 'termine') ? 'en_attente' : 'termine';

        DB::table('taches')
            ->where('id', $id)
            ->update(['statut' => $newStatut]);

        return response()->json(['statut' => $newStatut]);
    }
}