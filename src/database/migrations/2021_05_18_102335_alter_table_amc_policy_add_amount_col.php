<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableAmcPolicyAddAmountCol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('amc_policies', function (Blueprint $table) {
            $table->unsignedDecimal('amount', 16, 2)->nullable()->after('part_discount_percentage');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('amc_policies', function (Blueprint $table) {
            $table->dropColumn('amount');
        });
    }
}
