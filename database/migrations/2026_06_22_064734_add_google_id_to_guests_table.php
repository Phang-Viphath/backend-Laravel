<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('guests', 'google_id')) {
            Schema::table('guests', function (Blueprint $table) {
                $table->string('google_id')->nullable()->after('email');
            });
        }
    }

    public function down()
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->dropColumn('google_id');
        });
    }
};
