<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDownloadLogsTable extends Migration
{
    public function up()
    {
        Schema::create('download_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->morphs('downloadable'); // downloadable_id + downloadable_type
            $table->ipAddress('ip_address')->nullable();
            $table->timestamp('downloaded_at')->useCurrent();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('download_logs');
    }
}
