<?php
namespace Abs\FinancialYearsPkg\Database\Seeds;

use App\Permission;
use Illuminate\Database\Seeder;

class FinancialYearsPermissionSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$permissions = [
			//MASTER > CUSTOMERS
			5200 => [
				'display_order' => 10,
				'parent_id' => null,
				'name' => 'financial-years',
				'display_name' => 'Financial Years',
			],
			5201 => [
				'display_order' => 1,
				'parent_id' => 5200,
				'name' => 'add-financial-year',
				'display_name' => 'Add',
			],
			5202 => [
				'display_order' => 2,
				'parent_id' => 5200,
				'name' => 'edit-financial-year',
				'display_name' => 'Edit',
			],
			5203 => [
				'display_order' => 3,
				'parent_id' => 5200,
				'name' => 'delete-financial-year',
				'display_name' => 'Delete',
			],

		];

		foreach ($permissions as $permission_id => $permsion) {
			$permission = Permission::firstOrNew([
				'id' => $permission_id,
			]);
			$permission->fill($permsion);
			$permission->save();
		}
	}
}