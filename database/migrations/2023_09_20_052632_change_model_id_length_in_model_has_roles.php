<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeModelIdLengthInModelHasRoles extends Migration
{
    public function up()
    {
        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->string('model_id', 50)->change(); // Change the length to 50 characters or your desired length
        });
    }

    public function down()
    {
        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->string('model_id', 36)->change(); // Revert to the original length if needed
        });
    }
};
