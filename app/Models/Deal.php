<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** A deal saved by the admin on Manage Deals (/admin/claims). */
class Deal extends Model
{
    protected $guarded = [];

    public function businesses()
    {
        return $this->hasMany(Business::class);
    }
}
