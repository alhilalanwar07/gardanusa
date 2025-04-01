<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'project_date',
        'client_id',
        'service_id',
        'url',
        'slug',
        'challenge',
        'solution',
        'gallery',
    ];

    protected $casts = [
        'gallery' => 'array'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }

    
}
