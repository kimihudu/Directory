<?php
/**
 * MailChimp Process ajax.
 *
 * @package WordPress
 * @subpackage Directory
 */

$file = dirname( dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) );
require( $file . '/wp-load.php' );
global $wpdb;

$apikey = $_REQUEST['api_key'];
// $listId = 'YOUR MAILCHIMP LIST ID - see lists() method';
$listId = $_REQUEST['list_id'];

$name = $_REQUEST['name'];
$email = $_REQUEST['email'];

$data = [
	'email'     => $email,
	'status'    => 'subscribed',
	'firstname' => $name,
];

$result = syncMailchimp( $data );

if ( $result != 200 ) {
	esc_html_e( 'Error while Subscription.', 'templatic' );
} else {
	esc_html_e( 'Successfully Subscribed. Please check confirmation email.', 'templatic' );
}

function syncMailchimp( $data ) {
	$apiKey = $_REQUEST['api_key'];
	$listId = $_REQUEST['list_id'];

	$memberId = md5( strtolower( $data['email'] ) );
	$dataCenter = substr( $apiKey,strpos( $apiKey,'-' ) + 1 );
	$url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;

	$json = json_encode([
		'email_address' => $data['email'],
		'status'        => $data['status'], // "subscribed","unsubscribed","cleaned","pending"
		'merge_fields'  => [
			'FNAME'     => $data['firstname'],
		],
	]);

	$ch = curl_init( $url );

	curl_setopt( $ch, CURLOPT_USERPWD, 'user:' . $apiKey );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, [ 'Content-Type: application/json' ] );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
	curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'PUT' );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $json );

	$result = curl_exec( $ch );
	$httpCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
	curl_close( $ch );

	return $httpCode;
}
// CONFIGURE ERROR OR SUCCESS PROCESS FINISH.

