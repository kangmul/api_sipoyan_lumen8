<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


class RoleTableCreate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role', function (Blueprint $table) {
            $table->id();
            $table->string('role');
            $table->string('is_active');
            $table->string('created_by');
            $table->timestamps();
        });

        DB::table('role')->insert(
            [
                [
                    'id' => '100',
                    'role' => 'admin',
                    'is_active' => true,
                    'created_by' => 'admin',
                    'created_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => '1',
                    'role' => 'member',
                    'is_active' => true,
                    'created_by' => 'admin',
                    'created_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => '2',
                    'role' => 'user',
                    'is_active' => true,
                    'created_by' => 'admin',
                    'created_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => '3',
                    'role' => 'operator',
                    'is_active' => true,
                    'created_by' => 'admin',
                    'created_at' => date('Y-m-d H:i:s'),
                ],
            ],
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role');
    }
}
