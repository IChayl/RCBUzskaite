<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KustibasVeidi;
use App\Http\Controllers\Concerns\HandlesSafeDelete;
use Illuminate\Support\Facades\DB;

// Kontrolieris kustību veidu datu pārvaldībai.
class KustibasVeidiController extends Controller
{
    use HandlesSafeDelete;
    /**
     * Parāda kustību veidu sarakstu ar meklēšanu un kārtošanu.
     */
    public function showAll(Request $request)
    {
        // Meklēšanas virkne un meklējamā kolonna
        $q = trim($request->input('q', ''));
        $column = $request->input('column', 'all');

        // Kārtošanas parametri
        $sort = $request->input('sort', 'kustibas_veids_id');
        $direction = strtolower($request->input('direction', 'asc')) === 'desc' ? 'desc' : 'asc';

        // Atļauto sortēšanas kolonnu saraksts
        $allowedSort = ['kustibas_veids_id', 'nosaukums', 'apraksts'];
        if (!in_array($sort, $allowedSort, true)) {
            $sort = 'kustibas_veids_id';
        }

        // Atļautās meklēšanas kolonnas
        $allowedColumns = ['all', 'nosaukums', 'apraksts'];
        if (!in_array($column, $allowedColumns, true)) {
            $column = 'all';
        }

        $query = KustibasVeidi::query();

        // Meklēšanas nosacījums
        if ($q !== '') {
            if ($column === 'all') {
                $query->where(function ($query) use ($q) {
                    $query->where('nosaukums', 'like', "%{$q}%")
                        ->orWhere('apraksts', 'like', "%{$q}%");
                });
            } else {
                $query->where($column, 'like', "%{$q}%");
            }
        }

        // Paginate + kārtošana
        $veidi = $query->orderBy($sort, $direction)->paginate(7)->withQueryString();
        return view('kustibas_veidi', compact('veidi', 'sort', 'direction', 'q', 'column'));
    }

    /**
     * Atver jauna kustības veida formu.
     */
    public function create()
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        return view('createKustibasVeidi');
    }

    /**
     * Saglabā jaunu kustības veida ierakstu.
     */
    public function store(Request $req)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        $data = $req->validate([
            'nosaukums' => 'required|string|max:50',
            'apraksts' => 'nullable|string|max:200',
        ]);

        $veids = new KustibasVeidi();
        $veids->nosaukums = $data['nosaukums'];
        $veids->apraksts = $data['apraksts'] ?? null;
        $veids->save();

        return redirect()->to('/kustibas_veidi')->with('success','Ieraksts pievienots');
    }

    /**
     * Parāda viena kustības veida detalizētu informāciju.
     */
    public function details($id)
    {
        $veids = KustibasVeidi::find($id);
        return view('detailsKustibasVeidi', ['veids' => $veids]);
    }

    /**
     * Atver kustības veida rediģēšanas formu.
     */
    public function edit($id)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        $veids = KustibasVeidi::find($id);
        return view('editKustibasVeidi', ['veids' => $veids]);
    }

    /**
     * Atjaunina kustības veida ierakstu datubāzē.
     */
    public function update(Request $req, $id)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        $data = $req->validate([
            'nosaukums' => 'required|string|max:50',
            'apraksts' => 'nullable|string|max:200',
        ]);

        DB::table('kustibas_veidi')->where('kustibas_veids_id', $id)->update([
            'nosaukums' => $data['nosaukums'],
            'apraksts' => $data['apraksts'] ?? null,
        ]);

        return redirect()->to('/kustibas_veidi')->with('success','Ieraksts atjaunināts');
    }

    /**
     * Dzēš kustības veida ierakstu.
     */
    public function delete($id)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        $usedIn = $this->detectReferenceUsage($id, [
            ['table' => 'inventara_kustiba', 'column' => 'kustibas_veids_id', 'label' => 'inventara_kustiba.kustibas_veids_id'],
        ]);

        $this->deleteWithForeignKeyChecksDisabled('kustibas_veidi', 'kustibas_veids_id', $id);

        return redirect('/kustibas_veidi')->with('success', $this->buildDeleteMessage('Kustības veida', $usedIn));
    }
}
