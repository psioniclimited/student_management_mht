<?php

namespace App\Modules\Company\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Company\Models\Company;
use App\Modules\Company\Models\BranchInformation;
use App\Modules\Company\Models\BranchType;
use Datatables;

class CompanyController extends Controller {

    public function getCompany() {
        return view('Company::company');
    }

    public function companyData() {
        $allCompanies = Company::all();
        return Datatables::of($allCompanies)
        ->addColumn('Link', function ($allCompanies) {
            return '<a href="' . url('/company_edit') . '/' . $allCompanies->id . '"' . 'class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
        })
        ->editColumn('id', '{{$id}}')
        ->setRowId('id')
        ->make(true);
    }

    public function createCompanyProcess(\App\Http\Requests\CompanyRequest $request) {
        $crCompany = new Company();

        $crCompany->name_of_company = $request->input('cname');
        $crCompany->address = $request->input('addrs');
        $crCompany->contact_number = $request->input('cnum');

        $crCompany->save();

        return redirect('companyinfo');
    }

    public function editCompanyInfo($id){

        $getCompanyInfo = Company::where('id',$id)->get();

        return view('Company::company')->with('getCompanyInfo', $getCompanyInfo);

        //return $getCompanyInfo;
    }

    public function updateCompanyInfo(\App\Http\Requests\CompanyRequest $request){

        $companyID = $request->input('companyID');

        $getCompanyInfo = Company::find($companyID);

        $getCompanyInfo->name_of_company = $request->input('cname');
        $getCompanyInfo->address = $request->input('addrs');
        $getCompanyInfo->contact_number = $request->input('cnum');

        $getCompanyInfo->save();

        return redirect('companyinfo');

    }

    // Branch
    public function getBranches() {
        $companyInfo = Company::select('id', 'name_of_company')->get();
        $branchType = BranchType::select('id', 'description')->get();
        return view('Company::branches')->with('companyInfo', $companyInfo)->with('branchType', $branchType);
    }

    public function createBranchProcess(\App\Http\Requests\BranchRequest $request) {
        $stBranch = new BranchInformation();
        $stBranch->branch_name = $request->input('bname');
        $stBranch->branch_address = $request->input('baddrs');
        $stBranch->contact_number = $request->input('cnum');
        $stBranch->branch_type_id = $request->input('btypeid');
        $stBranch->companie_information_id = $request->input('cinfoid');

        $stBranch->save();

        return redirect('branches');
    }

    public function branchesData() {
        $allBranches = BranchInformation::all();
        return Datatables::of($allBranches)
        ->addColumn('Link', function ($allBranches) {
            return '<a href="' . url('/branch_edit') . '/' . $allBranches->id . '"' . 'class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
        })
        ->editColumn('id', '{{$id}}')
        ->setRowId('id')
        ->make(true);
    }

    public function editBranchInfo($id){
        $companyInfo = Company::select('id', 'name_of_company')->get();
        $branchType = BranchType::select('id', 'description')->get();
        $getBranchInfo = BranchInformation::where('id',$id)->get();

        return view('Company::branches')->with('getBranchInfo', $getBranchInfo)->with('companyInfo', $companyInfo)->with('branchType', $branchType);

        // return $getBranchInfo;
    }



}
