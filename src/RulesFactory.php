<?php

namespace MediaWiki\Extension\LDAPAuthorization;

class RulesFactory {

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
	 * @param \MediaWiki\Extension\LDAPProvider\Client $ldapClient
	 * @param \Config $config
	 */
	public function __construct( $ldapClient, $config ) {
		$this->ldapClient = $ldapClient;
		$this->config = $config;
	}

	/**
	 * @return IRule[]
	 */
	public function makeRules() {
		
	}

}
