<?php

namespace MediaWiki\Extension\LDAPAuthorization\Requirement;

use MediaWiki\Extension\LDAPAuthorization\IRequirement;

class RequiredGroups implements IRequirement {

	protected $requiredGroups = [];

	protected $groups = [];

	/**
	 *
	 * @param array $requiredGroups
	 * @param array $groups
	 */
	public function __construct( $requiredGroups, $groups ) {
		$this->requiredGroups = $requiredGroups;
		$this->groups = $groups;

	}

	public function isSatisfied() {
		//TODO: Group name normalization
		$intersect = array_intersect(
			$this->requiredGroups,
			$this->groups
		);

		return empty(
			array_intersect(
				$this->requiredGroups,
				$intersect
			)
		);
	}
}