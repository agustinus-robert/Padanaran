<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Traits\Metable\MetableSchema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER SEQUENCE users_id_seq RESTART WITH 10000001;");

        Schema::create('user_password_resets', function (Blueprint $table) {
            $table->string('token');
            $table->string('email');
            $table->integer('expired_in')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('user_emails', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('address')->unique();
            $table->timestamp('verified_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('user_phones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('number')->unique();
            $table->boolean('whatsapp')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('user_profile', function (Blueprint $table) {
            $table->integer('user_id');
            $table->string('name');
            $table->string('prefix')->nullable();
            $table->string('suffix')->nullable();
            $table->string('pob')->nullable();
            $table->date('dob')->nullable();
            $table->smallInteger('sex')->nullable();
            $table->smallInteger('blood')->nullable();
            $table->smallInteger('religion')->nullable()->default(0);
            $table->boolean('is_dead')->default(false);
            $table->string('avatar')->nullable();
            $table->integer('country_id')->nullable();
            $table->string('nik')->unique()->nullable();
            $table->smallInteger('religion_id')->nullable();
            $table->smallInteger('child_num')->nullable();
            $table->smallInteger('siblings_count')->nullable();
            $table->string('diseases')->nullable();
            $table->smallInteger('height')->nullable();
            $table->smallInteger('weight')->nullable();
            $table->smallInteger('hobby_id')->nullable();
            $table->smallInteger('desire_id')->nullable();
            $table->timestamps();

            $table->primary('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('ref_countries')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('hobby_id')->references('id')->on('ref_hobbies')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('religion_id')->references('id')->on('ref_religions')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('desire_id')->references('id')->on('ref_desires')->onUpdate('cascade')->onDelete('set null');
        });

        Schema::create('user_father', function (Blueprint $table) {
            $table->integer('user_id');
            $table->integer('father_id');

            $table->primary('user_id');

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('father_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('user_mother', function (Blueprint $table) {
            $table->integer('user_id');
            $table->integer('mother_id');

            $table->primary('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('mother_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('user_foster', function (Blueprint $table) {
            $table->integer('user_id');
            $table->integer('foster_id');

            $table->primary('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('foster_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('user_sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->integer('user_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('payload');
            $table->integer('last_activity');

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('user_address', function (Blueprint $table) {
            $table->integer('user_id')->nullable();
            $table->string('address')->nullable();
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->string('village')->nullable();
            $table->integer('district_id')->nullable();
            $table->integer('postal')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('district_id')->references('id')->on('ref_province_regency_districts')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('user_studies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->smallInteger('grade_id')->nullable();
            $table->string('name')->nullable();
            $table->string('diploma_num')->nullable();
            $table->date('diploma_at')->nullable();
            $table->string('npsn')->nullable();
            $table->string('nss')->nullable();
            $table->integer('from')->nullable();
            $table->integer('to')->nullable();
            $table->smallInteger('accreditation')->nullable();
            $table->integer('district_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('grade_id')->references('id')->on('ref_grades')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('district_id')->references('id')->on('ref_province_regency_districts')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('user_achievements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->string('name')->nullable();
            $table->smallInteger('territory_id')->nullable();
            $table->smallInteger('type_id')->nullable();
            $table->smallInteger('num_id')->nullable();
            $table->integer('year')->nullable();
            $table->string('file')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('territory_id')->references('id')->on('ref_territories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('type_id')->references('id')->on('ref_achievement_types')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('num_id')->references('id')->on('ref_achievement_nums')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('user_appreciations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->string('name')->nullable();
            $table->smallInteger('territory_id')->nullable();
            $table->integer('year')->nullable();
            $table->string('file')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('territory_id')->references('id')->on('ref_territories')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('user_organizations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->string('name')->nullable();
            $table->smallInteger('type_id')->nullable();
            $table->smallInteger('position_id')->nullable();
            $table->decimal('duration', 5, 2)->nullable();
            $table->integer('year')->nullable();
            $table->string('file')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('type_id')->references('id')->on('ref_organization_types')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('position_id')->references('id')->on('ref_organization_positions')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('user_disabilities', function (Blueprint $table) {
            $table->integer('user_id');
            $table->smallInteger('disability_id');

            $table->primary(['user_id', 'disability_id']);
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('disability_id')->references('id')->on('ref_disabilities')->onUpdate('cascade')->onDelete('cascade');
        });
        // Schema::create('users', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('username')->nullable();
        //     $table->string('email_address')->nullable();
        //     $table->timestampTz('email_verified_at')->nullable();
        //     $table->string('password')->nullable();
        //     $table->rememberToken();
        //     $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
        //     $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
        //     $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
        //     //   $table->foreignId('embark_id')->nullable()->constrained()->nullOnDelete();
        //     $table->softDeletesTz();
        //     $table->timestampsTz();
        // });

        // MetableSchema::create('user_metas', 'user_id', 'users');

        // Schema::create('user_roles', function (Blueprint $table) {
        //     $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
        //     $table->foreignId('role_id')->constrained('app_roles')->cascadeOnUpdate()->cascadeOnDelete();
        //     $table->primary(['role_id', 'user_id']);
        // });

        // Schema::create('user_logs', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
        //     $table->string('message');
        //     $table->nullableMorphs('modelable');
        //     $table->string('ip')->nullable();
        //     $table->string('user_agent')->nullable();
        //     $table->timestampsTz();
        // });

        // Schema::create('user_password_resets', function (Blueprint $table) {
        //     $table->string('email')->index();
        //     $table->string('token');
        //     $table->timestampTz('created_at')->nullable();
        // });

        Schema::create('user_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->text('token');
            $table->softDeletesTz();
            $table->timestampsTz();
        });

        // Schema::create('user_rate', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
        //     $table->integer('hour');
        //     $table->decimal('price', 20, 2);
        //     $table->softDeletesTz();
        //     $table->timestampsTz();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('user_password_resets');
        // Schema::dropIfExists('user_logs');
        // Schema::dropIfExists('user_meta');
        // Schema::dropIfExists('users');
    }
};
