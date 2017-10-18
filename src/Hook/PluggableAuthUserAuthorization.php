<?php

namespace MediaWiki\Extension\LDAPAuthorization\Hook;

class PluggableAuthUserAuthorization {

	/**
	 *
	 * @var \User
	 */
	protected $user = null;

	/**
	 *
	 * @var boolean
	 */
	protected $authorized = false;

	/**
	 *
	 * @var \MediaWiki\Extension\LDAPProvider\Client
	 */
	protected $ldapClient = null;

	/**
	 *
	 * @param \MediaWiki\Extension\LDAPProvider\Client $ldapClient
	 * @param \User $user
	 * @param boolean $authorized
	 */
	public function __construct( $ldapClient, $user, &$authorized ) {
		$this->ldapClient = $ldapClient;
		$this->user = $user;
		$this->authorized =& $authorized;
	}

	/**
	 *
	 * @param \User $user
	 * @param boolean $authorized
	 */
	public static function callback( $user, &$authorized ) {
		$handler = new static( $username );
		return $handler->process();
	}

	public function process() {
		//TODO: Check if user fullfills "required groups" and "exclude groups"
		//contraint and return false if not
		return $this->authorized;
	}
}