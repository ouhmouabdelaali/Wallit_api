<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function register(Request $request)
    {
 
        
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            // 'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['user' => $user]);
    }

    // public function login(Request $request)
    // {
    //     try {
    //         $user = User::where('email', $request->email)->first();
    //         if (!$user || !Hash::check($request->password, $user->password)) {
    //             throw ValidationException::withMessages([
    //                 'email' => ['The provided credentials are incorrect.'],
    //             ]);
    //         }
    //         return response()->json([
    //             'token' => $user->createToken('token')->plainTextToken,
    //             'user' => $user
    //         ]);
    //     } catch (ValidationException $e) {
    //         return response()->json(['error' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
    //     }
    // }

    public function login(Request $request)
    {
      
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

      $user = User::where('email', $request->email)->firstOrFail();

        

   $token = $user->createToken('token-name')->plainTextToken;
      return response()->json(['access_token' => $token]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
