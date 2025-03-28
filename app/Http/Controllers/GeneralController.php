<?php

namespace App\Http\Controllers;

use App\Models\Admin\Master\City;
use App\Models\Admin\Master\District;
use App\Models\Admin\Master\Pincode;
use App\Models\Admin\Master\State;
use App\Models\LeadSource;
use App\Models\LeadStatus;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public function getCities(Request $req)
    {
        $cities = City::where('is_active','1');

        if($req->filled('district_id')){
            $cities->where('district_id', $req->district_id);
        }

        return response()->json($cities->get());
    }

    public function getStates()
    {
        $state = State::where('is_active','1')->get();
        return response()->json($state);
    }

    public function getPinCodes(Request $req)
    {
        $pincode = Pincode::where('is_active','1');

        if($req->filled('district_id')){
            $pincode->where('district_id', $req->district_id);
        }

        return response()->json($pincode->get());
    }

    public function getLeadSource()
    {
        return response()->json(LeadSource::where('is_active','1')->get());
    }

    public function getLeadStatus()
    {
        return response()->json(LeadStatus::where('is_active','1')->get());
    }

    public function getUsers()
    {
        return response()->json(User::where('active_status','Active')->get());
    }
    public function getProducts(Request $req)
    {
        $products = Product::where('products.is_active', '1')
        ->leftJoin('unit_of_measurements as uom', 'products.uom_id', 'uom.id')
        ->select('products.*','uom.name as uom_name','uom.code as uom_code');

        if ($req->filled('category_id')) {
            $products->where('category_id', $req->category_id);
        }

        return response()->json($products->get());
    }

    public function getDistricts(Request $req)
    {
        $districts = District::where('is_active', '1');

        if ($req->filled('state_id')) {
            $districts->where('state_id', $req->state_id);
        }

        return response()->json($districts->get());
    }
}
