<?php namespace Stb\Letter\Models;

use Model;

/**
 * Model
 */
class AcademicRank extends Model
{
    use \October\Rain\Database\Traits\Validation;
	use \October\Rain\Database\Traits\Sortable;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'stb_letter_academic_ranks';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

	/**
	 * @return array
	 */
	public function getData(){
		$data = self::orderBy('sort_order', 'asc')->get()->toArray();
		$ranks = [];
		foreach ($data as $rank){
			$ranks[$rank['id']]=$rank['name'];
		}
		return $ranks;
	}
}
