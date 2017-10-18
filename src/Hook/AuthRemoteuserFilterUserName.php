<?php
namespace MediaWiki\Extension\LDAPAuthorization\Hook;

use MediaWiki\Extension\LDAPProvider\UserDomainStore;
use MediaWiki\Extension\LDAPProvider\ClientFactory;

/**
 * In conjunction with "Extension:Auth_remoteuser" we need to make sure that
 * only authorized users are being logged on automatically
 */
class AuthRemoteuserFilterUserName {
	/**
	 *
	 * @var string
	 */
	protected $username = '';

	/**
	 *
	 * @var \MediaWiki\Extension\LDAPProvider\Client
	 */
	protected $ldapClient = null;

	/**
	 *
	 * @param \MediaWiki\Extension\LDAPProvider\Client $ldapClient
	 * @param string $username
	 */
	public function __construct( $ldapClient, &$username ) {
		$this->ldapClient = $ldapClient;
		$this->username &= $username;
	}

	/**
	 *
	 * @param string $username
	 * @return boolean
	 */
	public static function callback( &$username ) {
		$user = \User::newFromName( $username );
		$userDomainStore = new UserDomainStore(
			\MediaWiki\MediaWikiServices::getInstance()->getDBLoadBalancer()
		);
		$domain = $userDomainStore->getDomainForUser( $user );
		$ldapClient = ClientFactory::getInstance()->getForDomain( $domain );

		$handler = new static( $ldapClient, $username );
		return $handler->process();
	}

	public function process() {
		//TODO: Check if user fullfills "required groups" and "exclude groups"
		//contraint and return false if not
		return true;
	}
}