<?php

use App\Models\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->text('display_name');
            $table->text('technical_name');
        });

        Status::insert([
            'display_name' => 'Aktív',
            'technical_name' => 'active'
        ]);

        Status::insert([
            'display_name' => 'Inaktív',
            'technical_name' => 'inactive'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('statuses');
    }
};
