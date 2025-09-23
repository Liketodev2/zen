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
        Schema::create('basic_info_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tax_return_id')->constrained()->onDelete('cascade');
            $table->json('attach')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('day')->nullable();
            $table->string('month')->nullable();
            $table->string('year')->nullable();
            $table->string('phone')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->boolean('has_spouse')->nullable();
            $table->boolean('future_tax_return')->nullable();
            $table->boolean('australian_citizenship')->nullable();
            $table->string('visa_type')->nullable();
            $table->string('other_visa_type')->nullable();
            $table->boolean('long_stay_183')->nullable();
            $table->string('arrival_month')->nullable();
            $table->string('arrival_year')->nullable();
            $table->string('departure_month')->nullable();
            $table->string('departure_year')->nullable();
            $table->string('stay_purpose')->nullable();
            $table->boolean('full_tax_year')->nullable();
            $table->text('home_address')->nullable();
            $table->boolean('same_as_home_address')->nullable();
            $table->text('postal_address')->nullable();
            $table->boolean('has_education_debt')->nullable();
            $table->boolean('has_sfss_debt')->nullable();
            $table->text('other_tax_debts')->nullable();
            $table->string('occupation')->nullable();
            $table->string('other_occupation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('basic_info_forms');
    }
};
