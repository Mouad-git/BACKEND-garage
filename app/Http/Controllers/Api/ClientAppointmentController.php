<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientAppointmentController extends Controller
{
    // Liste des RDV du client avec les infos du véhicule
    public function index($clientId)
    {
        $appointments = DB::table('rendez_vous')
            ->join('vehicules', 'rendez_vous.vehicule_id', '=', 'vehicules.id')
            ->where('rendez_vous.client_id', $clientId)
            ->select(
                'rendez_vous.*', 
                'vehicules.marque', 
                'vehicules.modele', 
                'vehicules.immatriculation'
            )
            ->orderBy('date_heure', 'desc')
            ->get();

        return response()->json($appointments);
    }

    // Enregistrer un nouveau RDV
    public function store(Request $request)
    {
        $id = DB::table('rendez_vous')->insertGetId([
            'client_id'    => $request->client_id,
            'vehicule_id'  => $request->vehicule_id,
            'date_heure'   => $request->date . ' ' . $request->heure,
            'type_intervention' => $request->motif,
            'statut'       => 'en_attente',
            // 'notes'     => $request->notes
        ]);

        return response()->json(['id' => $id, 'message' => 'Rendez-vous créé']);
    }

    // Annuler un RDV
    public function cancel($id)
    {
        DB::table('rendez_vous')
            ->where('id', $id)
            ->update(['statut' => 'annule']);

        return response()->json(['message' => 'Rendez-vous annulé']);
    }
}