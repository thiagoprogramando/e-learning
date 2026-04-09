<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model {

    use SoftDeletes;
    
    protected $table = 'coupons';
    
    protected $fillable = [
        'created_by',
        'course_id',
        'user_id',
        'code',
        'quanty',
        'percentage',
        'value',
        'status'
    ];

    protected static function boot() {
        parent::boot();
        static::creating(function ($coupon) {
            if (empty($coupon->code)) {
                $coupon->code = self::generateUniqueCode();
            }
        });
    }

    public function statusLabel() {
        switch ($this->status) {
            case 'inactive':
                return 'Inativo';
            case 'active':
                return 'Ativo';
            case 'canceled':
                return 'Cancelado';
            default:
                return ucfirst($this->status);
        }
    }

    public function statusBgLabel() {
        switch ($this->status) {
            case 'inactive':
                return 'warning';
            case 'active':
                return 'success';
            case 'canceled':
                return 'danger';
            default:
                return ucfirst($this->status);
        }
    }

    private static function generateUniqueCode() {
        do {
            $code = self::generateCode();
        } while (self::where('code', $code)->exists());

        return $code;
    }

    private static function generateCode() {

        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code       = '';
        for ($i = 0; $i < 6; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $code;
    }
}
