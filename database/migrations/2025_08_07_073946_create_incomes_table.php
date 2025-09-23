<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tax_return_id')->constrained()->onDelete('cascade');
            $table->json('attach')->nullable();

            $table->json('salary')->nullable(); // Salary / Wages
            $table->json('interests')->nullable(); // Interest
            $table->json('dividends')->nullable(); // Dividends
            $table->json('government_allowances')->nullable(); // Government Allowances
            $table->json('government_pensions')->nullable(); // Government Pension
            $table->json('capital_gains')->nullable(); // Capital Gains or Losses
            $table->json('managed_funds')->nullable(); // Managed Funds
            $table->json('termination_payments')->nullable(); // Termination Payments
            $table->json('rent')->nullable(); // Rent Received
            $table->json('partnerships')->nullable(); // Partnerships and Trusts
            $table->json('annuities')->nullable(); // Australian Annuities
            $table->json('superannuation')->nullable(); // Superannuation Income Stream
            $table->json('super_lump_sums')->nullable(); // Super Lump Sums
            $table->json('ess')->nullable(); // Employee Share Schemes
            $table->json('personal_services')->nullable(); // Personal Services Income
            $table->json('business_income')->nullable(); // Income / Loss From Business
            $table->json('business_losses')->nullable(); // Deferred Business Losses
            $table->json('foreign_income')->nullable(); // Foreign Source Income
            $table->json('other_income')->nullable(); // Other Income

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
