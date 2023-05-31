<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeFile extends Model
{
    protected $fillable = ['emp_full_name', 'emp_code'];
    use HasFactory;
}
