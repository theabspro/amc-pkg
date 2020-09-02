<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableAmcMembersAddColServiceExpirtDate extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('amc_members', function (Blueprint $table) {
			//drop old unique
			$table->dropForeign('amc_members_company_id_foreign');
			$table->dropForeign('amc_members_policy_id_foreign');
			$table->dropUnique('amc_members_company_id_policy_id_number_unique');

			$table->unsignedInteger('entity_type_id')->nullable()->after('company_id');
			$table->date('start_date')->nullable()->after('number');
			$table->foreign('entity_type_id')->references('id')->on('configs')->onDelete('cascade')->onUpdate('cascade');
			$table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
			$table->foreign('policy_id')->references('id')->on('amc_policies')->onDelete('cascade')->onUpdate('cascade');

			$table->unique(['company_id', 'entity_type_id', 'vehicle_id', 'policy_id', 'number'], 'unique_company_vehicle_policy_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('amc_members', function (Blueprint $table) {

			$table->dropForeign('amc_members_company_id_foreign');
			$table->dropForeign('amc_members_policy_id_foreign');
			$table->dropForeign('amc_members_entity_type_id_foreign');
			$table->dropForeign('amc_members_vehicle_id_foreign');

			$table->dropUnique('unique_company_vehicle_policy_id');
			$table->dropColumn('entity_type_id');
			$table->dropColumn('start_date');

			$table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
			$table->foreign('policy_id')->references('id')->on('amc_policies')->onDelete('cascade')->onUpdate('cascade');
			$table->unique(['company_id', 'policy_id', 'number'], 'amc_members_company_id_policy_id_number_unique');
		});
	}
}
