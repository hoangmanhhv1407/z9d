<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class NDV02Elixir extends Model
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
    protected $table = 'ND_V02_Elixir';


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

    public function NDV01Charac()
    {
        return $this->belongsTo(NDV01Charac::class, 'cuid', 'unique_id');
    }
}
