<?php
// app/Models/WeaponChangeLog.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeaponChangeLog extends Model
{
    protected $fillable = [
        'user_id',
        'character_id',
        'character_name',
        'old_weapon_id',
        'new_weapon_id',
        'cost'
    ];
}