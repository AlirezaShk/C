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
if($pt->config->registerStep == 1){
    header("Location: " . PT_Link("verify"));
    exit();
}
elseif($pt->config->registerStep == 2){
    header("Location: " . PT_Link("register2"));
    exit();
}
$color1      = '000';
$color2      = 'ffad17';
$errors      = array();
$erros_final = '';
$username    = '';
$email       = '';
$success     = '';
$recaptcha   = '<div class="g-recaptcha" data-callback="correctCaptcha" data-sitekey="' . $pt->config->recaptcha_key . '"></div>';
$pt->custom_fields = $db->where('registration_page','1')->get(T_FIELDS);
$field_data        = array();
if ($pt->config->recaptcha != 'on') {
    $recaptcha = '';
}
if (!empty($_POST)) {
    if (empty($_POST['mobile'])) {
        $errors[] = $lang->please_check_details;
    } else {
        $mobile        = PT_Secure($_POST['mobile']);
        define(HasSignedUp,PT_HasUserSignedup($mobile));
        if(PT_IsMobileActive($mobile)){
            $pt->config->registerStep = 2;
            $_SESSION['temp_registerStep'] = $pt->config->registerStep;
            $_SESSION['temp_httpRef'] = PT_Link("register");
            header("Location: ". PT_Link('register2'));
            exit();
        }
        $password_hashed = sha1($_POST['mobile']);
        if (strlen($_POST['mobile']) != 11) {
            $errors[] = $lang->mobiel_characters_length;
        }
        if (!preg_match('/09(0[1-2]|1[0-9]|3[0-9]|2[0-1]|9[0])-?[0-9]{3}-?[0-9]{4}/', $_POST['mobile'])) {
            $errors[] = $lang->mobile_invalid_characters;
        }
        if ($pt->config->recaptcha == 'on') {
            if (!isset($_POST['g-recaptcha-response']) || empty($_POST['g-recaptcha-response'])) {
                $errors[] = $lang->reCaptcha_error;
            }
        }

        if (empty($_POST['accept_terms'])) {
            $errors[] = $lang->terms_accept;
        } elseif ($_POST['accept_terms'] != 'on') {
            $errors[] = $lang->terms_accept;
        }
        if (empty($errors)) {
//            $active = ($pt->config->validation == 'on') ? 0 : 1;
            $email_code = randomString();
            $_SESSION['mobile'] = $_POST['mobile'];
            if (PT_MobileExists($_POST['mobile'])) {
                $user = PT_MobileDetail($_POST['mobile']);
                $db->where('id', $user->id);
                $email_code = randomString();
                echo $email_code.'wwww<br>';
                $update_data = array('email_code' => $email_code);
                $update = $db->update(T_USERS, $update_data);
                echo $email_code.'cccc<br>';
                sendSMS($_POST['mobile'], $email_code);
                $success = $success_icon . $lang->successfully_joined_desc;
                $pt->config->registerStep = 1;
                $_SESSION['temp_registerStep'] = $pt->config->registerStep;
                $_SESSION['temp_httpRef'] = PT_Link("register");
                header("Location: ". PT_Link('verify'));
                exit();
            }else{
                $insert_data = array(
                    'username' => $_POST['mobile'],
                    'mobile' => $_POST['mobile'],
                    'password' => $password_hashed,
                    'email' => PT_Secure($_POST['mobile'].'@uoozet.com', 0),
                    'ip_address' => get_ip_address(),
                    'gender' => 'male',
                    'active' => 0,
                    'email_code' => $email_code,
                    'last_active' => time(),
                    'registered' => date('Y') . '/' . intval(date('m'))
                );
                $insert_data['language'] = /*$pt->config->language*/'farsi';
//                if (!empty($_SESSION['lang'])) {
//                    if (in_array($_SESSION['lang'], $langs)) {
//                        $insert_data['language'] = $_SESSION['lang'];
//                    }
//                }

                $user_id             = $db->insert(T_USERS, $insert_data);
                if (!empty($user_id)) {
                    if (!empty($field_data)) {
                        PT_UpdateUserCustomData($user_id,$field_data,false);
                    }

                    sendSMS($_POST['mobile'], $email_code);
                    $success = $success_icon . $lang->successfully_joined_desc;
                    $pt->config->registerStep = 1;
                    $_SESSION['temp_registerStep'] = $pt->config->registerStep;
                    $_SESSION['temp_httpRef'] = PT_Link("register");
                    header("Location: ".PT_Link('verify'));
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
if (!empty($pt->custom_fields)) {
    foreach ($pt->custom_fields as $field) {
        $field_id       = $field->id;
        $fid            = "fid_$field_id";
        $pt->filed      = $field;
        $custom_fields .= PT_LoadPage('auth/register/custom-fields',array(
            'NAME'      => $field->name,
            'FID'       => $fid
        ));
    }
}

$pt->content     = PT_LoadPage('auth/register/content', array(
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