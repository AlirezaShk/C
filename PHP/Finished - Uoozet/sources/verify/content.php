<?php
if (IS_LOGGED == true || $pt->config->user_registration != 'on' || HasSignedUp) {
    header("Location: " . PT_Link(''));
    exit();
}
if(session_status() == PHP_SESSION_NONE){
    ob_start();
    session_start();
}
if(!isset($_SESSION['temp_registerStep']))
    $_SESSION['temp_registerStep'] = 0;
$pt->config->registerStep = $_SESSION['temp_registerStep'];
$httpReferer = "";
if(isset($_SESSION['temp_httpRef'])){
    $httpReferer = $_SESSION['temp_httpRef'];
}
if($httpReferer != PT_Link('loginWithPhone')){
    if($pt->config->registerStep == 0){
        header("Location: " . PT_Link("loginWithPhone"));
        exit();
    }
//    elseif($pt->config->registerStep == 2){
//        header("Location: " . PT_Link("register2"));
//        exit();
//    }
}
$color1 = 'fff';
$color2 = '000';
$errors = array();
$erros_final = '';
$username = '';
$email = '';
$success = '';
$recaptcha = '<div class="g-recaptcha" data-sitekey="' . $pt->config->recaptcha_key . '"></div>';
$pt->custom_fields = $db->where('registration_page', '1')->get(T_FIELDS);
$field_data = array();
if ($pt->config->recaptcha != 'on') {
    $recaptcha = '';
}
if (!empty($_POST)) {
    if (empty($_POST['code'])) {
        $errors[] = $lang->please_check_details;
    } else {
        $code = PT_Secure($_POST['code']);
        $mobile = $_SESSION['mobile'];
        $user = PT_IsCodeValid($mobile, $code);
        if (!$user) {
            $errors[] = 'user not found';
        } elseif (empty($errors)) {
            if($httpReferer == PT_Link('loginWithPhone')){
                $session_id          = sha1(rand(11111, 99999)) . time() . md5(microtime());
                $insert_data         = array(
                    'user_id' => $user->id,
                    'session_id' => $session_id,
                    'time' => time()
                );
                $insert              = $db->insert(T_SESSIONS, $insert_data);
                $_SESSION['user_id'] = $session_id;
                setcookie("user_id", $session_id, time() + (10 * 365 * 24 * 60 * 60), "/");
                $pt->loggedin = true;
                if (!empty($_GET['to'])) {
                    $site_url = $_GET['to'];
                }

                $db->where('id',$user->id)->update(T_USERS,array(
                    'ip_address' => get_ip_address()
                ));

                unset($_SESSION['temp_httpRef']);
                header("Location: " . PT_Link(''));
                exit();
            }
            $email_code = randomString();
            $update_data = array('active' => 1 /*, 'email_code' => $email_code */);
            $update = $db->where('id', $user->id)->update(T_USERS, $update_data);
            if ($update) {

                $db->where('id',$user->id)->update(T_USERS,array(
                    'ip_address' => get_ip_address()
                ));
//                $pt->config->registerStep = 2;
//                $_SESSION['temp_registerStep'] = $pt->config->registerStep;
//                header("Location: " . $site_url . "/register2");
//                exit();
                if (!empty($field_data)) {
                    PT_UpdateUserCustomData($user_id,$field_data,false);
                }
                $session_id          = sha1(rand(11111, 99999)) . time() . md5(microtime());
                $insert_data         = array(
                    'user_id' => $user->id,
                    'session_id' => $session_id,
                    'time' => time()
                );
                $insert              = $db->insert(T_SESSIONS, $insert_data);
                $_SESSION['user_id'] = $session_id;
                setcookie("user_id", $session_id, time() + (10 * 365 * 24 * 60 * 60), "/");
                $pt->loggedin = true;
                $success = $success_icon . $lang->successfully_joined_desc;
                $update_data = array('completeSignup' => 1);
                $db->where('id',$user->id)->update(T_USERS, $update_data);
                unset($_SESSION['temp_registerStep']);
                if (isset($_SESSION['inviteCode'])) {
                    $registerCode = new RegisterCode();
                    $user = new User();
                    $nextUser = $registerCode->getOne($_SESSION['inviteCode'], 1);
                    $user->modifyPoints($nextUser['user_id'], $nextUser['points']);
                }
                unset($_SESSION['inviteCode']);
                header("Location: ". $site_url);
                exit();
            }else{
                $errors[] = $lang->code_is_wrong;
            }
        }
    }
}
$pt->page = 'login';
$pt->title = $lang->register . ' | ' . $pt->config->title;
$pt->description = $pt->config->description;
$pt->keyword = $pt->config->keyword;
$custom_fields = "";
if (!empty($errors)) {
    foreach ($errors as $key => $error) {
        $erros_final .= $error_icon . $error . "<br>";
    }
}
if (!empty($pt->custom_fields)) {
    foreach ($pt->custom_fields as $field) {
        $field_id = $field->id;
        $fid = "fid_$field_id";
        $pt->filed = $field;
        $custom_fields .= PT_LoadPage('auth/verify/custom-fields', array(
            'NAME' => $field->name,
            'FID' => $fid
        ));
    }
}
$pt->content = PT_LoadPage('auth/verify/content', array(
    'HEADER' =>  ($httpReferer == PT_Link('loginWithPhone'))?("none"):("blocck"),
    'COLOR1' => $color1,
    'COLOR2' => $color2,
    'ERRORS' => $erros_final,
    'USERNAME' => $username,
    'MOBILE' => $mobile,
    'EMAIL' => $email,
    'SUCCESS' => $success,
    'RECAPTCHA' => $recaptcha,
    'CUSTOM_FIELDS' => $custom_fields
));