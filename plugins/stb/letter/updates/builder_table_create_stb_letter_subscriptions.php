<?php namespace Stb\Letter\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateStbLetterSubscriptions extends Migration
{
    public function up()
    {
        Schema::create('stb_letter_subscriptions', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 500);
            $table->string('orcid_id', 20);
            $table->string('email', 255);
            $table->string('institution_code', 20);
            $table->string('institution', 255);
            $table->smallInteger('radio_already_signed')->default(0);
            $table->smallInteger('radio_divulgacao')->default(0);
            $table->smallInteger('radio_apoio')->default(0);
            $table->smallInteger('radio_mailing')->default(0);
            $table->string('rank', 100);
            $table->smallInteger('gdpr');
            $table->string('token', 100);
            $table->smallInteger('is_verified')->default(0);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('stb_letter_subscriptions');
    }
}
