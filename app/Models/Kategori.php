<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kategori extends Model
{
    public $timestamps = false;
    protected $table = "kategori";
    // protected $fillable = ['nama_kategori'];
    protected $guarded = ['id'];
}
