<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
class UserController extends Controller
{
    public function csrftoken(){
        return csrf_token();
    }

   
    //  انشاء مستخدم جديد
    public function register(Request $request)
    {
    
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

     
        return response()->json([
            'message' =>  __('messages.user_created_successfully'),
            'user' => $user,
        ], 201);
    }

    // دالة تسجيل الدخول
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        App::setLocale($request->header('Accept-Language', 'en'));  // تغيير اللغة بناءً على الـ Header

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => __('messages.invalid_credentials')], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['message'=>__('messages.login'),'token' => $token], 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return User::with('posts')->get();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return User::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());

        return response()->json($user, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::destroy($id);

        return response()->json(['message' => __('messages.user_deleted_successfully')], 204);
    }
}