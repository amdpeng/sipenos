<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NomorKeluar extends Model
{
    use HasFactory;
    protected $fillable =
    [
        'nomor_surat',
        'type_surat',
        'tanggal_surat',
        'nama_guru',
        'status',
        'keterangan_surat',
    ];
    protected $casts = [
        'nama_guru' => 'array',
    ];
    public function gurus()
    {
        return $this->belongsTo(Teacher::class, 'gurus_id','id');
    }
    protected static function booted()
    {
        static::creating(function ($nomorSurat) {
            // Isi kolom created_by dengan ID pengguna yang sedang login
            $nomorSurat->created_by = auth()->id();
        });
    }

    // Relasi ke User
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($surat) {
            if (empty($surat->nomor_surat)) {
                $surat->nomor_surat = self::generateNomorSurat($surat->type_surat);
            }
        });
    }
    public static function convertToRoman($month)
    {
        $romans = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
        ];

        return $romans[$month] ?? $month; // Kembalikan Romawi atau angka asli jika tidak valid
    }

    // Fungsi generate nomor surat
    public static function generateNomorSurat($type_surat)
    {
        $tahun = now()->year; // Tahun saat ini
        $bulan = now()->month; // Bulan saat ini dalam angka
        $bulanRomawi = self::convertToRoman($bulan); // Konversi bulan ke Romawi

        // Ambil nomor terakhir berdasarkan tipe surat dan tahun
        $lastSurat = self::where('type_surat', $type_surat)
            ->whereYear('created_at', $tahun)
            ->orderBy('id', 'desc')
            ->first();

        // Tentukan nomor berikutnya
        $nextNumber = $lastSurat ? ((int) explode('/', $lastSurat->nomor_surat)[0] + 1) : 1;

        // Format nomor surat
        $prefix = 'SMKN1WON';

        return sprintf('%03d/%s-%s/%s/%d', $nextNumber, $prefix, $type_surat, $bulanRomawi, $tahun);
    }
}
