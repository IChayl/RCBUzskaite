<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Telpa;
use App\Http\Controllers\Concerns\HandlesSafeDelete;
use Illuminate\Support\Facades\DB;

// Kontrolieris telpu ierakstu pārvaldībai.
class TelpaController extends Controller
{
    use HandlesSafeDelete;

    /**
     * Rāda visu telpu sarakstu.
     */
    public function showAllTelpa(Request $request)
    {
        $q = trim($request->input('q', ''));

        $sort = $request->input('sort', 'telpas_id');
        $direction = strtolower($request->input('direction', 'asc')) === 'desc' ? 'desc' : 'asc';

        $allowedSort = ['telpas_id', 'nosaukums', 'platiba', 'numurs'];
        if (!in_array($sort, $allowedSort, true)) {
            $sort = 'telpas_id';
        }

        $query = Telpa::query();

        // Meklēšana telpām tikai pēc nosaukuma.
        if ($q !== '') {
            $query->where('nosaukums', 'like', "%{$q}%");
        }

        $telpas = $query->orderBy($sort, $direction)->paginate(7)->withQueryString();

        return view('telpa', compact('telpas', 'sort', 'direction', 'q'));
    }

    /**
     * Forma jaunas telpas izveidei.
     */
    public function createTelpa()
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }

        return view('createTelpa');
    }

    /**
     * Saglabā jaunu telpas ierakstu.
     */
    public function TelpaSubmit(Request $req)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }

        $data = $req->validate([
            'nosaukums' => 'required|string|max:50',
            'platiba' => 'nullable|string|max:10',
            'platība' => 'nullable|string|max:10',
            'numurs' => 'nullable|integer',
            'stavs' => 'nullable|integer',
        ]);

        $platiba = $data['platiba'] ?? ($data['platība'] ?? null);

        $t = new Telpa();
        $t->nosaukums = $data['nosaukums'];
        $t->platiba = $platiba;
        $t->numurs = $data['numurs'] ?? null;
        if (array_key_exists('stavs', $data)) {
            $t->stavs = $data['stavs'];
        }
        $t->save();

        return redirect()->to('/telpa')->with('success', 'Ieraksts pievienots');
    }

    /**
     * Parāda telpas detalizētu informāciju.
     */
    public function TelpaDetails($id)
    {
        $t = Telpa::findOrFail($id);

        return view('detailsTelpa', ['telpa' => $t]);
    }

    /**
     * Atver telpas rediģēšanas formu.
     */
    public function TelpaEdit($id)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }

        $t = Telpa::findOrFail($id);

        return view('editTelpa', ['telpa' => $t]);
    }

    /**
     * Saglabā telpas izmaiņas datubāzē.
     */
    public function editSubmit(Request $req, $id)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }

        $data = $req->validate([
            'nosaukums' => 'required|string|max:50',
            'platiba' => 'nullable|string|max:10',
            'platība' => 'nullable|string|max:10',
            'numurs' => 'nullable|integer',
            'stavs' => 'nullable|integer',
        ]);

        $platiba = $data['platiba'] ?? ($data['platība'] ?? null);

        DB::table('telpa')
            ->where('telpas_id', $id)
            ->update([
                'nosaukums' => $data['nosaukums'],
                'platiba' => $platiba,
                'numurs' => $data['numurs'] ?? null,
            ]);

        return redirect()->to('/telpa')->with('success', 'Ieraksts atjaunināts');
    }

    /**
     * Dzēš telpas ierakstu.
     */
    public function TelpaDelete($id)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }

        $usedIn = $this->detectReferenceUsage($id, [
            ['table' => 'inventars', 'column' => 'telpas_id', 'label' => 'inventars.telpas_id'],
            ['table' => 'inventara_kustiba', 'column' => 'veca_telpa_id', 'label' => 'inventara_kustiba.veca_telpa_id'],
            ['table' => 'inventara_kustiba', 'column' => 'jauna_telpa_id', 'label' => 'inventara_kustiba.jauna_telpa_id'],
        ]);

        $this->deleteWithForeignKeyChecksDisabled('telpa', 'telpas_id', $id);

        return redirect('/telpa')->with('success', $this->buildDeleteMessage('Telpas', $usedIn));
    }
}
