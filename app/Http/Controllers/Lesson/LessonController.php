<?php

namespace App\Http\Controllers\Lesson;

use App\Http\Controllers\Controller;
use App\Models\Block;
use App\Models\Lesson;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LessonController extends Controller {
    
    public function index(Request $request) {

        $query = Lesson::query();

        if (Auth::user()->role == 'instructor') {
            $query->where('teacher_id', Auth::id());
        }

        if (!empty(request('search'))) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
            });
        }

        if (!empty($request->teacher_id)) {
            $query->where('teacher_id', $request->teacher_id);
        }

        if (!empty($request->created_by)) {
            $query->where('created_by', $request->created_by);
        }

        if (!empty($request->subject_id)) {
            $query->where('subject_id', $request->subject_id);
        }

        return view('app.Lesson.index', [
            'lessons' => $query->latest()->paginate(30)->withQueryString(),
            'teachers' => User::where('role', 'instructor')->select('id', 'name')->get(),
            'creators' => User::whereIn('role', ['admin', 'instructor'])->select('id', 'name')->get(),
            'subjects' => Subject::select('id', 'title', 'description')->orderBy('title')->get(),
        ]);
    }

    public function show (Request $request, $uuid) {
        
        $lesson = Lesson::where('uuid', $uuid)->first();
        if (!$lesson) {
            return redirect()->back()->with('error', 'Aula não encontrada/disponível!');
        }

        $blocks = Block::where('lesson_id', $lesson->id);
        if ($request->has('type')) {
            $blocks = $blocks->where('type', $request->type);
        }
        if ($request->has('search')) {
            $blocks = $blocks->where('content', 'like', "%{$request->search}%");
        }

        return view('app.Lesson.show', [
            'lesson' => $lesson,
            'blocks' => $blocks->orderBy('order', 'asc')->get(),
        ]);
    }

    public function store (Request $request) {

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $lesson                 = new Lesson();
        $lesson->uuid           = Str::uuid();
        $lesson->title          = $request->title;
        $lesson->description    = $request->description;
        $lesson->created_by     = $request->created_by ?? Auth::id();
        $lesson->teacher_id     = $request->teacher_id ?? Auth::id();
        $lesson->subject_id     = $request->subject_id ?? null;
        if ($lesson->save()) {
            return redirect()->route('lesson', ['uuid' => $lesson->uuid])->with('success', 'Aula criada com sucesso!');
        }

        return redirect()->back()->with('error', 'Falha ao criar a aula. Verifique os dados e tente novamente!!');
    }

    public function update (Request $request, $uuid) {

        $lesson = Lesson::where('uuid', $uuid)->first();
        if (!$lesson) {
            return redirect()->back()->with('error', 'Aula não encontrada/disponível!');
        }

        $lesson->title          = $request->title;
        $lesson->description    = $request->description;
        $lesson->created_by     = $request->created_by ?? Auth::id();
        $lesson->teacher_id     = $request->teacher_id ?? null;
        $lesson->subject_id     = $request->subject_id ?? null;
        if ($lesson->save()) {
            return redirect()->back()->with('success', 'Aula atualizada com sucesso!');
        }

        return redirect()->back()->with('error', 'Falha ao atualizar a aula. Verifique os dados e tente novamente!!');
    }

    public function destroy (Request $request, $uuid) {

        if (Hash::check($request->password, Auth::user()->password)) {

            $lesson = Lesson::where('uuid', $uuid)->first();
            if ($lesson &&$lesson->delete()) {
                return redirect()->back()->with('success', 'Aula deletada com sucesso!');
            }

            return redirect()->back()->with('error', 'Falha ao tentar excluir a aula. Verifique os dados e tente novamente!!');
        }

        return redirect()->back()->with('error', 'Credenciais inválidas. Verifique os dados e tente novamente!!');
    }
}
