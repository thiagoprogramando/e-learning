<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TicketController extends Controller {
    
    public function store (Request $request) {

        $ticket             = new Ticket();
        $ticket->uuid       = Str::uuid();
        $ticket->created_by = Auth::user()->id;
        $ticket->course_id  = $request->course_id;
        $ticket->lesson_id  = $request->lesson_id;
        $ticket->comment    = $request->comment;
        if ($ticket->save()) {
            return redirect()->back()->with('success', 'O Ticket foi aberto com o protocolo: '.$ticket->uuid);
        }

        return redirect()->back()->with('error', 'Falha ao tentar abrir Ticket, verifique os dados e tente novamente!');
    }

    public function update (Request $request, $uuid) {
        
        $ticket = Ticket::where('uuid', $uuid)->first();
        if (!$ticket) {
            return redirect()->back()->with('error', 'Ticket não encontrado/disponível!');
        }

        if (!empty($request->comment)) {
            $ticket->comment = $request->comment;
        }
        if (!empty($request->response)) {
            $ticket->response = $request->response;
        }

        $ticket->teacher_id = Auth::user()->id;
        if ($ticket->save()) {
            return redirect()->back()->with('success', 'O Ticket foi respondido com sucesso!');
        }

        return redirect()->back()->with('error', 'Falha ao tentar responder Ticket, verifique os dados e tente novamente!');
    }

    public function destroy ($uuid) {
        
        $ticket = Ticket::where('uuid', $uuid)->first();
        if ($ticket && $ticket->delete()) {
            return redirect()->back()->with('success', 'Ticket deletado com sucesso!');
        }

        return redirect()->back()->with('error', 'Ticket não encontrado/disponível!');
    }
}
