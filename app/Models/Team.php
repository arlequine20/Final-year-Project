<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Team extends Model
{
    protected $fillable = [
        'name',
        'description',
        'created_by',
        'manager_id',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    public function tasks()
{
    return $this->hasMany(Task::class);
}
public function manager()
{
    return $this->belongsTo(User::class, 'manager_id');
}
}