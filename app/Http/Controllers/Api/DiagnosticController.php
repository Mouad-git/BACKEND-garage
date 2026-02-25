<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiagnosticController extends Controller
{
    // 1. Récupérer la file d'attente (Réceptions 'en_attente')
    public function getQueue()
{
    $queue = DB::table('receptions')
        ->join('vehicules', 'receptions.vehicule_id', '=', 'vehicules.id')
        // On cherche si un diagnostic existe déjà pour cette réception
        ->leftJoin('diagnostics', 'receptions.id', '=', 'diagnostics.reception_id')
        ->select(
            'receptions.id as id_reception',
            'receptions.motif_visite as motif',
            'receptions.statut as reception_statut',
            'vehicules.immatriculation as plaque',
            'vehicules.marque',
            'vehicules.modele',
            'diagnostics.id as diagnostic_id', // Si existe = déjà fait
            'diagnostics.description as existing_desc',
            'diagnostics.codes_pannes_json as existing_codes'
        )
        // On affiche les réceptions 'en_attente' ET 'diagnostique'
        ->whereIn('receptions.statut', ['en_attente', 'diagnostique'])
        ->orderBy('receptions.id', 'desc')
        ->get();

    return response()->json($queue);
}

    // 2. Enregistrer le diagnostic
    public function store(Request $request)
    {
        // On utilise une transaction pour être sûr que les deux opérations réussissent
        DB::transaction(function () use ($request) {
            
            // A. Insérer dans la table diagnostics
            DB::table('diagnostics')->insert([
                'reception_id'      => $request->reception_id,
                'utilisateur_id'    => $request->utilisateur_id,
                'description'       => $request->description,
                'codes_pannes_json' => $request->codes_pannes_json, // Déjà stringify par React
                'date_diagnostic'   => now()
            ]);

            // B. Mettre à jour le statut de la réception pour qu'elle sorte de la file
            DB::table('receptions')
                ->where('id', $request->reception_id)
                ->update(['statut' => 'diagnostique']);
        });

        return response()->json(['message' => 'Diagnostic enregistré et statut mis à jour']);
    }
}
