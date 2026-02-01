<?php

declare(strict_types=1);

namespace OCA\Registration\Db;

use OCP\AppFramework\Db\Entity;

/**
 * @method string getEmail()
 * @method void setEmail(string $email)
 * @method string getCode()
 * @method void setCode(string $code)
 * @method string getCreatedBy()
 * @method void setCreatedBy(string $createdBy)
 * @method int getCreatedAt()
 * @method void setCreatedAt(int $createdAt)
 */
class Invitation extends Entity {
	public $id;
	protected $email;
	protected $code;
	protected $createdBy;
	protected $createdAt;

	public function __construct() {
		$this->addType('email', 'string');
		$this->addType('code', 'string');
		$this->addType('createdBy', 'string');
		$this->addType('createdAt', 'integer');
	}
}
