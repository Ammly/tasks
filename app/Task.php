<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [

        'title',

        'user_id',

        'status',

        'due_date',

    ];
}
