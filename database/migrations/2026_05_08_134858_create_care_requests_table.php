<?php

use App\Enums\CareRequestStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('care_requests', function (Blueprint $table) {
            $table->id();

            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('care_type_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('assigned_nurse_id')
                ->nullable()
                ->constrained('nurse_profiles')
                ->nullOnDelete();

            $table->unsignedTinyInteger('care_for');

            $table->string('patient_name')->nullable();

            $table->string('patient_age')->nullable();

            $table->string('contact_phone', 15);

            $table->string('secondary_phone', 15)->nullable();

            $table->decimal('latitude', 10, 7);

            $table->decimal('longitude', 10, 7);

            $table->text('address');

            $table->string('city');

            $table->string('state');

            $table->string('country')->nullable();

            $table->string('pincode');

            $table->date('start_date');

            $table->date('end_date')->nullable();

            $table->time('start_time')->nullable();

            $table->time('end_time')->nullable();

            $table->text('notes')->nullable();

            $table->string('otp')->nullable();

            $table->timestamp('otp_expires_at')->nullable();

            $table->timestamp('service_started_at')->nullable();

            $table->timestamp('service_ended_at')->nullable();

            $table->decimal('final_amount', 10, 2)->nullable();

            $table->decimal('commission_amount', 10, 2)->nullable();

            $table->decimal('nurse_payout', 10, 2)->nullable();

            $table->unsignedTinyInteger('cancelled_by')->nullable();

            $table->boolean('is_disputed')->default(false);

            $table->unsignedTinyInteger('status')->default(0);

            $table->timestamps();

            $table->softDeletes();

            $table->index('status');

            $table->index('user_id');

            $table->index('assigned_nurse_id');

            $table->index(['user_id', 'status']);

            $table->index(['assigned_nurse_id', 'status']);

            $table->index(['care_type_id', 'status']);

            $table->index(['start_date', 'end_date']);

            $table->index(['latitude', 'longitude']);

            $table->index(['city', 'status']);

            $table->index(['status', 'start_date']);

            $table->index(['status', 'care_type_id', 'start_date']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('care_requests');
    }
};
