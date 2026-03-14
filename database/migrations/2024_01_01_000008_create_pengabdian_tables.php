<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Program PkM
        Schema::create('pkm_programs', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('code')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('pkm_program_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pkm_program_id')->constrained()->onDelete('cascade');
            $table->string('locale', 5);
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->unique(['pkm_program_id', 'locale'], 'pkm_prog_trans_unique');
        });

        // Pengabdian
        Schema::create('pengabdians', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->foreignId('program_id')->nullable()->constrained('pkm_programs')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('year', 4)->nullable();
            $table->decimal('budget', 15, 2)->nullable();
            $table->string('location')->nullable();
            $table->string('partner')->nullable();
            $table->string('status')->default('draft'); // draft, submitted, approved, rejected, ongoing, completed
            $table->string('featured_image')->nullable();
            $table->boolean('is_published')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('pengabdian_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengabdian_id')->constrained()->onDelete('cascade');
            $table->string('locale', 5);
            $table->string('title');
            $table->text('abstract')->nullable();
            $table->longText('content')->nullable();
            $table->text('output')->nullable();
            $table->timestamps();
            
            $table->unique(['pengabdian_id', 'locale']);
        });

        // Pengabdian Team
        Schema::create('pengabdian_team', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengabdian_id')->constrained()->onDelete('cascade');
            $table->foreignId('researcher_id')->constrained()->onDelete('cascade');
            $table->string('role')->default('member'); // leader, member
            $table->timestamps();
        });

        // Panduan Pengabdian
        Schema::create('pkm_guides', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('file')->nullable();
            $table->string('year', 4)->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('pkm_guide_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pkm_guide_id')->constrained()->onDelete('cascade');
            $table->string('locale', 5);
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->unique(['pkm_guide_id', 'locale'], 'pkm_guide_trans_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pkm_guide_translations');
        Schema::dropIfExists('pkm_guides');
        Schema::dropIfExists('pengabdian_team');
        Schema::dropIfExists('pengabdian_translations');
        Schema::dropIfExists('pengabdians');
        Schema::dropIfExists('pkm_program_translations');
        Schema::dropIfExists('pkm_programs');
    }
};
