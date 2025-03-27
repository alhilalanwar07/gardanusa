<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'name',
        'position',
        'photo',
        'bio',
        'social_links',
    ];

    protected $casts = [
        'social_links' => 'array',
    ];

    public function getSocialLinksAttribute($value)
    {
        return $value ? json_decode($value, true) : [
            'facebook' => null,
            'instagram' => null,
            'linkedin' => null,
        ];
    }

    /**
     * Mutator untuk memastikan social_links selalu disimpan sebagai JSON.
     */
    public function setSocialLinksAttribute($value)
    {
        $this->attributes['social_links'] = json_encode($value);
    }
}
