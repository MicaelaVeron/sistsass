<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }
        $organizations = $user ? \App\Models\User::getUserOrganizations($user->id) : [];
        return response()->json([
            'token' => $user->createToken('vue-auth')->plainTextToken,
            'user' => $user,
            'organizations' => $organizations
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function updateContext(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('updateContext called', $request->all());

        $request->validate([
            'last_organization_id' => 'required|exists:organizations,id',
            'last_role_id' => 'required|exists:roles,id',
        ]);

        $user = $request->user();
        \Illuminate\Support\Facades\Log::info('User before update:', $user->toArray());

        $user->fill([
            'last_organization_id' => $request->last_organization_id,
            'last_role_id' => $request->last_role_id,
        ]);

        if ($user->save()) {
            \Illuminate\Support\Facades\Log::info('User saved successfully:', $user->toArray());
        } else {
            \Illuminate\Support\Facades\Log::error('User save failed');
        }

        return response()->json(['message' => 'Contexto actualizado correctamente']);
    }
}
