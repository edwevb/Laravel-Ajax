<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bidding extends Model
{
	protected $table = 'biddings';
	protected $fillable = ['opening'];

	public function kasus(){ 
		return $this->hasMany('App\Models\Kasus'); 
	}
}
