<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{

    protected $fillable = [
        'user_id', 'server_id',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function server() {
        return $this->belongsTo(Server::class);
    }

}
