<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyCancelEndEtcTables08Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tables08', function (Blueprint $table) {
            //カラム名変更
            $table->renameColumn('candel_end','cancel_end');


            $table->string('ticket_code',5)->unique()->change();
            $table->integer('sales_id')->unsigned()->unique()->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tables08', function (Blueprint $table) {
            //FKを削除
            //$table->dropForeign('biz_id');
            //$table->dropForeign('ticket_code');
            //$table->dropForeign('sales_id');

        });
    }
}
