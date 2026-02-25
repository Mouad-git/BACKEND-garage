<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientBillingController extends Controller
{
     public function getDevis($userId) 
    {
        return response()->json(
            DB::table('devis')
                ->join('diagnostics', 'devis.diagnostic_id', '=', 'diagnostics.id')
                ->join('receptions', 'diagnostics.reception_id', '=', 'receptions.id')
                ->join('vehicules', 'receptions.vehicule_id', '=', 'vehicules.id')
                ->join('clients', 'vehicules.client_id', '=', 'clients.id') // On lie la table clients
                ->where('clients.utilisateur_id', $userId) // On filtre par l'ID de l'utilisateur connecté
                ->select('devis.*', 'vehicules.marque', 'vehicules.modele', 'vehicules.immatriculation')
                ->orderBy('devis.date_creation', 'desc')
                ->get()
        );
    }

    // Récupérer les factures du client connecté
    public function getFactures($userId)
    {
        return response()->json(
            DB::table('factures')
                ->join('clients', 'factures.client_id', '=', 'clients.id')
                ->join('ordres_reparations', 'factures.ordre_id', '=', 'ordres_reparations.id')
                ->join('vehicules', 'ordres_reparations.vehicule_id', '=', 'vehicules.id')
                ->where('clients.utilisateur_id', $userId) // Filtrage par utilisateur
                ->select('factures.*', 'vehicules.marque', 'vehicules.modele', 'vehicules.immatriculation')
                ->orderBy('factures.date_facture', 'desc')
                ->get()
        );
    }

    // Action du client sur le devis (Accepter/Refuser)
    public function updateStatus(Request $request, $id)
    {
        $request->validate(['statut' => 'required|in:envoye,refuse']);

        DB::table('devis')->where('id', $id)->update([
            'statut' => $request->statut
        ]);

        return response()->json(['message' => 'Statut du devis mis à jour']);
    }
}