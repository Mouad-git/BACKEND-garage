<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommandeController extends Controller
{
    public function index() {
        return response()->json(
            DB::table('commandes_fournisseurs')
                ->join('fournisseurs', 'commandes_fournisseurs.fournisseur_id', '=', 'fournisseurs.id')
                ->select('commandes_fournisseurs.*', 'fournisseurs.nom_societe as fournisseur_nom')
                ->orderBy('id', 'desc')->get()
        );
    }

    public function store(Request $request) {
        return DB::transaction(function () use ($request) {
            // 1. Créer l'entête
            $commandeId = DB::table('commandes_fournisseurs')->insertGetId([
                'fournisseur_id' => $request->fournisseur_id,
                'numero_commande' => 'CMD-' . date('Y') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                'statut' => 'envoye',
                'total_ht' => $request->total_ht,
                'date_commande' => now()
            ]);

            // 2. Créer les lignes
            foreach ($request->lignes as $ligne) {
                DB::table('lignes_commandes_fournisseurs')->insert([
                    'commande_id' => $commandeId,
                    'piece_id' => $ligne['piece_id'],
                    'quantite_commandee' => $ligne['qty'],
                    'prix_achat_unitaire_reel' => $ligne['prix_achat_reel']
                ]);
            }
            return response()->json(['message' => 'Commande envoyée']);
        });
    }

    public function confirmReception($id) {
        return DB::transaction(function () use ($id) {
            // 1. Récupérer les lignes de la commande
            $lignes = DB::table('lignes_commandes_fournisseurs')->where('commande_id', $id)->get();

            foreach ($lignes as $ligne) {
                // 2. Incrémenter le stock actuel dans la table pièces
                DB::table('pieces')->where('id', $ligne->piece_id)->increment('stock_actuel', $ligne->quantite_commandee);
                
                // 3. Mettre à jour le prix d'achat par défaut avec le dernier prix réel
                DB::table('pieces')->where('id', $ligne->piece_id)->update(['prix_achat_defaut' => $ligne->prix_achat_unitaire_reel]);
            }

            // 4. Mettre à jour le statut de la commande
            DB::table('commandes_fournisseurs')->where('id', $id)->update([
                'statut' => 'recu',
                'date_reception_reelle' => now()
            ]);

            return response()->json(['message' => 'Stock mis à jour avec succès']);
        });
    }
}