<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacturationController extends Controller
{
    public function getQueue()
{
    $queue = DB::table('ordres_reparations')
        ->join('devis', 'ordres_reparations.devis_id', '=', 'devis.id')
        ->join('vehicules', 'ordres_reparations.vehicule_id', '=', 'vehicules.id')
        ->join('clients', 'vehicules.client_id', '=', 'clients.id')
        // On récupère les infos de la facture SI elle existe
        ->leftJoin('factures', 'ordres_reparations.id', '=', 'factures.ordre_id')
        ->where('ordres_reparations.statut', '=', 'termine')
        ->select(
            'ordres_reparations.id as ordre_id',
            'devis.id as id_devis',
            'clients.id as client_id',
            'clients.nom_complet as client_nom',
            'clients.adresse as client_adresse',
            'vehicules.immatriculation as plaque',
            'vehicules.modele',
            'vehicules.vin',
            'devis.montant_ht',
            'devis.montant_ttc',
            'factures.numero_facture', // Sera null si pas payé
            'factures.mode_paiement as paid_mode'
        )
        ->orderBy('ordres_reparations.id', 'desc')
        ->get();

    return response()->json($queue);
}

    // 2. Générer la facture et enregistrer le paiement
    public function store(Request $request)
    {
        return DB::transaction(function () use ($request) {
            // A. Génération du numéro de facture automatique
            $year = date('Y');
            $lastInvoice = DB::table('factures')->whereYear('date_facture', $year)->count();
            $numeroFacture = "FA-" . $year . "-" . str_pad($lastInvoice + 1, 4, '0', STR_PAD_LEFT);

            // B. Insertion dans la table factures
            $invoiceId = DB::table('factures')->insertGetId([
                'ordre_id'          => $request->ordre_id,
                'client_id'         => $request->client_id,
                'numero_facture'    => $numeroFacture,
                'montant_total_ttc' => $request->montant_total_ttc,
                'mode_paiement'     => $request->mode_paiement, // CB, Espèces, etc.
                'statut_paiement'   => 'paye',
                'date_facture'      => now()
            ]);

            return response()->json([
                'message' => 'Facture générée avec succès',
                'numero_facture' => $numeroFacture,
                'id' => $invoiceId
            ]);
        });
    }
}