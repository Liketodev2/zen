<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deductions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tax_return_id')->constrained()->onDelete('cascade');
            $table->json('attach')->nullable();
            
            $table->json('car_expenses')->nullable(); // Car expenses + parking + tolls
            $table->json('travel_expenses')->nullable(); // Travel Expenses
            $table->json('mobile_phone')->nullable(); // Mobile Phone
            $table->json('internet_access')->nullable(); // Internet Access
            $table->json('computer')->nullable(); // Computer / Laptop
            $table->json('gifts')->nullable(); // Gifts / Donations
            $table->json('home_office')->nullable(); // Home Office Expenses
            $table->json('books')->nullable(); // Books & Work Related
            $table->json('tax_affairs')->nullable(); // Cost of Tax Affairs
            $table->json('uniforms')->nullable(); // Uniforms
            $table->json('education')->nullable(); // Education Expenses
            $table->json('tools')->nullable(); // Tools and Equipment
            $table->json('superannuation')->nullable(); // Superannuation Contributions
            $table->json('office_occupancy')->nullable(); // Home Office Occupancy
            $table->json('union_fees')->nullable(); // Union Fees
            $table->json('sun_protection')->nullable(); // Sun Protection
            $table->json('low_value_pool')->nullable(); // Low Value Pool Deduction
            $table->json('interest_deduction')->nullable(); // Interest Deduction
            $table->json('dividend_deduction')->nullable(); // Dividend Deduction
            $table->json('upp')->nullable(); // Deductible Amount Of UPP
            $table->json('project_pool')->nullable(); // Deduction For Project Pool
            $table->json('investment_scheme')->nullable(); // Investment Scheme Deduction
            $table->json('other')->nullable(); // Other Deductions

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deductions');
    }
};
