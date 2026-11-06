<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Service;
use App\Models\Utilisateur;
use App\Services\RejetDossierNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // POST /api/register
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $service = Service::first() ?? Service::create([
            'nom_service' => 'Non affecté',
            'description' => 'Service par défaut assigné à l\'inscription',
        ]);

        $utilisateur = Utilisateur::create([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'matricule' => trim((string) ($data['matricule'] ?? '')),
            'mot_de_passe' => Hash::make($data['password']),
            'role' => 'fonctionnaire',
            'id_service' => $service->id_service,
        ]);

        $token = $utilisateur->createToken('auth_token')->plainTextToken;

        $this->notifierRejets($utilisateur);

        return response()->json([
            'utilisateur' => $utilisateur->load('service'),
            'token' => $token,
        ], 201);
    }

    // POST /api/login
    public function login(LoginRequest $request)
    {
        $utilisateur = Utilisateur::where('email', $request->input('email'))->first();

        if (! $utilisateur || ! Hash::check($request->input('password'), (string) $utilisateur->getAuthPassword())) {
            throw ValidationException::withMessages([
                'email' => ['Identifiants incorrects.'],
            ]);
        }

        if ($utilisateur->role === 'fonctionnaire') {
            $enregistre = trim((string) $utilisateur->matricule);

            if ($enregistre !== '') {
                $saisi = trim((string) $request->input('matricule'));

                if ($saisi === '' || $saisi !== $enregistre) {
                    throw ValidationException::withMessages([
                        'matricule' => ['IM (matricule) incorrect ou non associé à ce compte.'],
                    ]);
                }
            }
        }

        $token = $utilisateur->createToken('auth_token')->plainTextToken;

        $this->notifierRejets($utilisateur);

        return response()->json([
            'utilisateur' => $utilisateur->load('service'),
            'token' => $token,
        ]);
    }

    // POST /api/logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Déconnecté avec succès']);
    }

    // GET /api/moi
    public function moi(Request $request)
    {
        return response()->json($request->user()->load('service'));
    }

    private function notifierRejets(Utilisateur $utilisateur): void
    {
        try {
            app(RejetDossierNotifier::class)->notifierPourFonctionnaire($utilisateur);
        } catch (\Throwable $e) {
            Log::warning(
                "Échec de la notification des rejets à la connexion pour {$utilisateur->email} : {$e->getMessage()}"
            );
        }
    }
}