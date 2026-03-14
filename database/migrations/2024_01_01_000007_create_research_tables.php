<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Skema Hibah Penelitian
        Schema::create('research_schemes', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('code')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('research_scheme_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('research_scheme_id')->constrained()->onDelete('cascade');
            $table->string('locale', 5);
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('requirements')->nullable();
            $table->timestamps();
            
            $table->unique(['research_scheme_id', 'locale'], 'rscheme_trans_unique');
        });

        // Penelitian
        Schema::create('researches', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->foreignId('scheme_id')->nullable()->constrained('research_schemes')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('year', 4)->nullable();
            $table->decimal('budget', 15, 2)->nullable();
            $table->string('status')->default('draft'); // draft, submitted, approved, rejected, ongoing, completed
            $table->string('featured_image')->nullable();
            $table->boolean('is_published')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('research_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('research_id')->constrained('researches')->onDelete('cascade');
            $table->string('locale', 5);
            $table->string('title');
            $table->text('abstract')->nullable();
            $table->longText('content')->nullable();
            $table->text('keywords')->nullable();
            $table->timestamps();
            
            $table->unique(['research_id', 'locale']);
        });

        // Peneliti (Researchers)
        Schema::create('researchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('nidn')->nullable();
            $table->string('nip')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('department')->nullable();
            $table->string('faculty')->nullable();
            $table->string('photo')->nullable();
            $table->text('bio')->nullable();
            $table->string('scopus_id')->nullable();
            $table->string('google_scholar_id')->nullable();
            $table->string('orcid')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Research Team
        Schema::create('research_team', function (Blueprint $table) {
            $table->id();
            $table->foreignId('research_id')->constrained('researches')->onDelete('cascade');
            $table->foreignId('researcher_id')->constrained()->onDelete('cascade');
            $table->string('role')->default('member'); // leader, member
            $table->timestamps();
        });

        // Roadmap Penelitian
        Schema::create('research_roadmaps', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('year_start', 4)->nullable();
            $table->string('year_end', 4)->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('research_roadmap_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('research_roadmap_id')->constrained()->onDelete('cascade');
            $table->string('locale', 5);
            $table->string('title');
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->timestamps();
            
            $table->unique(['research_roadmap_id', 'locale'], 'roadmap_trans_unique');
        });

        // Panduan Penelitian
        Schema::create('research_guides', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('file')->nullable();
            $table->string('year', 4)->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('research_guide_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('research_guide_id')->constrained()->onDelete('cascade');
            $table->string('locale', 5);
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->unique(['research_guide_id', 'locale'], 'rguide_trans_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('research_guide_translations');
        Schema::dropIfExists('research_guides');
        Schema::dropIfExists('research_roadmap_translations');
        Schema::dropIfExists('research_roadmaps');
        Schema::dropIfExists('research_team');
        Schema::dropIfExists('researchers');
        Schema::dropIfExists('research_translations');
        Schema::dropIfExists('researches');
        Schema::dropIfExists('research_scheme_translations');
        Schema::dropIfExists('research_schemes');
    }
};
