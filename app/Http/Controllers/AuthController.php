<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function handleLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            $user->update([
                'last_login_at' => now(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => '¡Bienvenido de vuelta!',
                'redirect' => $user->isAdmin() ? route('admin.dashboard') : route('profile.index')
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Las credenciales proporcionadas no coinciden con nuestros registros.'
        ], 401);
    }

    public function handleRegister(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string'],
            'country' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'origin' => ['nullable', 'string'],
        ]);

        $roleEstudiante = Role::where('name', 'estudiante')->first();
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'country' => $request->country,
            'password' => Hash::make($request->password),
            'origin' => $request->origin,
            'role_id' => $roleEstudiante ? $roleEstudiante->id : null,
            'membership' => 'regular',
        ]);

        Auth::login($user);

        // Add welcome achievement
        $user->grades()->create([
            'course_title' => 'Edukan2 Portal',
            'module_name' => 'Bienvenida Mente Divergente',
            'grade' => 100,
            'requires_revision' => false,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => '¡Bienvenido a la comunidad global de Edukan2!',
            'redirect' => route('profile.index')
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Sesión cerrada con éxito.');
    }

    // Google OAuth integration
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // Register a new user
                $roleEstudiante = Role::where('name', 'estudiante')->first();
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(bin2hex(random_bytes(10))), // Random password
                    'role_id' => $roleEstudiante ? $roleEstudiante->id : null,
                    'membership' => 'regular',
                ]);

                // Initial welcome record
                $user->grades()->create([
                    'course_title' => 'Edukan2 Portal',
                    'module_name' => 'Bienvenida Mente Divergente',
                    'grade' => 100,
                    'requires_revision' => false,
                ]);
            }

            Auth::login($user);
            
            $user->update([
                'last_login_at' => now(),
            ]);

            return redirect()->route('profile.index')->with('success', '¡Bienvenido de vuelta!');
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Hubo un problema al autenticarte con Google.');
        }
    }
}
