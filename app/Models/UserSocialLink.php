<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSocialLink extends Model
{
    protected $fillable = [
        'facebook_url',
        'linkedIn_url',
        'twitter_url',
        'youtube_url',
        'github_url'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
