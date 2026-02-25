<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // Important


class AuthController extends Controller {
    public function register(Request $request)
    {
        // 1. Validation des données
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|string|email|unique:users',
            'password'   => 'required|string|min:8',
            'role'       => 'required|in:user,admin,premium',
        ]);

        // 2. Utilisation d'une transaction pour l'intégrité des données
        return DB::transaction(function () use ($request) {
            
            // A. Insertion dans la table `users`
            $userId = DB::table('users')->insertGetId([
                'name'       => $request->first_name . ' ' . $request->last_name,
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'email'      => $request->email,
                'phone'      => $request->phone,
                'role'       => $request->role,
                'password'   => Hash::make($request->password),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // B. CONDITION : Si le rôle est 'user', on insère dans la table `clients`
            if ($request->role === 'user' || $request->role === 'premium') {
    DB::table('clients')->insert([
        'utilisateur_id'      => $userId, 
        'nom_complet'         => $request->first_name . ' ' . $request->last_name,
        'type_client'         => ($request->role === 'premium') ? 'professionnel' : 'particulier',
        'adresse'             => $request->adresse ?? 'Non renseignée',
        'date_enregistrement' => now()
    ]);
}

            // C. Génération du Token (nécessite le modèle User)
            $user = User::find($userId);
            $token = $user->createToken('token')->plainTextToken;

            return response()->json([
                'user'  => $user,
                'message' => 'Inscription réussie'
            ]);
        });
    }

    public function login(Request $request) {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Identifiants invalides'], 401);
        }
        return response()->json(['token' => $user->createToken('token')->plainTextToken, 'user' => $user]);
    }

    public function updateProfile(Request $request, $id)
{
    DB::table('users')->where('id', $id)->update([
        'first_name' => $request->first_name,
        'last_name'  => $request->last_name,
        'phone'      => $request->phone,
        'name'       => $request->first_name . ' ' . $request->last_name,
        'updated_at' => now(),
    ]);

    return response()->json(['message' => 'Profil mis à jour']);
}
}