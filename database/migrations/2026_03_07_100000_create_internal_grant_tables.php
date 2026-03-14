<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Skema Hibah Internal (Internal Grant Schemes)
        Schema::create('internal_grant_schemes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('max_budget', 15, 2)->nullable();
            $table->integer('max_duration_months')->default(12);
            $table->text('requirements')->nullable();
            $table->text('output_requirements')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Periode Pengajuan (Submission Periods)
        Schema::create('internal_grant_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scheme_id')->constrained('internal_grant_schemes')->onDelete('cascade');
            $table->string('year', 4);
            $table->string('semester', 20)->nullable(); // Ganjil, Genap
            $table->date('submission_start');
            $table->date('submission_end');
            $table->date('review_start')->nullable();
            $table->date('review_end')->nullable();
            $table->date('announcement_date')->nullable();
            $table->decimal('total_budget_available', 15, 2)->nullable();
            $table->integer('max_proposals')->nullable();
            $table->string('status')->default('draft'); // draft, open, closed, review, announced
            $table->timestamps();
        });

        // Pengajuan Hibah Internal (Internal Grant Submissions)
        Schema::create('internal_grant_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique();
            $table->foreignId('period_id')->constrained('internal_grant_periods')->onDelete('cascade');
            $table->foreignId('researcher_id')->constrained('researchers')->onDelete('cascade');
            $table->string('title');
            $table->text('abstract')->nullable();
            $table->text('background')->nullable();
            $table->text('objectives')->nullable();
            $table->text('methodology')->nullable();
            $table->text('expected_output')->nullable();
            $table->text('timeline')->nullable();
            $table->decimal('requested_budget', 15, 2)->nullable();
            $table->json('budget_details')->nullable();
            $table->string('proposal_file')->nullable();
            $table->string('supporting_documents')->nullable();
            $table->string('status')->default('draft'); // draft, submitted, under_review, revision, accepted, rejected, cancelled
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });

        // Tim Pengajuan (Submission Team Members)
        Schema::create('internal_grant_submission_team', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('internal_grant_submissions')->onDelete('cascade');
            $table->foreignId('researcher_id')->constrained('researchers')->onDelete('cascade');
            $table->string('role')->default('member'); // leader, member
            $table->timestamps();
            
            $table->unique(['submission_id', 'researcher_id'], 'ig_team_unique');
        });

        // Review Pengajuan (Submission Reviews)
        Schema::create('internal_grant_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('internal_grant_submissions')->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->integer('score_relevance')->nullable(); // 0-100
            $table->integer('score_methodology')->nullable();
            $table->integer('score_output')->nullable();
            $table->integer('score_budget')->nullable();
            $table->integer('score_team')->nullable();
            $table->integer('total_score')->nullable();
            $table->text('comments')->nullable();
            $table->text('suggestions')->nullable();
            $table->string('recommendation')->nullable(); // accept, revision, reject
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });

        // Penerimaan Hibah (Accepted Grants)
        Schema::create('internal_grants', function (Blueprint $table) {
            $table->id();
            $table->string('contract_number')->unique();
            $table->foreignId('submission_id')->constrained('internal_grant_submissions')->onDelete('cascade');
            $table->date('contract_date')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('approved_budget', 15, 2);
            $table->string('contract_file')->nullable();
            $table->string('status')->default('active'); // active, completed, terminated, extended
            $table->text('notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        // Pencairan Dana (Fund Disbursements)
        Schema::create('internal_grant_disbursements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grant_id')->constrained('internal_grants')->onDelete('cascade');
            $table->string('phase')->default('1'); // 1, 2, 3, etc
            $table->decimal('amount', 15, 2);
            $table->date('disbursement_date')->nullable();
            $table->string('status')->default('pending'); // pending, processed, completed
            $table->string('proof_file')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Laporan Kemajuan (Progress Reports)
        Schema::create('internal_grant_progress_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grant_id')->constrained('internal_grants')->onDelete('cascade');
            $table->string('report_type')->default('progress'); // progress, final
            $table->string('period')->nullable(); // e.g., "Month 1-3"
            $table->text('activities')->nullable();
            $table->text('achievements')->nullable();
            $table->text('obstacles')->nullable();
            $table->text('solutions')->nullable();
            $table->integer('progress_percentage')->default(0);
            $table->json('budget_realization')->nullable();
            $table->decimal('budget_spent', 15, 2)->default(0);
            $table->string('report_file')->nullable();
            $table->json('attachments')->nullable();
            $table->string('status')->default('draft'); // draft, submitted, approved, revision
            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('reviewer_notes')->nullable();
            $table->timestamps();
        });

        // Output/Luaran (Outputs)
        Schema::create('internal_grant_outputs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grant_id')->constrained('internal_grants')->onDelete('cascade');
            $table->string('output_type'); // publication, hki, prototype, book, etc
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->default('planned'); // planned, in_progress, completed
            $table->string('evidence_file')->nullable();
            $table->string('url')->nullable();
            $table->date('completion_date')->nullable();
            $table->timestamps();
        });

        // Laporan Akhir (Final Reports) 
        Schema::create('internal_grant_final_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grant_id')->constrained('internal_grants')->onDelete('cascade');
            $table->text('executive_summary')->nullable();
            $table->text('introduction')->nullable();
            $table->text('methodology')->nullable();
            $table->text('results')->nullable();
            $table->text('discussion')->nullable();
            $table->text('conclusion')->nullable();
            $table->text('recommendations')->nullable();
            $table->text('references')->nullable();
            $table->decimal('total_budget_spent', 15, 2)->default(0);
            $table->json('budget_realization_detail')->nullable();
            $table->string('report_file')->nullable();
            $table->string('financial_report_file')->nullable();
            $table->json('output_evidence_files')->nullable();
            $table->string('status')->default('draft'); // draft, submitted, approved, revision
            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('reviewer_notes')->nullable();
            $table->integer('final_score')->nullable();
            $table->timestamps();
        });

        // Log Aktivitas
        Schema::create('internal_grant_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('loggable_type');
            $table->unsignedBigInteger('loggable_id');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action');
            $table->text('description')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->timestamps();

            $table->index(['loggable_type', 'loggable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internal_grant_activity_logs');
        Schema::dropIfExists('internal_grant_final_reports');
        Schema::dropIfExists('internal_grant_outputs');
        Schema::dropIfExists('internal_grant_progress_reports');
        Schema::dropIfExists('internal_grant_disbursements');
        Schema::dropIfExists('internal_grants');
        Schema::dropIfExists('internal_grant_reviews');
        Schema::dropIfExists('internal_grant_submission_team');
        Schema::dropIfExists('internal_grant_submissions');
        Schema::dropIfExists('internal_grant_periods');
        Schema::dropIfExists('internal_grant_schemes');
    }
};
