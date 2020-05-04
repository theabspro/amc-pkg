<?php
namespace Abs\AmcPkg\Database\Seeds;

use App\Permission;
use Illuminate\Database\Seeder;

class AmcPkgPermissionSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$permissions = [
			
			//Amc Policies
			[
				'display_order' => 99,
				'parent' => null,
				'name' => 'amc-policies',
				'display_name' => 'Amc Policies',
			],
			[
				'display_order' => 1,
				'parent' => 'amc-policies',
				'name' => 'add-amc-policy',
				'display_name' => 'Add',
			],
			[
				'display_order' => 2,
				'parent' => 'amc-policies',
				'name' => 'edit-amc-policy',
				'display_name' => 'Edit',
			],
			[
				'display_order' => 3,
				'parent' => 'amc-policies',
				'name' => 'delete-amc-policy',
				'display_name' => 'Delete',
			],

			
		];
		Permission::createFromArrays($permissions);
	}
}