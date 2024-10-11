<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    use HasFactory;

    protected $fillable = ['original_url', 'short_code', 'expires_at'];

    public function clicks() {
        return $this->hasMany(Click::class);
    }
}
