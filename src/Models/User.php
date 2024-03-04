<?php

namespace Pine\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
  protected $fillable = [
    'email',
  ];
};
