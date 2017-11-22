<?php

namespace MediaWiki\Extension\LDAPAuthorization\Hook;

use MediaWiki\Extension\LDAPProvider\UserDomainStore;
use MediaWiki\Extension\LDAPProvider\ClientFactory;
use MediaWiki\Extension\LDAPAuthorization\RequirementsChecker;
use MediaWiki\Extension\LDAPAuthorization\Config;

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
	 * @var \Config
	 */
	protected $config = null;

	/**
	 *
	 * @param \User $user
	 * @param boolean $authorized
	 */
	public function __construct( $user, &$authorized ) {
		$this->user = $user;
		$this->authorized =& $authorized;

		$userDomainStore = new UserDomainStore(
			\MediaWiki\MediaWikiServices::getInstance()->getDBLoadBalancer()
		);
		$domain = $userDomainStore->getDomainForUser( $user );
		$this->ldapClient = ClientFactory::getInstance()->getForDomain( $domain );

		$this->config = DomainConfigFactory::getInstance()->factory(
			$domain, Config::DOMAINCONFIG_SECTION
		);
	}

	/**
	 *
	 * @param \User $user
	 * @param boolean $authorized
	 */
	public static function callback( $user, &$authorized ) {
		$handler = new static( $user, &$authorized );
		return $handler->process();
	}

	/**
	 *
	 * @return boolean
	 */
	public function process() {
		$requirementsChecker = new RequirementsChecker( $this->ldapClient, $this->config );
		if( !$requirementsChecker->allSatisfiedBy( $user ) ) {
			$this->authorized = false;
			return false;
		}

		return true;
	}
}