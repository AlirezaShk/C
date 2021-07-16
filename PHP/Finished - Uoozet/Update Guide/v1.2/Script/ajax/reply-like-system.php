<?php
if (IS_LOGGED == false) {
    $data = array('status' => 400, 'error' => 'Not logged in');
    echo json_encode($data);
    exit();
}

if (!empty($_GET['first']) && !empty($_POST['id'])) {
    $id                    = PT_Secure($_POST['id']);
    $is_this_valid_reply = $db->where('id', $id)->getValue(T_COMM_REPLIES, 'count(*)');
    if ($is_this_valid_reply > 0) {
        if ($_GET['first'] == 'like' || $_GET['first'] == 'up') {
            $db->where('user_id', $user->id);
            $db->where('reply_id', $id);
            $db->where('type', 1);
            $check_for_like = $db->getValue(T_COMMENTS_LIKES, 'count(*)');
            if ($check_for_like > 0) {
                $db->where('user_id', $user->id);
                $db->where('reply_id', $id);
                $db->where('type', 1);
                $delete = $db->delete(T_COMMENTS_LIKES);
                $data   = array(
                    'status' => 200,
                    'type' => 'deleted_like'
                );
            }

            else {

                $db->where('user_id', $user->id);
                $db->where('reply_id', $id);
                $db->where('type', 2);
                $delete = $db->delete(T_COMMENTS_LIKES);

                $insert_data = array(
                    'user_id' => $user->id,
                    'reply_id' => $id,
                    'time' => time(),
                    'type' => 1
                );

                $insert      = $db->insert(T_COMMENTS_LIKES, $insert_data);
                if ($insert) {
                    $data = array(
                        'status' => 200,
                        'type' => 'added_like'
                    );
                }
            }
        }

        elseif ($_GET['first'] == 'dislike' || $_GET['first'] == 'down') {
            $db->where('user_id', $user->id);
            $db->where('reply_id', $id);
            $db->where('type', 2);
            $check_for_like = $db->getValue(T_COMMENTS_LIKES, 'count(*)');

            if ($check_for_like > 0) {
                $db->where('user_id', $user->id);
                $db->where('reply_id', $id);
                $db->where('type', 2);
                $delete = $db->delete(T_COMMENTS_LIKES);
                $data   = array(
                    'status' => 200,
                    'type' => 'deleted_dislike',
                    'code' => 0,
                );
            }

            else {
                
                $db->where('user_id', $user->id);
                $db->where('reply_id', $id);
                $db->where('type', 1);
                $delete = $db->delete(T_COMMENTS_LIKES);

                $insert_data = array(
                    'user_id' => $user->id,
                    'reply_id' => $id,
                    'time' => time(),
                    'type' => 2
                );

                $insert      = $db->insert(T_COMMENTS_LIKES, $insert_data);
                if ($insert) {
                    $data = array(
                        'status' => 200,
                        'type' => 'added_dislike',
                        'code' => 1
                    );
                }
            }
        }

        $data['up']    = $db->where('reply_id', $id)->where('type', 1)->getValue(T_COMMENTS_LIKES, "count(*)");
        $data ['down'] = $db->where('reply_id', $id)->where('type', 2)->getValue(T_COMMENTS_LIKES, "count(*)");
    }
}
?>