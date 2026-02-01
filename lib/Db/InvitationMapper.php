<?php

declare(strict_types=1);

namespace OCA\Registration\Db;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\Entity;
use OCP\AppFramework\Db\QBMapper;
use OCP\IDBConnection;

class InvitationMapper extends QBMapper {
	public function __construct(IDBConnection $db) {
		parent::__construct($db, 'registration_invitations', Invitation::class);
	}

	/**
	 * @throws DoesNotExistException
	 */
	public function findByCode(string $code): Invitation {
		$query = $this->db->getQueryBuilder();
		$query->select('*')
			->from($this->getTableName())
			->where($query->expr()->eq('code', $query->createNamedParameter($code)));

		return $this->findEntity($query);
	}

	/**
	 * @throws DoesNotExistException
	 */
	public function findById(int $id): Invitation {
		$query = $this->db->getQueryBuilder();
		$query->select('*')
			->from($this->getTableName())
			->where($query->expr()->eq('id', $query->createNamedParameter($id)));

		return $this->findEntity($query);
	}
}
