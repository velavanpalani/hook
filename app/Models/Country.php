<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['country_code', 'name'];

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function states()

    {
        return $this->hasMany(State::class);
    }

}
