<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class NDV01Charac extends Model
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
    protected $table = 'ND_V01_Charac';

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
    protected $hidden = [''];
	

    public function getCurrentCharactersInfor($userid)
    {
        $characters = NDV01Charac::select(['ND_V01_CharacState.unique_id', 'chr_name', 'inner_level', 'gong', 'honor'])
            ->join('ND_V01_CharacState', 'ND_V01_CharacState.unique_id', '=', 'ND_V01_Charac.unique_id')
            ->where('ND_V01_Charac.user_id', $userid)
            ->get();

        return $characters;
    }
    public function getCurrentCharactersInforWithQuery($query)
    {
        $characters = NDV01Charac::select(['ND_V01_CharacState.unique_id', 'chr_name', 'inner_level', 'gong', 'honor'])
            ->join('ND_V01_CharacState', 'ND_V01_CharacState.unique_id', '=', 'ND_V01_Charac.unique_id')
            ->where($query)
            ->get();

        return $characters;
    }

    public function getCurrentCharactersElixir($userid)
    {
        $characters = NDV01Charac::select(['ND_V02_Elixir.cuid', 'money'])
            ->join('ND_V02_Elixir', 'ND_V02_Elixir.cuid', '=', 'ND_V01_Charac.unique_id')
            ->where('ND_V01_Charac.user_id', $userid)
            ->get();

        return $characters;
    }
    public function getCurrentCharacterselixirWithQuery($query)
    {
        $characters = NDV01Charac::select(['ND_V02_Elixir.cuid', 'money'])
            ->join('ND_V02_Elixir', 'ND_V02_Elixir.cuid', '=', 'ND_V01_Charac.unique_id')
            ->where($query)
            ->get();

        return $characters;
    }


    public function NDV01CharacState()
{
    return $this->hasOne(NDV01CharacState::class, 'unique_id', 'unique_id');
}
}