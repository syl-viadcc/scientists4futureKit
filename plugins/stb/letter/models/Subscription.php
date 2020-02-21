<?php namespace Stb\Letter\Models;

use Illuminate\Support\Carbon;
use Model;

/**
 * Model
 */
class Subscription extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'stb_letter_subscriptions';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    private $expire_time=1440;

	/**
	 * @return int
	 */
	public function getExpireTime($date){
	    return Carbon::parse($date)->addSeconds($this->expire_time*60)->getTimestamp();
	}
	/**
	 * @return int
	 */
	public function getToken(){
	    return md5(uniqid($this->email, true));
	}

	public $hasOne = [
		'rank' => 'Stb\Letter\Models\AcademicRank'
	];


	public static function toggleVip($modelID){
        $subscription = Subscription::find($modelID);
        if(isset($subscription)){
            if($subscription->radio_vip == 0){
                $subscription->radio_vip=1;
            } else{
                $subscription->radio_vip=0;
            }
            $subscription->save();
            return true;
        }
        return false;
    }
}
