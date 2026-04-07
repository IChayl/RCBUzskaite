<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class Lietotajs extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'lietotajs';
    protected $primaryKey = 'lietotajs_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = ['lietotajvards', 'parole', 'admina_tiesibas', 'avatar', 'vards', 'uzvards', 'epasts', 'telefons', 'amats', 'aktivs'];

    /**
     * Atgriež lietotāja pilno vārdu (vārds + uzvārds) vai lietotājvārdu, ja pilns vārds nav aizpildīts.
     */
    public function getPilnaisVardsAttribute(): string
    {
        $fullName = trim((string) ($this->vards ?? '') . ' ' . (string) ($this->uzvards ?? ''));

        return $fullName !== '' ? $fullName : (string) $this->lietotajvards;
    }

    /**
     * Atspējo noklusēto remember token kolonnu, jo tā neeksistē.
     */
    public function getRememberTokenName()
    {
        return null;
    }

    /**
     * Atrod reālo avatar faila ceļu projekta publiskajā mapē.
     */
    public function resolveAvatarPath(): ?string
    {
        if (! $this->avatar) {
            return null;
        }

        $rawPath = ltrim((string) $this->avatar, '/');
        $candidates = array_unique(array_filter([
            $rawPath,
            preg_replace('#^storage/#', '', $rawPath),
            preg_replace('#^public/#', '', $rawPath),
            'avatars/' . basename($rawPath),
        ]));

        foreach ($candidates as $candidate) {
            if (is_file(public_path($candidate))) {
                return $candidate;
            }

            // Saderībai ar veciem datiem pārbaudām arī storage/public ceļu.
            if (Storage::disk('public')->exists($candidate)) {
                return 'storage/' . ltrim($candidate, '/');
            }
        }

        return null;
    }

    /**
     * Pārbauda, vai lietotājam ir reāli pieejams avatar fails.
     */
    public function hasAvatarFile(): bool
    {
        return $this->resolveAvatarPath() !== null;
    }

}
