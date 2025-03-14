<?php
// app/Models/WeaponImage.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeaponImage extends Model
{
    protected $fillable = [
        'weapon_id',
        'description',
        'image_url'
    ];
}