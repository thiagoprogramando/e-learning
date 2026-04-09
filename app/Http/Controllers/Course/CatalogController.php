<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\Invoice;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CatalogController extends Controller {
    
    public function index() {

        $query = Course::query();
        if (!empty(request('search'))) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
            });
        }

        return view('app.Course.Catalog.index', [
            'courses'   => $query->whereIn('is_published', [true, 1])->latest()->paginate(30)->withQueryString(),
        ]);
    }

    public function show ($course, $lesson = null) {

        $course = Course::where('uuid', $course)->first();
        if (!$course) {
            return redirect()->route('catalog')->with('infor', 'Curso indisponível para compra!');
        }

        return view('app.Course.Catalog.show', [
            'course' => $course,
            'lesson' => Lesson::where('uuid', $lesson)->first()
        ]);
    }

    public function buyCourse (Request $request, $uuid) {

        $course = Course::where('uuid', $uuid)->first();
        if (!$course) {
            return redirect()->route('catalog')->with('infor', 'Curso indisponível para compra!');
        }

        if ($course->hasApprovedInvoiceForAuthenticatedUser()) {
            return redirect()->route('catalog')->with('infor', 'Você já comprou este curso!');
        }

        $this->deletePendingInvoicesForCourseAndUser($course);

        $finalValue = $course->value;
        $couponId   = null;

        if ($request->filled('coupon_code')) {

            $coupon = Coupon::where('code', $request->coupon_code)->where('status', 'active')->first();
            if ($coupon) {

                if ($coupon->user_id && $coupon->user_id != Auth::id()) {
                    return redirect()->back()->with('error', 'Cupom não disponível para seu Perfil!');
                }
                if ($coupon->course_id && $coupon->course_id != $course->id) {
                    return redirect()->back()->with('error', 'Cupom não válido para este curso!');
                }
                if (!is_null($coupon->quanty) && $coupon->quanty <= 0) {
                    return redirect()->back()->with('error', 'Cupom expirado!');
                }
                if ($coupon->percentage) {
                    $discount = ($course->value * $coupon->percentage) / 100;
                    $finalValue = $course->value - $discount;
                } else {
                    $finalValue = $course->value - $coupon->value;
                }
                if ($finalValue < 0) {
                    $finalValue = 0;
                }

                $couponId = $coupon->id;
            }
        }

        $invoice                        = new Invoice();
        $invoice->uuid                  = Str::uuid();
        $invoice->user_id               = Auth::id();
        $invoice->course_id             = $course->id;
        $invoice->coupon_id             = $couponId;
        $invoice->payment_description   = $course->title;
        $invoice->payment_value         = $finalValue;
        $invoice->payment_method        = $request->input('payment_method', 'PIX');
        $invoice->payment_type          = 'revenue';
        $invoice->payment_due_date      = now()->addDays(1);
        if ($finalValue <= 0) {
            $invoice->payment_status    = 'paid';
            $invoice->payment_paid_at   = now();
        }
        
        if ($invoice->save()) {
            return redirect()->route('invoices')->with('success', 'Pedido feito com sucesso!');
        }

        return redirect()->route('catalog')->with('error', 'Falha ao comprar o curso, verifique os dados e tente novamente!');
    }

    private function deletePendingInvoicesForCourseAndUser(Course $course): void {
        Invoice::where('user_id', Auth::id())->where('course_id', $course->id)->where('payment_status', 'pending')->delete();
    }
}
