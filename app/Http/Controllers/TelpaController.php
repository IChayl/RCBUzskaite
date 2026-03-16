<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Telpa;
use Illuminate\Support\Facades\DB;

// Kontrolieris telpu ierakstu pārvaldībai.
class TelpaController extends Controller
{
    // Rāda visu telpu sarakstu
    public function showAllTelpa(Request $request)
    {
        // Uzstādam meklēšanas tekstu un izvēlētās kolonnas izziņu
        $q = trim($request->input('q', ''));
        $column = $request->input('column', 'all');

        // Sortēšanas iestatījumi, uzstādām noklusējuma kolonnas un virzienu
        $sort = $request->input('sort', 'telpas_id');
        $direction = strtolower($request->input('direction', 'asc')) === 'desc' ? 'desc' : 'asc';

        // Drošība: atļautās kolonnas, pēc kurām drīkst kārtot
        $allowedSort = ['telpas_id', 'nosaukums', 'izmeri', 'numurs', 'stavs'];
        if (!in_array($sort, $allowedSort, true)) {
            $sort = 'telpas_id';
        }

        // Drošība: atļautās kolonnas meklēšanai
        $allowedColumns = ['all', 'nosaukums', 'izmeri', 'numurs', 'stavs'];
        if (!in_array($column, $allowedColumns, true)) {
            $column = 'all';
        }

        $query = Telpa::query();

        // Pievienojam meklēšanas nosacījumus tikai, ja ir ievadīts meklēšanas teksts
        if ($q !== '') {
            if ($column === 'all') {
                // Meklē visās kolonnās
                $query->where(function ($query) use ($q) {
                    $query->where('nosaukums', 'like', "%{$q}%")
                        ->orWhere('izmeri', 'like', "%{$q}%")
                        ->orWhere('numurs', 'like', "%{$q}%")
                        ->orWhere('stavs', 'like', "%{$q}%");
                });
            } else {
                // Meklē tikai konkrētajā kolonnā
                $query->where($column, 'like', "%{$q}%");
            }
        }

        // Paginācija + kārtošana pēc norādītajām kritērijiem
        $telpas = $query->orderBy($sort, $direction)->paginate(15)->withQueryString();

        // Nosūtām datus uz skatu
        return view('telpa', compact('telpas', 'sort', 'direction', 'q', 'column'));
    }

    // Forma jaunas telpas izveidei.
    public function createTelpa()
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        return view('createTelpa');
    }

    // Saglabā jaunu telpas ierakstu.
    public function TelpaSubmit(Request $req)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        $data = $req->validate([
            'nosaukums' => 'required|string|max:50',
            'izmeri' => 'nullable|string|max:10',
            'numurs' => 'nullable|integer',
            'stavs' => 'required|integer',
        ]);

        $t = new Telpa();
        $t->nosaukums = $data['nosaukums'];
        $t->izmeri = $data['izmeri'] ?? null;
        $t->numurs = $data['numurs'] ?? null;
        $t->stavs = $data['stavs'];
        $t->save();

        return redirect()->to('/telpa')->with('success','Ieraksts pievienots');
    }

    // Parāda telpas detalizētu informāciju.
    public function TelpaDetails($id)
    {
        $t = Telpa::find($id);
        return view('detailsTelpa', ['telpa' => $t]);
    }

    // Atver telpas rediģēšanas formu.
    public function TelpaEdit($id)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        $t = Telpa::find($id);
        return view('editTelpa', ['telpa' => $t]);
    }

    // Saglabā telpas izmaiņas datubāzē.
    public function editSubmit(Request $req, $id)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        $data = $req->validate([
            'nosaukums' => 'required|string|max:50',
            'izmeri' => 'nullable|string|max:10',
            'numurs' => 'nullable|integer',
            'stavs' => 'required|integer',
        ]);

        DB::table('telpa')
            ->where('telpas_id',$id)
            ->update([
                'nosaukums' => $data['nosaukums'],
                'izmeri' => $data['izmeri'] ?? null,
                'numurs' => $data['numurs'] ?? null,
                'stavs' => $data['stavs'],
            ]);

        return redirect()->to('/telpa')->with('success','Ieraksts atjaunināts');
    }

    // Dzēš telpas ierakstu.
    public function TelpaDelete($id)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        DB::table('telpa')->where('telpas_id',$id)->delete();
        return redirect('/telpa')->with('success','Ieraksts dzēsts');
    }
}
