<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;


class MemberModel extends Model
{
    protected $table 	= 'members';
    protected $guarded = [''];
    protected $hidden   = ['created_at','updated_at'];
    public $incrementing = true;
    protected $keyType = 'uuid';

}