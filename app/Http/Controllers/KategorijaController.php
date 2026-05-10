<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\KategorijaModel;
use App\Http\Controllers\Concerns\HandlesSafeDelete;
use Illuminate\Support\Facades\DB;

class KategorijaController extends Controller
{
    use HandlesSafeDelete;

// Kontrolieris kategoriju ierakstu pārvaldībai.

// Rāda visu kategoriju sarakstu
   public function showAllKategorija(Request $request)
    {
        // Meklēšanas frāze un izvēlētā kolonna (vai meklēt visur)
        $q = trim($request->input('q', ''));
        $column = $request->input('column', 'all');

        // Kārsotāji
        $sort = $request->input('sort', 'kategorija_id');
        $direction = strtolower($request->input('direction', 'asc')) === 'desc' ? 'desc' : 'asc';

        // Atļautās kolonnas kārtošanai
        $allowedSort = ['kategorija_id', 'nosaukums', 'apraksts'];
        if (!in_array($sort, $allowedSort, true)) {
            $sort = 'kategorija_id';
        }

        // Atļautās kolonnas meklēšanai
        $allowedColumns = ['all', 'nosaukums', 'apraksts'];
        if (!in_array($column, $allowedColumns, true)) {
            $column = 'all';
        }

        $query = KategorijaModel::query();

        // Meklēšana
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

        // Paginācija + kārtošana
        $kategorija = $query->orderBy($sort, $direction)->paginate(8)->withQueryString();

        return view('kategorija', compact('kategorija', 'sort', 'direction', 'q', 'column'));
    }

    
     //Rāda formu jaunas kategorijas izveidei.
     
    public function createKategorija()
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        return view('createKategorija');
    }

    
     //Saglabā jaunu kategoriju datubāzē.
     
 public function KatSubmit(Request $Kategorija)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        $data = $Kategorija->validate([
            'nosaukums' => 'required|string|max:50',
            'apraksts' => 'nullable|string|max:200',
        ]);

        $kategorija = new KategorijaModel();
        $kategorija->nosaukums = $data['nosaukums'];
        $kategorija->apraksts = $data['apraksts'] ?? null;
        $kategorija->save();

        return redirect()->to('/kategorija')->with('success', 'Ieraksts pievienots');
    }

// Rāda konkrētas kategorijas detaļas.
        public function Katdetails($id)
    {
        // Tieša modeļa izmantošana ir vienkāršāka un korekti ievēro primāro atslēgu
        $kategorija = KategorijaModel::find($id);
        return view('detailsKategorija', ['kategorija' => $kategorija]);
    }

    /**
     * Atver kategorijas rediģēšanas formu (tikai administratoram).
     */
    public function KatEdit($id)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        $kategorija = KategorijaModel::find($id);
        return view('editKategorija', ['kategorija' => $kategorija]);
    }

    /**
     * Saglabā kategorijas izmaiņas datubāzē.
     */
    public function editSubmit(Request $dati, $id)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        $data = $dati->validate([
            'nosaukums' => 'required|string|max:50',
            'apraksts' => 'nullable|string|max:200',
        ]);

        DB::table('kategorija')
            ->where('kategorija_id', $id)
            ->update([
                'nosaukums' => $data['nosaukums'],
                'apraksts' => $data['apraksts'] ?? null,
            ]);

        return redirect()->to('/kategorija')->with('success', 'Ieraksts atjaunināts');
    }

    /**
     * Dzēš kategorijas ierakstu (tikai administratoram).
     */
    public function KatDelete($id)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        $usedIn = $this->detectReferenceUsage($id, [
            ['table' => 'inventars', 'column' => 'kategorija_id', 'label' => 'inventars.kategorija_id'],
        ]);

        $this->deleteWithForeignKeyChecksDisabled('kategorija', 'kategorija_id', $id);

        return redirect('/kategorija')->with('success', $this->buildDeleteMessage('Kategorijas', $usedIn));
    }
}
