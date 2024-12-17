<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMetaFieldsToCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            // Add your new columns, specifying the column type you want:
            $table->string('meta_title')->nullable()->after('branche_unterkategorie');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->longText('html_content')->nullable()->after('meta_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description', 'html_content']);
        });
    }
}
