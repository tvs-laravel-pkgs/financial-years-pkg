<?php

namespace Abs\FinancialYearsPkg;

use Abs\HelperPkg\Traits\SeederTrait;
use App\BaseModel;
use App\Company;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinancialYear extends BaseModel {
	use SoftDeletes;
	use SeederTrait;
	protected $table = 'financial_years';
	protected $fillable = [
		'code',
		'from',
		'company_id',
	];

	protected static $excelColumnRules = [
		'Code' => [
			'table_column_name' => 'code',
			'rules' => [
				'required' => [
				],
			],
		],
		'From' => [
			'table_column_name' => 'from',
			'rules' => [
				'required' => [
				],
			],
		],
	];

	public static function saveFromObject($record_data) {
		$record = [
			'Company Code' => $record_data->company_code,
			'Code' => $record_data->code,
			'From' => $record_data->from,
		];
		return static::saveFromExcelArray($record);
	}

	public static function saveFromExcelArray($record_data) {
		$errors = [];
		$company = Company::where('code', $record_data['Company Code'])->first();
		if (!$company) {
			return [
				'success' => false,
				'errors' => ['Invalid Company : ' . $record_data['Company Code']],
			];
		}

		if (!isset($record_data['created_by_id'])) {
			$admin = $company->admin();

			if (!$admin) {
				return [
					'success' => false,
					'errors' => ['Default Admin user not found'],
				];
			}
			$created_by_id = $admin->id;
		} else {
			$created_by_id = $record_data['created_by_id'];
		}

		$record = self::firstOrNew([
			'company_id' => $company->id,
			'from' => $record_data['From'],
		]);

		$result = Self::validateAndFillExcelColumns($record_data, Static::$excelColumnRules, $record);
		if (!$result['success']) {
			return $result;
		}
		$record->company_id = $company->id;
		$record->created_by_id = $created_by_id;
		$record->save();
		return [
			'success' => true,
		];
	}

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
			// ->where('company_id', Auth::user()->company_id)
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
