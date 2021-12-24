<?php

namespace App\Traits;

trait WhenSearch 
{
    public function scopeWhenSearch($query, $field)
    {
        $query->when(request('keyword'), function($query) use ($field)
        {
            $query->where($field, 'like', '%'.request('keyword').'%');
        });
    }
}
