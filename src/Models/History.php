<?php

namespace Jakmall\Recruitment\Calculator\Models;

use \Illuminate\Database\Eloquent\Model;

Class History extends Model{

    protected $table = 'histories';
    protected $fillable = ['command','description','result','output'];
}