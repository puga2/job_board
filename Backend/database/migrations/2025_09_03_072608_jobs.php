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
        //
        Schema::create('jobs',function(Blueprint $table){
            $table->id();
            $table->string('title',255);
            $table->text('description');
            $table->decimal('salary',10,2);
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('job_function_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employment_type_id')->constrained()->cascadeOnDelete();
            $table->enum('status',['draft','published','closed'])->default('draft');
            $table->dateTime('posted_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('jobs');
    }
};
