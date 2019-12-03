<?php

namespace Abs\FinancialYearsPkg;
use Abs\FinancialYearsPkg\FinancialYear;
use App\Address;
use App\Country;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Validator;
use Yajra\Datatables\Datatables;

class FinancialYearController extends Controller {

	public function __construct() {
	}

	public function getFinancialYearList(Request $request) {
		$financial_years_list = FinancialYear::withTrashed()
			->select(
				'financial_years.id',
				'financial_years.code',
				'financial_years.from',
				DB::raw('IF(financial_years.deleted_at IS NULL,"Active","Inactive") as status')
			)
			->where('financial_years.company_id', Auth::user()->company_id)
			->where(function ($query) use ($request) {
				if (!empty($request->code)) {
					$query->where('financial_years.code', 'LIKE', '%' . $request->code . '%');
				}
			})
			->where(function ($query) use ($request) {
				if (!empty($request->from)) {
					$query->where('financial_years.from', 'LIKE', '%' . $request->from . '%');
				}
			})
			->orderby('financial_years.id', 'desc');

		return Datatables::of($financial_years_list)
			->addColumn('code', function ($financial_years_list) {
				$status = $financial_years_list->status == 'Active' ? 'green' : 'red';
				return '<span class="status-indicator ' . $status . '"></span>' . $financial_years_list->code;
			})
			->addColumn('action', function ($financial_years_list) {
				$edit_img = asset('public/theme/img/table/cndn/edit.svg');
				$delete_img = asset('public/theme/img/table/cndn/delete.svg');
				return '
					<a href="#!/financial-year-pkg/financial-year/edit/' . $financial_years_list->id . '">
						<img src="' . $edit_img . '" alt="View" class="img-responsive">
					</a>
					<a href="javascript:;" data-toggle="modal" data-target="#delete_financial_year"
					onclick="angular.element(this).scope().deleteFinancialYear(' . $financial_years_list->id . ')" dusk = "delete-btn" title="Delete">
					<img src="' . $delete_img . '" alt="delete" class="img-responsive">
					</a>
					';
			})
			->make(true);
	}

	public function getFinancialYearFormData($id = NULL) {
		if (!$id) {
			$financial_year = new FinancialYear;
			$action = 'Add';
		} else {
			$financial_year = FinancialYear::withTrashed()->find($id);
			$action = 'Edit';
		}
		$this->data['financial_year'] = $financial_year;
		$this->data['action'] = $action;

		return response()->json($this->data);
	}

	public function saveFinancialYear(Request $request) {
		 //dd($request->all());
		try {
			$error_messages = [
				'code.required' => 'FinancialYear Code is Required',
				'code.max' => 'Maximum 191 Characters',
				'code.min' => 'Minimum 3 Characters',
				'code.unique' => 'Code already taken',
				'from.required' => 'FinancialYear Name is Required',
				'from.max' => 'Maximum 4 Characters',
				'from.min' => 'Minimum 4 Characters',
				'from.unique' => 'From already taken',
			];
			$validator = Validator::make($request->all(),
			 [
				'code' => 'required|max:191|min:3|unique:financial_years,code,'.$request->id.',id,company_id,'.Auth::user()->company_id,
				'from' => 'required|numeric|digits:4|unique:financial_years,from,'.$request->id.',id,company_id,'.Auth::user()->company_id,
			], $error_messages);
			if ($validator->fails()) {
				return response()->json(['success' => false, 'errors' => $validator->errors()->all()]);
			}

			DB::beginTransaction();
			if (!$request->id) {
				$financial_year = new FinancialYear;
				$financial_year->created_by_id = Auth::user()->id;
				$financial_year->created_at = Carbon::now();
				$financial_year->updated_at = NULL;
			} else {
				$financial_year = FinancialYear::withTrashed()->find($request->id);
				$financial_year->updated_by_id = Auth::user()->id;
				$financial_year->updated_at = Carbon::now();
			}
			$financial_year->fill($request->all());
			$financial_year->company_id = Auth::user()->company_id;
			if ($request->status == 'Inactive') {
				$financial_year->deleted_at = Carbon::now();
				$financial_year->deleted_by_id = Auth::user()->id;
			} else {
				$financial_year->deleted_by_id = NULL;
				$financial_year->deleted_at = NULL;
			}
			$financial_year->save();

			DB::commit();
			if (!($request->id)) {
				return response()->json(['success' => true, 'message' => ['FinancialYear Details Added Successfully']]);
			} else {
				return response()->json(['success' => true, 'message' => ['FinancialYear Details Updated Successfully']]);
			}
		} catch (Exceprion $e) {
			DB::rollBack();
			return response()->json(['success' => false, 'errors' => ['Exception Error' => $e->getMessage()]]);
		}
	}
	public function deleteFinancialYear($id) {
		$delete_status = FinancialYear::withTrashed()->where('id', $id)->forceDelete();
		if ($delete_status) {
			return response()->json(['success' => true]);
		}
	}
}
