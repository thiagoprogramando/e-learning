<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model {

    use SoftDeletes;
    
    protected $table = 'invoices';
    
    protected $fillable = [
        'uuid',
        'user_id',
        'course_id',
        'coupon_id',
        'payment_description',
        'payment_value',
        'payment_status',
        'payment_method',
        'payment_type',
        'payment_token',
        'payment_url',
        'payment_due_date',
        'payment_paid_at',
    ];

    protected $casts = [
        'payment_due_date' => 'datetime',
        'payment_paid_at' => 'datetime',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function course() {
        return $this->belongsTo(Course::class);
    }

    public function coupon() {
        return $this->belongsTo(Coupon::class);
    }

    public function paymentLabel() {
        switch ($this->payment_status) {
            case 'pending':
                return 'Pendente';
            case 'paid':
                return 'Pago';
            case 'canceled':
                return 'Cancelado';
            default:
                return ucfirst($this->payment_status);
        }
    }

    public function paymentBgLabel() {
        switch ($this->payment_status) {
            case 'pending':
                return 'warning';
            case 'paid':
                return 'success';
            case 'canceled':
                return 'danger';
            default:
                return ucfirst($this->payment_status);
        }
    }
}