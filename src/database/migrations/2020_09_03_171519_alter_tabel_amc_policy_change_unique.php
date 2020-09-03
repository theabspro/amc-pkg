<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTabelAmcPolicyChangeUnique extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('amc_policies', function (Blueprint $table) {
			$table->dropForeign('amc_policies_company_id_foreign');
			$table->dropUnique('amc_policies_company_id_name_unique');

			$table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
			$table->unique(['company_id', 'name', 'type']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('amc_policies', function (Blueprint $table) {
			$table->dropForeign('amc_policies_company_id_foreign');
			$table->dropUnique('amc_policies_company_id_name_type_unique');

			$table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
			$table->unique(['company_id', 'name']);
		});
	}
}
