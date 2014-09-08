<?php
//slack methods
require_once('log.php');

/*
Slash Command Incoming Variables

token=aliyefnrcliauwyfercuyen
team_id=T0001
channel_id=C2147483705
channel_name=test
user_id=U2147483697
user_name=Steve
command=/weather
text=94070
*/
function BuildSlashCommand($request)
{
	$cmd = new stdClass();
	$cmd->Token = $request['token'];
	$cmd->TeamId = $request['team_id'];
	$cmd->ChannelId = $request['channel_id'];
	$cmd->ChannelName = $request['channel_name'];
	$cmd->UserId = $request['user_id'];
	$cmd->UserName = $request['user_name'];
	$cmd->Command = $request['command'];
	$cmd->Text = $request['text'];

	return $cmd;
}

//text-formatting functions

function BuildUserLink($username)
{
    $userlink = "<https://cim.slack.com/team/{$username}|@{$username}>";
    return $userlink;
}

function SanitizeText($text)
{
	$text = strtr($text, array('<br />' => '\n', '<div>' => '\n', '<p>' => '\n'));
	return html_entity_decode(strip_tags($text), ENT_HTML401 | ENT_COMPAT, 'UTF-8');
}

function TruncateText($text, $len)
{
	if(strlen($text) <= $len)
		return $text;

	return substr($text,0,$len)."...[MORE]";
}

function l($text, $url)
{
	return '<' . $url . '|' . $text . '>';
}

function em($text)
{
	return '_' . $text . '_';
}

function b($text)
{
	return '*' . $text . '*';
}

//posting functions

function slack_incoming_hook_post($uri, $user, $channel, $icon, $emoji, $payload)
{

	$data = array(
		"text" => $payload,
		"channel" => "#" . $channel,
		"username" => $user
	);

	if ($icon != null) {
		$data['icon_url'] = $icon;
	} elseif ($emoji != null) {
		$data['icon_emoji'] = $emoji;
	}

	$data_string = "payload=" . json_encode($data, JSON_HEX_AMP | JSON_HEX_APOS | JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);

	mylog('sent.txt', $data_string);
	return curl_post($uri, $data_string);
}

function SendIncomingWebHookMessage($channel, $payload, $attachments)
{
	global $SLACK_INCOMING_HOOK_URL, $RALLYBOT_NAME, $RALLYBOT_ICON;

	//allow bot to display formatted attachment text
	$attachments->mrkdwn_in = ['pretext', 'text', 'title', 'fields'];

	$reply = slack_incoming_hook_post_with_attachments($SLACK_INCOMING_HOOK_URL, $RALLYBOT_NAME, $channel, $RALLYBOT_ICON, $payload, $attachments);

	$success = ($reply == 'ok');
	if (!$success) {
		trigger_error('Unable to send Incoming WebHook message: ' . $reply);
	}
	return $success;
}

function slack_incoming_hook_post_with_attachments($uri, $user, $channel, $icon, $payload, $attachments)
{

	$data = array(
		"text" => $payload,
		"channel" => "#" . $channel,
		"username" => $user,
		"icon_url" => $icon,
		"attachments" => array(
			$attachments
		)
	);

	$data_string = "payload=" . json_encode($data, JSON_HEX_AMP | JSON_HEX_APOS | JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);
	$data_string = strtr($data_string, array('\\\\n' => '\n')); //unescape slashes in newline characters
	mylog('sent.txt',$data_string);
	return curl_post($uri, $data_string);
}

/*
slack attachment format

{
	"fallback": "Required text summary of the attachment that is shown by clients that understand attachments but choose not to show them.",

	"text": "Optional text that should appear within the attachment",
	"pretext": "Optional text that should appear above the formatted data",

	"color": "#36a64f", // Can either be one of 'good', 'warning', 'danger', or any hex color code

	// Fields are displayed in a table on the message
	"fields": [
		{
			"title": "Required Field Title", // The title may not contain markup and will be escaped for you

			"value": "Text value of the field. May contain standard message markup and must be escaped as normal. May be multi-line.",

			"short": false // Optional flag indicating whether the `value` is short enough to be displayed side-by-side with other values
		}
	]
}
*/
function MakeAttachment($pretext, $text, $color, $fields, $fallback)
{

	$obj = new stdClass;
	$obj->fallback = $fallback;
	$obj->text = $text;
	$obj->pretext = $pretext;
	$obj->color = $color;

	if (sizeof($fields) > 0)
		$obj->fields = $fields;

	return $obj;
}

function MakeField($title, $value, $short = false)
{
	$attachmentfield = array(
		"title" => $title,
		"value" => $value,
		"short" => $short
	);

	return $attachmentfield;
}
