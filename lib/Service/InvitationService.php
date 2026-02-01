<?php

declare(strict_types=1);

namespace OCA\Registration\Service;

use OCA\Registration\Db\Invitation;
use OCA\Registration\Db\InvitationMapper;
use OCP\AppFramework\Db\DoesNotExistException;
use OCP\IL10N;
use OCP\IUserSession;
use OCP\Security\ISecureRandom;

class InvitationService {

	public function __construct(
		private InvitationMapper $invitationMapper,
		private ISecureRandom $secureRandom,
		private IUserSession $userSession,
		private MailService $mailService,
		private IL10N $l10n,
	) {
	}

	/**
	 * @throws RegistrationException
	 */
	public function createInvitation(string $email): Invitation {
		$this->mailService->validateEmail($email);

		$invitation = new Invitation();
		$invitation->setEmail($email);
		$invitation->setCode($this->secureRandom->generate(16, ISecureRandom::CHAR_ALPHANUMERIC));
		$invitation->setCreatedAt(time());

		$currentUser = $this->userSession->getUser();
		if ($currentUser) {
			$invitation->setCreatedBy($currentUser->getUID());
		}

		$this->invitationMapper->insert($invitation);

		$this->mailService->sendInvitation($email, $invitation->getCode());

		return $invitation;
	}

	/**
	 * @throws RegistrationException
	 */
	public function validateInvitation(string $code, string $email = null): Invitation {
		try {
			$invitation = $this->invitationMapper->findByCode($code);
		} catch (DoesNotExistException $e) {
			throw new RegistrationException($this->l10n->t('Invalid invitation code.'));
		}

		if ($email !== null && strtolower($invitation->getEmail()) !== strtolower($email)) {
			throw new RegistrationException($this->l10n->t('The invitation code does not match the email address.'));
		}

		return $invitation;
	}

	public function consumeInvitation(Invitation $invitation): bool {
		try {
			$this->invitationMapper->delete($invitation);
			return true;
		} catch (DoesNotExistException $e) {
			return false;
		}
	}

	/**
	 * @throws RegistrationException
	 */
	public function getInvitation(int $id): Invitation {
		try {
			return $this->invitationMapper->findById($id);
		} catch (DoesNotExistException $e) {
			throw new RegistrationException($this->l10n->t('Invitation not found.'));
		}
	}
}
