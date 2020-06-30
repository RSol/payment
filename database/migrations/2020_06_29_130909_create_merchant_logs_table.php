<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_logs', static function (Blueprint $table) {
            $table->id();
            $table->string('system');
            $table->unsignedBigInteger('invoice_id');
            $table->integer('status');
            $table->string('payment_id');
            $table->string('confirmation_url');
            $table->text('metadata');
            $table->text('payment_method');
            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('invoices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchant_logs', static function (Blueprint $table) {
            $table->dropForeign(['invoice_id']);
        });
        Schema::dropIfExists('merchant_logs');
    }
}
