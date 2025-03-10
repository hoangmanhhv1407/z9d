<?php

namespace App\Models;

use App\User as User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

use Illuminate\Foundation\Auth\User as Authenticatable;

class GameConnect extends Authenticatable
{
    use Notifiable;

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'sqlsrv';
    public $timestamps = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Tbl_ND_GameConnect';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    /* protected $fillable = ['userid','userpassword']; */

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        '',
    ];

//    public function causer()
//    {
//        return $this->belongsTo(User::class);
//    }
}
