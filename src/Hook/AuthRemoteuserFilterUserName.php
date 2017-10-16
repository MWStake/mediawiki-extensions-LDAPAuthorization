<?php

namespace MediaWiki\Extension\LDAPAuthorization\Hook;

/**
 * In conjunction with "Extension:Auth_remoteuser" we need to make sure that
 * only authorized users are being logged on automatically
 */
class AuthRemoteuserFilterUserName {

	protected $username = '';

	public function __construct( &$username ) {
		$this->username &= $username;
	}

	public static function callback( &$username ) {
		$handler = new static( $username );
		return $handler->process();
	}

	public function process() {
		//TODO: Check if user fullfills "required groups" and "exclude groups"
		//contraint and return false if not
		return true;
	}
}