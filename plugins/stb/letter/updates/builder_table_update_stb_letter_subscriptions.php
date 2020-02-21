<?php namespace Stb\Letter\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateStbLetterSubscriptions extends Migration
{
    public function up()
    {
        Schema::table('stb_letter_subscriptions', function($table)
        {
            $table->integer('rank_id');
            $table->dropColumn('rank');
        });
    }
    
    public function down()
    {
        Schema::table('stb_letter_subscriptions', function($table)
        {
            $table->dropColumn('rank_id');
            $table->string('rank', 100);
        });
    }
}
