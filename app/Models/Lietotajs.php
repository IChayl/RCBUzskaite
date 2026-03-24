<?php

namespace App\Models;

use App\Mail\VerifyEmailMailable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
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
     * Atrod reālo avatar faila ceļu publiskajā diskā arī tad, ja datubāzē saglabāts atšķirīgs prefikss.
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
            if (Storage::disk('public')->exists($candidate)) {
                return $candidate;
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

    /**
     * Ģenerē un sūta e-pasta verifikācijas kodu
     * 
     * @return string Ģenerētais verifikācijas kods
     */
    public function generateAndSendVerificationCode(): string
    {
        // Izmantojam tieši 6 ciparus, lai kodu būtu viegli ievadīt ar roku.
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Saglabājam kodu kopā ar termiņu, lai vēlāk varētu pārbaudīt derīgumu.
        $this->email_verification_code = $code;
        $this->email_verification_code_expires_at = Carbon::now()->addHours(24);
        $this->save();

        // E-pasta saturs tiek veidots atsevišķā Mailable klasē, lai loģika būtu nodalīta.
        Mail::to($this->epasts)->send(new VerifyEmailMailable($this, $code));

        return $code;
    }

    /**
     * Pārbauda un verifikē e-pasta kodu
     * 
     * @param string $code Verifikācijas kods no lietotāja
     * @return bool Vai kods ir derīgs
     */
    public function verifyEmailCode(string $code): bool
    {
        // Vispirms pārbaudām pašu vērtību, pēc tam termiņu, lai nepieņemtu vecu kodu.
        if ($this->email_verification_code !== $code) {
            return false;
        }

        if (Carbon::now()->isAfter($this->email_verification_code_expires_at)) {
            return false;
        }

        // Pēc veiksmīgas apstiprināšanas notīrām kodu, lai to nevarētu izmantot atkārtoti.
        $this->email_verified_at = Carbon::now();
        $this->email_verification_code = null;
        $this->email_verification_code_expires_at = null;
        $this->save();

        return true;
    }

    /**
     * Pārbauda, vai e-pasts ir verifikāts
     * 
     * @return bool
     */
    public function isEmailVerified(): bool
    {
        return $this->email_verified_at !== null;
    }

    /**
     * Pārbauda, vai verifikācijas kods ir derīgs
     * 
     * @return bool
     */
    public function hasValidVerificationCode(): bool
    {
        if (! $this->email_verification_code) {
            return false;
        }

        return Carbon::now()->isBefore($this->email_verification_code_expires_at);
    }
}
