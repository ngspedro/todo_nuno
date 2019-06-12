<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title', 'completed', 'dueDate', 'priorityId', 'userId'];

    public function priority(){
        return $this->belongsTo('App\Priority', 'priorityId');
    }

    public function user(){
        return $this.belongsTo('App\User', 'userId');
    }
}
