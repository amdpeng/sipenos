<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    protected $fillable =
    [
        'nip_nuptk',
        'teacher_name',
        'gender',
        'religion',
        'date_of_birth',
        'jenis_ptk',
        'type',
        'whatsapp_number',
    ];
}
