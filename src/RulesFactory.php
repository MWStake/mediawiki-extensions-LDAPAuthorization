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
	 * @var IRule[]
	 */
	protected $rules = [];

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
		$this->makeGroupRules();
		$this->makeAttributeMatchRules();

		return $this->rules;
	}

	protected function makeGroupRules() {
		if( !$this->config->has( Config::RULES_GROUPS ) ) {
			return;
		}

		$groups = $this->config->get( Config::RULES_GROUPS );
		if( isset( $groups[Config::RULES_GROUPS_REQUIRED ] ) ) {
			$this->rules[] = new GroupsRequired(
				$this->ldapClient,
				$groups[Config::RULES_GROUPS_REQUIRED ]
			);
		}
		if( isset( $groups[Config::RULES_GROUPS_EXCLUDED ] ) ) {
			$this->rules[] = new GroupsExcluded(
				$this->ldapClient,
				$groups[Config::RULES_GROUPS_EXCLUDED ]
			);
		}
	}

	protected function makeAttributeMatchRules() {
		if( !$this->config->has( Config::RULES_ATTRIBUTES ) ) {
			return;
		}
		$attributes = $this->config->get( Config::RULES_ATTRIBUTES );

		$this->rules[] = new AttributesMatch( $this->ldapClient, $attributes );
	}

}
