<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::unprepared(file_get_contents( "database/migrations/references.sql" ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ref_transportations');
        Schema::dropIfExists('ref_territories');
        Schema::dropIfExists('ref_salaries');
        Schema::dropIfExists('ref_religions');
        Schema::dropIfExists('ref_province_regency_districts');
        Schema::dropIfExists('ref_province_regencies');
        Schema::dropIfExists('ref_provinces');
        Schema::dropIfExists('ref_periods');
        Schema::dropIfExists('ref_organization_types');
        Schema::dropIfExists('ref_organization_positions');
        Schema::dropIfExists('ref_hobbies');
        Schema::dropIfExists('ref_habitations');
        Schema::dropIfExists('ref_grade_levels');
        Schema::dropIfExists('ref_grades');
        Schema::dropIfExists('ref_employments');
        Schema::dropIfExists('ref_disabilities');
        Schema::dropIfExists('ref_desires');
        Schema::dropIfExists('ref_countries');
        Schema::dropIfExists('ref_achievement_types');
        Schema::dropIfExists('ref_achievement_nums');
    }
}
