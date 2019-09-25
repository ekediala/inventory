<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $guarded = ['id'];

    protected $with = ['user'];

    public function user(){
        return $this->belongsTo('App\User');
    }
}
