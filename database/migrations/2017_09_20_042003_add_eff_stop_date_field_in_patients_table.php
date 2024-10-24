<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEffStopDateFieldInPatientsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('patients', function(Blueprint $table) {
            $table->date('eff_stop_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('patients', function(Blueprint $table) {
            $table->dropColumn('eff_stop_date');
        });
    }
}
