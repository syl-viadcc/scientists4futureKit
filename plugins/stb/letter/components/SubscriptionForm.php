<?php
/**
 * Created by PhpStorm.
 * User: sylwia
 * Date: 23/10/2018
 * Time: 22:01
 */
namespace Stb\Letter\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Indikator\News\Models\Subscribers;
use October\Rain\Support\Facades\Url;
use Stb\Letter\Classes\StbCrypto;
use Stb\Letter\Models\AcademicRank;
use Stb\Letter\Models\Institution;
use Stb\Letter\Models\Subscription;
use Stb\Letter\Models\Settings;

use October\Rain\Support\Facades\Flash;
use AjaxException;
use Mail;
use Validator;
use ValidationException;
use Request;
use Response;

class SubscriptionForm extends ComponentBase{

	/**
	 * Contact form validation rules.
	 * @var array
	 */
	public $formValidationRules = [
		'name' => 'required',
		'bi_number' => 'required',
		'email' => [
		    'required','email', 'unique_verified', 'unique_not_verified'],
        'orcid_id' => [
            'required',
            'unique:stb_letter_subscriptions,orcid_id',
            'regex:/^\d{4}-\d{4}-\d{4}-(\d{3}X|\d{4})$/i',
            'not_in:0000-0001-2345-6789',
            'orcid_ping'
        ],
		'institution' => 'required'
	];

    public $formValidationMessages = [];


	/**
	 * Returns information about this component, including name and description.
	 */
	public function componentDetails()
	{
		return [
			'name' => 'stb.letter::lang.plugin.name',
			'description' => 'stb.letter::lang.plugin.description'
		];
	}
	public function settings(){
	    return [
			'form_enabled' => Settings::get('form_enabled', false),
			'recaptcha_enabled' => Settings::get('recaptcha_enabled', false),
			'site_key' => Settings::get('site_key', ''),
			'secret_key' => Settings::get('secret_key', ''),
			'ranks' => (new AcademicRank())->getData(),
			'institutions' => (new Institution())->getData()
		];
	}


	public function onCaptchaValidate(){
        /**
         * Validating reCaptcha
         */
        if (Settings::get('recaptcha_enabled', false)) {
            $response = json_decode( file_get_contents( "https://www.google.com/recaptcha/api/siteverify?secret=" . Settings::get( 'secret_key' ) . "&response=" . post( 'token' ) . "&remoteip=" . $_SERVER['REMOTE_ADDR'] ), true );
            if(!$response['success']){
                $reCapchaErrorCodes= [
                    'missing-input-secret'=>trans('stb.letter::validation.recapcha.missing-input-secret'),
                    'invalid-input-secret'=>trans('stb.letter::validation.recapcha.invalid-input-secret'),
                    'missing-input-response'=>trans('stb.letter::validation.recapcha.missing-input-response'),
                    'invalid-input-response'=>trans('stb.letter::validation.recapcha.invalid-input-response'),
                    'bad-request'=>trans('stb.letter::validation.recapcha.bad-request'),
                    'timeout-or-duplicate'=>trans('stb.letter::validation.recapcha.timeout-or-duplicate'),
                    'invalid-keys'=>trans('stb.letter::validation.recapcha.invalid-keys'),
                    'incorrect-captcha-sol'=>trans('stb.letter::validation.recapcha.invalid-sol'),
                ];
                $flashMessage=trans('stb.letter::validation.recapcha.flash-message').': ';
                foreach ($response['error-codes'] as $msg){
                    $flashMessage .= $reCapchaErrorCodes[$msg];
                }
                return Response::make(array_merge($response, ['error-messages'=>$flashMessage]), 500);
            }else{
                return $response;
            }
        }
    }

    public function onEmailValidate(){

		$emailValidationRules = [
			'email' => 'required|email|unique_verified|unique_not_verified'
		];

		$emailValidationMessages = $this->getEmailValidationMessages();

		$validator = Validator::make(post(), $emailValidationRules,$emailValidationMessages);

		if ($validator->fails()) {
			$messages = $validator->messages();
			throw new AjaxException(['email'=>post('email'), 'message'=>$messages->first()]);
		}
		return ['status'=>'success'];
	}

	private function getEmailValidationMessages(){
        return [
            'email.required' => trans('stb.letter::validation.custom.email.required'),
            'email.email' => trans('stb.letter::validation.custom.email.email'),
            'email.unique_verified' => trans('stb.letter::validation.custom.email.unique_verified'),
            'email.unique_not_verified' => trans('stb.letter::validation.custom.email.unique_not_verified'),
        ];
    }

