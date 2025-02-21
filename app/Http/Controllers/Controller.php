<?php

namespace App\Http\Controllers;

use App\Models\Admin\Master\City;
use App\Models\Admin\Master\District;
use App\Models\Admin\Master\Pincode;
use App\Models\Admin\Master\State;
use Illuminate\Http\Request;

abstract class Controller
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

    public function getDistricts(Request $req)
    {
        $districts = District::where('is_active', '1');

        if ($req->filled('state_id')) {
            $districts->where('state_id', $req->state_id);
        }

        return response()->json($districts->get());
    }
}
