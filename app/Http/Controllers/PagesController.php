<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bidding;
use Illuminate\Support\Facades\DB;
class PagesController extends Controller
{
	public function index()
	{
		return view('index');
	}

    public function show($param)
    {
    	$data = DB::table('biddings')->where('opening', $param)->first();
    	// return json_encode(array('data'=>$data));
    	return view('openings.opening', compact('data'));
    }
}
