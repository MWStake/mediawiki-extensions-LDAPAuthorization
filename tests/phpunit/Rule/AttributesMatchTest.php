<?php

namespace MediaWiki\Extension\LDAPAuthorization\Tests;

use MediaWiki\Extension\LDAPAuthorization\AttributesMatch;

class AttributesMatchTest extends \PHPUnit_Framework_TestCase {
	/**
	 *
	 * @param array $ruledefinition
	 * @param array $attribs
	 * @param boolean $expected
	 */
	public function testEvaluate( $ruledefinition, $attribs, $expected ) {
		$attributeRule = new AttributesMatch( $ruledefinition );
		$result = $attributeRule->applies( $attribs );

		$this->assertEquals( $expected, $result );
	}

	protected $rule1 = [
			"&" => [
					"status" => "active",
					"|" => [
							"department" => [
									"100",
									"200"
							],
							"level" => [
									"5",
									"6"
							]
					]
			]
	];

	protected $rule2 = [
			"status" => "active",
			"|" => [
					"department" => [
							"100",
							"200"
					],
					"level" => [
							"5",
							"6"
					]
			]
	];

	public function provideRules() {
		return [
			'example1-from-mworg-positive' => [
				$this->rule1,
				[
					'status' => 'active',
					'department' => 200
				],
				true
			],
			'example2-from-mworg-positive' => [
				$this->rule2,
				[
					'status' => 'active',
					'department' => 500,
					'level' => 5
				],
				true
			],
			'example1-from-mworg-negative' => [
				$this->rule1,
				[
					'status' => 'deactive',
					'department' => 200,
					'level' => 7
				],
				true
			],
			'example2-from-mworg-negative' => [
				$this->rule2,
				[
					'status' => 'active',
					'level' => 7
				],
				true
			]
		];
	}
}