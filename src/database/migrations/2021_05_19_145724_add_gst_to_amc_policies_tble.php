<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGstToAmcPoliciesTble extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('amc_policies', function (Blueprint $table) {
			$table->unsignedDecimal('amount_before_gst', 16, 2)->nullable()->after('part_discount_percentage');
			$table->unsignedDecimal('gst_amount', 16, 2)->nullable()->after('amount_before_gst');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('amc_policies', function (Blueprint $table) {
			$table->dropColumn('amount_before_gst');
			$table->dropColumn('gst_amount');
		});
	}
}
