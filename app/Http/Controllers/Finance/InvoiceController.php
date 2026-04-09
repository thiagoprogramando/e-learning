<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class InvoiceController extends Controller {
    
    public function index(Request $request) {

        if (Auth::user()->role === 'admin') {
            $query = Invoice::query();
        } else {
            $query = Invoice::query()->where('user_id', Auth::user()->id);
        }
        
        if (!empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('payment_description', 'like', "%$search%")
                  ->orWhere('payment_method', 'like', "%$search%")
                  ->orWhere('payment_status', 'like', "%$search%");
            });
        }
        if (!empty($request->payment_status)) {
            $query->where('payment_status', $request->payment_status);
        }
        if (!empty($request->payment_due_date)) {
            $query->whereDate('payment_due_date', $request->payment_due_date);
        }
        if (!empty($request->payment_paid_at)) {
            $query->whereDate('payment_paid_at', $request->payment_paid_at);
        }
        if (!empty($request->course_id)) {
            $query->where('course_id', $request->course_id);
        }
        if (!empty($request->user_id)) {
            $query->where('user_id', $request->user_id);
        }

        $invoices = (clone $query)->latest()->paginate(30)->withQueryString();

        return view('app.Finance.index', [
            'invoices' => $invoices,
            'stats' => [
                'total'     => (clone $query)->count(),
                'paid'      => (clone $query)->where('payment_status', 'paid')->count(),
                'pending'   => (clone $query)->where('payment_status', 'pending')->count(),
                'canceled'  => (clone $query)->where('payment_status', 'canceled')->count(),
            ],
            'users'    => User::orderBy('name', 'desc')->get(),
            'courses'  => Course::orderBy('title', 'desc')->get(),
        ]);
    }

    public function store (Request $request) {

        $invoice                      = new Invoice();
        $invoice->uuid                = Str::uuid();
        $invoice->user_id             = $request->user_id;
        $invoice->course_id           = $request->course_id;
        $invoice->coupon_id           = $request->coupon_id;
        $invoice->payment_description = $request->payment_description;
        $invoice->payment_value       = $this->formatValue($request->payment_value);
        $invoice->payment_status      = $request->payment_status;
        $invoice->payment_method      = $request->payment_method;
        $invoice->payment_due_date    = $request->payment_due_date;
        $invoice->payment_paid_at     = $request->payment_paid_at;
        $invoice->payment_type        = $request->payment_type;
        if ($invoice->save()) {
            return redirect()->back()->with('success', 'Fatura gerada com sucesso!');
        }

        return redirect()->back()->with('infor', 'Falha ao tentar gerar Fatura, verifique os dados e tente novamente!');
    }

    public function update (Request $request, $uuid) {

        $invoice = Invoice::where('uuid', $uuid)->first();
        if (!$invoice) {
            return redirect()->back()->with('infor', 'Fatura indisponível ou não encontrada!');
        }

        if ($request->filled('user_id')) {
            $invoice->user_id = $request->user_id;
        }
        if ($request->filled('coupon_id')) {
            $invoice->coupon_id = $request->coupon_id;
        }
        if ($request->filled('payment_description')) {
            $invoice->payment_description = $request->payment_description;
        }
        if ($request->filled('payment_value')) {
            $invoice->payment_value = $this->formatValue($request->payment_value);
        }
        if ($request->filled('payment_status')) {
            $invoice->payment_status = $request->payment_status;
        }
        if ($request->filled('payment_method')) {
            $invoice->payment_method = $request->payment_method;
        }
        if ($request->filled('payment_due_date')) {
            $invoice->payment_due_date = $request->payment_due_date;
        }

        if ($invoice->save()) {
            return redirect()->back()->with('success', 'Fatura atualizada com sucesso!');
        }

        return redirect()->back()->with('infor', 'Falha ao salvar');
    }

    public function destroy ($uuid) {

        $invoice = Invoice::where('uuid', $uuid)->first();
        if ($invoice && $invoice->delete()) {
            return redirect()->back()->with('success', 'Fatura removida com sucesso!');
        }

        return redirect()->back()->with('error', 'Fatura não encontrada/disponível!');
    }

    private function formatValue ($valor) {
        
        $valor = preg_replace('/[^0-9,]/', '', $valor);
        $valor = str_replace(',', '.', $valor);
        $valorFloat = floatval($valor);
    
        return number_format($valorFloat, 2, '.', '');
    }
}
