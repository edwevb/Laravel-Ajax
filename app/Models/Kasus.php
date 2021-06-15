<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kasus extends Model
{
	protected $table = 'cases';
	protected $fillable = ['description'];

	 public function bidding(){
    	return $this->belongsTo('App\Models\Bidding');
    }
}
