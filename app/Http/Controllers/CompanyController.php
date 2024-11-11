<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
                      ->orWhere('jobtitel', 'like', "%{$search}%")
                      ->orWhere('webseite', 'like', "%{$search}%");
            }
    
            $totalRecords = Company::count(); // Total records without filters
            $filteredRecords = $query->count(); // Total records after filtering
    
            // Apply pagination and ordering
            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $query->skip($start)->take($length);
    
            $companies = $query->get();
    
            // Format data for DataTables
            $formattedData = $companies->map(function ($company, $index) use ($start) {
                return [
                    'checkbox' => '<input type="checkbox" class="form-checkbox">',
                    'id' => "<span class='text-sm font-rajdhani'>" . ($start + $index + 1) . "</span>",
                    'name' => "<span class='text-sm font-rajdhani'>{$company->anrede} {$company->vorname} {$company->nachname}</span>",
                    'firmen_id' => "<span class='text-sm font-rajdhani uppercase'>{$company->firmen_id}</span>",
                    'firmenname' => "<a href='{$company->webseite}' target='_blank' class='text-sm text-red-600 font-rajdhani font-semibold'>{$company->firmenname}</a>",
                    'jobtitel' => "<span class='text-sm font-rajdhani'>{$company->jobtitel}</span>",
                    'webseite' => "<span class='text-sm font-rajdhani'>{$company->webseite}</span>",
                    'actions' => ' 
                            <button class="view-company-btn text-gray-600 hover:text-gray-800" data-id="' . $company->id . '">
                                <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_319_1195)">
                                <path d="M1.5 8.00033C1.5 8.00033 4.16667 2.66699 8.83333 2.66699C13.5 2.66699 16.1667 8.00033 16.1667 8.00033C16.1667 8.00033 13.5 13.3337 8.83333 13.3337C4.16667 13.3337 1.5 8.00033 1.5 8.00033Z" stroke="#1E1E1E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M8.83333 10.0003C9.9379 10.0003 10.8333 9.1049 10.8333 8.00033C10.8333 6.89576 9.9379 6.00033 8.83333 6.00033C7.72876 6.00033 6.83333 6.89576 6.83333 8.00033C6.83333 9.1049 7.72876 10.0003 8.83333 10.0003Z" stroke="#1E1E1E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </g>
                                <defs>
                                <clipPath id="clip0_319_1195">
                                <rect width="16" height="16" fill="white" transform="translate(0.833496)"/>
                                </clipPath>
                                </defs>
                                </svg>
                            </button>
                            <button class="delete-company-btn text-gray-600 hover:text-gray-800" data-id="' . $company->id . '">
                                <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4.83325 14C4.46659 14 4.1527 13.8694 3.89159 13.6083C3.63047 13.3472 3.49992 13.0333 3.49992 12.6667V4H2.83325V2.66667H6.16659V2H10.1666V2.66667H13.4999V4H12.8333V12.6667C12.8333 13.0333 12.7027 13.3472 12.4416 13.6083C12.1805 13.8694 11.8666 14 11.4999 14H4.83325ZM11.4999 4H4.83325V12.6667H11.4999V4ZM6.16659 11.3333H7.49992V5.33333H6.16659V11.3333ZM8.83325 11.3333H10.1666V5.33333H8.83325V11.3333Z" fill="#1D1B20"/>
                                </svg>
                            </button>
                            <button class="edit-company-btn text-gray-600 hover:text-gray-800" data-id="' . $company->id . '">
                                <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3.49996 12.6667H4.44996L10.9666 6.15L10.0166 5.2L3.49996 11.7167V12.6667ZM2.16663 14V11.1667L10.9666 2.38333C11.1 2.26111 11.2472 2.16667 11.4083 2.1C11.5694 2.03333 11.7388 2 11.9166 2C12.0944 2 12.2666 2.03333 12.4333 2.1C12.6 2.16667 12.7444 2.26667 12.8666 2.4L13.7833 3.33333C13.9166 3.45556 14.0139 3.6 14.075 3.76667C14.1361 3.93333 14.1666 4.1 14.1666 4.26667C14.1666 4.44444 14.1361 4.61389 14.075 4.775C14.0139 4.93611 13.9166 5.08333 13.7833 5.21667L4.99996 14H2.16663ZM10.4833 5.68333L10.0166 5.2L10.9666 6.15L10.4833 5.68333Z" fill="#1D1B20"/>
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
            'file' => 'required|mimes:csv,txt|max:2048', // Max size 2MB, adjust if necessary
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
            'ID', 'Anrede', 'Vorname', 'Nachname', 'Firmen-ID', 'Firmenname', 'Jobtitel', 'Webseite',
            'E-Mail-Adresse', 'Straße', 'Hausnummer', 'PLZ', 'Ort', 'Land', 'Telefonnummer',
            'Telefonnummer (Firma)', 'E-Mail-Adresse (Firma)', 'LinkedIn Account (Firma)',
            'NACE-Code (Ebene 1)', 'NACE-Code (Ebene 2)', 'Beschreibung NACE-Code (Ebene 2)',
            'WZ-Code', 'Beschreibung WZ-Code', 'Branche (Hauptkategorie)', 'Branche (Unterkategorie)'
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

            // Prepare the data for either updating or creating the record
            $data = [
                'anrede' => $row[$columnMap['Anrede']] ?? null,
                'vorname' => $row[$columnMap['Vorname']] ?? null,
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
                'branche_unterkategorie' => $row[$columnMap['Branche (Unterkategorie)']] ?? null
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
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,txt|max:28585048', // Max size 2MB
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $file = $request->file('file');
        $filePath = $file->getRealPath();
        $handle = fopen($filePath, 'r');

        // Define columns and map
        $requiredColumns = [
            'ID', 'Anrede', 'Vorname', 'Nachname', 'Firmen-ID', 'Firmenname', 'Jobtitel', 'Webseite',
            'E-Mail-Adresse', 'Straße', 'Hausnummer', 'PLZ', 'Ort', 'Land', 'Telefonnummer',
            'Telefonnummer (Firma)', 'E-Mail-Adresse (Firma)', 'LinkedIn Account (Firma)',
            'NACE-Code (Ebene 1)', 'NACE-Code (Ebene 2)', 'Beschreibung NACE-Code (Ebene 2)',
            'WZ-Code', 'Beschreibung WZ-Code', 'Branche (Hauptkategorie)', 'Branche (Unterkategorie)'
        ];

        $header = fgetcsv($handle, 0, ",");
        $columnMap = array_flip($header);

        // Ensure all required columns are in place
        foreach ($requiredColumns as $column) {
            if (!array_key_exists($column, $columnMap)) {
                return response()->json(['error' => 'CSV enthält keine erforderlichen Kopfzeilen'], 400);
            }
        }

        // Process CSV rows
        try {
            while (($row = fgetcsv($handle, 0, ",")) !== false) {
                $data = [
                    'anrede' => $row[$columnMap['Anrede']] ?? null,
                    'vorname' => $row[$columnMap['Vorname']] ?? null,
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
                    'branche_unterkategorie' => $row[$columnMap['Branche (Unterkategorie)']] ?? null
                ];
                Company::updateOrCreate(['firmen_id' => $data['firmen_id']], $data);
            }
            fclose($handle);
            return response()->json(['message' => 'Erfolgreich importierte Unternehmen, ggf. mit Aktualisierungen'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Bei der Verarbeitung ist ein Fehler aufgetreten'], 500);
        }
    }

    
    public function store(Request $request)
    {
        $validated = $request->validate([
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

}
