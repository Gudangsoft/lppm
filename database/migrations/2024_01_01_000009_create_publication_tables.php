<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Jurnal
        Schema::create('journals', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('issn')->nullable();
            $table->string('eissn')->nullable();
            $table->string('publisher')->nullable();
            $table->string('website')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('accreditation')->nullable(); // Sinta 1-6
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('journal_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_id')->constrained()->onDelete('cascade');
            $table->string('locale', 5);
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('focus_scope')->nullable();
            $table->timestamps();
            
            $table->unique(['journal_id', 'locale']);
        });

        // Publikasi (Articles)
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('type')->default('journal'); // journal, proceeding, book, other
            $table->foreignId('journal_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('year', 4)->nullable();
            $table->string('volume')->nullable();
            $table->string('issue')->nullable();
            $table->string('pages')->nullable();
            $table->string('doi')->nullable();
            $table->string('url')->nullable();
            $table->string('file')->nullable();
            $table->string('cover_image')->nullable();
            $table->boolean('is_published')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('publication_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('publication_id')->constrained()->onDelete('cascade');
            $table->string('locale', 5);
            $table->string('title');
            $table->text('abstract')->nullable();
            $table->text('keywords')->nullable();
            $table->timestamps();
            
            $table->unique(['publication_id', 'locale']);
        });

        // Publication Authors
        Schema::create('publication_authors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('publication_id')->constrained()->onDelete('cascade');
            $table->foreignId('researcher_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name'); // for external authors
            $table->string('affiliation')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_corresponding')->default(false);
            $table->timestamps();
        });

        // HKI (Intellectual Property)
        Schema::create('hkis', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('type')->default('paten'); // paten, hak_cipta, merek, desain_industri, rahasia_dagang
            $table->string('registration_number')->nullable();
            $table->string('certificate_number')->nullable();
            $table->date('filing_date')->nullable();
            $table->date('registration_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('status')->default('pending'); // pending, registered, expired
            $table->string('certificate_file')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_published')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('hki_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hki_id')->constrained()->onDelete('cascade');
            $table->string('locale', 5);
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('inventors')->nullable();
            $table->timestamps();
            
            $table->unique(['hki_id', 'locale']);
        });

        // Repository
        Schema::create('repositories', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('type')->default('thesis'); // thesis, dissertation, research_report, other
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('year', 4)->nullable();
            $table->string('file')->nullable();
            $table->string('cover_image')->nullable();
            $table->integer('downloads')->default(0);
            $table->boolean('is_published')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('repository_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repository_id')->constrained()->onDelete('cascade');
            $table->string('locale', 5);
            $table->string('title');
            $table->text('abstract')->nullable();
            $table->text('keywords')->nullable();
            $table->string('authors')->nullable();
            $table->timestamps();
            
            $table->unique(['repository_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repository_translations');
        Schema::dropIfExists('repositories');
        Schema::dropIfExists('hki_translations');
        Schema::dropIfExists('hkis');
        Schema::dropIfExists('publication_authors');
        Schema::dropIfExists('publication_translations');
        Schema::dropIfExists('publications');
        Schema::dropIfExists('journal_translations');
        Schema::dropIfExists('journals');
    }
};
