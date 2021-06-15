<?php

namespace App\Http\Controllers;

use App\Models\Bidding;
use Illuminate\Http\Request;
use Validator;

class BiddingController extends Controller
{

    public function index()
    {
        $data_bidding = Bidding::get();
        return view('auth.bidding.index',['data_bidding' => $data_bidding]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'opening' => 'required'
            ],
            [
                'opening.required' => 'Opening tidak boleh kosong!'
            ]
        );
        if ($validator->passes()) {
            $response = Bidding::create($request->all());
            return response()->json([
                'success'=> 'Data berhasil ditambahkan!',
                'data' => $response
            ], 200);
        }
        return response()->json([
            'error'=> $validator->errors()->all()
        ]);
    }

    public function update(Request $request, Bidding $bidding)
    {
        $validator = Validator::make($request->all(),
            [
                'opening' => 'required'
            ],
            [
                'opening.required' => 'Opening tidak boleh kosong!'
            ]
        );
        if ($validator->passes()) {
            $bidding->update($request->all());
            $response = $bidding['opening'];
            return response()->json([
                'success'=> 'Data berhasil diperbaharui!',
                'data' => $response
            ], 200);
        }
        return response()->json([
            'error'=> $validator->errors()->all()
        ]);
    }

    public function destroy(Bidding $bidding)
    {
        $bidding::destroy($bidding->id);
        return response()->json([
            'success'=> 'Data berhasil dihapus!',
        ], 200);
    }
}
