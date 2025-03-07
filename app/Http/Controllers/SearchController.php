<?php

namespace App\Http\Controllers;
use App\Models\Company; 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('query');
        $cacheKey = 'search_' . md5($query);
        $results = Cache::remember($cacheKey, 60, function () use ($query) {
            return Company::select(['firmen_id', 'anrede', 'webseite', 'firmenname', 'vorname', 'nachname', 'id', 'jobtitel', 'hausnummer', 'strasse', 'ort', 'plz', 'land'])
                          ->where('firmenname', 'like', "%{$query}%")
                          ->orWhere('vorname', 'like', "%{$query}%")
                          ->orWhere('full_name', 'like', "%{$query}%")
                          ->orWhere('nachname', 'like', "%{$query}%")
                          ->orWhere('jobtitel', 'like', "%{$query}%")
                           ->orWhere('firmen_id', 'like', "%{$query}%")
                          ->orWhere('webseite', 'like', "%{$query}%")
                          ->take(5) 
                          ->get();
        });
    
        return response()->json($results);
    }

    public function details($id)
    {
        $id = urldecode($id);
        $cacheKey = 'details_' . $id;
        $details = Cache::remember($cacheKey, 60, function () use ($id) {
            $explode = explode('_', $id);
            // $firmenname = str_replace('_', ' ', $explode[2]);
            return Company::where('vorname', '=', $explode[0])
                            ->where('nachname', '=', $explode[1])
                            ->first() ?? [];
        });

        if (!$details) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json($details);
    }

    public function loadMore(Request $request)
    {
        $perPage = $request->get('size'); 
        $results = Company::select(['firmen_id','anrede', 'webseite', 'firmenname', 'vorname', 'nachname', 'id', 'jobtitel', 'hausnummer', 'strasse', 'ort', 'plz', 'land','telefonnummer','branche_hauptkategorie']) 
                   ->paginate($perPage);

        return response()->json($results);
    }

}