<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
class PersonalAccessToken extends Model
{
    protected $table = 'personal_access_tokens';

    protected $fillable = ['id','tokenable_id', 'tokenable_type', 'name', 'token', 'abilities', 'last_used_at', 'expires_at'];

    public function tokenable(): MorphTo
    {
        return $this->morphTo();
    }
}
