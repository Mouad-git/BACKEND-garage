<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
use App\Http\Controllers\Api\ClientController;

Route::get('/clients', [ClientController::class, 'index']);
Route::post('/clients', [ClientController::class, 'store']);
Route::put('/clients/{id}', [ClientController::class, 'update']);
Route::delete('/clients/{id}', [ClientController::class, 'destroy']);

use App\Http\Controllers\Api\VehiculeController;

Route::get('/vehicules', [VehiculeController::class, 'index']);
Route::post('/vehicules', [VehiculeController::class, 'store']);
Route::put('/vehicules/{id}', [VehiculeController::class, 'update']);
Route::delete('/vehicules/{id}', [VehiculeController::class, 'destroy']);

use App\Http\Controllers\Api\DiagnosticController;

Route::get('/receptions-queue', [DiagnosticController::class, 'getQueue']);
Route::post('/diagnostics', [DiagnosticController::class, 'store']);

use App\Http\Controllers\Api\ReceptionController;

Route::get('/search-vehicules', [ReceptionController::class, 'searchVehicules']);
Route::post('/receptions', [ReceptionController::class, 'store']);

use App\Http\Controllers\Api\PieceController;

Route::apiResource('pieces', PieceController::class);

use App\Http\Controllers\Api\TechnicienController;

// Si tu as utilisé apiResource :
Route::apiResource('techniciens', TechnicienController::class);

// OU si tu as écrit les routes manuellement :
Route::get('/techniciens', [TechnicienController::class, 'index']);
Route::post('/techniciens', [TechnicienController::class, 'store']);

use App\Http\Controllers\Api\FournisseurController;

Route::apiResource('fournisseurs', FournisseurController::class);

use App\Http\Controllers\Api\CrmController;


Route::get('/communications', [CrmController::class, 'index']);

// 2. Enregistrer un nouveau message envoyé (SMS ou Email)
Route::post('/communications', [CrmController::class, 'store']);

// 3. Récupérer la liste intelligente des clients à relancer pour le CT
// (Ceux dont la dernière visite date de plus de 2 ans)
Route::get('/ct-reminders', [CrmController::class, 'getCtReminders']);

use App\Http\Controllers\Api\CommandeController;

Route::get('/commandes', [CommandeController::class, 'index']);
Route::post('/commandes', [CommandeController::class, 'store']);
Route::put('/commandes/{id}/reception', [CommandeController::class, 'confirmReception']);

use App\Http\Controllers\Api\DashboardController;

Route::get('/dashboard/stats', [DashboardController::class, 'getStats']);
Route::get('/dashboard/active-jobs', [DashboardController::class, 'getActiveJobs']);
Route::get('/dashboard/low-stock', [DashboardController::class, 'getLowStock']);

use App\Http\Controllers\Api\TachedController;

Route::get('/atelier/kanban', [TachedController::class, 'getKanban']);
Route::put('/ordres/{id}/statut', [TachedController::class, 'updateOrderStatus']);
Route::put('/taches/{id}/toggle', [TachedController::class, 'toggleTask']);

use App\Http\Controllers\Api\DevisController;

// Liste des diagnostics en attente
Route::get('/devis-queue', [DevisController::class, 'getQueue']);

// Enregistrer le devis
Route::post('/devis', [DevisController::class, 'store']);

// Valider le devis (Déclenche OR + Tâches)
Route::put('/devis/{id}/valider', [DevisController::class, 'valider']);

use App\Http\Controllers\Api\FacturationController;

// Liste des ordres de réparation terminés mais pas encore facturés
Route::get('/facturation-queue', [FacturationController::class, 'getQueue']);

// Enregistrer une facture et marquer le paiement
Route::post('/factures', [FacturationController::class, 'store']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



use App\Http\Controllers\Api\ClientDashboarController;
use App\Http\Controllers\Api\ClientAppointmentController;
use App\Http\Controllers\Api\VehiculeClientController;
// Récupérer les véhicules du client ID 1 (Jean Dupont)
Route::get('/client/{clientId}/vehicules', [VehiculeClientController::class, 'getClientVehicles']);
Route::get('/client/{clientId}/dashboard', [ClientDashboarController::class, 'index']);

// Rendez-vous
Route::get('/client/{clientId}/rendez-vous', [ClientAppointmentController::class, 'index']);
Route::post('/rendez-vous', [ClientAppointmentController::class, 'store']);
Route::put('/rendez-vous/{id}/annuler', [ClientAppointmentController::class, 'cancel']);
// CRUD Classique
Route::post('/vehicules', [VehiculeClientController::class, 'store']);
Route::put('/vehicules/{id}', [VehiculeClientController::class, 'update']);
Route::delete('/vehicules/{id}', [VehiculeClientController::class, 'destroy']);

use App\Http\Controllers\Api\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::put('/user/{id}', [AuthController::class, 'updateProfile']);

use App\Http\Controllers\Api\ClientBillingController;

Route::get('/client/{clientId}/devis', [ClientBillingController::class, 'getDevis']);
Route::get('/client/{clientId}/factures', [ClientBillingController::class, 'getFactures']);
Route::put('/devis/{id}/client-action', [ClientBillingController::class, 'updateStatus']);