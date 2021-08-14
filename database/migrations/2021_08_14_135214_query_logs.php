<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class QueryLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Добавление текстового поля для даты и времени запроса - если это необходимо помимо поля created_at в таблице
         Schema::table('query_logs', function (Blueprint $table) {
         $table->string('timestamp')->after('responseHttpCode'); // 
     });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
