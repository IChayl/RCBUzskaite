<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventaraKustiba;
use App\Models\Inventar;
use App\Models\KustibasVeidi;
use App\Models\Norakstishana;
use App\Models\Lietotajs;
use App\Models\Telpa;
use App\Http\Controllers\Concerns\HandlesSafeDelete;
use App\Http\Controllers\Concerns\NormalizesDateRanges;
use Illuminate\Support\Facades\DB;

// Kontrolieris inventāra kustību pārvaldībai.
class InventaraKustibaController extends Controller
{
    use HandlesSafeDelete;
    use NormalizesDateRanges;
    /**
     * Parāda kustību sarakstu ar filtrēšanu, kārtošanu un lapošanu.
     */
    public function showAllKustiba(Request $request)

    {
        // Pirms saraksta ielādes pārliecināmies, ka inventāra aktuālais stāvoklis
        // (telpa/atbildīgais) atbilst kustību vēsturei.
        $this->syncAllExistingDataBetweenKustibasAndInventars();

        $user = auth()->user();
    // Meklēšanas teksts un izvēlētā kolonna (vai "all" līdz meklēšanai visur)
        $q = trim($request->input('q', ''));
        $column = $request->input('column', 'all');
        $filterVeids = $request->input('filter_veids');
        $filterAtbildigais = $request->input('filter_atbildigais');
        [$dateFrom, $dateTo] = $this->normalizeDateRange($request, 'datums_no', 'datums_lidz');

        // Kārtošanas iestatījumi: kolonna un virziens (asc/desc)
        $sort = $request->input('sort', 'kustiba_id');
        $direction = strtolower($request->input('direction', 'asc')) === 'desc' ? 'desc' : 'asc';

        // Drošība: atļautās kolonnas, pēc kurām var kārtot
        $allowedSort = ['kustiba_id', 'datums', 'inventara_numurs', 'inventars', 'kustibas_veids', 'lietotajs', 'veca_telpa', 'jauna_telpa'];
        if (!in_array($sort, $allowedSort, true)) {
            $sort = 'kustiba_id';
        }

        // Atļautās kolonnas meklēšanai.
        $allowedColumns = ['all', 'inventars', 'inventara_numurs'];
        if (!in_array($column, $allowedColumns, true)) {
            $column = 'all';
        }

        // Ielādējam saistītos modeļus, lai samazinātu papildus SQL pieprasījumus skatā.
        $query = InventaraKustiba::query()->with(['inventars', 'lietotajs', 'jaunaisAtbildigais', 'kustibasVeids', 'vecaTelpa', 'jaunaTelpa']);

        // Ja lietotājs nav admins, rādam tikai viņa kustības.
        if (! $user->admina_tiesibas) {
            $query->where('atbildigais_lietotajs_id', $user->lietotajs_id);
        }   

        // Meklēšanas loģika pa vienu vai visām kolonnām.
        if ($q !== '') {
            if ($column === 'all') {
                $query->where(function ($query) use ($q) {
                    $query->whereHas('inventars', function ($q2) use ($q) {
                            $q2->where('nosaukums', 'like', "%{$q}%");
                        });
                });
            } elseif ($column === 'inventars') {
                $query->whereHas('inventars', function ($q2) use ($q) {
                    $q2->where('nosaukums', 'like', "%{$q}%");
                });
            } elseif ($column === 'inventara_numurs') {
                $query->whereHas('inventars', function ($q2) use ($q) {
                    $q2->where('inventara_numurs', 'like', "%{$q}%");
                });
            }
        }

        if (!empty($filterVeids)) {
            $query->where('kustibas_veids_id', (int) $filterVeids);
        }

        if (!empty($filterAtbildigais)) {
            $query->where('atbildigais_lietotajs_id', (int) $filterAtbildigais);
        }

        if (!empty($dateFrom)) {
            $query->whereDate('datums', '>=', $dateFrom);
        }

        if (!empty($dateTo)) {
            $query->whereDate('datums', '<=', $dateTo);
        }

        // Kārtošana pēc saistītajiem modeļiem (jāizmanto join, lai var kārtot pēc saistītajām tabulām)
        if ($sort === 'inventars') {
            $query->leftJoin('inventars', 'inventara_kustiba.inventars_id', '=', 'inventars.inventars_id')
                ->orderBy('inventars.nosaukums', $direction)
                ->select('inventara_kustiba.*');
        } elseif ($sort === 'inventara_numurs') {
            $query->leftJoin('inventars', 'inventara_kustiba.inventars_id', '=', 'inventars.inventars_id')
                ->orderBy('inventars.inventara_numurs', $direction)
                ->select('inventara_kustiba.*');
        } elseif ($sort === 'kustibas_veids') {
            $query->leftJoin('kustibas_veidi', 'inventara_kustiba.kustibas_veids_id', '=', 'kustibas_veidi.kustibas_veids_id')
                ->orderBy('kustibas_veidi.nosaukums', $direction)
                ->select('inventara_kustiba.*');
        } elseif ($sort === 'lietotajs') {
            $query->leftJoin('lietotajs', 'inventara_kustiba.atbildigais_lietotajs_id', '=', 'lietotajs.lietotajs_id')
                ->orderBy('lietotajs.lietotajvards', $direction)
                ->select('inventara_kustiba.*');
        } elseif ($sort === 'veca_telpa') {
            $query->leftJoin('telpa as veca', 'inventara_kustiba.veca_telpa_id', '=', 'veca.telpas_id')
                ->orderBy('veca.nosaukums', $direction)
                ->select('inventara_kustiba.*');
        } elseif ($sort === 'jauna_telpa') {
            $query->leftJoin('telpa as jauna', 'inventara_kustiba.jauna_telpa_id', '=', 'jauna.telpas_id')
                ->orderBy('jauna.nosaukums', $direction)
                ->select('inventara_kustiba.*');
        } else {
            $query->orderBy($sort, $direction);
        }

        // print_all režīmā ielādējam pilnu rezultātu kopu drukāšanai.
        $perPage = $request->boolean('print_all') ? 100000 : 7;
        $kustibas = $query->paginate($perPage)->withQueryString();

        $kustibasVeidiFiltram = KustibasVeidi::orderBy('nosaukums')->get();
        $lietotajiFiltram = Lietotajs::orderBy('lietotajvards')->get();

        return view('inventara_kustiba', compact(
            'kustibas',
            'sort',
            'direction',
            'q',
            'column',
            'kustibasVeidiFiltram',
            'lietotajiFiltram',
            'filterVeids',
            'filterAtbildigais',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Atver kustības izveides formu ar nepieciešamajiem izvēļņu datiem.
     */
    public function createKustiba()
    {
        $inventari = Inventar::withoutAcceptedNorakstishana()->orderBy('inventars_id','asc')->get();
        $kustibasVeidi = KustibasVeidi::orderBy('kustibas_veids_id','asc')->get();
        $lietotaji = Lietotajs::orderBy('lietotajs_id','asc')->get();
        $telpas = Telpa::orderBy('telpas_id','asc')->get();
        return view('createInventaraKustiba', ['inventari' => $inventari, 'kustibasVeidi' => $kustibasVeidi, 'lietotaji' => $lietotaji, 'telpas' => $telpas]);
    }

    /**
     * Validē un saglabā jaunu inventāra kustību.
     */
    public function KustibaSubmit(Request $req)
    {
        $data = $req->validate([
            'datums' => 'nullable|date',
            'inventars_id' => 'required|integer|exists:inventars,inventars_id',
            'kustibas_veids_id' => 'required|integer|exists:kustibas_veidi,kustibas_veids_id',
            'atbildigais_lietotajs_id' => 'nullable|integer|exists:lietotajs,lietotajs_id',
            'Jatbildigais_lietotajs_id' => 'nullable|integer|exists:lietotajs,lietotajs_id',
            'veca_telpa_id' => 'nullable|integer|exists:telpa,telpas_id',
            'jauna_telpa_id' => 'nullable|integer|exists:telpa,telpas_id',
            'piezimes' => 'nullable|string|max:255',
        ]);

        // Datumu veidošanas brīdī iestata sistēma, nevis lietotājs.
        $data['datums'] = now()->toDateString();

        // Kustības veids "Norakstīšana" tiek ģenerēts automātiski no norakstīšanas plūsmas.
        if ($this->isNorakstisanaMovement($data['kustibas_veids_id'] ?? null)) {
            return back()->withInput()->withErrors([
                'kustibas_veids_id' => 'Kustības veids "Norakstīšana" tiek pievienots automātiski un nav manuāli izvēlams.',
            ]);
        }

        // Veco telpu un esošo atbildīgo vienmēr ņemam no inventāra kartītes,
        // lai novērstu manuālas neatbilstības formā.
        $inventars = Inventar::findOrFail((int) $data['inventars_id']);
        $data['veca_telpa_id'] = $inventars->telpas_id;
        if (empty($inventars->atbildigais_id)) {
            return back()->withInput()->withErrors([
                'atbildigais_lietotajs_id' => 'Izvēlētajam inventāram nav norādīts atbildīgais darbinieks.',
            ]);
        }
        $data['atbildigais_lietotajs_id'] = (int) $inventars->atbildigais_id;

        // Kustības tipa noteikšana ietekmē obligātos laukus un to validāciju.
        $isParvietosana = $this->isParvietosanaMovement($data['kustibas_veids_id']);
        $isNodosana = $this->isNodosanaMovement($data['kustibas_veids_id']);

        if ($isParvietosana && empty($data['jauna_telpa_id'])) {
            return back()->withInput()->withErrors([
                'jauna_telpa_id' => 'Kustības veidam "Pārvietošana" ir obligāti jānorāda jaunā telpa.',
            ]);
        }

        if ($isNodosana) {
            if (empty($data['Jatbildigais_lietotajs_id'])) {
                return back()->withInput()->withErrors([
                    'Jatbildigais_lietotajs_id' => 'Kustības veidam "Nodošana" ir obligāti jānorāda jaunais atbildīgais.',
                ]);
            }

            if ((int) $data['Jatbildigais_lietotajs_id'] === (int) $data['atbildigais_lietotajs_id']) {
                return back()->withInput()->withErrors([
                    'Jatbildigais_lietotajs_id' => 'Jaunais atbildīgais nedrīkst sakrist ar esošo atbildīgo.',
                ]);
            }
        } else {
            $data['Jatbildigais_lietotajs_id'] = 0;
        }

        // Ja kustības tips neprasa jauno telpu, to notīrām konsekventam datu modelim.
        if (! $isParvietosana) {
            $data['jauna_telpa_id'] = null;
        }

        $i = new InventaraKustiba();
        $i->datums = $data['datums'];
        $i->inventars_id = $data['inventars_id'];
        $i->kustibas_veids_id = $data['kustibas_veids_id'] ?? null;
        $i->atbildigais_lietotajs_id = $data['atbildigais_lietotajs_id'];
        $i->Jatbildigais_lietotajs_id = $data['Jatbildigais_lietotajs_id'] ?? null;
        $i->veca_telpa_id = $data['veca_telpa_id'] ?? null;
        $i->jauna_telpa_id = $data['jauna_telpa_id'] ?? null;
        $i->piezimes = $data['piezimes'] ?? null;
        $i->save();

        // Pēc kustības saglabāšanas atjaunojam inventāra stāvokli,
        // lai saraksti rāda aktuālo telpu/atbildīgo.
        if ($isNodosana) {
            $inventars->atbildigais_id = $data['Jatbildigais_lietotajs_id'];
        }

        if ($isParvietosana && ! empty($data['jauna_telpa_id'])) {
            $inventars->telpas_id = $data['jauna_telpa_id'];
        }

        if ($isNodosana || $isParvietosana) {
            $inventars->save();
        }

        return redirect()->to('/inventara_kustiba')->with('success','Ieraksts pievienots');
    }

    /**
     * Parāda vienas kustības detalizētu informāciju.
     */
    public function KustibaDetails($id)
    {
        $i = InventaraKustiba::with(['inventars','lietotajs','kustibasVeids'])->find($id);
        return view('detailsInventaraKustiba', ['kustiba' => $i]);
    }

    /**
     * Atver kustības rediģēšanas formu (tikai administratoram).
     */
    public function KustibaEdit($id)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        $i = InventaraKustiba::find($id);
        $inventari = Inventar::withoutAcceptedNorakstishana()->orderBy('inventars_id','asc')->get();
        $kustibasVeidi = KustibasVeidi::orderBy('kustibas_veids_id','asc')->get();
        $lietotaji = Lietotajs::orderBy('lietotajs_id','asc')->get();
        $telpas = Telpa::orderBy('telpas_id','asc')->get();
        return view('editInventaraKustiba', ['kustiba' => $i, 'inventari' => $inventari, 'kustibasVeidi' => $kustibasVeidi, 'lietotaji' => $lietotaji, 'telpas' => $telpas]);
    }

    /**
     * Saglabā kustības ieraksta izmaiņas.
     */
    public function editSubmit(Request $req, $id)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }
        $data = $req->validate([
            'datums' => 'required|date',
            'inventars_id' => 'required|integer|exists:inventars,inventars_id',
            'kustibas_veids_id' => 'nullable|integer|exists:kustibas_veidi,kustibas_veids_id',
            'atbildigais_lietotajs_id' => 'required|integer|exists:lietotajs,lietotajs_id',
            'Jatbildigais_lietotajs_id' => 'nullable|integer|exists:lietotajs,lietotajs_id',
            'veca_telpa_id' => 'nullable|integer|exists:telpa,telpas_id',
            'jauna_telpa_id' => 'nullable|integer|exists:telpa,telpas_id',
            'piezimes' => 'nullable|string|max:255',
        ]);

        $existingKustiba = InventaraKustiba::findOrFail($id);

        if (
            $this->isNorakstisanaMovement($data['kustibas_veids_id'] ?? null)
            && (int) $existingKustiba->kustibas_veids_id !== (int) ($data['kustibas_veids_id'] ?? 0)
        ) {
            return back()->withInput()->withErrors([
                'kustibas_veids_id' => 'Kustības veids "Norakstīšana" tiek pievienots automātiski un nav manuāli izvēlams.',
            ]);
        }

        $inventars = Inventar::findOrFail((int) $data['inventars_id']);
        $data['veca_telpa_id'] = $inventars->telpas_id;
        if (empty($inventars->atbildigais_id)) {
            return back()->withInput()->withErrors([
                'atbildigais_lietotajs_id' => 'Izvēlētajam inventāram nav norādīts atbildīgais darbinieks.',
            ]);
        }
        $data['atbildigais_lietotajs_id'] = (int) $inventars->atbildigais_id;

        $isParvietosana = $this->isParvietosanaMovement($data['kustibas_veids_id'] ?? null);
        $isNodosana = $this->isNodosanaMovement($data['kustibas_veids_id'] ?? null);

        if ($isParvietosana && empty($data['jauna_telpa_id'])) {
            return back()->withInput()->withErrors([
                'jauna_telpa_id' => 'Kustības veidam "Pārvietošana" ir obligāti jānorāda jaunā telpa.',
            ]);
        }

        if ($isNodosana) {
            if (empty($data['Jatbildigais_lietotajs_id'])) {
                return back()->withInput()->withErrors([
                    'Jatbildigais_lietotajs_id' => 'Kustības veidam "Nodošana" ir obligāti jānorāda jaunais atbildīgais.',
                ]);
            }

            if ((int) $data['Jatbildigais_lietotajs_id'] === (int) $data['atbildigais_lietotajs_id']) {
                return back()->withInput()->withErrors([
                    'Jatbildigais_lietotajs_id' => 'Jaunais atbildīgais nedrīkst sakrist ar esošo atbildīgo.',
                ]);
            }
        } else {
            $data['Jatbildigais_lietotajs_id'] = 0;
        }

        if (! $isParvietosana) {
            $data['jauna_telpa_id'] = null;
        }

        DB::table('inventara_kustiba')->where('kustiba_id',$id)->update([
            'datums' => $data['datums'],
            'inventars_id' => $data['inventars_id'],
            'kustibas_veids_id' => $data['kustibas_veids_id'] ?? null,
            'atbildigais_lietotajs_id' => $data['atbildigais_lietotajs_id'],
            'Jatbildigais_lietotajs_id' => $data['Jatbildigais_lietotajs_id'] ?? 0,
            'veca_telpa_id' => $data['veca_telpa_id'] ?? null,
            'jauna_telpa_id' => $data['jauna_telpa_id'] ?? null,
            'piezimes' => $data['piezimes'] ?? null,
        ]);

        if ($isNodosana) {
            $inventars->atbildigais_id = $data['Jatbildigais_lietotajs_id'];
        }

        if ($isParvietosana && ! empty($data['jauna_telpa_id'])) {
            $inventars->telpas_id = $data['jauna_telpa_id'];
        }

        if ($isNodosana || $isParvietosana) {
            $inventars->save();
        }

        return redirect()->to('/inventara_kustiba')->with('success','Ieraksts atjaunināts');
    }

