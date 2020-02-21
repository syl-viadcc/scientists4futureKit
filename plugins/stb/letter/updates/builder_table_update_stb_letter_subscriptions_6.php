<?php namespace Stb\Letter\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateStbLetterSubscriptions6 extends Migration
{
    public function up()
    {
        Schema::table('stb_letter_subscriptions', function($table)
        {
            $table->dropColumn('institution_code');
        });
    }
    
    public function down()
    {
        Schema::table('stb_letter_subscriptions', function($table)
        {
            $table->string('institution_code', 20);
        });
    }
}
