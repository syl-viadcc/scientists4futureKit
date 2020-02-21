<?php
namespace Stb\Letter\Components;

use Carbon\Carbon;
use Cms\Classes\ComponentBase;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Stb\Letter\Models\AcademicRank;
use Stb\Letter\Models\Subscription;
use Flash;

class Assinantes extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'stb.letter::lang.plugin.assinantes_name',
            'description' => 'stb.letter::lang.plugin.assinantes_name'
        ];
    }

	public function settings(){
		return [
			'ranks' => (new AcademicRank())->getData()
		];
	}


	public function assinantesList()
    {
        return Subscription::select('id', 'name', 'highest_degree', 'institution', 'rank_id')->where('is_verified', 1)
	        ->whereNotIn('id', [1,2,3])
            ->orderBy('radio_vip', 'desc')
            ->orderBy('created_at', 'asc')->paginate(50);
    }

	public function assinantes123()
	{
		return Subscription::select('id', 'name', 'highest_degree', 'institution', 'rank_id')->where('is_verified', 1)
			->whereIn('id', [1,2,3])->orderBy('created_at', 'asc')->get();

	}
}
