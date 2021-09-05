<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyTicketCodeTables02Table extends Migration
{
    /**
     * Run the migrations.
     *一度外部キーを子テーブルから削除していく
     * @return void
     */
    public function up()
    {
        Schema::table('tables08', function (Blueprint $table) {
            //
            $table->dropForeign(['biz_id','ticket_code','sales_id']);
        });
        Schema::table('tables07', function (Blueprint $table) {
            $table->dropForeign(['biz_id','ticket_code','sales_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tables02', function (Blueprint $table) {
            //
        });
    }
}
