<?php

namespace Abs\FinancialYearsPkg;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinancialYear extends Model {
	use SoftDeletes;
	protected $table = 'financial_years';
	protected $fillable = [
		'code',
		'from',
		'company_id',
	];

	// public static function createFromObject($record_data) {

	// 	$errors = [];
	// 	$company = Company::where('code', $record_data->company)->first();
	// 	if (!$company) {
	// 		dump('Invalid Company : ' . $record_data->company);
	// 		return;
	// 	}

	// 	$admin = $company->admin();
	// 	if (!$admin) {
	// 		dump('Default Admin user not found');
	// 		return;
	// 	}

	// 	$type = Config::where('name', $record_data->type)->where('config_type_id', 89)->first();
	// 	if (!$type) {
	// 		$errors[] = 'Invalid Tax Type : ' . $record_data->type;
	// 	}

	// 	if (count($errors) > 0) {
	// 		dump($errors);
	// 		return;
	// 	}

	// 	$record = self::firstOrNew([
	// 		'company_id' => $company->id,
	// 		'name' => $record_data->tax_name,
	// 	]);
	// 	$record->type_id = $type->id;
	// 	$record->created_by_id = $admin->id;
	// 	$record->save();
	// 	return $record;
	// }

	// public static function createFromCollection($records) {
	// 	foreach ($records as $key => $record_data) {
	// 		try {
	// 			if (!$record_data->company) {
	// 				continue;
	// 			}
	// 			$record = self::createFromObject($record_data);
	// 		} catch (Exception $e) {
	// 			dd($e);
	// 		}
	// 	}
	// }

	public static function getCurrentFinancialYear() {
		if (date('m') > 3) {
			$year = date('Y') + 1;
		} else {
			$year = date('Y');
		}
		//GET FINANCIAL YEAR ID
		$financial_year = FinancialYear::where('from', $year)
			->where('company_id', Auth::user()->company_id)
			->first();
		if (!$financial_year) {
			return [
				'success' => false,
				'error' => 'Validation Error',
				'errors' => [
					'Fiancial Year Not Found',
				],
			];
		}
		return [
			'success' => true,
			'financial_year' => $financial_year,
		];

	}
}
