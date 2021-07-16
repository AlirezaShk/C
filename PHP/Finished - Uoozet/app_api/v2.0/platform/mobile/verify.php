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

else if (!PT_MobileExists($_POST['mobile'])) {
    $response_data       = array(
        'api_status'     => '400',
        'api_version'    => $api_version,
        'errors'         => array(
            'error_id'   => '5',
            'error_text' => 'mobile is not exists'
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

else if (empty($_POST['code'])){
    $response_data       = array(
        'api_status'     => '400',
        'api_version'    => $api_version,
        'errors'         => array(
            'error_id'   => '10',
            'error_text' => 'Please write your code'
        )
    );
}

else{
    $user = PT_IsCodeValid($_POST['mobile'], $_POST['code']);
    if(!$user){
        $response_data       = array(
            'api_status'     => '400',
            'api_version'    => $api_version,
            'errors'         => array(
                'error_id'   => '11',
                'error_text' => 'code is ivalid'
            )
        );
    }elseif (empty($response_data['errors'])){

        $db->where('id', $user->id);
        $email_code = randomString();
        $update_data = array('active' => 1, 'email_code' => $email_code);
        $update = $db->update(T_USERS, $update_data);
        if($update){
            $session_id      = sha1(rand(11111, 99999)) . time() . md5(microtime());
            $platforms       = array('phone','web');

            foreach ($platforms as $platform_name) {
                $insert_data     = array(
                    'user_id'    => $user->id,
                    'session_id' => $session_id,
                    'time'       => time(),
                    'platform'   => $platform_name
                );

                $insert = $db->insert(T_SESSIONS, $insert_data);
            }

            if (!empty($insert)) {
                $response_data     = array(
                    'api_status'   => '200',
                    'api_version'  => $api_version,
                    'success_type' => 'registered',
                    'message'      => 'Successfully joined, Please wait..',
                    'data'         => array(
                        'user_id'  => $user->id,
                        'name'     => $user->first_name,
                        'last_name'=> $user->last_name,
                        'username' => $user->username,
                        'email'    => $user->email,
                        's'        => $session_id,
                        'cookie'   => $session_id
                    )
                );
                if($user->mobile == $user->username)
                    $response_data['data']['username'] = '';
                if($user->mobile == explode('@', $user->email)[0])
                    $response_data['data']['email'] = '';
            }

            else{
                $response_data       = array(
                    'api_status'     => '500',
                    'api_version'    => $api_version,
                    'errors'         => array(
                        'error_id'   => '14',
                        'error_text' => 'Error: an unknown error occurred. Please try again later'
                    )
                );
            }
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
}