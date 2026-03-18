<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Norakstishana;
use App\Models\Inventar;
use App\Http\Controllers\Concerns\HandlesSafeDelete;
use Illuminate\Support\Facades\DB;

// Kontrolieris norakstīšanas ierakstu pārvaldībai.
class NorakstishanaController extends Controller
{
    use HandlesSafeDelete;
    /**
     * Parāda norakstīšanas sarakstu ar meklēšanu, kārtošanu un lapošanu.
     */
    public function showAll(Request $request)
    {
        // Meklēšanas teksta un kolonnas iestatījumi
        $q = trim($request->input('q', ''));
        $column = $request->input('column', 'all');

        // Kārtošanas parametri
        $sort = $request->input('sort', 'norakstishana_id');
        $direction = strtolower($request->input('direction', 'asc')) === 'desc' ? 'desc' : 'asc';

        // Atļautās kolonnas kārtošanai
        $allowedSort = ['norakstishana_id', 'inventara_id', 'norDatums', 'iemesls', 'talaka_riciba'];
        if (!in_array($sort, $allowedSort, true)) {
            $sort = 'norakstishana_id';
        }

        // Atļautās kolonnas meklēšanai
        $allowedColumns = ['all', 'inventara_id', 'norDatums', 'iemesls', 'talaka_riciba'];
        if (!in_array($column, $allowedColumns, true)) {
            $column = 'all';
        }

        $query = Norakstishana::query()
            ->with(['inventars']);

        // Meklēšana kolonnā vai visās kolonnās
        if ($q !== '') {
            if ($column === 'all') {
                $query->where(function ($query) use ($q) {
                    $query->where('norDatums', 'like', "%{$q}%")
                        ->orWhere('iemesls', 'like', "%{$q}%")
                        ->orWhere('talaka_riciba', 'like', "%{$q}%")
                        ->orWhereHas('inventars', function ($q2) use ($q) {
                            $q2->where('nosaukums', 'like', "%{$q}%");
                        });
                });
            } elseif ($column === 'norDatums') {
                $query->where('norDatums', 'like', "%{$q}%");
            } elseif ($column === 'iemesls') {
                $query->where('iemesls', 'like', "%{$q}%");
            } elseif ($column === 'talaka_riciba') {
                $query->where('talaka_riciba', 'like', "%{$q}%");
            } elseif ($column === 'inventara_id') {
                $query->where('inventara_id', 'like', "%{$q}%");
            }
        }

        // Kārtošana
        $norakstishanas = $query->orderBy($sort, $direction)->paginate(7)->withQueryString();

        return view('norakstishana', compact('norakstishanas', 'sort', 'direction', 'q', 'column'));
    }

    /**
     * Ielādē formas datus jaunas norakstīšanas izveidei.
     */
    public function create()
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        $inventari = Inventar::orderBy('inventars_id','asc')->get();
        return view('createNorakstishana', ['inventari' => $inventari]);
    }

    /**
     * Validē ievadi un saglabā jaunu norakstīšanas ierakstu.
     */
    public function store(Request $req)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        $data = $req->validate([
            'inventara_id' => 'required|integer|exists:inventars,inventars_id',
            'norDatums' => 'required|date',
            'iemesls' => 'required|string|max:30',
            'talaka_riciba' => 'required|string|max:50',
        ]);

        $norakstishana = new Norakstishana();
        $norakstishana->inventara_id = $data['inventara_id'];
        $norakstishana->norDatums = $data['norDatums'];
        $norakstishana->iemesls = $data['iemesls'];
        $norakstishana->talaka_riciba = $data['talaka_riciba'];
        $norakstishana->save();

        return redirect()->to('/norakstishana')->with('success','Ieraksts pievienots');
    }

    /**
     * Parāda viena norakstīšanas detalizēto skatu.
     */
    public function details($id)
    {
        $norakstishana = Norakstishana::find($id);
        return view('detailsNorakstishana', ['norakstishana' => $norakstishana]);
    }

    /**
     * Atver norakstīšanas rediģēšanas formu (tikai administratoram).
     */
    public function edit($id)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        $norakstishana = Norakstishana::find($id);
        $inventari = Inventar::orderBy('inventars_id','asc')->get();
        return view('editNorakstishana', ['norakstishana' => $norakstishana, 'inventari' => $inventari]);
    }

    /**
     * Saglabā norakstīšanas izmaiņas datubāzē.
     */
    public function update(Request $req, $id)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        $data = $req->validate([
            'inventara_id' => 'required|integer|exists:inventars,inventars_id',
            'norDatums' => 'required|date',
            'iemesls' => 'required|string|max:30',
            'talaka_riciba' => 'required|string|max:50',
        ]);

        DB::table('Norakstishana')->where('norakstishana_id',$id)->update([
            'inventara_id' => $data['inventara_id'],
            'norDatums' => $data['norDatums'],
            'iemesls' => $data['iemesls'],
            'talaka_riciba' => $data['talaka_riciba'],
        ]);

        return redirect()->to('/norakstishana')->with('success','Ieraksts atjaunināts');
    }

    /**
     * Dzēš norakstīšanas ierakstu (tikai administratoram).
     */
    public function delete($id)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        $this->deleteWithForeignKeyChecksDisabled('Norakstishana', 'norakstishana_id', $id);

        return redirect('/norakstishana')->with('success', $this->buildDeleteMessage('Norakstīšanas', []));
    }
}
