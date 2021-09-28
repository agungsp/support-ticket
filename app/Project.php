<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    public $table = 'projects';

    protected $dates = [
        'created_at',
        'updated_at',
        // 'deleted_at',
    ];

    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
        // 'deleted_at',
    ];

    public function user(){
        return $this->belongsToMany(User::class, 'user_project', 'project_id', 'user_id');
    }

    public function ticket(){
        return $this->hasMany(Ticket::class, 'project_id', 'id');
    }
}
