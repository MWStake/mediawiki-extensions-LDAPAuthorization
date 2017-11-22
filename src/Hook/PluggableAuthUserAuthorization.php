<?php

namespace MediaWiki\Extension\LDAPAuthorization\Hook;

use MediaWiki\Extension\LDAPProvider\UserDomainStore;
use MediaWiki\Extension\LDAPProvider\ClientFactory;
use MediaWiki\Extension\LDAPAuthorization\RulesFactory;

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
		$rulesFactory = new RulesFactory( $this->ldapClient );
		$rules = $rulesFactory->makeRules();
		foreach( $rules as $rule ) {
			if( !$rule->applies( $this->user ) ) {
				$this->authorized = false;
				return false;
			}
		}

		return true;
	}
}