    /**
     * Dzēš kustības ierakstu (tikai administratoram).
     */
    public function KustibaDelete($id)
    {
        if (!auth()->user()->admina_tiesibas) {
            abort(403, 'Ir nepieciešamas administratora tiesības.');
        }

        // Dzēšot norakstīšanas kustību, dzēšam arī saistīto norakstīšanu,
        // lai tā netiktu atjaunota ar sinhronizāciju.
        $kustiba = InventaraKustiba::findOrFail($id);
        $this->deleteLinkedNorakstishanaForKustiba($kustiba);
        $this->deleteWithForeignKeyChecksDisabled('inventara_kustiba', 'kustiba_id', $id);

        return redirect('/inventara_kustiba')->with('success', $this->buildDeleteMessage('Inventāra kustības', []));
    }

    private function deleteLinkedNorakstishanaForKustiba(InventaraKustiba $kustiba): void
    {
        // Saistītu norakstīšanu dzēšam tikai tad, ja dzēstā kustība ir norakstīšanas tipa.
        if (! $this->isNorakstisanaMovement($kustiba->kustibas_veids_id)) {
            return;
        }

        $linkedNorakstishanaIds = collect();
        $notes = (string) ($kustiba->piezimes ?? '');

        // 1) Primārā sasaite: no piezīmēm izlasām [NORAKSTISHANA:id].
        if (preg_match('/\[NORAKSTISHANA:(\d+)\]/', $notes, $matches) === 1) {
            $linkedNorakstishanaIds = collect([(int) $matches[1]]);
        }

        if ($linkedNorakstishanaIds->isEmpty()) {
            // 2) Rezerves sasaite vecākiem sinhronizētiem ierakstiem.
            $linkedNorakstishanaIds = Norakstishana::query()
                ->where('inventara_id', (int) $kustiba->inventars_id)
                ->where('akceptets', true)
                ->whereDate('norDatums', $kustiba->datums)
                ->where(function ($query) {
                    $query->where('iemesls', 'Sinhronizēts no kustības')
                        ->orWhere('iemesls', 'like', 'Sinhronizēts no kustības%');
                })
                ->pluck('norakstishana_id');
        }

        if ($linkedNorakstishanaIds->isEmpty()) {
            // Ja tiešu saistību neatrodam, dzēšam tikai kustību.
            return;
        }

        // Dzēšam saistīto norakstīšanu, lai sinhronizācija to neatjaunotu.
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        try {
            DB::table('Norakstishana')
                ->whereIn('norakstishana_id', $linkedNorakstishanaIds->all())
                ->delete();
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    private function syncAllExistingDataBetweenKustibasAndInventars(): void
    {
        // Vēsturiskā sinhronizācija: atjaunojam inventāra aktuālo stāvokli no kustību žurnāla.
        // Sakārtojam pēc datuma un ID, lai piemērošanas secība būtu stabila.
        $kustibas = InventaraKustiba::query()
            ->orderBy('datums')
            ->orderBy('kustiba_id')
            ->get();

        foreach ($kustibas as $kustiba) {
            $inventars = Inventar::find($kustiba->inventars_id);

            if (! $inventars) {
                continue;
            }

            $isNodosana = $this->isNodosanaMovement($kustiba->kustibas_veids_id);
            $isParvietosana = $this->isParvietosanaMovement($kustiba->kustibas_veids_id);
            $needsSave = false;

            // Nodošana maina atbildīgo darbinieku.
            if ($isNodosana && ! empty($kustiba->Jatbildigais_lietotajs_id)) {
                $newAtbildigais = (int) $kustiba->Jatbildigais_lietotajs_id;
                if ($newAtbildigais > 0 && (int) $inventars->atbildigais_id !== $newAtbildigais) {
                    $inventars->atbildigais_id = $newAtbildigais;
                    $needsSave = true;
                }
            }

            // Pārvietošana maina inventāra telpu.
            if ($isParvietosana && ! empty($kustiba->jauna_telpa_id)) {
                $newTelpa = (int) $kustiba->jauna_telpa_id;
                if ($newTelpa > 0 && (int) $inventars->telpas_id !== $newTelpa) {
                    $inventars->telpas_id = $newTelpa;
                    $needsSave = true;
                }
            }

            if ($needsSave) {
                $inventars->save();
            }
        }
    }

    private function isParvietosanaMovement($kustibasVeidsId): bool
    {
        if (empty($kustibasVeidsId)) {
            return false;
        }

        $nosaukums = KustibasVeidi::query()
            ->where('kustibas_veids_id', $kustibasVeidsId)
            ->value('nosaukums');

        if (! is_string($nosaukums)) {
            return false;
        }

        $normalized = mb_strtolower($nosaukums, 'UTF-8');

        return str_contains($normalized, 'pārvietošan') || str_contains($normalized, 'parvietosan');
    }

    private function isNorakstisanaMovement($kustibasVeidsId): bool
    {
        if (empty($kustibasVeidsId)) {
            return false;
        }

        $nosaukums = KustibasVeidi::query()
            ->where('kustibas_veids_id', $kustibasVeidsId)
            ->value('nosaukums');

        if (! is_string($nosaukums)) {
            return false;
        }

        $normalized = mb_strtolower($nosaukums, 'UTF-8');

        return str_contains($normalized, 'norakst');
    }

    private function isNodosanaMovement($kustibasVeidsId): bool
    {
        if (empty($kustibasVeidsId)) {
            return false;
        }

        $nosaukums = KustibasVeidi::query()
            ->where('kustibas_veids_id', $kustibasVeidsId)
            ->value('nosaukums');

        if (! is_string($nosaukums)) {
            return false;
        }

        $normalized = mb_strtolower($nosaukums, 'UTF-8');

        return str_contains($normalized, 'nodo');
    }
}
