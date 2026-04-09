<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller {
    
    public function index(Request $request) {
        
        $query = User::query();

        if ($request->filled('name')) {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }
        if ($request->filled('cpfcnpj')) {
            $query->where('cpfcnpj', preg_replace('/\D/', '', $request->cpfcnpj));
        }
        if ($request->filled('email')) {
            $query->where('email', $request->email);
        }
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return view('app.User.index', [
            'users' => $query->paginate(30)
        ]);
    }

    public function show ($uuid) {

        $user = User::where('uuid', $uuid)->first();
        if (!$user) {
            return redirect()->back()->with('infor', 'Perfil não encontrado/disponível!');
        }

        return view('app.User.show', [
            'user' => $user
        ]);
    }

    public function update(Request $request, $uuid) {

        $user = User::where('uuid', $uuid)->first();
        if (!$user) {
            return redirect()->back()->with('error', 'Perfil não encontrado/disponível!');
        }

        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->has('cpfcnpj')) {
            $user->cpfcnpj = preg_replace('/[\.\-\/]/', '', $request->cpfcnpj);
        }
        if ($request->has('role')) {
            $user->role = $request->role;
        }
        if ($request->has('phone')) {
            $user->phone = preg_replace('/[\.\-\/]/', '', $request->phone);
        }
        if ($request->has('status')) {
            $user->status = $request->status;
        }
        if ($request->has('password')) {
            if ($request->password !== $request->confirm_password) {
                return redirect()->back()->with('infor', 'As senhas não conferem, verifique os dados e tente novamente!');
            }
            $user->password = bcrypt($request->password);
        }
        if (!empty($request->photo)) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $file     = $request->file('photo');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('profile-images', $filename, 'public');
            $user->avatar = 'profile-images/' . $filename;
        }

        if ($user->save()) {
            return redirect()->back()->with('success', 'Perfil salvo com sucesso!');
        }

        return redirect()->back()->with('error', 'Não foi possível atualizar o Perfil, verifique os dados e tente novamente!');
    }

    public function destroy ($uuid) {

        $user = User::where('uuid', $uuid)->first();
        if ($user && $user->delete()) {
            return redirect()->back()->with('success', 'Perfil deletado com sucesso!');
        }

        return redirect()->back()->with('error', 'Perfil não encontrado/disponível!');
    }
}
