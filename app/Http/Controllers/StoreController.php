<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use DB;


class StoreController extends Controller
{
    public function index(){
        $userLatitude = "23.022505"; // User's latitude
        $userLongitude = "72.5713621"; // User's longitude

        $stores = Store::select(
                'id', 
                'title', 
                'address', 
                'latitude', 
                'longitude',
                DB::raw('(6371 * acos(cos(radians('.$userLatitude.')) * cos(radians(latitude)) * cos(radians(longitude) - radians('.$userLongitude.')) + sin(radians('.$userLatitude.')) * sin(radians(latitude)))) AS distance')
            )
            ->orderBy('distance', 'ASC')
            ->get();
        return view('store.list',compact('stores'));
    }

}
