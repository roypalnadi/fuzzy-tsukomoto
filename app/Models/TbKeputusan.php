<?php

namespace App\Models;

use App\Http\Controllers\FuzzyController;
use Illuminate\Database\Eloquent\Model;

class TbKeputusan extends Model
{
    protected $table = 'tb_keputusan';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama',
        'psikologi',
        'tkk',
        'jasmani',
        'akademik',
        'kuota',
        'hasil',
    ];

    protected $append = [
        'hasil_label',
    ];

    public function getHasilLabelAttribute()
    {
        return FuzzyController::getLabelHasil($this->hasil);
    }
}
