<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Fetch companies with pagination (adjust the page size as needed)
        $companies = Company::paginate(10);
        return view('companies', compact('companies'));
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

        return response()->json(['message' => 'Companies imported successfully, with updates where necessary'], 200);
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

    public function edit($id)
    {
        $company = Company::findOrFail($id);
        return response()->json($company);
    }

}
