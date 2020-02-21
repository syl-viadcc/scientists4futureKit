<?php namespace Stb\Letter\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Stb\Letter\Models\Subscription;

class Subsriptions extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController', 'Backend\Behaviors\FormController'    ];
    
    public $listConfig = 'config_list.yaml';
	public $formConfig = 'config_form.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Stb.Letter', 'main-menu-letter');
    }

    public function onVipClick(){
        $value = input('isVIP');
        if(Subscription::toggleVip($value)){
            return ['#layout-flash-messages' => '<p data-control="flash-message" class="flash-message success" data-interval="5">"OK! VIP toggled."</p>'];
        }
        return ['#layout-flash-messages' => '<p data-control="flash-message" class="flash-message error" data-interval="5">"Ups! Something went wrong! The VIP toggle did not applied. Please contact admin."</p>'];
    }
}
