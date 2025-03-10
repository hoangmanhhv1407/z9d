<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RankingEventConfig extends Model
{
    protected $table = 'ranking_event_configs';

    protected $fillable = [
        'status', 
        'start_time', 
        'end_time',
        'type_status',
        'type_start_time', 
        'type_end_time'
    ];

    protected $casts = [
        'status' => 'boolean',
        'type_status' => 'array',
        'type_start_time' => 'array',
        'type_end_time' => 'array',
    ];

    // Relationship với bảng rewards
    public function rewards()
    {
        return $this->hasMany(RankingReward::class);
    }

    // Check xem event type có đang được kích hoạt không
    public function isTypeActive($type)
    {
        if (!$this->status) {
            return false;
        }

        $typeStatus = $this->type_status ?? [];
        if (!isset($typeStatus[$type]) || $typeStatus[$type] !== "1") {
            return false;
        }

        // Chỉ kiểm tra xem type có được bật không, không kiểm tra thời gian
        return true;
    }

    // Get config của một type cụ thể
    public function getTypeConfig($type)
    {
        return [
            'status' => $this->type_status[$type] ?? false,
            'start_time' => $this->type_start_time[$type] ?? null,
            'end_time' => $this->type_end_time[$type] ?? null
        ];
    }
}