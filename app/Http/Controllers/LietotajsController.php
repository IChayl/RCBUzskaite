<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lietotajs;
use App\Http\Controllers\Concerns\HandlesSafeDelete;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

// Kontrolieris lietotāju pārvaldībai (CRUD darbības).
class LietotajsController extends Controller
{
    use HandlesSafeDelete;
    /**
     * Parāda visu lietotāju sarakstu.
     */
    public function showAllLietotaji()
    {
        $user = auth()->user();
        $canViewDarbiniekiTable = $user->admina_tiesibas || in_array((string) $user->amats, ['Direktors', 'Dir.Vietnieks'], true);

        $lietotaji = $canViewDarbiniekiTable
            ? Lietotajs::query()->orderBy('lietotajs_id', 'asc')->get()
            : collect();

        return view('lietotaji', [
            'lietotaji' => $lietotaji,
            'canViewDarbiniekiTable' => $canViewDarbiniekiTable,
        ]);
    }

    /**
     * Atver lietotāja izveides formu (tikai administratoram).
     */
    public function createLietotajs()
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        return view('createLietotajs');
    }

    /**
     * Validē ievadi un izveido jaunu lietotāja ierakstu.
     */
    public function LietotajsSubmit(Request $req)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        $req->validate([
            'parole' => 'required|string|max:255',
            'avatar' => 'nullable|image|max:2048',
            'vards' => 'nullable|string|max:50',
            'uzvards' => 'nullable|string|max:50',
            'epasts' => ['required', 'email', 'max:100', Rule::unique('lietotajs', 'epasts')],
            'telefons' => ['nullable', 'string', 'max:20', 'regex:/^[0-9]+$/'],
            'amats' => 'nullable|string|max:50',
        ]);

        $u = new Lietotajs();
        $u->lietotajvards = $this->generateSystemUsername((string) $req->input('epasts'));
        $u->parole = $req->input('parole');
        $u->admina_tiesibas = $req->input('admina_tiesibas') ? 1 : 0;
        $u->vards = $req->input('vards');
        $u->uzvards = $req->input('uzvards');
        $u->epasts = $req->input('epasts');
        $u->telefons = $req->input('telefons');
        $u->amats = $req->input('amats');
        $u->aktivs = 1;

        // Ja augšupielādēts attēls, saglabā to publiskajā diskā.
        if ($req->hasFile('avatar')) {
            Storage::disk('public')->makeDirectory('avatars');
            $path = $req->file('avatar')->store('avatars', 'public');
            $u->avatar = $path;
        }

        $u->save();
        return redirect()->to('/lietotajs')->with('success','Ieraksts pievienots');
    }

    /**
     * Parāda lietotāja detalizēto skatu.
     */
    public function LietotajsDetails($id)
    {
        $u = Lietotajs::find($id);
        return view('detailsLietotajs', ['lietotajs' => $u]);
    }

    /**
     * Atver lietotāja rediģēšanas formu (tikai administratoram).
     */
    public function LietotajsEdit($id)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        $u = Lietotajs::find($id);
        return view('editLietotajs', ['lietotajs' => $u]);
    }

    /**
     * Validē un saglabā lietotāja izmaiņas.
     */
    public function editSubmit(Request $req, $id)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        $req->validate([
            'parole' => 'required|string|max:255',
            'avatar' => 'nullable|image|max:2048',
            'vards' => 'nullable|string|max:50',
            'uzvards' => 'nullable|string|max:50',
            'epasts' => ['required', 'email', 'max:100', Rule::unique('lietotajs', 'epasts')->ignore($id, 'lietotajs_id')],
            'telefons' => ['nullable', 'string', 'max:20', 'regex:/^[0-9]+$/'],
            'amats' => 'nullable|string|max:50',
        ]);

        $email = (string) $req->input('epasts');

        $data = [
            'lietotajvards' => $this->generateSystemUsername($email, (int) $id),
            'parole' => $req->input('parole'),
            'admina_tiesibas' => $req->input('admina_tiesibas') ? 1 : 0,
            'vards' => $req->input('vards'),
            'uzvards' => $req->input('uzvards'),
            'epasts' => $email,
            'telefons' => $req->input('telefons'),
            'amats' => $req->input('amats'),
        ];

        // Ja pievienots jauns avatar attēls, aizvieto ceļu ar jauno failu.
        if ($req->hasFile('avatar')) {
            Storage::disk('public')->makeDirectory('avatars');
            $path = $req->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        DB::table('lietotajs')->where('lietotajs_id',$id)->update($data);
        return redirect()->to('/lietotajs')->with('success','Ieraksts atjaunināts');
    }

    /**
     * Atgriež lietotāja avatar attēlu tieši no publiskā diska.
     */
    public function avatar($id)
    {
        $lietotajs = Lietotajs::findOrFail($id);
        $avatarPath = $lietotajs->resolveAvatarPath();

        if (! $avatarPath) {
            abort(404);
        }

        return response()->file(Storage::disk('public')->path($avatarPath));
    }

    /**
     * Dzēš lietotāja ierakstu (tikai administratoram).
     */
    public function LietotajsDelete($id)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        $usedIn = $this->detectReferenceUsage($id, [
            ['table' => 'inventars', 'column' => 'atbildigais_id', 'label' => 'inventars.atbildigais_id'],
            ['table' => 'inventara_kustiba', 'column' => 'atbildigais_lietotajs_id', 'label' => 'inventara_kustiba.atbildigais_lietotajs_id'],
        ]);

        $this->deleteWithForeignKeyChecksDisabled('lietotajs', 'lietotajs_id', $id);

        return redirect('/lietotajs')->with('success', $this->buildDeleteMessage('Lietotāja', $usedIn));
    }

    private function generateSystemUsername(string $email, ?int $ignoreId = null): string
    {
        $base = strtolower((string) preg_replace('/[^a-z0-9]/i', '', strstr($email, '@', true) ?: $email));
        if ($base === '') {
            $base = 'user';
        }

        $base = substr($base, 0, 14);
        $candidate = $base;
        $counter = 1;

        while (
            Lietotajs::query()
                ->where('lietotajvards', $candidate)
                ->when($ignoreId !== null, function ($query) use ($ignoreId) {
                    $query->where('lietotajs_id', '!=', $ignoreId);
                })
                ->exists()
        ) {
            $suffix = (string) $counter;
            $candidate = substr($base, 0, 20 - strlen($suffix)) . $suffix;
            $counter++;
        }

        return $candidate;
    }
}
