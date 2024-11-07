<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->text('nachname')->nullable();              
            $table->text('firmenname')->nullable();
            $table->text('jobtitel')->nullable();
            $table->text('beschreibung_nace_code_ebene_2')->nullable();  
            $table->text('anrede')->nullable();                      // Anrede
            $table->text('vorname')->nullable();                     // Vorname
            $table->text('firmen_id')->nullable();                   // Firmen-ID
            $table->text('webseite')->nullable();                    // Webseite
            $table->text('email_adresse')->nullable();               // E-Mail-Adresse
            $table->text('strasse')->nullable();                     // Straße
            $table->text('hausnummer')->nullable();                  // Hausnummer
            $table->text('plz')->nullable();                         // PLZ
            $table->text('ort')->nullable();                         // Ort
            $table->text('land')->nullable();                        // Land
            $table->text('telefonnummer')->nullable();               // Telefonnummer
            $table->text('telefonnummer_firma')->nullable();         // Telefonnummer (Firma)
            $table->text('email_adresse_firma')->nullable();         // E-Mail-Adresse (Firma)
            $table->text('linkedin_account_firma')->nullable();      // LinkedIn Account (Firma)
            $table->text('nace_code_ebene_1')->nullable();           // NACE-Code (Ebene 1)
            $table->text('nace_code_ebene_2')->nullable();           // NACE-Code (Ebene 2)
            $table->text('wz_code')->nullable();                     // WZ-Code
            $table->text('beschreibung_wz_code')->nullable();          // Beschreibung WZ-Code
            $table->text('branche_hauptkategorie')->nullable();      // Branche (Hauptkategorie)
            $table->text('branche_unterkategorie')->nullable();      // Branche (Unterkategorie)
            $table->string('status')->nullable();                      // Status
            $table->boolean('duplicate')->default(false);              // Duplicate
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
