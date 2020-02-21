<?php namespace Stb\Letter\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateStbLetterSubscriptions5 extends Migration
{
    public function up()
    {
        Schema::table('stb_letter_subscriptions', function($table)
        {
            $table->string('bi_number', 50)->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('stb_letter_subscriptions', function($table)
        {
            $table->dropColumn('bi_number');
        });
    }
}
