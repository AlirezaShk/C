<?php
// +------------------------------------------------------------------------+
// | @author Deen Doughouz (DoughouzForest)
// | @author_url 1: http://www.playtubescript.com
// | @author_url 2: http://codecanyon.net/user/doughouzforest
// | @author_email: wowondersocial@gmail.com   
// +------------------------------------------------------------------------+
// | PlayTube - The Ultimate Video Sharing Platform
// | Copyright (c) 2017 PlayTube. All rights reserved.
// +------------------------------------------------------------------------+
if (IS_LOGGED === true) {
    $response_data       = array(
        'api_status'     => '304',
        'api_version'    => $api_version,
        'errors'         => array(
            'error_id'   => '1',
            'error_text' => 'You are already logged in'
        )
    );
}
else if ($pt->config->user_registration != 'on') {
	$response_data       = array(
        'api_status'     => '400',
        'api_version'    => $api_version,
        'errors'         => array(
            'error_id'   => '2',
            'error_text' => 'Sorry, User registration is currently disabled'
        )
    );
}
else{
	if (empty($_POST['mobile'])) {
	    $response_data       = array(
	        'api_status'     => '400',
	        'api_version'    => $api_version,
	        'errors'         => array(
	            'error_id'   => '3',
	            'error_text' => 'Please write your mobile'
	        )
	    );
	}

	else if (strlen($_POST['mobile']) != 11) {
	    $response_data       = array(
	        'api_status'     => '400',
	        'api_version'    => $api_version,
	        'errors'         => array(
	            'error_id'   => '4',
	            'error_text' => 'mobile must be 11 character (with zero)'
	        )
	    );
	}

    else if (!preg_match('/09(0[1-2]|1[0-9]|3[0-9]|2[0-1]|9[0])-?[0-9]{3}-?[0-9]{4}/', number2farsi($_POST['mobile']))) {
        $response_data       = array(
            'api_status'     => '400',
            'api_version'    => $api_version,
            'errors'         => array(
                'error_id'   => '6',
                'error_text' => 'Invalid mobile characters'
            )
        );
    }

	else if (PT_MobileExists($_POST['mobile'])) {
	    $user = PT_UsernameDetail($_POST['mobile']);
        $email_code = randomString();
        $update_data = array('email_code' => $email_code);
        $update = $db->where('id', $user->id)->update(T_USERS, $update_data);
        if($update){
            sendSMS($_POST['mobile'], $email_code);
            $response_data     = array(
                'api_status'   => '200',
                'api_version'  => $api_version,
                'success_type' => 'registered',
                'message'      => 'Registration successful! We have sent you an code, Please check your text message to verify your account.',
            );
        }else{
            $response_data       = array(
                'api_status'     => '500',
                'api_version'    => $api_version,
                'errors'         => array(
                    'error_id'   => '14',
                    'error_text' => 'Error: an unknown error occurred. Please try again later'
                )
            );
        }
	}

	else if (empty($response_data['errors'])) {
	    $username                   = PT_Secure($_POST['mobile'], 0);
	    $password                   = PT_Secure($_POST['mobile'], 0);
	    $email                      = PT_Secure($_POST['mobile'].'@uoozet.com', 0);
	    $email_code                 = randomString();
	    $gender                     = 'male';

	    if (!empty($_POST['gender'])) {
	    	if ($_POST['gender'] == 'female') {
	    		$gender             = 'female';
	    	}
	    }

	    $active           = ($pt->config->validation == 'on') ? 0 : 1;
	    $password_hashed  = sha1($password);
	    $email_code       = randomString();
        $insert_data      = array(
            'username'    => $username,
            'mobile'      => $username,
            'password'    => $password_hashed,
            'email'       => $email,
            'gender'      => $gender,
            'active'      => $active,
            'email_code'  => $email_code,
            'last_active' => time(),
            'registered'  => date('Y') . '/' . intval(date('m'))
        );
        if (!empty($_POST['device_id'])) {
        	$insert_data['device_id'] = PT_Secure($_POST['device_id']);
        }

        $user_id          = $db->insert(T_USERS, $insert_data);
	    if (!empty($user_id)) {
            sendSMS($username, $email_code);
            $response_data     = array(
                'api_status'   => '200',
                'api_version'  => $api_version,
                'success_type' => 'registered',
                'message'      => 'Registration successful! We have sent you an code, Please check your text message to verify your account.'
            );
	    }
	}
}