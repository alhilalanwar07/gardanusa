<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['name', 'logo', 'website'];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }
}
