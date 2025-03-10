<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFirstRechargeClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('first_recharge_claims', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->timestamp('claimed_at');
            $table->timestamps();

            // Đảm bảo mỗi user chỉ có thể claim một lần
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('first_recharge_claims');
    }
}