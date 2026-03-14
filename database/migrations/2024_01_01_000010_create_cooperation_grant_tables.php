<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kerjasama (Cooperation/Partnership)
        Schema::create('cooperations', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('type')->default('mou'); // mou, moa, ia, other
            $table->string('partner_name');
            $table->string('partner_logo')->nullable();
            $table->string('partner_country')->nullable();
            $table->string('document_number')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status')->default('active'); // active, expired, terminated
            $table->string('document_file')->nullable();
            $table->boolean('is_published')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('cooperation_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cooperation_id')->constrained()->onDelete('cascade');
            $table->string('locale', 5);
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('scope')->nullable();
            $table->timestamps();
            
            $table->unique(['cooperation_id', 'locale']);
        });

        // Hibah (Grants)
        Schema::create('grants', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('type')->default('internal'); // internal, external, international
            $table->string('funding_source')->nullable();
            $table->string('year', 4)->nullable();
            $table->decimal('total_budget', 15, 2)->nullable();
            $table->date('deadline')->nullable();
            $table->string('status')->default('open'); // open, closed, ongoing
            $table->string('image')->nullable();
            $table->string('document_file')->nullable();
            $table->boolean('is_published')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('grant_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grant_id')->constrained()->onDelete('cascade');
            $table->string('locale', 5);
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('requirements')->nullable();
            $table->text('benefits')->nullable();
            $table->timestamps();
            
            $table->unique(['grant_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grant_translations');
        Schema::dropIfExists('grants');
        Schema::dropIfExists('cooperation_translations');
        Schema::dropIfExists('cooperations');
    }
};
