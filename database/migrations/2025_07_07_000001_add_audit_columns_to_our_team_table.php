<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAuditColumnsToOurTeamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('our_team', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable()->after('social_links');
            $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
            $table->softDeletes()->after('updated_by');
            $table->unsignedBigInteger('deleted_by')->nullable()->after('deleted_at');
            $table->timestamp('restored_at')->nullable()->after('deleted_by');
            $table->unsignedBigInteger('restored_by')->nullable()->after('restored_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('our_team', function (Blueprint $table) {
            $table->dropColumn(['created_by', 'updated_by', 'deleted_at', 'deleted_by', 'restored_at', 'restored_by']);
        });
    }
}
