<?php namespace App\Http\Controllers;

class SolutionController extends Controller {


	/**
	 * Create a new controller instance.
	 * @return void
	 */
	public function __construct()
	{
	}

	/**
	 * Show the application dashboard to the user.
	 * @return Response
	 */

	public function show_user_data_bikebros($user_parse_id)
	{
		$bikebros_cxense_id = "1130531920128972215";
		$user_profile_array = $this->retrieve_user_data($bikebros_cxense_id, $user_parse_id);
		
		return view('userdata_show')
				->with("user_parse_id",$user_parse_id)
				->with("user_profile_array",$user_profile_array)
				->with("cxense_site_id",$bikebros_cxense_id);
	}

	public function show_user_data_dac($user_parse_id)
	{
		$dac_cxense_id = "1128275557251903601";
		
		$user_profile_array = $this->retrieve_user_data($dac_cxense_id, $user_parse_id);

		return view('userdata_show')
				->with("user_parse_id",$user_parse_id)
				->with("user_profile_array",$user_profile_array)
				->with("cxense_site_id",$dac_cxense_id);
	}

	public function bikebros_redirect($user_parse_id)
	{

		$bikebros_cxense_id = "1130531920128972215";
		$redirect_url = "/solution_dac_redirect/" . $user_parse_id;

		return view('userdata_redirect')
				->with("user_parse_id",$user_parse_id)
				->with("redirect_url",$redirect_url)
				->with("cxense_site_id",$bikebros_cxense_id);
	}

	public function dac_redirect($user_parse_id)
	{
		$dac_cxense_id = "1128275557251903601";
		$redirect_url = "/show_user_data_dac/" . $user_parse_id;

		return view('userdata_redirect')
				->with("user_parse_id",$user_parse_id)
				->with("redirect_url",$redirect_url)
				->with("cxense_site_id",$dac_cxense_id);
	}

	public function people_list()
	{
		return view('solution_home');
	}


	public function retrieve_user_data($cxense_id, $user_parse_id){

		$username="cxense-team@dac.co.jp";
		$apikey="api&user&Qkc0a6QqYvTPjOsYbhR7Sg==";
		$date = date("Y-m-d\TH:i:s.000O");
		$signature=hash_hmac("sha256", $date, $apikey);
		$url = 'https://api.cxense.com/profile/user';

		$plainjson_payload = "{\"id\":\"$user_parse_id\", \"type\":\"dac\" }";
		$options = array(
		    'http' => array(
		        'header'  => "Content-Type: application/json; charset=UTF-8\r\n".
		                     "X-cXense-Authentication: username=$username date=$date hmac-sha256-hex=$signature\r\n",
		        'method'  => 'POST',
		        'content' => $plainjson_payload,
		    ),
		);
		$context  = stream_context_create($options);
		$user_profile   = file_get_contents($url, false, $context);
		$obj = json_decode($user_profile);
		$user_profile_array = $obj->{'profile'};
/*
		foreach ($user_profile_array as $user_profile){

			$user_item = $user_profile->{'item'};
			$user_group_array = $user_profile->{'groups'};
			echo "user item is <strong>" . $user_item . "</strong><br>";
			foreach ($user_group_array as $user_group){
			//	$count =  $user_group->{'count'};
			//	echo "count is " . $count . "<br>";
				$group =  $user_group->{'group'};
				echo "group is " . $group . "<br>";
				$weight =  $user_group->{'weight'};
				echo "weight is " . $weight . "<br>";
			}
			echo "<br>";
			echo "<br>";
			echo "<br>";
		}
*/
		echo "object retrieved by profile/user/";
		echo "<br>";
		echo "parse id used is " . $user_parse_id . "<br>";
		echo "cxense id used is " . $cxense_id;

		return $user_profile_array;
	}
}
