<?php

namespace App\Http\Controllers\Lesson;

use App\Http\Controllers\Controller;
use App\Models\Block;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class BlockController extends Controller {
    
    public function store(Request $request, $uuid) {

        $request->validate([
            'type'          => 'required|in:text,video,image,audio,embed',
            'content_text'  => 'required_if:type,text',
            'content_embed' => 'required_if:type,embed',
            'content_video' => "required_if:type,video|file|mimetypes:video/mp4,video/webm,video/ogg",
            'content_audio' => "required_if:type,audio|file|mimetypes:audio/mpeg,audio/wav,audio/ogg",
        ], [], [
            'type.required'                 => 'O tipo de bloco é obrigatório.',
            'type.in'                       => 'O tipo de bloco deve ser um dos tipos válidos.',
            'content_text.required_if'      => 'O conteúdo de texto é obrigatório para este tipo.',
            'content_embed.required_if'     => 'O conteúdo de incorporação é obrigatório para este tipo.',
            'content_video.required_if'     => 'O arquivo de vídeo é obrigatório para este tipo.',
            'content_video.file'            => 'O conteúdo de vídeo deve ser um arquivo.',
            'content_video.mimetypes'       => 'O arquivo de vídeo deve ser do tipo MP4, WebM ou OGG.',
            'content_video.max'             => 'O arquivo de vídeo excede o tamanho máximo permitido.',
            'content_audio.required_if'     => 'O arquivo de áudio é obrigatório para este tipo.',
            'content_audio.file'            => 'O conteúdo de áudio deve ser um arquivo.',
            'content_audio.mimetypes'       => 'O arquivo de áudio deve ser do tipo MP3, WAV ou OGG.',
            'content_audio.max'             => 'O arquivo de áudio excede o tamanho máximo permitido.',
        ]);
        
        $lesson = Lesson::where('uuid', $uuid)->first();
        if (!$lesson) {
            return redirect()->back()->with('error', 'Aula não encontrada/disponível!');
        }

        $block              = new Block();
        $block->title       = $request->title;
        $block->lesson_id   = $lesson->id;
        $block->type        = $request->type;
        $block->order       = $request->order ?? 0;

        switch ($request->type) {
            case 'text':
                $block->content = $request->content_text;
                break;
            case 'video':
                $path = $request->file('content_video')->store('videos', 'public');
                $block->content = Storage::url($path);
                break;
            case 'image':
                $path = $request->file('content_image')->store('images', 'public');
                $block->content = Storage::url($path);
                break;
            case 'audio':
                $path = $request->file('content_audio')->store('audios', 'public');
                $block->content = Storage::url($path);
                break;
            case 'embed':
                $block->content = $request->content_embed;
                break;
        }

        if (!$block->save()) {
            return redirect()->back()->with('error', 'Erro ao criar o bloco, tente novamente!');
        }

        return redirect()->back()->with('success', 'Bloco criado com sucesso!');
    }

    public function destroy(Request $request, $id) {
        
        if (Hash::check($request->password, Auth::user()->password)) {

            $block = Block::where('id', $id)->first();
            if ($block &&$block->delete()) {
                return redirect()->back()->with('success', 'Bloco deletado com sucesso!');
            }

            return redirect()->back()->with('error', 'Falha ao tentar excluir o bloco. Verifique os dados e tente novamente!!');
        }

        return redirect()->back()->with('error', 'Credenciais inválidas. Verifique os dados e tente novamente!!');  
    }
}
