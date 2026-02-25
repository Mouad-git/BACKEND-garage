<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DevisController extends Controller
{
    // 1. Récupérer les diagnostics qui attendent un devis
    public function getQueue()
{
    $queue = DB::table('diagnostics')
        ->join('receptions', 'diagnostics.reception_id', '=', 'receptions.id')
        ->join('vehicules', 'receptions.vehicule_id', '=', 'vehicules.id')
        ->join('clients', 'vehicules.client_id', '=', 'clients.id')
        // On récupère les infos du devis s'il existe
        ->leftJoin('devis', 'diagnostics.id', '=', 'devis.diagnostic_id')
        ->select(
            'diagnostics.id as id_diag',
            'receptions.vehicule_id',
            'vehicules.immatriculation as plaque',
            'vehicules.modele',
            'clients.nom_complet as client',
            'clients.adresse as client_adresse',
            'diagnostics.description as rapport',
            'devis.statut as devis_statut', // Pour l'icône dans la barre
            'devis.id as devis_id'
        )
        ->orderBy('diagnostics.id', 'desc')
        ->get();

    return response()->json($queue);
}

    // 2. Enregistrer un nouveau devis (Brouillon)
    public function store(Request $request)
    {
        $tasks = $request->input('tasks', []);
        $parts = $request->input('parts', []);

        return DB::transaction(function () use ($request, $tasks, $parts) {
            $devisId = DB::table('devis')->insertGetId([
                'diagnostic_id' => $request->diagnostic_id,
                'montant_ht'    => $request->montant_ht,
                'montant_ttc'   => $request->montant_ttc,
                'statut'        => 'brouillon',
                'date_creation' => now()
            ]);

            // Insertion des tâches (Main d'oeuvre)
            foreach ($tasks as $task) {
                if (!empty($task['label'])) {
                    DB::table('lignes_devis')->insert([
                        'devis_id'      => $devisId,
                        'type'          => 'main_doeuvre',
                        'description'   => $task['label'],
                        'quantite'      => $task['hours'],
                        'prix_unitaire' => $task['rate'],
                        'total_ligne'   => $task['hours'] * $task['rate']
                    ]);
                }
            }

            // Insertion des pièces
            foreach ($parts as $part) {
                if (!empty($part['piece_id'])) {
                    DB::table('lignes_devis')->insert([
                        'devis_id'      => $devisId,
                        'type'          => 'piece',
                        'piece_id'      => $part['piece_id'],
                        'description'   => $part['name'],
                        'quantite'      => $part['qty'],
                        'prix_unitaire' => $part['price'],
                        'total_ligne'   => $part['qty'] * $part['price']
                    ]);
                }
            }

            return response()->json(['message' => 'Devis enregistré !', 'id' => $devisId]);
        });
    }

    public function valider($id)
{
    return DB::transaction(function () use ($id) {
        // 1. Récupérer le devis et l'ID véhicule via les jointures
        $infos = DB::table('devis')
            ->join('diagnostics', 'devis.diagnostic_id', '=', 'diagnostics.id')
            ->join('receptions', 'diagnostics.reception_id', '=', 'receptions.id')
            ->select('receptions.vehicule_id')
            ->where('devis.id', $id)
            ->first();

        if (!$infos) return response()->json(['error' => 'Données introuvables'], 404);

        // 2. Créer l'Ordre de Réparation
        $ordreId = DB::table('ordres_reparations')->insertGetId([
            'devis_id'    => $id,
            'vehicule_id' => $infos->vehicule_id,
            'statut'      => 'en_attente',
            'date_debut'  => now()
        ]);

        // 3. Récupérer uniquement les lignes 'main_doeuvre' du devis pour créer les tâches
        $lignes = DB::table('lignes_devis')
            ->where('devis_id', $id)
            ->get();

        foreach ($lignes as $l) {
            if ($l->type === 'main_doeuvre') {
                // INSERT DANS LA TABLE TACHES
                DB::table('taches')->insert([
                    'ordre_id'     => $ordreId,
                    'libelle'      => $l->description,
                    'temps_estime' => $l->quantite,
                    'statut'       => 'en_attente'
                ]);
            } else if ($l->type === 'piece') {
                // DIMINUER LE STOCK
                DB::table('pieces')->where('id', $l->piece_id)->decrement('stock_actuel', $l->quantite);
            }
        }

        // 4. Mettre le devis à jour
        DB::table('devis')->where('id', $id)->update(['statut' => 'valide']);

        return response()->json(['message' => 'OK']);
    });
}
}