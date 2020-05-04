<?php

namespace Abs\AmcPkg;
use App\Http\Controllers\Controller;
use App\AmcPolicy;
use Auth;
use Carbon\Carbon;
use DB;
use Entrust;
use Illuminate\Http\Request;
use Validator;
use Yajra\Datatables\Datatables;

class AmcPolicyController extends Controller {

	public function __construct() {
		$this->data['theme'] = config('custom.theme');
	}

	public function getAmcPolicyList(Request $request) {
		$amc_policies = AmcPolicy::withTrashed()

			->select([
				'amc_policies.id',
				'amc_policies.name',
				'amc_policies.code',

				DB::raw('IF(amc_policies.deleted_at IS NULL, "Active","Inactive") as status'),
			])
			->where('amc_policies.company_id', Auth::user()->company_id)

			->where(function ($query) use ($request) {
				if (!empty($request->name)) {
					$query->where('amc_policies.name', 'LIKE', '%' . $request->name . '%');
				}
			})
			->where(function ($query) use ($request) {
				if ($request->status == '1') {
					$query->whereNull('amc_policies.deleted_at');
				} else if ($request->status == '0') {
					$query->whereNotNull('amc_policies.deleted_at');
				}
			})
		;

		return Datatables::of($amc_policies)
			->rawColumns(['name', 'action'])
			->addColumn('name', function ($amc_policy) {
				$status = $amc_policy->status == 'Active' ? 'green' : 'red';
				return '<span class="status-indicator ' . $status . '"></span>' . $amc_policy->name;
			})
			->addColumn('action', function ($amc_policy) {
				$img1 = asset('public/themes/' . $this->data['theme'] . '/img/content/table/edit-yellow.svg');
				$img1_active = asset('public/themes/' . $this->data['theme'] . '/img/content/table/edit-yellow-active.svg');
				$img_delete = asset('public/themes/' . $this->data['theme'] . '/img/content/table/delete-default.svg');
				$img_delete_active = asset('public/themes/' . $this->data['theme'] . '/img/content/table/delete-active.svg');
				$output = '';
				if (Entrust::can('edit-amc_policy')) {
					$output .= '<a href="#!/amc-pkg/amc_policy/edit/' . $amc_policy->id . '" id = "" title="Edit"><img src="' . $img1 . '" alt="Edit" class="img-responsive" onmouseover=this.src="' . $img1 . '" onmouseout=this.src="' . $img1 . '"></a>';
				}
				if (Entrust::can('delete-amc_policy')) {
					$output .= '<a href="javascript:;" data-toggle="modal" data-target="#amc_policy-delete-modal" onclick="angular.element(this).scope().deleteAmcPolicy(' . $amc_policy->id . ')" title="Delete"><img src="' . $img_delete . '" alt="Delete" class="img-responsive delete" onmouseover=this.src="' . $img_delete . '" onmouseout=this.src="' . $img_delete . '"></a>';
				}
				return $output;
			})
			->make(true);
	}

	public function getAmcPolicyFormData(Request $request) {
		$id = $request->id;
		if (!$id) {
			$amc_policy = new AmcPolicy;
			$action = 'Add';
		} else {
			$amc_policy = AmcPolicy::withTrashed()->find($id);
			$action = 'Edit';
		}
		$this->data['success'] = true;
		$this->data['amc_policy'] = $amc_policy;
		$this->data['action'] = $action;
		return response()->json($this->data);
	}

	public function saveAmcPolicy(Request $request) {
		// dd($request->all());
		try {
			$error_messages = [
				'code.required' => 'Short Name is Required',
				'code.unique' => 'Short Name is already taken',
				'code.min' => 'Short Name is Minimum 3 Charachers',
				'code.max' => 'Short Name is Maximum 32 Charachers',
				'name.required' => 'Name is Required',
				'name.unique' => 'Name is already taken',
				'name.min' => 'Name is Minimum 3 Charachers',
				'name.max' => 'Name is Maximum 191 Charachers',
			];
			$validator = Validator::make($request->all(), [
				'code' => [
					'required:true',
					'min:3',
					'max:32',
					'unique:amc_policies,code,' . $request->id . ',id,company_id,' . Auth::user()->company_id,
				],
				'name' => [
					'required:true',
					'min:3',
					'max:191',
					'unique:amc_policies,name,' . $request->id . ',id,company_id,' . Auth::user()->company_id,
				],
			], $error_messages);
			if ($validator->fails()) {
				return response()->json(['success' => false, 'errors' => $validator->errors()->all()]);
			}

			DB::beginTransaction();
			if (!$request->id) {
				$amc_policy = new AmcPolicy;
				$amc_policy->company_id = Auth::user()->company_id;
			} else {
				$amc_policy = AmcPolicy::withTrashed()->find($request->id);
			}
			$amc_policy->fill($request->all());
			if ($request->status == 'Inactive') {
				$amc_policy->deleted_at = Carbon::now();
			} else {
				$amc_policy->deleted_at = NULL;
			}
			$amc_policy->save();

			DB::commit();
			if (!($request->id)) {
				return response()->json([
					'success' => true,
					'message' => 'Amc Policy Added Successfully',
				]);
			} else {
				return response()->json([
					'success' => true,
					'message' => 'Amc Policy Updated Successfully',
				]);
			}
		} catch (Exceprion $e) {
			DB::rollBack();
			return response()->json([
				'success' => false,
				'error' => $e->getMessage(),
			]);
		}
	}

	public function deleteAmcPolicy(Request $request) {
		DB::beginTransaction();
		// dd($request->id);
		try {
			$amc_policy = AmcPolicy::withTrashed()->where('id', $request->id)->forceDelete();
			if ($amc_policy) {
				DB::commit();
				return response()->json(['success' => true, 'message' => 'Amc Policy Deleted Successfully']);
			}
		} catch (Exception $e) {
			DB::rollBack();
			return response()->json(['success' => false, 'errors' => ['Exception Error' => $e->getMessage()]]);
		}
	}

	public function getAmcPolicys(Request $request) {
		$amc_policies = AmcPolicy::withTrashed()
			->with([
				'amc-policies',
				'amc-policies.user',
			])
			->select([
				'amc_policies.id',
				'amc_policies.name',
				'amc_policies.code',
				DB::raw('IF(amc_policies.deleted_at IS NULL, "Active","Inactive") as status'),
			])
			->where('amc_policies.company_id', Auth::user()->company_id)
			->get();

		return response()->json([
			'success' => true,
			'amc_policies' => $amc_policies,
		]);
	}
}