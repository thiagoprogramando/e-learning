<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller {
    
    public function index (Request $request) {

        $query = Coupon::query();
        if (!empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%$search%");
            });
        }
        if (!empty($request->status)) {
            $query->where('status', $request->status);
        }
        if (!empty($request->user_id)) {
            $query->where('user_id', $request->user_id);
        }
        if (!empty($request->course_id)) {
            $query->where('course_id', $request->course_id);
        }

        return view('app.Finance.Coupon.index', [
            'coupons'   => $query->paginate(30),
            'users'     => User::orderBy('name', 'desc')->get(),
            'courses'   => Course::orderBy('title', 'desc')->get(),
        ]);
    }

    public function valited (Request $request) {

       $request->validate([
            'code'      => 'required|string',
            'course_id' => 'nullable|exists:courses,id',
            'user_id'   => 'nullable|exists:users,id',
        ]);

        $coupon = Coupon::where('code', $request->code)->where('status', 'active')->first();
        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Cupom inválido/expirado!'
            ]);
        }

        if ($coupon->user_id && $coupon->user_id != $request->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Cupom não disponível para seu Perfil!'
            ]);
        }

        if ($coupon->course_id) {
            if (!$request->course_id || $coupon->course_id != $request->course_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cupom não válido para este curso!'
                ]);
            }
        }

        if (!is_null($coupon->quanty) && $coupon->quanty <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cupom expirado!'
            ]);
        }

        $discount = $coupon->percentage ? $coupon->percentage . '%' : 'R$ ' . number_format($coupon->value, 2, ',', '.');
        return response()->json([
            'success' => true,
            'message' => 'Será aplicado um desconto de ' . $discount . ' na compra!'
        ]);
    }

    public function store (Request $request) {

        $coupon                 = new Coupon();
        $coupon->created_by     = Auth::id();
        $coupon->course_id      = $request->course_id;
        $coupon->user_id        = $request->user_id;
        $coupon->quanty         = $request->quanty;
        $coupon->percentage     = $request->percentage;
        $coupon->value          = $this->formatValue($request->value);
        $coupon->status         = $request->status;
        if ($coupon->save()) {
            return redirect()->back()->with('success', 'CUPOM gerado com sucesso!');
        }

        return redirect()->back()->with('infor', 'Falha ao tentar gerar CUPOM, verifique os dados e tente novamente!');
    }

    public function update (Request $request, $id) {

        $coupon = Coupon::find($id);
        if (!$coupon) {
            return redirect()->back()->with('error', 'CUPOM indisponível!');
        }

        if ($request->filled('course_id')) {
            $coupon->course_id = $request->course_id;
        }
        if ($request->filled('user_id')) {
            $coupon->user_id = $request->user_id;
        }
        if ($request->filled('quanty')) {
            $coupon->quanty = $request->quanty;
        }
        if ($request->filled('percentage')) {
            $coupon->percentage = $request->percentage;
            $coupon->value = 0;
        } elseif ($request->filled('value')) {

            $value = $this->formatValue($request->value);
            if ($value > 0) {
                $coupon->value = $value;
                $coupon->percentage = 0;
            }
        }
        if ($request->filled('status')) {
            $coupon->status = $request->status;
        }

        if ($coupon->save()) {
            return redirect()->back()->with('success', 'CUPOM gerado com sucesso!');
        }

        return redirect()->back()->with('infor', 'Falha ao tentar gerar CUPOM, verifique os dados e tente novamente!');
    }

    public function destroy ($id) {
    
        $coupon = Coupon::find($id);
        if ($coupon && $coupon->delete()) {
            return redirect()->back()->with('success', 'CUPOM removido com sucesso!');
        }

        return redirect()->back()->with('error', 'CUPOM não encontrado/disponível!');
    }

    private function formatValue ($valor) {
        
        $valor = preg_replace('/[^0-9,]/', '', $valor);
        $valor = str_replace(',', '.', $valor);
        $valorFloat = floatval($valor);
    
        return number_format($valorFloat, 2, '.', '');
    }
}
