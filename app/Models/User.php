<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['username', 'name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable {
        
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'uuid',
        'username',
        'avatar',
        'name',
        'skills',
        'email',
        'phone',
        'cpfcnpj',
        'password',
        'role',
        'status'
    ];

    public function maskName () {
        
        $names = explode(' ', trim($this->name));
        return implode(' ', array_slice($names, 0, 2));
    }

    public function maskRole () {
        switch ($this->role) {
            case 'admin':
                return 'Administrador';
            case 'instructor':
                return 'Instrutor';
            case 'student':
                return 'Estudante';
            default:
                return ucfirst($this->payment_status);
        }
    }

    public function maskCpfCnpj () {

        $value = preg_replace('/\D/', '', $this->cpfcnpj);
        if (strlen($value) === 11) {
            return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $value);
        } elseif (strlen($value) === 14) {
            return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "$1.$2.$3/$4-$5", $value);
        }

        return $this->cpfcnpj;
    }

    protected static function booted(): void {
        static::creating(function (User $user) {
            if (empty($user->username)) {
                $user->username = self::generateUniqueUsername($user->name);
            }
        });
    }

    public static function generateUsernameFromName(string $name): string {
        $name = trim($name);
        if ($name === '') {
            return 'usr';
        }

        $parts = preg_split('/\s+/', $name);
        $base = '';

        if (!empty($parts[0])) {
            $base = mb_substr($parts[0], 0, 3);
        }

        if (mb_strlen($base) < 3 && count($parts) > 1) {
            $extra = mb_substr($parts[count($parts) - 1], 0, 3 - mb_strlen($base));
            $base .= $extra;
        }

        $base = mb_strtolower($base);
        $base = preg_replace('/[^a-z0-9]/u', '', $base);

        if ($base === '') {
            $base = 'usr';
        }

        if (mb_strlen($base) < 3) {
            $base = str_pad($base, 3, 'x');
        }

        return $base;
    }

    public static function generateUniqueUsername(string $name): string {
        $base = self::generateUsernameFromName($name);

        do {
            $suffix = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
            $username = $base . $suffix;
        } while (self::where('username', $username)->exists());

        return $username;
    }

    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
