<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

        protected $fillable = [
        'name',
        'email',
        'phone',
        'department_id'
    ];

    // Ocultars las columnas que no es necesario mostrar
    protected $hidden =[
        'updated_at',
        'created_at'
    ];
    
}
