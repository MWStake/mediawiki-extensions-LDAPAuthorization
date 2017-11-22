<?php

namespace MediaWiki\Extension\LDAPAuthorization\Tests;

use MediaWiki\Extension\LDAPAuthorization\Requirement\ExcludedGroups;

class ExcludedGroupsTest extends \PHPUnit_Framework_TestCase {
	/**
	 *
	 * @param array $excludedGroups
	 * @param array $groups
	 * @param boolean $expected
	 * @dataProvider provideData
	 */
	public function testIsSatisfied( $excludedGroups, $groups, $expected ) {
		$requirement = new ExcludedGroups( $excludedGroups, $groups );
		$result = $requirement->isSatisfied();

		$this->assertEquals( $expected, $result );
	}

	public function provideData() {
		return [
			'positive' => [
				[ 'A', 'D' ],
				[ 'A', 'B', 'C' ],
				true
			],
			'negative' => [
				[ 'X', 'Y' ],
				[ 'A', 'C', 'D' ],
				false
			]
		];
	}
}