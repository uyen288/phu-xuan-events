<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('category_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title');
            $table->text('description');
            $table->string('location');

            // THÊM: Cột lưu tên file ảnh banner sự kiện (để trống nếu chưa upload)
            $table->string('banner')->nullable();

            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('capacity');
            $table->enum('status', [
                'draft',
                'published',
                'cancelled',
                'completed'
            ])->default('draft');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};