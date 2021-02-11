<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_units', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('details');
            $table->decimal('rent', 16, 2);
            $table->decimal('deposit', 16, 2)->nullable();
            $table->decimal('commission', 10, 2)->default(0.0);
            $table->text('description');
            $table->enum('status',['vacant','occupied','unavailable'])->default('vacant');
            $table->foreignId('property_id')
                ->constrained()
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreignId('landlord_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null')
                ->onUpdate('cascade');

            $table->foreignId('manager_id')
                ->nullable()
                ->constrained('users','id')
                ->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('property_units');
    }
}
