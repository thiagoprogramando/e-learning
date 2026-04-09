<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseController extends Controller {
    
    public function index(Request $request) {

        $query = Course::query();
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

        return view('app.Course.index', [
            'courses'   => $query->latest()->paginate(30)->withQueryString(),
            'teachers'  => User::where('role', 'instructor')->select('id', 'name')->get(),
            'creators'  => User::whereIn('role', ['admin', 'instructor'])->select('id', 'name')->get(),
            'lessons'   => Lesson::select('id', 'title')->get(),
        ]);
    }

    public function store (Request $request) {

        $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'thumbnail'     => 'nullable|image|max:2048',
            'duration'      => 'nullable|numeric',
            'time'          => 'nullable|string|in:monthly,quarterly,semi-annual,annual,lifetime',
            'teacher_id'    => 'nullable|exists:users,id',
        ], [
            'title.required'        => 'O título é obrigatório.',
            'title.string'          => 'O título deve ser um texto válido.',
            'title.max'             => 'O título pode ter no máximo 255 caracteres.',
            'description.string'    => 'A descrição deve ser um texto válido.',
            'thumbnail.image'       => 'O arquivo deve ser uma imagem válida.',
            'thumbnail.max'         => 'A imagem deve ter no máximo 2MB.',
            'duration.numeric'      => 'A duração deve ser um número.',
            'time.in'               => 'O período deve ser: mensal, trimestral, semestral, anual ou vitalício.',
            'teacher_id.exists'     => 'O professor selecionado é inválido.',
        ]);

        $course                 = new Course();
        $course->uuid           = Str::uuid();
        $course->title          = $request->title;
        $course->description    = $request->description;
        $course->value          = $this->formatValue($request->value);
        $course->duration       = $request->duration;
        $course->time           = $request->time;
        $course->teacher_id     = $request->teacher_id;
        $course->created_by     = Auth::id();
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('thumbnails', 'public');
            $course->thumbnail = Storage::url($path);
        }
        
        if ($course->save()) {
            return redirect()->back()->with('success', 'Curso criado com sucesso!');
        }

        return redirect()->back()->with('error', 'Falha ao cadastrar curso, verifique os dados e tente novamente!');
    }

    public function update (Request $request, $uuid) {

        $course = Course::where('uuid', $uuid)->first();
        if (!$course) {
            return redirect()->back()->with('error', 'Curso não encontrado/disponível!');
        }

        $course->title          = $request->title;
        $course->description    = $request->description;
        $course->value          = $this->formatValue($request->value);
        $course->duration       = $request->duration;
        $course->time           = $request->time;
        $course->teacher_id     = $request->teacher_id;
        $course->created_by     = $request->created_by ?? Auth::id();
        $course->is_published   = $request->is_published;
        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $course->thumbnail));
            }
            $path = $request->file('thumbnail')->store('thumbnails', 'public');
            $course->thumbnail = Storage::url($path);
        }
        
        if ($course->save()) {
            return redirect()->back()->with('success', 'Curso atualizado com sucesso!');
        }

        return redirect()->back()->with('error', 'Falha ao atualizar curso, verifique os dados e tente novamente!');
    }

    public function storeLesson (Request $request, $id) {
    
        $course = Course::find($id);
        if (!$course) {
            return redirect()->back()->with('error', 'Curso não encontrado/disponível!');
        }

        $course->lessons()->syncWithoutDetaching($request->lesson_id);

        return redirect()->back()->with('success', 'Aulas associadas com sucesso!');
    }

    public function destroyLesson ($id) {
    
        $course = Course::find($id);
        if (!$course) {
            return redirect()->back()->with('error', 'Curso não encontrado/disponível!');
        }

        $course->lessons()->detach($id);

        return redirect()->back()->with('success', 'Aula removida do curso com sucesso!');
    }

    private function formatValue ($valor) {
        
        $valor = preg_replace('/[^0-9,]/', '', $valor);
        $valor = str_replace(',', '.', $valor);
        $valorFloat = floatval($valor);
    
        return number_format($valorFloat, 2, '.', '');
    }
}
