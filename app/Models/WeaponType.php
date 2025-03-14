<?php
// app/Models/WeaponType.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeaponType extends Model
{
    protected $fillable = [
        'name',
        'min_id',
        'max_id'
    ];
}