<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RankingReward extends Model
{
    protected $fillable = ['ranking_type', 'rank_from', 'rank_to', 'product_id'];

public function product()
{
    // Loại bỏ điều kiện where('prd_status', 1)
    return $this->belongsTo(Product::class);
}
}