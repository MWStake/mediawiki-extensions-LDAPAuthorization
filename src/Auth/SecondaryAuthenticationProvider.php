<?php

namespace MediaWiki\Extension\LDAPAuthorization\Auth;

use MediaWiki\Auth\AuthenticationRequest;
use MediaWiki\Auth\AuthenticationResponse;

class SecondaryAuthenticationProvider extends \MediaWiki\Auth\AbstractSecondaryAuthenticationProvider {

	/**
	 *
	 * @param \User $user
	 * @param \User $creator
	 * @param AuthenticationRequest[] $reqs
	 * @retrun AuthenticationResponse
	 */
	public function beginSecondaryAccountCreation( $user, $creator, array $reqs ) {

		return AuthenticationResponse::newPass();
	}

	/**
	 *
	 * @param \User $user
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function beginSecondaryAuthentication( $user, array $reqs ) {

		return AuthenticationResponse::newPass();
	}

	/**
	 *
	 * @param \User $action
	 * @param array $options
	 * @return AuthenticationRequest[]
	 */
	public function getAuthenticationRequests($action, array $options) {
		return [];
	}
}