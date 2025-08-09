<?php

use App\Models\ReservationType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('reservation_types', function (Blueprint $table) {
            $table->id();
            $table->text('display_name');
            $table->text('technical_name');
        });

        ReservationType::insert([
            'display_name' => 'gyors foglalás',
            'technical_name' => 'instant'
        ]);

        ReservationType::insert([
            'display_name' => 'előfoglalás',
            'technical_name' => 'pre'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('reservation_types');
    }
};
