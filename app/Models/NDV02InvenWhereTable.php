<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NDV02InvenWhereTable extends Model
{
    protected $connection = 'cuuamsql2';
    protected $table = 'ND_V02_INVEN_WHERE_TABLE';
    protected $primaryKey = 'cuid';
    public $timestamps = false;

    protected $fillable = [
        'cuid',
        'inven_table',
        'strhld_i',
        'check_date'
    ];

    protected $casts = [
        'cuid' => 'integer',
        'inven_table' => 'integer',
        'strhld_i' => 'integer',
        'check_date' => 'datetime',
    ];

    /**
     * Lấy character liên quan
     */
    public function character()
    {
        return $this->belongsTo(NDV01CharacState::class, 'cuid', 'unique_id');
    }

    /**
     * Lấy inventory table dựa vào index
     */
    public function getInventoryTable()
    {
        // Hàm này sẽ trả về model tương ứng dựa vào inven_table
        $invenTableClass = 'App\\Models\\NDV02InvenTable' . $this->inven_table;
        if (class_exists($invenTableClass)) {
            return app($invenTableClass)->where('cuid', $this->cuid);
        }
        return null;
    }
}