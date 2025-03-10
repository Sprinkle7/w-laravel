<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Facades\Log;
class CompanyController extends Controller
{
    /**
    * 
    * Display a listing of the resource.
    * @return \Illuminate\Http\Response
    *
    */

    public function index(Request $request)
    {
        try {
            $query = Company::query();
            // Apply search filtering
            if ($search = $request->input('search')['value'] ?? null) {
                $query->where('firmenname', 'like', "%{$search}%")
                ->orWhere('vorname', 'like', "%{$search}%")
                ->orWhere('nachname', 'like', "%{$search}%")
                ->orWhere('full_name', 'like', "%{$search}%")
                ->orWhere('jobtitel', 'like', "%{$search}%")
                ->orWhere('firmen_id', 'like', "%{$search}%")
                ->orWhere('webseite', 'like', "%{$search}%");
            }
    
            $totalRecords = Company::count(); 
            $filteredRecords = $query->count();
            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $query->skip($start)->take($length);
            $companies = $query->get();

            $formattedData = $companies->map(function ($company, $index) use ($start) {
                return [
                    'checkbox' => '<input type="checkbox" value="' . $company->id . '" class="form-checkbox">',
                    'id' => "<span class='text-sm font-rajdhani'>" . ($start + $index + 1) . "</span>",
                    'name' => "<span class='text-sm font-rajdhani'>{$company->anrede} {$company->vorname} {$company->nachname}</span>",
                    'firmen_id' => "<span class='text-sm font-rajdhani uppercase'>{$company->firmen_id}</span>",
                    'firmenname' => "<a href='{$company->webseite}' target='_blank' class='text-sm text-red-600 font-rajdhani font-semibold'>{$company->firmenname}</a>",
                    'jobtitel' => "<span class='text-sm font-rajdhani'>{$company->jobtitel}</span>",
                    'webseite' => "<span class='text-sm font-rajdhani'>{$company->webseite}</span>",
                    'actions' => ' 
                    <button class="edit-company-btn text-gray-600 hover:text-gray-800" data-id="' . $company->id . '">
                        <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3.49996 12.6667H4.44996L10.9666 6.15L10.0166 5.2L3.49996 11.7167V12.6667ZM2.16663 14V11.1667L10.9666 2.38333C11.1 2.26111 11.2472 2.16667 11.4083 2.1C11.5694 2.03333 11.7388 2 11.9166 2C12.0944 2 12.2666 2.03333 12.4333 2.1C12.6 2.16667 12.7444 2.26667 12.8666 2.4L13.7833 3.33333C13.9166 3.45556 14.0139 3.6 14.075 3.76667C14.1361 3.93333 14.1666 4.1 14.1666 4.26667C14.1666 4.44444 14.1361 4.61389 14.075 4.775C14.0139 4.93611 13.9166 5.08333 13.7833 5.21667L4.99996 14H2.16663ZM10.4833 5.68333L10.0166 5.2L10.9666 6.15L10.4833 5.68333Z" fill="#1D1B20"/>
                        </svg>
                    </button>
                    <button class="delete-company-btn text-gray-600 hover:text-gray-800" data-id="' . $company->id . '">
                        <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4.83325 14C4.46659 14 4.1527 13.8694 3.89159 13.6083C3.63047 13.3472 3.49992 13.0333 3.49992 12.6667V4H2.83325V2.66667H6.16659V2H10.1666V2.66667H13.4999V4H12.8333V12.6667C12.8333 13.0333 12.7027 13.3472 12.4416 13.6083C12.1805 13.8694 11.8666 14 11.4999 14H4.83325ZM11.4999 4H4.83325V12.6667H11.4999V4ZM6.16659 11.3333H7.49992V5.33333H6.16659V11.3333ZM8.83325 11.3333H10.1666V5.33333H8.83325V11.3333Z" fill="#1D1B20"/>
                        </svg>
                    </button>'
                ];
            });
    
            return response()->json([
                'draw' => (int) $request->input('draw'),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $formattedData
            ]);
    
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function import(Request $request)
    {
        // Validate the uploaded file
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,txt|max:250048', // Max size 2MB, adjust if necessary
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        // Open the CSV file
        $file = $request->file('file');
        $filePath = $file->getRealPath();
        $handle = fopen($filePath, 'r');

        // Define the expected columns and their mappings
        $requiredColumns = [
            'ID','Titel','chr', 'Vorname', 'Nachname', 'Firmen-ID', 'Firmenname', 'Jobtitel', 'Webseite',
            'E-Mail-Adresse', 'Straße', 'Hausnummer', 'PLZ', 'Ort', 'Land', 'Telefonnummer',
            'Telefonnummer (Firma)', 'E-Mail-Adresse (Firma)', 'LinkedIn Account (Firma)',
            'NACE-Code (Ebene 1)', 'NACE-Code (Ebene 2)', 'Beschreibung NACE-Code (Ebene 2)',
            'WZ-Code', 'Beschreibung WZ-Code', 'Branche (Hauptkategorie)', 'Branche (Unterkategorie)','Weitere Quellen','KI TEXT'
        ];

        // Read the CSV header row and map it to column indices
        $header = fgetcsv($handle, 0, ",");
        $columnMap = [];

        foreach ($requiredColumns as $column) {
            $index = array_search($column, $header);
            if ($index !== false) {
                $columnMap[$column] = $index;
            }
        }

        if (count($columnMap) !== count($requiredColumns)) {
            return response()->json(['error' => 'CSV does not contain the required headers.'], 400);
        }

        // Process each row in the CSV
        while (($row = fgetcsv($handle, 0, ",")) !== false) {
            // Check if the company with this 'firmen_id' already exists
            $company = Company::where('firmen_id', $row[$columnMap['Firmen-ID']] ?? null)->first();
            $rawContent = $row[$columnMap['KI TEXT']];
            // 1) Extract the meta title:
            $metaTitle = '';
            $metaDescription = '';
            $htmlContent = '';

            if(!empty($rawContent)) {
                if (preg_match('/Meta-Titel:\s*(.*)/i', $rawContent, $matchesTitle)) {
                    $metaTitle = trim($matchesTitle[1]);
                }

                if (preg_match('/Meta-Description:\s*(.*)/i', $rawContent, $matchesDesc)) {
                    $metaDescription = trim($matchesDesc[1]);
                }

                $processed = preg_replace('/Meta-Titel:.*(\r?\n)?/i', '', $rawContent);
                $processed = preg_replace('/Meta-Description:.*(\r?\n)?/i', '', $processed);

                $lines = preg_split('/\r?\n/', $processed);
                $htmlLines = [];

                foreach ($lines as $line) {
                    $original = trim($line);
                    if ($original === '') {
                        continue; 
                    }

                    if (preg_match('/^#{3}\s+(.*)/', $original, $m)) {
                        $htmlLines[] = "<h3>".trim($m[1])."</h3>";
                        continue;
                    }

                    if (preg_match('/^#{2}\s+(.*)/', $original, $m)) {
                        $htmlLines[] = "<h2>".trim($m[1])."</h2>";
                        continue;
                    }

                    if (preg_match('/^#\s+(.*)/', $original, $m)) {
                        $htmlLines[] = "<h1>".trim($m[1])."</h1>";
                        continue;
                    }

                    if (preg_match('/^H1:\s*(.*)/i', $original, $m)) {
                        $htmlLines[] = "<h1>".trim($m[1])."</h1>";
                        continue;
                    }

                    if (preg_match('/^H2:\s*(.*)/i', $original, $m)) {
                        $htmlLines[] = "<h2>".trim($m[1])."</h2>";
                        continue;
                    }

                    if (preg_match('/^H3:\s*(.*)/i', $original, $m)) {
                        $htmlLines[] = "<h3>".trim($m[1])."</h3>";
                        continue;
                    }

                    $lineNoStars = preg_replace('/^\*+\s*/', '', $original);

                    if ($lineNoStars !== $original) {
                        $htmlLines[] = "<p>" . trim($lineNoStars) . "</p>";
                        continue;
                    }

                    $htmlLines[] = "<p>".$original."</p>";
                }

                $htmlContent = implode("\n", $htmlLines);
                $metaTitle = preg_replace('/^\**\s*/', '', $metaTitle);
                $metaDescription = preg_replace('/^\**\s*/', '', $metaDescription);
                $metaTitle = preg_replace('/\*+/', '', $metaTitle);
                $metaDescription = preg_replace('/\*+/', '', $metaDescription);
                $htmlContent = preg_replace('/\(H\d\)/i', '', $htmlContent);
                $htmlContent = preg_replace('/\*+/', '', $htmlContent);
                $htmlContent = preg_replace('/H\d:\s*/i', '', $htmlContent);
            }

            // Prepare the data for either updating or creating the record
            $data = [
                'title' => $row[$columnMap['Titel']] ?? null,
                'anrede' => $row[$columnMap['chr']] ?? null,
                'vorname' => $row[$columnMap['Vorname']] ?? null,
                'full_name' => $row[$columnMap['Vorname']]. ' ' .$row[$columnMap['Nachname']] ?? null,
                'nachname' => $row[$columnMap['Nachname']] ?? null,
                'firmen_id' => $row[$columnMap['Firmen-ID']] ?? null,
                'firmenname' => $row[$columnMap['Firmenname']] ?? null,
                'jobtitel' => $row[$columnMap['Jobtitel']] ?? null,
                'webseite' => $row[$columnMap['Webseite']] ?? null,
                'email_adresse' => $row[$columnMap['E-Mail-Adresse']] ?? null,
                'strasse' => $row[$columnMap['Straße']] ?? null,
                'hausnummer' => $row[$columnMap['Hausnummer']] ?? null,
                'plz' => $row[$columnMap['PLZ']] ?? null,
                'ort' => $row[$columnMap['Ort']] ?? null,
                'cid' => $row[$columnMap['ID']] ?? null,
                'land' => $row[$columnMap['Land']] ?? null,
                'telefonnummer' => $row[$columnMap['Telefonnummer']] ?? null,
                'telefonnummer_firma' => $row[$columnMap['Telefonnummer (Firma)']] ?? null,
                'email_adresse_firma' => $row[$columnMap['E-Mail-Adresse (Firma)']] ?? null,
                'linkedin_account_firma' => $row[$columnMap['LinkedIn Account (Firma)']] ?? null,
                'nace_code_ebene_1' => $row[$columnMap['NACE-Code (Ebene 1)']] ?? null,
                'nace_code_ebene_2' => $row[$columnMap['NACE-Code (Ebene 2)']] ?? null,
                'beschreibung_nace_code_ebene_2' => $row[$columnMap['Beschreibung NACE-Code (Ebene 2)']] ?? null,
                'wz_code' => $row[$columnMap['WZ-Code']] ?? null,
                'beschreibung_wz_code' => $row[$columnMap['Beschreibung WZ-Code']] ?? null,
                'branche_hauptkategorie' => $row[$columnMap['Branche (Hauptkategorie)']] ?? null,
                'branche_unterkategorie' => $row[$columnMap['Branche (Unterkategorie)']] ?? null,
                'other_sources' => $row[$columnMap['Weitere Quellen']] ?? null,
                'checking' => $row[$columnMap['KI TEXT']] ?? null,
                'meta_title' => $metaTitle,
                'meta_description' => $metaDescription,
                'html_content' => $htmlContent
            ];

            // Update if exists, otherwise create a new record
            if ($company) {
                $company->update($data);
            } else {
                Company::create($data);
            }
        }

        fclose($handle);

        return response()->json(['message' => 'Erfolgreich importierte Unternehmen, ggf. mit Aktualisierungen'], 200);
    }

    public function import_frontend(Request $request)
    {
        ini_set('memory_limit', '512M'); // Adjust as needed
        set_time_limit(0); // Remove execution time limit

        // Validate the uploaded file
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,txt|max:28585048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $file = $request->file('file');
        $filePath = $file->getRealPath();
        $handle = fopen($filePath, 'r');

        // Define required columns
        $requiredColumns = [
            'ID', 'Titel', 'chr', 'Vorname', 'Nachname', 'Firmen-ID', 'Firmenname', 'Jobtitel', 'Webseite',
            'E-Mail-Adresse', 'Straße', 'Hausnummer', 'PLZ', 'Ort', 'Land', 'Telefonnummer',
            'Telefonnummer (Firma)', 'E-Mail-Adresse (Firma)', 'LinkedIn Account (Firma)',
            'NACE-Code (Ebene 1)', 'NACE-Code (Ebene 2)', 'Beschreibung NACE-Code (Ebene 2)',
            'WZ-Code', 'Beschreibung WZ-Code', 'Branche (Hauptkategorie)', 'Branche (Unterkategorie)', 'Weitere Quellen', 'KI TEXT'
        ];

        // Read header and map columns
        $header = fgetcsv($handle, 0, ",");
        $columnMap = array_flip($header);

        // Ensure all required columns are present
        foreach ($requiredColumns as $column) {
            if (!array_key_exists($column, $columnMap)) {
                fclose($handle);
                return response()->json(['error' => 'CSV enthält keine erforderlichen Kopfzeilen'], 400);
            }
        }

        // Process CSV in chunks using LazyCollection
        LazyCollection::make(function () use ($handle) {
            while (($row = fgetcsv($handle, 0, ",")) !== false) {
                yield $row;
            }
        })->chunk(500)->each(function ($chunk) use ($columnMap) {
            foreach ($chunk as $row) {
                $rawContent = $row[$columnMap['KI TEXT']] ?? null;
                $metaTitle = '';
                $metaDescription = '';
                $htmlContent = '';

                if (!empty($rawContent)) {
                    if (preg_match('/Meta-Titel:\s*(.*)/i', $rawContent, $matchesTitle)) {
                        $metaTitle = trim($matchesTitle[1]);
                    }

                    if (preg_match('/Meta-Description:\s*(.*)/i', $rawContent, $matchesDesc)) {
                        $metaDescription = trim($matchesDesc[1]);
                    }

                    $processed = preg_replace('/Meta-Titel:.*(\r?\n)?/i', '', $rawContent);
                    $processed = preg_replace('/Meta-Description:.*(\r?\n)?/i', '', $processed);

                    $lines = preg_split('/\r?\n/', $processed);
                    $htmlLines = [];

                    foreach ($lines as $line) {
                        $original = trim($line);
                        if ($original === '') {
                            continue; 
                        }

                        if (preg_match('/^H1:\*\*\s*(.*)/i', $original, $m)) {
                            $htmlLines[] = "<h1>" . trim($m[1]) . "</h1>";
                            continue;
                        }
    
                        if (preg_match('/^H2:\*\*\s*(.*)/i', $original, $m)) {
                            $htmlLines[] = "<h2>" . trim($m[1]) . "</h2>";
                            continue;
                        }
    
                        if (preg_match('/^H3:\*\*\s*(.*)/i', $original, $m)) {
                            $htmlLines[] = "<h3>" . trim($m[1]) . "</h3>";
                            continue;
                        }
    
                        // Check for other heading patterns
                        if (preg_match('/^#{3}\s+(.*)/', $original, $m)) {
                            $htmlLines[] = "<h3>" . trim($m[1]) . "</h3>";
                            continue;
                        }
    
                        if (preg_match('/^#{2}\s+(.*)/', $original, $m)) {
                            $htmlLines[] = "<h2>" . trim($m[1]) . "</h2>";
                            continue;
                        }
    
                        if (preg_match('/^#\s+(.*)/', $original, $m)) {
                            $htmlLines[] = "<h1>" . trim($m[1]) . "</h1>";
                            continue;
                        }
    
                        // Handle bold formatting
                        if (preg_match('/\*\*(.*?)\*\*/', $original)) {
                            $original = preg_replace('/\*\*(.*?)\*\*/', '<b>$1</b>', $original);
                        }

                        $lineNoStars = preg_replace('/^\*+\s*/', '', $original);

                        if ($lineNoStars !== $original) {
                            $htmlLines[] = "<p>" . trim($lineNoStars) . "</p>";
                            continue;
                        }

                        $htmlLines[] = "<p>".$original."</p>";
                    }

                    $htmlContent = implode("\n", $htmlLines);
                }

                $rowsToInsert = [
                    'title' => $row[$columnMap['Titel']] ?? null,
                    'anrede' => $row[$columnMap['chr']] ?? null,
                    'vorname' => $row[$columnMap['Vorname']] ?? null,
                    'nachname' => $row[$columnMap['Nachname']] ?? null,
                    'full_name' => $row[$columnMap['Vorname']]. ' ' .$row[$columnMap['Nachname']] ?? null,
                    'firmen_id' => $row[$columnMap['Firmen-ID']] ?? null,
                    'firmenname' => $row[$columnMap['Firmenname']] ?? null,
                    'jobtitel' => $row[$columnMap['Jobtitel']] ?? null,
                    'webseite' => $row[$columnMap['Webseite']] ?? null,
                    'email_adresse' => $row[$columnMap['E-Mail-Adresse']] ?? null,
                    'strasse' => $row[$columnMap['Straße']] ?? null,
                    'hausnummer' => $row[$columnMap['Hausnummer']] ?? null,
                    'plz' => $row[$columnMap['PLZ']] ?? null,
                    'ort' => $row[$columnMap['Ort']] ?? null,
                    'cid' => $row[$columnMap['ID']] ?? null,
                    'land' => $row[$columnMap['Land']] ?? null,
                    'telefonnummer' => $row[$columnMap['Telefonnummer']] ?? null,
                    'telefonnummer_firma' => $row[$columnMap['Telefonnummer (Firma)']] ?? null,
                    'email_adresse_firma' => $row[$columnMap['E-Mail-Adresse (Firma)']] ?? null,
                    'linkedin_account_firma' => $row[$columnMap['LinkedIn Account (Firma)']] ?? null,
                    'nace_code_ebene_1' => $row[$columnMap['NACE-Code (Ebene 1)']] ?? null,
                    'nace_code_ebene_2' => $row[$columnMap['NACE-Code (Ebene 2)']] ?? null,
                    'beschreibung_nace_code_ebene_2' => $row[$columnMap['Beschreibung NACE-Code (Ebene 2)']] ?? null,
                    'wz_code' => $row[$columnMap['WZ-Code']] ?? null,
                    'beschreibung_wz_code' => $row[$columnMap['Beschreibung WZ-Code']] ?? null,
                    'branche_hauptkategorie' => $row[$columnMap['Branche (Hauptkategorie)']] ?? null,
                    'branche_unterkategorie' => $row[$columnMap['Branche (Unterkategorie)']] ?? null,
                    'other_sources' => $row[$columnMap['Weitere Quellen']] ?? null,
                    'meta_title' => $metaTitle,
                    'meta_description' => $metaDescription,
                    'html_content' => $htmlContent
                ];

                Company::updateOrCreate(['firmen_id' => $rowsToInsert['firmen_id']], $rowsToInsert);
            }
// sdfsdf
            // Perform bulk upsert
            // Company::upsert($rowsToInsert, ['firmen_id'], [
            //     'title', 'anrede', 'vorname', 'nachname', 'firmenname', 'jobtitel', 'webseite',
            //     'email_adresse', 'strasse', 'hausnummer', 'plz', 'ort', 'cid', 'land', 'telefonnummer',
            //     'telefonnummer_firma', 'email_adresse_firma', 'linkedin_account_firma', 'nace_code_ebene_1', 'nace_code_ebene_2', 'beschreibung_nace_code_ebene_2', 'wz_code', 'beschreibung_wz_code','branche_hauptkategorie','branche_unterkategorie','other_sources', 'meta_title',
            //     'meta_description', 'html_content'
            // ]);
        });

        fclose($handle);
        return response()->json(['message' => 'Erfolgreich importierte Unternehmen, ggf. mit Aktualisierungen'], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'anrede' => 'nullable|string|max:255',
            'vorname' => 'nullable|string|max:255',
            'nachname' => 'nullable|string|max:255',
            'firmen_id' => 'required|string|max:255',
            'firmenname' => 'nullable|string',
            'jobtitel' => 'nullable|string|max:255',
            'email_adresse' => 'nullable|email|max:255',
            'strasse' => 'nullable|string|max:255',
            'hausnummer' => 'nullable|string|max:255',
            'plz' => 'nullable|string|max:255',
            'ort' => 'nullable|string|max:255',
            'land' => 'nullable|string|max:255',
            'telefonnummer' => 'nullable|string|max:255',
            'telefonnummer_firma' => 'nullable|string|max:255',
            'other_sources' => 'nullable|string|max:255'
        ]);

        $company = Company::updateOrCreate(['firmen_id' => $validated['firmen_id']], $validated);

        return response()->json(['success' => true, 'company' => $company]);
    }

    public function update(Request $request, $id)
    {
        $company = Company::findOrFail($id);
        $company->update($request->all());

        return response()->json(['success' => true, 'message' => 'Unternehmen erfolgreich aktualisiert']);
    }

    public function destroy($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();

        return response()->json(['success' => true, 'message' => 'Unternehmen erfolgreich gelöscht']);
    }


    public function edit($id)
    {
        $company = Company::findOrFail($id);
        return response()->json($company);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');
        if (!empty($ids)) {
            Company::whereIn('id', $ids)->delete();
            return response()->json(['message' => 'Ausgewählte Datensätze wurden erfolgreich gelöscht'], 200);
        }
        return response()->json(['message' => 'Keine IDs ausgewählt'], 400);
    }

}
