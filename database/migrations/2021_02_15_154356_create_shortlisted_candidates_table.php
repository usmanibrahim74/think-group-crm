<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShortlistedCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shortlisted_candidates', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade');
            $table->integer('job_id')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('contact_via');
            $table->string('employer_notes')->nullable();
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
        Schema::dropIfExists('shortlisted_candidates');
    }
}
