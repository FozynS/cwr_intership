<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsSystemFieldInPatientCommentsTable extends Migration {
    public function up() {
        Schema::table('patient_comments', function(Blueprint $table) {
            $table->boolean('is_system_comment')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('patient_comments', function(Blueprint $table) {
            $table->dropColumn('is_system_comment');
        });
    }
}