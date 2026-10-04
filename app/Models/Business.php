<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    protected $guarded = [];

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    /** The deal the admin set for this merchant (from Manage Deals). */
    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }
}
