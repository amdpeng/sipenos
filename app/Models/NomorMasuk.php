<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NomorMasuk extends Model
{
    use HasFactory;
    protected $fillable =
    [
        'nomor_surat',
        'tanggal_surat',
        'pengirim',
        'keterangan_surat',
        'satuan_pendidikan',
        'status',
        'file_surat',
    ];
}
