<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityStreamTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('activity-stream.tables.activities'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('verb');
            $table->morphs('actor');
            $table->morphs('object');
            $table->nullableMorphs('target');
            $table->text('extra')->nullable();
            $table->timestamps();
        });

        Schema::create(config('activity-stream.tables.feeds'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('activity_id')->index();
            $table->morphs('owner');
            $table->enum('type', ['flat', 'timeline', 'notification']);
            $table->timestamps();

            $table->index(['owner_id', 'owner_type']);
            $table->unique(['owner_id', 'owner_type', 'activity_id', 'type']);
        });

        Schema::create(config('activity-stream.tables.follows'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('follower');
            $table->morphs('followable');
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
        Schema::dropIfExists(config('activity-stream.tables.activities'));
        Schema::dropIfExists(config('activity-stream.tables.feeds'));
        Schema::dropIfExists(config('activity-stream.tables.follow'));
    }
}
