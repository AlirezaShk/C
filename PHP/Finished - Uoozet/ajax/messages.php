<?php
if (IS_LOGGED == false) {
    $data = array(
        'status' => 400,
        'error' => 'Not logged in'
    );
    echo json_encode($data);
    exit();
}

if ($first == 'new') {
	if (!empty($_POST['id']) && !empty($_POST['new-message'])) {
		$link_regex = '/(http\:\/\/|https\:\/\/|www\.)([^\ ]+)/i';
	    $i          = 0;
	    preg_match_all($link_regex, PT_Secure($_POST['new-message']), $matches);
	    foreach ($matches[0] as $match) {
	        $match_url           = strip_tags($match);
	        $syntax              = '[a]' . urlencode($match_url) . '[/a]';
	        $_POST['new-message'] = str_replace($match, $syntax, $_POST['new-message']);
	    }
		$new_message = PT_Secure($_POST['new-message']);
		$id = PT_Secure($_POST['id']);
		if ($id != $pt->user->id) {
			$chat_exits = $db->where("user_one", $pt->user->id)->where("user_two", $id)->getValue(T_CHATS, 'count(*)');
			if (!empty($chat_exits)) {
				$db->where("user_two", $pt->user->id)->where("user_one", $id)->update(T_CHATS, array('time' => time()));
				$db->where("user_one", $pt->user->id)->where("user_two", $id)->update(T_CHATS, array('time' => time()));
				if ($db->where("user_two", $pt->user->id)->where("user_one", $id)->getValue(T_CHATS, 'count(*)') == 0) {
					$db->insert(T_CHATS, array('user_two' => $pt->user->id, 'user_one' => $id,'time' => time()));
				}
			} else {
				$db->insert(T_CHATS, array('user_one' => $pt->user->id, 'user_two' => $id,'time' => time()));
				if (empty($db->where("user_two", $pt->user->id)->where("user_one", $id)->getValue(T_CHATS, 'count(*)'))) {
					$db->insert(T_CHATS, array('user_two' => $pt->user->id, 'user_one' => $id,'time' => time()));
				}
			}
			$insert_message = array(
				'from_id' => $pt->user->id,
				'to_id' => $id,
				'text' => $new_message,
				'time' => time()
			);
            $unReadSvg = '<svg version="1.1" id="Capa_1"  width="16" height="15" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	  viewBox="0 0 448.8 448.8" style="enable-background:new 0 0 448.8 448.8;" xml:space="preserve">
<g>
	<g id="check">
		<polygon points="142.8,323.85 35.7,216.75 0,252.45 142.8,395.25 448.8,89.25 413.1,53.55 		"/>
	</g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
</svg>';
            $readSvg = '<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 width="16" height="15" viewBox="0 0 594.149 594.149" style="enable-background:new 0 0 594.149 594.149;"
	 xml:space="preserve">
<g>
	<g id="done-all">
		<path d="M448.8,161.925l-35.7-35.7l-160.65,160.65l35.7,35.7L448.8,161.925z M555.899,126.225l-267.75,270.3l-107.1-107.1
			l-35.7,35.7l142.8,142.8l306-306L555.899,126.225z M0,325.125l142.8,142.8l35.7-35.7l-142.8-142.8L0,325.125z"/>
	</g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
</svg>';
			$insert = $db->insert(T_MESSAGES, $insert_message);
			if ($insert) {
				$pt->message = PT_GetMessageData($insert);
				$data = array(
					'status' => 200,
					'message_id' => $_POST['message_id'],
					'message' => PT_LoadPage('messages/ajax/outgoing', array(
						'ID' => $pt->message->id,
						'TEXT' => $pt->message->text,
                        'SVG' =>$pt->message->seen == 0 ? $unReadSvg : $readSvg

                    ))
				);
			}
		}
	}
}

if ($first == 'fetch') {
    if (empty($_POST['last_id'])) {
		$_POST['last_id'] = 0;
	}
	if (empty($_POST['id'])) {
		$_POST['id'] = 0;
	}
	if (empty($_POST['first_id'])) {
		$_POST['first_id'] = 0;
	}
	$messages_html = PT_GetMessages($_POST['id'], array('last_id' => $_POST['last_id'], 'first_id' => $_POST['first_id'], 'return_method' => 'html'));
	if (!empty($messages_html)) {
		$html = PT_LoadPage("messages/{$pt->config->server}/messages", array('MESSAGES' => $messages_html));
	} else {
		$html = PT_LoadPage("messages/ajax/no-messages");
	}
//
    $lastSeen = PT_GetLastSeen($_POST['id'], array('last_id' => $_POST['last_id'], 'first_id' => $_POST['first_id'], 'return_method' => 'html'));

	$users_html = PT_GetMessagesUserList(array('return_method' => 'html'));

	if (!empty($messages_html) || !empty($users_html)) {
		$data = array('status' => 200, 'message' => $messages_html, 'users' => $users_html, 'lastSeen'=>$lastSeen[0]->seen);
	}
}

if ($first == 'search') {
	$keyword = '';
	$users_html = '<p class="text-center">' . $lang->no_match_found . '</p>';
	if (isset($_POST['keyword'])) {
		$users_html = PT_GetMessagesUserList(array('return_method' => 'html', 'keyword' => $_POST['keyword']));
	}
	$data = array('status' => 200, 'users' => $users_html);
}

if ($first == 'delete_chat') {
	if (!empty($_POST['id'])) {
		$id = PT_Secure($_POST['id']);
		$messages = $db->where("(from_id = {$pt->user->id} AND to_id = {$id}) OR (from_id = {$id} AND to_id = {$pt->user->id})")->get(T_MESSAGES);
		$update1 = array();
		$update2 = array();
		$erase = array();
		foreach ($messages as $key => $message) {
			if ($message->from_deleted == 1 || $message->to_deleted == 1) {
				$erase[] = $message->id;
			} else {
				if ($message->to_id == $pt->user->id) {
					$update2[] = $message->id;
				} else {
					$update1[] = $message->id;
				}
			}
		}
		if (!empty($erase)) {
			$erase = implode(',', $erase);
			$final_query = "DELETE FROM " . T_MESSAGES . " WHERE id IN ($erase)";
			$db->rawQuery($final_query);
		}
		if (!empty($update1)) {
			$update1 = implode(',', $update1);
			$final_query = "UPDATE " . T_MESSAGES . " set `from_deleted` = '1' WHERE `id` IN({$update1}) ";
			$db->rawQuery($final_query);
		}
		if (!empty($update2)) {
			$update2 = implode(',', $update2);
			$final_query = "UPDATE " . T_MESSAGES . " set `to_deleted` = '1' WHERE `id` IN({$update2}) ";
			$db->rawQuery($final_query);
		}
		$delete_chats = $db->rawQuery("DELETE FROM " . T_CHATS . " WHERE user_one = {$pt->user->id} AND user_two = $id");
	}
}
?>
