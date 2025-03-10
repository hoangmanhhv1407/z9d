<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class NDV02DepotInfo extends Model
{

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'cuuamsql2';
    public $timestamps = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ND_V02_DepotInfo';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        '',
    ];

    public function NDV02DepotInfo()
    {
        return $this->hasOne(NDV01Charac::class,'unique_id','cuid');
    }
}
