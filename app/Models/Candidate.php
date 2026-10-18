<?php

namespace App\Models;

use Database\Factories\CandidateFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public static function findOrCreateWithEmail(string $name, string $email, ?string $phone = null): self
    {
        $normalizedEmail = strtolower(trim($email));

        return static::query()->firstOrCreate(
            ['email' => $normalizedEmail],
            ['name' => trim($name), 'phone' => $phone],
        );
    }
}
