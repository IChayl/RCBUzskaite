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
     * @var array<int, string|null>
     */
    private array $movementTypeNames = [];

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
        $i->apstiprinats = Auth::user()->admina_tiesibas;
        $i->save();

        $this->syncInventarsStateFromKustibas([(int) $data['inventars_id']]);

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
        $affectedInventars = array_values(array_unique([
            (int) $existingKustiba->inventars_id,
            (int) $data['inventars_id'],
        ]));

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

        $this->syncInventarsStateFromKustibas($affectedInventars);

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
        $inventarsId = (int) $kustiba->inventars_id;
        $fallbackStates = [
            $inventarsId => [
                'telpas_id' => $kustiba->veca_telpa_id !== null ? (int) $kustiba->veca_telpa_id : null,
                'atbildigais_id' => ! empty($kustiba->atbildigais_lietotajs_id) ? (int) $kustiba->atbildigais_lietotajs_id : null,
            ],
        ];

        $this->deleteLinkedNorakstishanaForKustiba($kustiba);
        $this->deleteWithForeignKeyChecksDisabled('inventara_kustiba', 'kustiba_id', $id);
        $this->syncInventarsStateFromKustibas([$inventarsId], $fallbackStates);

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
        [$disableStatement, $enableStatement] = $this->getForeignKeyCheckStatements();

        if ($disableStatement !== null) {
            DB::statement($disableStatement);
        }

        try {
            DB::table('Norakstishana')
                ->whereIn('norakstishana_id', $linkedNorakstishanaIds->all())
                ->delete();
        } finally {
            if ($enableStatement !== null) {
                DB::statement($enableStatement);
            }
        }
    }

    private function syncAllExistingDataBetweenKustibasAndInventars(): void
    {
        $this->syncInventarsStateFromKustibas();
    }

    /**
     * @param array<int, int> $inventarsIds
     * @param array<int, array{telpas_id:int|null,atbildigais_id:int|null}> $fallbackStates
     */
    private function syncInventarsStateFromKustibas(array $inventarsIds = [], array $fallbackStates = []): void
    {
        $inventarsIds = array_values(array_unique(array_filter(array_map('intval', $inventarsIds), static fn (int $id): bool => $id > 0)));

        $inventariQuery = Inventar::query();
        if ($inventarsIds !== []) {
            $inventariQuery->whereIn('inventars_id', $inventarsIds);
        }

        $inventari = $inventariQuery->get()->keyBy('inventars_id');
        if ($inventari->isEmpty()) {
            return;
        }

        $kustibasByInventars = InventaraKustiba::query()
            ->whereIn('inventars_id', $inventari->keys()->all())
            ->orderBy('inventars_id')
            ->orderBy('datums')
            ->orderBy('kustiba_id')
            ->get()
            ->groupBy('inventars_id');

        foreach ($inventari as $inventarsId => $inventars) {
            $inventaraKustibas = $kustibasByInventars->get($inventarsId, collect());
            $targetTelpaId = $inventars->telpas_id;
            $targetAtbildigaisId = $inventars->atbildigais_id;

            if ($inventaraKustibas->isNotEmpty()) {
                $firstKustiba = $inventaraKustibas->first();

                if (! empty($firstKustiba->veca_telpa_id)) {
                    $targetTelpaId = (int) $firstKustiba->veca_telpa_id;
                }

                $initialAtbildigaisId = (int) ($firstKustiba->atbildigais_lietotajs_id ?? 0);
                if ($initialAtbildigaisId > 0) {
                    $targetAtbildigaisId = $initialAtbildigaisId;
                }

                foreach ($inventaraKustibas as $kustiba) {
                    if ($this->isParvietosanaMovement($kustiba->kustibas_veids_id) && ! empty($kustiba->jauna_telpa_id)) {
                        $targetTelpaId = (int) $kustiba->jauna_telpa_id;
                    }

                    if ($this->isNodosanaMovement($kustiba->kustibas_veids_id) && ! empty($kustiba->Jatbildigais_lietotajs_id)) {
                        $newAtbildigaisId = (int) $kustiba->Jatbildigais_lietotajs_id;
                        if ($newAtbildigaisId > 0) {
                            $targetAtbildigaisId = $newAtbildigaisId;
                        }
                    }
                }
            } elseif (array_key_exists((int) $inventarsId, $fallbackStates)) {
                $fallbackState = $fallbackStates[(int) $inventarsId];

                if (array_key_exists('telpas_id', $fallbackState) && $fallbackState['telpas_id'] !== null) {
                    $targetTelpaId = (int) $fallbackState['telpas_id'];
                }

                if (array_key_exists('atbildigais_id', $fallbackState)) {
                    $targetAtbildigaisId = $fallbackState['atbildigais_id'] !== null
                        ? (int) $fallbackState['atbildigais_id']
                        : null;
                }
            }

            if (
                (int) $inventars->telpas_id !== (int) $targetTelpaId
                || $this->normalizeNullableId($inventars->atbildigais_id) !== $this->normalizeNullableId($targetAtbildigaisId)
            ) {
                $inventars->telpas_id = $targetTelpaId;
                $inventars->atbildigais_id = $targetAtbildigaisId;
                $inventars->save();
            }
        }
    }

    private function normalizeNullableId($value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        $normalized = (int) $value;

        return $normalized > 0 ? $normalized : null;
    }

    private function isParvietosanaMovement($kustibasVeidsId): bool
    {
        if (empty($kustibasVeidsId)) {
            return false;
        }

        $normalized = $this->getMovementTypeName($kustibasVeidsId);

        if ($normalized === null) {
            return false;
        }

        return str_contains($normalized, 'pārvietošan') || str_contains($normalized, 'parvietosan');
    }

    private function isNorakstisanaMovement($kustibasVeidsId): bool
    {
        if (empty($kustibasVeidsId)) {
            return false;
        }

        $normalized = $this->getMovementTypeName($kustibasVeidsId);

        if ($normalized === null) {
            return false;
        }

        return str_contains($normalized, 'norakst');
    }

    private function isNodosanaMovement($kustibasVeidsId): bool
    {
        if (empty($kustibasVeidsId)) {
            return false;
        }

        $normalized = $this->getMovementTypeName($kustibasVeidsId);

        if ($normalized === null) {
            return false;
        }

        return str_contains($normalized, 'nodo');
    }

    private function getMovementTypeName($kustibasVeidsId): ?string
    {
        if (empty($kustibasVeidsId)) {
            return null;
        }

        $kustibasVeidsId = (int) $kustibasVeidsId;

        if (! array_key_exists($kustibasVeidsId, $this->movementTypeNames)) {
            $nosaukums = KustibasVeidi::query()
                ->where('kustibas_veids_id', $kustibasVeidsId)
                ->value('nosaukums');

            $this->movementTypeNames[$kustibasVeidsId] = is_string($nosaukums)
                ? mb_strtolower($nosaukums, 'UTF-8')
                : null;
        }

        return $this->movementTypeNames[$kustibasVeidsId];
    }
}
