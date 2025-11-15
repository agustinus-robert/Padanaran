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
        Schema::create('post', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menu')->onUpdate('cascade')->onDelete('cascade');
            $table->longText('content');
            $table->jsonb('tags')->nullable();
            $table->string('location', 255);
            $table->jsonb('image')->nullable();
            $table->integer('status');
            $table->jsonb('alt_image')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletesTz();
            $table->timestampsTz();
        });

        Schema::create('post_meta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('post')->onUpdate('cascade')->onDelete('cascade');
            $table->string('type', 50);
            $table->string('key', 255);
            $table->jsonb('value');
        });

        Schema::create('schedule_post', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('post')->onUpdate('cascade')->onDelete('cascade');
            $table->date('schedule_on');
            $table->timeTz('timepicker');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletesTz();
            $table->timestampsTz();
        });

        Schema::create('post_image', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menu')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('post_id')->constrained('post')->onUpdate('cascade')->onDelete('cascade');
            $table->jsonb('title');
            $table->jsonb('slug');
            $table->string('location', 255);
            $table->jsonb('image');
            $table->jsonb('content');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletesTz();
            $table->timestampsTz();
        });

        Schema::create('post_video', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menu')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('post_id')->constrained('post')->onUpdate('cascade')->onDelete('cascade');
            $table->jsonb('title');
            $table->jsonb('slug');
            $table->jsonb('deskripsi');
            $table->jsonb('link_embed');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletesTz();
            $table->timestampsTz();
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('post')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('comment_id')->nullable();
            $table->string('username', 255);
            $table->string('email', 255);
            $table->string('title', 255);
            $table->jsonb('description');
            $table->jsonb('notes');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletesTz();
            $table->timestampsTz();
        });

        Schema::create('post_site_configuration', function (Blueprint $table) {
            $table->id();
            $table->string('site_name');
            $table->jsonb('location');
            $table->string('email', 255);
            $table->string('call', 20);
            $table->jsonb('coordinate');
            $table->string('twitter')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('skype')->nullable();
            $table->string('linkedin')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletesTz();
            $table->timestampsTz();
        });

        Schema::create('user_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->nullable()->constrained('menu')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('post_id')->constrained('post')->onUpdate('cascade')->onDelete('cascade');
            $table->string('action', 255);
            $table->string('status', 255);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletesTz();
            $table->timestampsTz();
        });
    }

    public function down()
    {
        Schema::dropIfExists('post');
        Schema::dropIfExists('post_meta');
        Schema::dropIfExists('schedule_post');
        Schema::dropIfExists('post_image');
        Schema::dropIfExists('post_has_category');
        Schema::dropIfExists('post_image_has_category');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('post_site_configuration');
        Schema::dropIfExists('post_video');
        Schema::dropIfExists('user_log');
    }
};
