<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // 誰のタスクか
            $table->string('title', 100);                                   // タスクの名前
            $table->date('due_date')->nullable();                           // 期限(空でもOK)
            $table->boolean('is_completed')->default(false);                // 完了したか(初期値は未完了)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
