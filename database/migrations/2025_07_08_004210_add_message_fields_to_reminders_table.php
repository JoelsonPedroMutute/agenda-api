<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('reminders', function (Blueprint $table) {
        $table->string('message_status')->nullable()->after('method');
        $table->string('message_sid')->nullable()->after('message_status');
    });
}

public function down()
{
    Schema::table('reminders', function (Blueprint $table) {
        $table->dropColumn(['message_status', 'message_sid']);
    });
}

};
