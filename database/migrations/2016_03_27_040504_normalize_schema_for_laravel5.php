<?php

use Illuminate\Database\Migrations\Migration;

class NormalizeSchemaForLaravel5 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('builds', function ($table) {
            $table->renameColumn('is_published', 'published');
        });
        Schema::table('builds', function ($table) {
            $table->dropColumn('forge');
        });

        Schema::table('mods', function ($table) {
            $table->renameColumn('name', 'slug');
        });

        Schema::table('mods', function ($table) {
            $table->renameColumn('pretty_name', 'name');
        });

        Schema::table('users', function ($table) {
            $table->renameColumn('username', 'name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('builds', function ($table) {
            $table->renameColumn('published', 'is_published');
        });

        Schema::table('builds', function ($table) {
            $table->string('forge')->nullable();
        });

        Schema::table('mods', function ($table) {
            $table->renameColumn('name', 'pretty_name');
        });

        Schema::table('mods', function ($table) {
            $table->renameColumn('slug', 'name');
        });

        Schema::table('users', function ($table) {
            $table->renameColumn('name', 'username');
        });
    }
}
