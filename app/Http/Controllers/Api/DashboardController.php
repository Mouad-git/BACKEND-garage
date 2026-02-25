<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getStats()
    {
        // 1. Chiffre d'affaires (Somme des factures)
        // Note: Ajustez le nom de la colonne montant selon votre table
        $ca = DB::table('factures')->sum('montant_total_ttc') ?? 0;

        // 2. Véhicules en atelier (Ordres de réparation non terminés)
        $atelierCount = DB::table('ordres_reparations')
            ->where('statut', '!=', 'termine')
            ->count();

        // 3. Alertes pièces (Stock <= Seuil)
        $stockAlertsCount = DB::table('pieces')
            ->whereColumn('stock_actuel', '<=', 'seuil_alerte')
            ->count();

        // 4. RDV aujourd'hui
        $rdvToday = DB::table('rendez_vous')
            ->whereDate('date_heure', Carbon::today())
            ->count();

        return response()->json([
            'ca' => number_format($ca, 2, ',', ' ') . ' €',
            'atelier' => $atelierCount,
            'stock' => $stockAlertsCount,
            'rdv' => $rdvToday
        ]);
    }

    public function getActiveJobs()
{
    $jobs = DB::table('ordres_reparations')
        // On lie l'OR au véhicule
        ->join('vehicules', 'ordres_reparations.vehicule_id', '=', 'vehicules.id')
        // On récupère le nom du technicien s'il existe (via utilisateur_id ou technicien_id)
        // Vérifie bien si ta table techniciens a une colonne 'nom'
        ->leftJoin('techniciens', 'ordres_reparations.id', '=', 'techniciens.id') 
        ->select(
            'ordres_reparations.id',
            'vehicules.immatriculation as plaque',
            'vehicules.modele',
            'techniciens.nom as mecanicien', 
            'ordres_reparations.statut'
        )
        ->where('ordres_reparations.statut', '!=', 'termine')
        ->get();

    return response()->json($jobs);
}

    public function getLowStock()
    {
        $pieces = DB::table('pieces')
            ->whereColumn('stock_actuel', '<=', 'seuil_alerte')
            ->select('reference as ref', 'stock_actuel as stock', 'seuil_alerte as seuil')
            ->limit(5)
            ->get();

        return response()->json($pieces);
    }
}