<?php

namespace App\Models;

use App\CPU\Helpers;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Style extends Model
{

    public function reviews()
    {
        return $this->hasMany(Review::class, 'style_id');
    }

    public function rating()
    {
        return $this->hasMany(Review::class)
            ->select(DB::raw('avg(rating) average, style_id'))
            ->groupBy('style_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
