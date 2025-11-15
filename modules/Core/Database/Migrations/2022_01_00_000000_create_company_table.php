<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Traits\Metable\MetableSchema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cmp_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('grade_id');
            $table->string('kd');
            $table->string('name')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('grade_id')->references('id')->on('ref_grades')->onUpdate('cascade')->onDelete('cascade');
            $table->unique('kd');
        });

        Schema::create('cmp_role_permissions', function (Blueprint $table) {
            $table->unsignedSmallInteger('role_id');
            $table->unsignedSmallInteger('permission_id');

            $table->foreign('role_id')->references('id')->on('cmp_roles')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('app_permissions')->onUpdate('cascade')->onDelete('cascade');
            $table->primary(['role_id', 'permission_id']);
        });

        Schema::create('cmp_role_users', function (Blueprint $table) {
            $table->unsignedSmallInteger('role_id');
            $table->unsignedInteger('user_id');

            $table->foreign('role_id')->references('id')->on('cmp_roles')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->primary(['role_id', 'user_id']);
        });

        Schema::create('cmp_depts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kd');
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->unsignedSmallInteger('parent_id')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->unsignedSmallInteger('grade_id');
            $table->softDeletes();
            $table->timestamps();

            $table->unique('kd');
            $table->foreign('grade_id')->references('id')->on('ref_grades')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('cmp_depts')->onUpdate('cascade')->onDelete('set null');
        });

        Schema::create('cmp_positions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kd');
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->unsignedTinyInteger('type')->nullable();
            $table->unsignedTinyInteger('level')->nullable();
            $table->unsignedSmallInteger('dept_id')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->softDeletes();
            $table->timestamps();

            $table->unique('kd');
            $table->foreign('dept_id')->references('id')->on('cmp_depts')->onUpdate('cascade')->onDelete('set null');
        });

        Schema::create('cmp_position_trees', function (Blueprint $table) {
            $table->unsignedSmallInteger('position_id');
            $table->unsignedSmallInteger('parent_id');

            $table->primary(['position_id', 'parent_id']);
            $table->foreign('position_id')->references('id')->on('cmp_positions')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('cmp_positions')->onUpdate('cascade')->onDelete('cascade');
        });

        MetableSchema::create('cmp_position_meta', 'position_id', 'cmp_positions', 'unsignedSmallInteger');

        Schema::create('cmp_contracts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kd')->unique();
            $table->unsignedSmallInteger('grade_id');
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('grade_id')->references('id')->on('ref_grades')->onUpdate('cascade')->onDelete('cascade');
        });

        MetableSchema::create('cmp_contract_meta', 'contract_id', 'cmp_contracts', 'unsignedSmallInteger');

        Schema::create('cmp_moments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('type')->default(1);
            $table->unsignedSmallInteger('grade_id');
            $table->date('date');
            $table->string('name')->nullable();
            $table->boolean('is_holiday')->default(true);
            $table->text('meta')->nullable();
            $table->timestamps();

            $table->foreign('grade_id')->references('id')->on('ref_grades')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('cmp_vacation_ctgs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('grade_id');
            $table->string('name');
            $table->unsignedTinyInteger('type');
            $table->text('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('grade_id')->references('id')->on('ref_grades')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('cmp_leave_stdnts_ctgs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('grade_id');
            $table->string('name');
            $table->unsignedTinyInteger('parent_id')->nullable();
            $table->text('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('grade_id')->references('id')->on('ref_grades')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('cmp_leave_stdnts_ctgs')->onUpdate('cascade')->onDelete('set null');
        });

        Schema::create('cmp_leave_ctgs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('grade_id');
            $table->string('name');
            $table->unsignedTinyInteger('parent_id')->nullable();
            $table->text('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('grade_id')->references('id')->on('ref_grades')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('cmp_leave_ctgs')->onUpdate('cascade')->onDelete('set null');
        });

        Schema::create('cmp_outwork_ctgs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('grade_id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->double('price', 8, 2)->default(0);
            $table->text('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('grade_id')->references('id')->on('ref_grades')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('cmp_salary_slips', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('grade_id');
            $table->string('name');
            $table->unsignedTinyInteger('az')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('grade_id')->references('id')->on('ref_grades')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('cmp_salary_slip_ctgs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('grade_id');
            $table->unsignedTinyInteger('slip_id');
            $table->string('name');
            $table->unsignedTinyInteger('az')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('grade_id')->references('id')->on('ref_grades')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('slip_id')->references('id')->on('cmp_salary_slips')->onUpdate('cascade');
        });

        Schema::create('cmp_salary_slip_cmpnts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('grade_id');
            $table->unsignedTinyInteger('currency')->default(1);
            $table->string('kd')->unique();
            $table->unsignedTinyInteger('slip_id')->nullable();
            $table->unsignedTinyInteger('ctg_id')->nullable();
            $table->string('name');
            $table->unsignedTinyInteger('allowance')->nullable()->default(2);
            $table->unsignedTinyInteger('unit')->default(1);
            $table->unsignedTinyInteger('operate')->default(1);
            $table->jsonb('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('grade_id')->references('id')->on('ref_grades')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('slip_id')->references('id')->on('cmp_salary_slips')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('ctg_id')->references('id')->on('cmp_salary_slip_ctgs')->onUpdate('cascade')->onDelete('set null');
        });

        Schema::create('cmp_salary_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('grade_id');
            $table->string('kd')->unique();
            $table->string('name');
            $table->text('components')->nullable();
            $table->jsonb('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('grade_id')->references('id')->on('ref_grades')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('cmp_buildings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('grade_id');
            $table->string('kd')->unique();
            $table->string('name');
            $table->string('address_primary')->nullable();
            $table->string('address_secondary')->nullable();
            $table->string('address_city')->nullable();
            $table->unsignedInteger('state_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('grade_id')->references('id')->on('ref_grades')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('cmp_building_rooms', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('building_id')->nullable();
            $table->string('kd')->unique();
            $table->string('name');
            $table->text('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('building_id')->references('id')->on('cmp_buildings')->onUpdate('cascade')->onDelete('set null');
        });

        Schema::create('cmp_insurances', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('grade_id');
            $table->string('kd')->unique();
            $table->string('name')->nullable();
            $table->text('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('grade_id')->references('id')->on('ref_grades')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('cmp_insurance_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('insurance_id');
            $table->string('conditions');
            $table->double('cmp_price')->default(0);
            $table->unsignedTinyInteger('cmp_price_type')->default(1);
            $table->double('empl_price')->default(0);
            $table->unsignedTinyInteger('empl_price_type')->default(1);
            $table->double('price_factor')->default(0);
            $table->string('price_factor_additional')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('insurance_id')->references('id')->on('cmp_insurances')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('cmp_approvable', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('grade_id');
            $table->morphs('modelable');
            $table->morphs('userable');
            $table->unsignedTinyInteger('level')->default(1);
            $table->unsignedTinyInteger('cancelable')->default(0);
            $table->unsignedTinyInteger('result')->default(0);
            $table->text('reason')->nullable();
            $table->text('history')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('grade_id')->references('id')->on('ref_grades')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('cmp_ptkps', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('grade_id');
            $table->unsignedTinyInteger('sex')->nullable();
            $table->unsignedTinyInteger('mariage')->nullable();
            $table->unsignedTinyInteger('child')->nullable();
            $table->double('value', 20, 2)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('grade_id')->references('id')->on('ref_grades')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('cmp_payroll_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('grade_id');
            $table->string('key');
            $table->unsignedSmallInteger('az')->nullable();
            $table->text('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('grade_id')->references('id')->on('ref_grades')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('cmp_announcements', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('grade_id');
            $table->text('message')->nullable();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('grade_id')->references('id')->on('ref_grades')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('cmp_loan_ctgs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('grade_id');
            $table->unsignedTinyInteger('type');
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('interest_id')->nullable();
            $table->text('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('grade_id')->references('id')->on('ref_grades')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('interest_id')->references('id')->on('cmp_loan_ctgs')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cmp_loan_ctgs');
        Schema::dropIfExists('cmp_approvable');
        Schema::dropIfExists('cmp_insurance_prices');
        Schema::dropIfExists('cmp_insurances');
        Schema::dropIfExists('cmp_building_rooms');
        Schema::dropIfExists('cmp_buildings');
        Schema::dropIfExists('cmp_salary_slip_components');
        Schema::dropIfExists('cmp_salary_slip_ctgs');
        Schema::dropIfExists('cmp_salary_slips');
        Schema::dropIfExists('cmp_outwork_ctgs');
        Schema::dropIfExists('cmp_leave_ctgs');
        Schema::dropIfExists('cmp_vacation_ctgs');
        Schema::dropIfExists('cmp_moments');
        Schema::dropIfExists('cmp_contract_meta');
        Schema::dropIfExists('cmp_contracts');
        Schema::dropIfExists('cmp_position_meta');
        Schema::dropIfExists('cmp_position_trees');
        Schema::dropIfExists('cmp_positions');
        Schema::dropIfExists('cmp_depts');
        Schema::dropIfExists('cmp_role_users');
        Schema::dropIfExists('cmp_role_permissions');
        Schema::dropIfExists('cmp_roles');
        Schema::dropIfExists('cmp_ptkps');
        Schema::dropIfExists('cmp_payroll_settings');
        Schema::dropIfExists('cmp_announcements');
        Schema::dropIfExists('cmp_leave_stdnts_ctgs');
    }
};
