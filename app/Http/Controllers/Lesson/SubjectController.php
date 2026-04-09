<?php

namespace App\Http\Controllers\Lesson;

use App\Http\Controllers\Controller;
use App\Models\Subject;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SubjectController extends Controller {
    
    public function store (Request $request) {

        $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
        ]);

        $subject                = new Subject();
        $subject->title         = $request->title;
        $subject->description   = $request->description;
        if ($subject->save()) {
            return redirect()->back()->with('success', 'Assunto criado com sucesso!');
        }   

        return redirect()->back()->with('error', 'Falha ao criar o assunto!');
    }

    public function update (Request $request, $id) {

        $subject = Subject::find($id);
        if (!$subject) {
            return redirect()->back()->with('error', 'Assunto não encontrado/disponível!');
        }

        $subject->title         = $request->title;
        $subject->description   = $request->description;
        if ($subject->save()) {
            return redirect()->back()->with('success', 'Assunto atualizado com sucesso!');
        }

        return redirect()->back()->with('error', 'Falha ao atualizar o assunto!');
    }

    public function destroy (Request $request, $id) {

        if (Hash::check($request->password, Auth::user()->password)) {

            $subject = Subject::find($id);
            if ($subject && $subject->delete()) {
                return redirect()->back()->with('success', 'Assunto deletado com sucesso!');
            }

            return redirect()->back()->with('error', 'Falha ao tentar excluir o assunto. Verifique os dados e tente novamente!!');
        }

        return redirect()->back()->with('error', 'Credenciais inválidas. Verifique os dados e tente novamente!!');
    }
}
