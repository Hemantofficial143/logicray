<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyStoreRequest;
use App\Models\Company;
use App\Models\CompanyUsers;
use App\Models\Country;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class CompanyController extends Controller
{

    public function index()
    {
        $users = User::select('id', 'name')->get();
        $countries = Country::select('id', 'name')->get();
        return view('user.companies', compact('users', 'countries'));
    }

    public function get(Request $request)
    {

        $companies = Country::filter($request->country)->with('companies.users.user')->get();
        return response()->json(['success' => true, 'data' => $companies]);
    }

    public function store(CompanyStoreRequest $request)
    {
        $isEdit = $request->has('id');
        $company = $isEdit ? Company::find($request->id) : new Company();
        $company->name = $request->name;
        $company->country_id = $request->country;
        $company->save();
        $addUsers = $request->users;
        if ($isEdit) {
            $alreadyCompanyUsers = $company->users->pluck('id')->toArray();
            $newUsersFromRequest = $request->users;
            $wantToAddUsers = array_diff($newUsersFromRequest, $alreadyCompanyUsers);
            $addUsers = $wantToAddUsers;
            $wantToDeleteUsers = array_diff($alreadyCompanyUsers, $newUsersFromRequest);
            if (count($wantToDeleteUsers) > 0) {
                CompanyUsers::where('company_id', $company->id)->whereIn('user_id', $wantToDeleteUsers)->delete();
            }
        }
        $usersInsert = [];
        foreach ($addUsers as $userID) {
            $usersInsert[] = [
                'user_id' => $userID,
                'company_id' => $company->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        CompanyUsers::insert($usersInsert);

        return response()->json(['success' => true, 'message' => 'Company Details Saved']);
    }

    public function delete(Company $company)
    {
        CompanyUsers::where('company_id', $company->id)->delete();
        $company->delete();
        return response()->json(['success' => true, 'message' => 'Company Deleted Successfully']);
    }


}
