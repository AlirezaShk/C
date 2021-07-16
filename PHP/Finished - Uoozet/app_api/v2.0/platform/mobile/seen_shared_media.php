<?php

/*if (!IS_LOGGED) {

	$response_data    = array(
	    'api_status'  => '400',
	    'api_version' => $api_version,
	    'errors' => array(
            'error_id' => '1',
            'error_text' => 'Not logged in'
        )
	);
}
else*/ if (empty($_POST['shared_id'])) {

	$response_data       = array(
        'api_status'     => '400',
        'api_version'    => $api_version,
        'errors'         => array(
            'error_id'   => '2',
            'error_text' => 'Bad Request, Invalid or missing parameter'
        )
    );
}
else{

	$user_id   = $user->id;
	$shared_id   = PT_Secure($_POST['shared_id']);

	if (!empty($_POST['shared_id']) && is_numeric($_POST['shared_id']) && $_POST['shared_id'] > 0) {
		$shared_data = $db->where('id',$shared_id)->getOne(T_SHAREDMEDIA);
	}

	if (!empty($shared_data)) {

	    $update_data = array();
	    $update_data['seen_status'] = 1;

	    $update           = $db->where('id',$shared_data->id)->update(T_SHAREDMEDIA, $update_data);
	    $response_data     = array(
		    'api_status'   => '200',
		    'api_version'  => $api_version,
		    'success_type' => 'seen_shared',
		    'message'      => 'The shared media seen'
		);
	}
	else{
    	$response_data       = array(
	        'api_status'     => '400',
	        'api_version'    => $api_version,
	        'errors'         => array(
	            'error_id'   => '3',
	            'error_text' => 'SharedMedia not found'
	        )
	    );
    }
}