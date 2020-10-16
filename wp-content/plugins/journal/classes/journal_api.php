<?php 
class JournalApiClass{
    function __construct()
    {
        $this->get_clj_settings = get_option("clj_settings");        
    }
    public function make_api_request($url)
    {
        $get_url = "https://restapi.e-conomic.com/".$url;
        $args = array(
        'timeout'     => 15,
        'redirection' => 10,
        'httpversion' => '1.0',
        'headers'     => array(
            'X-AppSecretToken'=> isset($this->get_clj_settings['secret_token'])?$this->get_clj_settings['secret_token']:"",
            'X-AgreementGrantToken'=> isset($this->get_clj_settings['grant_token'])?$this->get_clj_settings['grant_token']:"",
            'Content-Type' => "application/json"
            ),
        'sslverify'   => false,
        );
	
        $next = false;
        $initial_array = array();

        do{		
			$response = wp_remote_get($get_url,$args);
			$response_code = wp_remote_retrieve_response_code($response);
			$response_body = wp_remote_retrieve_body($response);	
			$response_array = json_decode($response_body,true);
			$collection_response_array = $response_array['collection'];			
			$initial_array = array_merge($initial_array,$collection_response_array);			
			if(isset($response_array['pagination']['nextPage']))
			{
				$get_url = $response_array['pagination']['nextPage'];
				$next = true;
			}
			else
			{
				$next = false;
			}
	
	    }
        while($next === true);
        return $initial_array;
    }
}
?>