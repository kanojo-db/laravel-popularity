<?php

namespace KanojoDb\LaravelPopular\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = ['client_hash', 'date', 'model_id', 'model_type'];
}
