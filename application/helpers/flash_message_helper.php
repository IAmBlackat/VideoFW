<?php

function flash_message() {
	$ci = & get_instance();
	$flashmsg = userdata('message');
	$html = '';
	if (is_array($flashmsg)) {
		foreach ($flashmsg as $value) {
			$html .= '<li>' . $value . '</li>';
		}
	} else
		$html = $flashmsg;
	unset_userdata('message');
	return $html;
}

function set_flash_message($message) {
	$ci = & get_instance();
	set_userdata('message', $message);
}

function set_flash_error($message) {
	$ci = & get_instance();
	set_userdata('error', $message);
}

function set_userdata($key, $value) {
	if (isset($key) && !empty($key)) {
		$ci = & get_instance();
		$ci->session->set_userdata($key, $value);
	}
}

function unset_userdata($key) {
	if (isset($key) && !empty($key)) {
		$ci = & get_instance();
		$ci->session->unset_userdata($key);
	}
}

function userdata($key, $default = '') {
	if (!empty($key)) {
		$ci = & get_instance();
		$value = $ci->session->userdata($key);
		if (!$value)
			return $default;
		return $value;
	}
	return FALSE;
}

function has_message() {
	$ci = & get_instance();
	return userdata('message') != '';
}

function has_error() {
	$ci = & get_instance();
	return userdata('error') != '';
}

function flash_error() {
	$ci = & get_instance();
	$flashmsg = userdata('error');
	$html = '';
	if (is_array($flashmsg)) {
		foreach ($flashmsg as $value) {
			$html .= '<li>' . $value . '</li>';
		}
	} else
		$html = $flashmsg;
	unset_userdata('error');
	return $html;
}