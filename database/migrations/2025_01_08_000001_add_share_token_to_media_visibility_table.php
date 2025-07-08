<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShareTokenToMediaVisibilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('media_visibility', function (Blueprint $table) {
            $table->string('share_token')->nullable()->unique()->after('visibility');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('media_visibility', function (Blueprint $table) {
            $table->dropColumn('share_token');
        });
    }
}
