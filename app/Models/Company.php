<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'anrede',
        'vorname',
        'nachname',
        'firmen_id',
        'firmenname',
        'jobtitel',
        'webseite',
        'email_adresse',
        'strasse',
        'hausnummer',
        'plz',
        'ort',
        'land',
        'telefonnummer',
        'telefonnummer_firma',
        'email_adresse_firma',
        'linkedin_account_firma',
        'nace_code_ebene_1',
        'nace_code_ebene_2',
        'beschreibung_nace_code_ebene_2',
        'wz_code',
        'beschreibung_wz_code',
        'branche_hauptkategorie',
        'branche_unterkategorie',
        'status',
        'duplicate',
    ];
}
