<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexFieldInAssessmentFormsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('assessment_forms', function(Blueprint $table) {
            $table->integer('ind')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('assessment_forms', function(Blueprint $table) {
            $table->dropColumn('ind');
        });
    }
}
