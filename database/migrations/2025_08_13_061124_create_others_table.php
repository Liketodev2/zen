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
        Schema::create('others', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tax_return_id')
                ->constrained('tax_returns')
                ->onDelete('cascade');

            $table->json('attach')->nullable();
            $table->string('any_dependent_children')->nullable();
            $table->string('additional_questions')->nullable();
            $table->json('income_tests')->nullable();
            $table->json('mls')->nullable();
            $table->json('spouse_details')->nullable(); // Spouse Details
            $table->json('private_health_insurance')->nullable(); // Private Health Insurance
            $table->json('zone_overseas_forces_offset')->nullable(); // Zone / Overseas Forces Offset
            $table->json('seniors_offset')->nullable(); // Seniors Offset
            $table->json('medicare_reduction_exemption')->nullable(); // Medicare Reduction / Exemption
            $table->json('part_year_tax_free_threshold')->nullable(); // Part-year Tax-free Threshold
            $table->json('medical_expenses_offset')->nullable(); // Medical Expenses Offset
            $table->json('under_18')->nullable(); // Under 18
            $table->json('working_holiday_maker_net_income')->nullable(); // Working Holiday Maker Net Income
            $table->json('superannuation_income_stream_offset')->nullable(); // Superannuation Income Stream Offset
            $table->json('superannuation_contributions_spouse')->nullable(); // Superannuation Contributions on Behalf of Your Spouse
            $table->json('tax_losses_earlier_income_years')->nullable(); // Tax Losses of Earlier Income Years
            $table->json('dependent_invalid_and_carer')->nullable(); // Dependent (invalid and carer)
            $table->json('superannuation_co_contribution')->nullable(); // Superannuation Co-Contribution
            $table->json('other_tax_offsets_refundable')->nullable(); // Other Tax Offsets (Refundable)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('others');
    }
};