	/**
     * ^\d{4}-\d{4}-\d{4}-(\d{3}X|\d{4})$
	*/
    public function onOrcidValidate(){

        //like placeholder

        $orcidValidationRules = [
            'orcid_id' => [
                'required',
                'unique:stb_letter_subscriptions,orcid_id',
                'regex:/^\d{4}-\d{4}-\d{4}-(\d{3}X|\d{4})$/i',
                'not_in:0000-0001-2345-6789',
                'orcid_ping'
                ]
        ];

        $orcidValidationMessages = $this->getOrcidValidationMessages();

        $validator = Validator::make(post(), $orcidValidationRules,$orcidValidationMessages);

        if ($validator->fails()) {
            $messages = $validator->messages();
            throw new AjaxException(['orcid_id'=>post('orcid_id'), 'message'=>$messages->first()]);
        }

        //make ping to
        return ['status'=>'success'];
    }

    private function getOrcidValidationMessages(){
        return [
            'orcid_id.required' => trans('stb.letter::validation.custom.orcid_id.required'),
            'orcid_id.unique' => trans('stb.letter::validation.custom.orcid_id.unique'),
            'orcid_id.regex' => trans('stb.letter::validation.custom.orcid_id.regex'),
            'orcid_id.not_in' => trans('stb.letter::validation.custom.orcid_id.not_in'),
            'orcid_id.orcid_ping' => trans('stb.letter::validation.custom.orcid_id.orcid_ping'),
        ];
    }

	/**
	 *  form submit handler
	 */
	public function onFormSubmit(){

        /**
		 * Form validation
		 */
		$this->formValidationMessages = array_merge($this->getEmailValidationMessages(), $this->getOrcidValidationMessages(),
            [
                'name.required' => trans('stb.letter::validation.custom.name.required'),
                'bi_number.required' => trans('stb.letter::validation.custom.bi_number.required')
            ]);

		$validator = Validator::make(post(), $this->formValidationRules,$this->formValidationMessages);

        if ($validator->fails()) {
			$messages = $validator->messages();
			Flash::error($messages->first());
			return Redirect::back()->withInput()->withErrors($validator);
		}

		/**
		 * At this point all validations succeded
		 * further processing form
		 */
		$this->submitForm();
		return Redirect::to('post-assinatura');

	}

	protected function submitForm(){

		$model = new Subscription();
		$model->name = post('name');
		$model->bi_number = post('bi_number');
		$model->email = post('email');
		$model->rank_id = post('rank_id');
		$model->institution = post('institution');
		$model->orcid_id = post('orcid_id');
		#$model->radio_already_signed = (post('jaAssinouOptions') == 'sim')? 1 : 0; ##hide
        $model->radio_divulgacao = (post('participarOptions') == 'sim')? 1 : 0;# ok mailing list
        $model->radio_apoio = (post('apoioOptions') == 'sim')? 1 : 0;# ok
        #$model->radio_mailing = (post('mailingOptions') == 'sim')? 1 : 0; #remove
        $model->highest_degree = post('highest_degree');
        $model->is_verified = 0;
        $model->token = $model->getToken();

        $model->save();

		if($model->radio_divulgacao == 1){
			$subscirber = new Subscribers();
			$subscirber->email = $model->email;
			$subscirber->name = $model->name;
			$subscirber->status = 3;
			$subscirber->save();
		}

		if(Settings::get('email_verification_enabled',false) && !empty(Settings::get('email_verification_from',''))){
            self::sendVerificationMail($model, $model->getExpireTime($model->created_at));
        }
	}

//	public function onRun() {
//        $vars = ['date'=>date("Y-m-d_H-i-s" )];
//        $data = json_encode(Subscription::all()->toArray());
//        $iv = StbCrypto::getIV();
//        $vars['iv']=$iv;
//        $vars['cipher']=StbCrypto::crypt($data, 'e', $iv);
//        Mail::send('stb.letter::mail.backup-notification', $vars, function($message) use ($vars){
//            $message->to('sylwia.bugla@gmail.com', 'Backup '.date("Y-m-d H:i:s" ));
//            $message->subject('Backup S4F::IV::'.$vars['iv'].'::');
//            $message->attachData($vars['cipher'], 'assinantes-backup-'.date("Y-m-d_H-i-s" ).'.json');
//        });
//        /**To decript use like this:
//         * $iv - Random Initialization Vector provided in email
//         * $cipher - content of the file.
//         * $decrypted = StbCrypto::crypt($cipher, 'd', $iv);
//         * dd($decrypted);
//         */
//	}


	/**
	 * Send notification email
     * @param Subscription $model
     * @param timestamp $expires
	 */
	public static function sendVerificationMail($model, $expires){
	    $vars = [
			'name' => $model->name,
			'email' => $model->email,
            'confirmation_link' => Url::to('verifyEmail', [
                'token'   => $model->token,
                'expire' => $expires
            ])
		];

		Mail::send('stb.letter::mail.email-verification', $vars, function($message) use ($vars) {
			$message->to($vars['email']);
			$message->from(Settings::get('email_verification_from', 'S4F Portugal'));
			$message->replyTo(Settings::get('email_verification_from'), 'S4F Portugal');
		});
	}
}