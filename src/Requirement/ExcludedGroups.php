<?php

namespace MediaWiki\Extension\LDAPAuthorization\Requirement;

use MediaWiki\Extension\LDAPAuthorization\IRequirement;

class ExcludedGroups implements IRequirement {

	protected $excludedGroups = [];

	protected $groups = [];

	public function __construct( $excludedGroups, $groups ) {
		$this->excludedGroups = $excludedGroups;
		$this->groups = $groups;

	}

	public function isSatisfied() {
		//TODO: Group name normalization
		return empty(
			array_intersect(
				$this->excludedGroups,
				$this->groups
			)
		);
	}
}