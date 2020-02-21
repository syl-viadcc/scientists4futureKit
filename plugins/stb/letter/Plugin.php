<?php namespace Stb\Letter;

use Illuminate\Support\Facades\Mail;
use Stb\Letter\Classes\StbCrypto;
use Stb\Letter\Models\Subscription;
use System\Classes\PluginBase;
use Validator;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
	    return [
		    'name'        => 'stb.letter::lang.plugin.name',
		    'description' => 'stb.letter::lang.plugin.description',
		    'author'      => 'STB',
		    'icon'        => 'oc-icon-file-text',
		    'homepage'    => ''
	    ];
    }

	public function registerSettings()
	{
		return [
			'settings' => [
				'label'       => 'stb.letter::lang.plugin.name',
				'description' => 'stb.letter::lang.plugin.description',
				'icon'        => 'oc-icon-edit',
				'class'       => 'Stb\Letter\Models\Settings',
				'order'       => 100
			]
		];
	}

	public function registerComponents()
	{
		return [
			'Stb\Letter\Components\SubscriptionForm'      => 'subscriptionForm',
			'Stb\Letter\Components\VerifyEmail'      => 'verifyemail',
			'Stb\Letter\Components\Assinantes'      => 'assinantes',
		];
	}

	public function registerSchedule($schedule)
	{
		$schedule->call(function () {
            $vars = ['date'=>date("Y-m-d_H-i-s" )];
            $data = json_encode(Subscription::all()->toArray());
            $iv = StbCrypto::getIV();
            $vars['iv']=$iv;
            $vars['cipher']=StbCrypto::crypt($data, 'e', $iv);
            Mail::send('stb.letter::mail.backup-notification', $vars, function($message) use ($vars){
                $message->to('scientists4future@protonmail.ch', 'Backup S4F');
                $message->subject('Backup S4F::IV::'.$vars['iv']);
                $message->attachData($vars['cipher'], 'assinantes-backup-'.date("Y-m-d_H-i-s" ).'.json');
            });
		})->twiceDaily(13, 23);

		$schedule->command('cache:clear')->daily();
	}

    public function boot()
    {
        Validator::extend('orcid_ping', function($attribute, $value, $parameters) {
            $re = '/^\d{4}-\d{4}-\d{4}-(\d{3}X|\d{4})$/m';
            preg_match_all($re, trim($value), $matches, PREG_SET_ORDER, 0);
            if(empty($matches)) {
                return false;
            }
            $contents = file_get_contents("https://pub.orcid.org/v2.1/search/?q=orcid:".trim($value));
            if($contents !== false){
                if(strstr($contents, 'failed to open stream')){
                    return false;
                }
                $xml = simplexml_load_string($contents);
                $json = json_encode($xml);
                $array = json_decode($json,TRUE);
                if($array["@attributes"]["num-found"]==1){
                    return true;
                }
            }
            return false;
        });

        Validator::extend('unique_verified', function($attribute, $value, $parameters) {
            $exists = Subscription::where('email', $value)->where('is_verified', 1)->whereNull('token')->count();
            if($exists){
                return false;
            };
            return true;
        });
        Validator::extend('unique_not_verified', function($attribute, $value, $parameters) {
            $exists = Subscription::where('email', $value)->where('is_verified', 0)->whereNotNull('token')->count();
            if($exists){
                return false;
            };
            return true;
        });
    }

    public function registerMailTemplates()
    {
        return [
            'stb.letter::mail.email-verification' => 'Email with Hash to verify email address.'
        ];
    }



    public function registerMailLayouts()
    {
        return [
            'stb.letter::mail.layouts-notification'
        ];
    }
}
