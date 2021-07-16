<?php
//$pt->config->user_registration = 'on';
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
if($pt->config->registerStep == 1){
    header("Location: " . PT_Link("verify"));
    exit();
}
elseif($pt->config->registerStep == 0){
    header("Location: " . PT_Link("register"));
    exit();
}
$color1      = '000';
$color2      = 'ffad17';
$errors      = array();
$erros_final = '';
$username    = '';
$email       = '';
$success     = '';
$recaptcha   = '<div class="g-recaptcha" data-sitekey="' . $pt->config->recaptcha_key . '"></div>';
$pt->custom_fields = $db->where('registration_page','1')->get(T_FIELDS);
$field_data        = array();
if ($pt->config->recaptcha != 'on') {
    $recaptcha = '';
}
if (!empty($_POST)) {
    if (empty($_POST['username']) || empty($_POST['password'])) {
        $errors[] = $lang->invalid_username_or_password;
    } else {
        $username        = PT_Secure($_POST['username']);
        if(PT_UsernameExists($username)){
            $errors[] = $lang->username_is_taken;
        }
        $password        = PT_Secure($_POST['password']);
        $password_hashed = sha1($password);

        $active = ($pt->config->validation == 'on') ? 0 : 1;
        if (empty($errors)) {
            if (!PT_MobileExists($_SESSION['mobile'])) {
                $errors[] = $lang->session_not_found;
//                print_r($_SESSION);
                unset($_SESSION['temp_registerStep']);
                header("Location: " . $site_url);
                exit();
            }else{
                $update_data = array(
                    'username' => $_POST['username'],
                    'mobile' => $_SESSION['mobile'],
                    'password' => $password_hashed,
                    'email' => PT_Secure($_POST['email']),
                    'ip_address' => get_ip_address(),
                    'gender' => 'male',
                    'active' => $active,
                    'email_code' => $email_code,
                    'last_active' => time(),
                    'registered' => date('Y') . '/' . intval(date('m')) . '/' . intval(date('d'))
                );
                $update_data['language'] = /*$pt->config->language*/'farsi';
                if (!empty($_SESSION['lang'])) {
                    if (in_array($_SESSION['lang'], $langs)) {
                        $update_data['language'] = $_SESSION['lang'];
                    }
                }
                $user = PT_MobileDetail($_SESSION['mobile']);
                $user_id             = $db->where('id',$user->id)->update(T_USERS, $update_data);
                if (!empty($user_id)) {
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
                    header("Location: ". $site_url);
                    exit();
                }
            }
        }
    }
}
$pt->page          = 'login';
$pt->title         = $lang->register . ' | ' . $pt->config->title;
$pt->description   = $pt->config->description;
$pt->keyword       = $pt->config->keyword;
$custom_fields     = "";
if (!empty($errors)) {
    foreach ($errors as $key => $error) {
        $erros_final .= $error_icon . $error . "<br>";
    }
}

$pt->content     = PT_LoadPage('auth/register2/content', array(
    'COLOR1' => $color1,
    'COLOR2' => $color2,
    'ERRORS' => $erros_final,
    'USERNAME' => $username,
    'MOBILE' => $mobile,
    'EMAIL' => $email,
    'SUCCESS' => $success,
));