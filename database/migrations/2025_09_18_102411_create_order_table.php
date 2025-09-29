<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('name', 255);
            $table->string('phone', 255);
            $table->string('email', 255);
            $table->string('address', 1000);
            $table->text('note')->nullable();
            $table->string('payment_method', 50)->default('cod'); // thêm cột này
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes('deleted_at');
            $table->unsignedInteger('status');
            $table->unsignedTinyInteger('status')->default(0); // 0: chờ, 1: đã thanh toán, 2: chờ xác thực
            $table->string('payment_proof')->nullable();      // lưu đường dẫn chứng từ khi test ngân hàng
            $table->timestamp('paid_at')->nullable();         // thời gian thanh toán thực tế

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order', function (Blueprint $table) {
            if (Schema::hasColumn('order', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
            if (Schema::hasColumn('order', 'payment_proof')) {
                $table->dropColumn('payment_proof');
            }
            if (Schema::hasColumn('order', 'paid_at')) {
                $table->dropColumn('paid_at');
            }
            // Không revert status sang kiểu cũ tự động
        });
    }
};
