<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{

    protected $guarded = [];
    
    public function cities()
    {
    	return $this->hasMany(City::class);
    }

    public function getContactsAttribute($contacts)
    {
        if (is_string($contacts)) {
            return json_decode($contacts, true);
        }
        return $contacts;
    }

    public function setContactsAttribute($contacts)
    {
        if (is_array($contacts)) {
            $this->attributes['contacts'] = json_encode($contacts);
        }
    }
    
}
