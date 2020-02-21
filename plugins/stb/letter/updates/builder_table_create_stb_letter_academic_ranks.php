<?php namespace Stb\Letter\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateStbLetterAcademicRanks extends Migration
{
    public function up()
    {
        Schema::create('stb_letter_academic_ranks', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->integer('sort_order')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('stb_letter_academic_ranks');
    }
}
