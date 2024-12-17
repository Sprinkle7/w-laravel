<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Services\ArticleGeneratorService;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    public function generateArticle(Request $request)
    {
        $validated = $request->validate([
            'company_ids' => 'required|array|min:1',
            'company_ids.*' => 'integer|exists:companies,id'
        ]);

        $companies = Company::whereIn('id', $validated['company_ids'])->get();

        $articles = [];
        foreach ($companies as $company) {
            $data = [
                'title' => (!empty($company->title) ? $company->title: ''),
                'anrede' => $company->anrede,
                'vorname' => $company->vorname,
                'nachname' => $company->nachname,
                'jobtitel' => $company->jobtitel,
                'webseite' => $company->other_sources,
                'telefonnummer_firma' => $company->telefonnummer_firma,
                'firmen_id' => $company->firmen_id,
                'strasse' => $company->strasse,
                'beschreibung_nace_code_ebene_2' => $company->beschreibung_nace_code_ebene_2,
                'wz_code' => $company->wz_code,
                'telefonnummer' => $company->telefonnummer,
            ];

            $structuredContent = ArticleGeneratorService::generateArticle($data);

            foreach ($structuredContent as $section) {
                DB::table('article_contents')->insert([
                    'company_id' => $company->id, 
                    'tag' => $section['tag'], 
                    'value' => $section['value'], 
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            $articles[] = [
                'company_id' => $company->id,
                'structured_content' => $structuredContent
            ];
        }

        return response()->json(['articles' => $data]);
    }
}
