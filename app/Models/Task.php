<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 


class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'team_id',
        'assigned_to',
        'status',
        'priority',
        'due_date',
        'created_by'
    ];
  

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}