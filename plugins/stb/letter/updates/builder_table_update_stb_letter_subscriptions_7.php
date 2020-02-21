<?php namespace Stb\Letter\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateStbLetterSubscriptions7 extends Migration
{
    public function up()
    {
        Schema::table('stb_letter_subscriptions', function($table)
        {
            $table->smallInteger('radio_vip')->default(0);
            $table->dropColumn('radio_already_signed');
            $table->dropColumn('radio_mailing');
        });
    }
    
    public function down()
    {
        Schema::table('stb_letter_subscriptions', function($table)
        {
            $table->dropColumn('radio_vip');
            $table->smallInteger('radio_already_signed')->default(0);
            $table->smallInteger('radio_mailing')->default(0);
        });
    }
}
