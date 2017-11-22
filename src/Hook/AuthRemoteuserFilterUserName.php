<?php
namespace MediaWiki\Extension\LDAPAuthorization\Hook;

use MediaWiki\Extension\LDAPProvider\UserDomainStore;
use MediaWiki\Extension\LDAPProvider\ClientFactory;
use MediaWiki\Extension\LDAPProvider\DomainConfigFactory;
use MediaWiki\Extension\LDAPAuthorization\RequirementsChecker;
use MediaWiki\Extension\LDAPAuthorization\Config;

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
	 * @var \User
	 */
	protected $user = null;

	/**
	 *
	 * @var \Config
	 */
	protected $config = null;

	/**
	 *
	 * @param string $username
	 */
	public function __construct( &$username ) {
		$this->username &= $username;

		$this->user = \User::newFromName( $username );
		$userDomainStore = new UserDomainStore(
			\MediaWiki\MediaWikiServices::getInstance()->getDBLoadBalancer()
		);
		$domain = $userDomainStore->getDomainForUser( $this->user );
		$this->ldapClient = ClientFactory::getInstance()->getForDomain( $domain );

		$this->config = DomainConfigFactory::getInstance()->factory(
			$domain, Config::DOMAINCONFIG_SECTION
		);
	}

	/**
	 *
	 * @param string $username
	 * @return boolean
	 */
	public static function callback( &$username ) {
		$handler = new static( $username );
		return $handler->process();
	}

	public function process() {
		$requirementsChecker = new RequirementsChecker( $this->ldapClient, $this->config );
		if( !$requirementsChecker->allSatisfiedBy( $user ) ) {
			return false;
		}

		return true;
	}
}