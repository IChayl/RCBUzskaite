<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\KategorijaModel;
use Illuminate\Support\Facades\DB;

class KategorijaController extends Controller
{

// Rāda visu kategoriju sarakstu
   public function showAllKategorija(Request $request)
    {
        $q = trim($request->input('q', ''));
        $sort = $request->input('sort', 'kategorija_id');
        $direction = strtolower($request->input('direction', 'asc')) === 'desc' ? 'desc' : 'asc';

        $allowedSort = ['kategorija_id', 'nosaukums', 'apraksts'];
        if (!in_array($sort, $allowedSort, true)) {
            $sort = 'kategorija_id';
        }

        $query = KategorijaModel::query();

        if ($q !== '') {
            $query->where(function ($query) use ($q) {
                $query->where('nosaukums', 'like', "%{$q}%")
                    ->orWhere('apraksts', 'like', "%{$q}%");
            });
        }

        $kategorija = $query->orderBy($sort, $direction)->get();

        return view('kategorija', compact('kategorija', 'sort', 'direction', 'q'));
    }

    
     //Rāda formu jaunas kategorijas izveidei.
     
    public function createKategorija()
    {
        return view('createKategorija');
    }

   
     //Saglabā jaunu kategoriju datubāzē.
     
 public function KatSubmit(Request $Kategorija)
    {
        $kategorija = new KategorijaModel();
        $kategorija->nosaukums = $Kategorija->input('nosaukums');
        $kategorija->apraksts = $Kategorija->input('apraksts');
        $kategorija->save();

        return redirect()->to('/kategorija')->with('success', 'Ieraksts pievienots');
    }

// Rāda konkrētas kategorijas detaļas.
        public function Katdetails($id)
    {
        // using the model directly is simpler and honours primary key
        $kategorija = KategorijaModel::find($id);
        return view('detailsKategorija', ['kategorija' => $kategorija]);
    }

    public function KatEdit($id)
    {
        // use the Eloquent model so the custom primary key is respected
        $kategorija = KategorijaModel::find($id);
        return view('editKategorija', ['kategorija' => $kategorija]);
    }

    public function editSubmit(Request $dati, $id)
    {
    

        DB::table('kategorija')
            ->where('kategorija_id', $id)
            ->update([
                'nosaukums' => $dati->input('nosaukums'),
                'apraksts' => $dati->input('apraksts'),
            ]);

        return redirect()->to('/kategorija')->with('success', 'Ieraksts atjaunināts');
    }

     public function KatDelete($id)
    {
        DB::table('kategorija')->where('kategorija_id', $id)->delete();
        return redirect('/kategorija')->with('success', 'Ieraksts dzēsts');
    }
}
