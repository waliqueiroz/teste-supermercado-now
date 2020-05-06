<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'price', 'image', 'status_id'];

    public function status()
    {
        return $this->belongsTo('App\Models\Status');
    }
}
