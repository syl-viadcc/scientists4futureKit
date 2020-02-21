<?php
namespace Stb\Letter\Components;

use Carbon\Carbon;
use Cms\Classes\ComponentBase;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Indikator\News\Models\Subscribers;
use Stb\Letter\Models\Subscription;
use Flash;

class VerifyEmail extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'stb.letter::lang.plugin.verifyemail_name',
            'description' => 'stb.letter::lang.plugin.verifyemail_name'
        ];
    }

    public function onRun()
    {
        $this->page['name'] = Session::get('verifyEmailName');
        $this->page['email'] = Session::get('verifyEmailEmail');
        $this->page['view'] = Session::get('verifyEmailView');
        $this->page['message'] = Session::get('verifyEmailMessage');
    }

    public function onResendVerifyEmail(){
        if(post('email')){
            $subscription = Subscription::where('email', post('email'))->where('is_verified', 0)->first();
            if(isset($subscription)) {
                $subscription->token = $subscription->getToken();
                $subscription->save();
                try{
                    SubscriptionForm::sendVerificationMail($subscription, $subscription->getExpireTime(now()));
                }catch(\Exception $e){
                    $this->page['result'] = 'danger';
                    $this->page['message'] = trans('stb.letter::lang.plugin.verifyemail.fail_sent').'<br>'. $e->getMessage();
                }
                $this->page['message'] = str_replace_first(':email', post('email'), trans('stb.letter::lang.plugin.verifyemail.new_link_sent'));
                $this->page['result'] = 'success';
            }else{
                $this->page['result'] = 'danger';
                $this->page['message'] = trans('stb.letter::lang.plugin.verifyemail.no_subscription');
            }
        }else{
            $this->page['result'] = 'danger';
            $this->page['message'] = trans('stb.letter::lang.plugin.verifyemail.fail');
        }
    }


    public function verifyEmail($token, $expire)
    {
        Session::flush();
        $subscription = Subscription::where('token', $token)->where('is_verified', 0)->first();
        if(isset($subscription)) {
            Session::put('verifyEmailName', $subscription->name);
            Session::put('verifyEmailEmail', $subscription->email);

            if (Carbon::createFromTimestamp($expire)->isPast()) {
                Session::put('verifyEmailView', 'expired');
                return Redirect::to('post-verfiy');
            }
            if($subscription->is_verified) {
                Session::put('verifyEmailView', 'success');
                Session::put('verifyEmailMessage', trans('stb.letter::lang.plugin.verifyemail.already_verified'));
            }else{
                $subscription->is_verified = 1;
                $subscription->token = null;
                $subscription->save();
                if($subscription->radio_divulgacao == 1){
                	##change status in mailing list subscribed.
	                $mailingsubscriber = Subscribers::where('email', $subscription->email)->where('status', 3)->first();
	                if(isset($mailingsubscriber)){
		                $mailingsubscriber->status = 1;
		                $mailingsubscriber->save();
	                }
                }
                Session::put('verifyEmailView', 'success');
            }
        }else{
            Session::put('verifyEmailView', 'fail');
            Session::put('verifyEmailMessage', trans('stb.letter::lang.plugin.verifyemail.no_subscription'));
        }
        return Redirect::to('post-verfiy');
    }
}
