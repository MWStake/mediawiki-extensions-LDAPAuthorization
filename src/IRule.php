<?php

namespace MediaWiki\Extension\LDAPAuthorization;

interface IRule {

	/**
	 *
	 * @param \User $user
	 * @return boolean
	 */
	public function applies( $user );
}