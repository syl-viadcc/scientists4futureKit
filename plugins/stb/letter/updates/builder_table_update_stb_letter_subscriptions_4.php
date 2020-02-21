<?php namespace Stb\Letter\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateStbLetterSubscriptions4 extends Migration
{
    public function up()
    {
        Schema::table('stb_letter_subscriptions', function($table)
        {
            $table->dropColumn('gdpr');
        });
    }
    
    public function down()
    {
        Schema::table('stb_letter_subscriptions', function($table)
        {
            $table->smallInteger('gdpr');
        });
    }
}
