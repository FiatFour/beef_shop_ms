<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id('admin_id');
            $table->string('admin_name',50);
            $table->string('admin_lname',50);
            $table->string('admin_tel',33);
            $table->string('email',100)->unique();
            $table->string('password');
            $table->string('admin_img');
            $table->text('admin_address');
            $table->tinyInteger('admin_gender');
            $table->tinyInteger('admin_department');
            $table->boolean('super_admin')->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
