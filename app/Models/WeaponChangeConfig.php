<?php
// app/Models/WeaponChangeConfig.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeaponChangeConfig extends Model
{
    protected $fillable = [
        'change_fee',
        'min_level_required',
        'is_enabled',
        'maintenance_message'
    ];
}