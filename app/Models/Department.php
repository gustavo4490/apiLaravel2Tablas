<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    
    // se colocan las columnas que van en la migracion
    protected $fillable = ['name'];
}
