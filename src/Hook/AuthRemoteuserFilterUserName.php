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
	 * @var \User
	 */
	protected $user = null;

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
		$rulesFactory = new RulesFactory( $this->ldapClient );
		$rules = $rulesFactory->makeRules();
		foreach( $rules as $rule ) {
			if( !$rule->applies( $this->user ) ) {
				return false;
			}
		}

		return true;
	}
}