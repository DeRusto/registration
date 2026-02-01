<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2020 Nextcloud GmbH and Nextcloud contributors
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\Registration;

use OCA\Registration\AppInfo\Application;
use OCP\Authentication\IAlternativeLogin;
use OCP\IConfig;
use OCP\IL10N;
use OCP\IURLGenerator;

class RegistrationLoginOption implements IAlternativeLogin {

	public function __construct(
		protected IURLGenerator $url,
		protected IL10N $l,
		protected \OC_Defaults $theming,
		protected IConfig $config,
	) {
	}

	public function getLabel(): string {
		return $this->l->t('Register');
	}

	public function getLink(): string {
		$invitationOnly = $this->config->getAppValue(Application::APP_ID, 'invitation_only', 'no') === 'yes';
		$allowButton = $this->config->getAppValue(Application::APP_ID, 'allow_registration_button', 'yes') === 'yes';

		if ($invitationOnly && !$allowButton) {
			return '';
		}
		return $this->url->linkToRoute('registration.register.showEmailForm');
	}

	public function getClass(): string {
		return 'register-button';
	}

	public function load(): void {
	}
}
