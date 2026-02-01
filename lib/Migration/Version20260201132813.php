<?php

declare(strict_types=1);

namespace OCA\Registration\Migration;

use Closure;
use Doctrine\DBAL\Types\Types;
use OCP\DB\ISchemaWrapper;
use OCP\Migration\IOutput;
use OCP\Migration\SimpleMigrationStep;

class Version20260201132813 extends SimpleMigrationStep {

	public function changeSchema(IOutput $output, Closure $schemaClosure, array $options) {
		/** @var ISchemaWrapper $schema */
		$schema = $schemaClosure();

		if (!$schema->hasTable('registration_invitations')) {
			$table = $schema->createTable('registration_invitations');
			$table->addColumn('id', Types::INTEGER, [
				'autoincrement' => true,
				'notnull' => true,
				'unsigned' => true,
			]);
			$table->addColumn('email', Types::STRING, [
				'notnull' => true,
				'length' => 255,
			]);
			$table->addColumn('code', Types::STRING, [
				'notnull' => true,
				'length' => 64,
			]);
			$table->addColumn('created_by', Types::STRING, [
				'notnull' => false,
				'length' => 64,
			]);
			$table->addColumn('created_at', Types::INTEGER, [
				'notnull' => true,
				'unsigned' => true,
			]);
			$table->setPrimaryKey(['id']);
			$table->addUniqueIndex(['code'], 're_inv_code_idx');
		}

		if ($schema->hasTable('registration')) {
			$table = $schema->getTable('registration');
			if (!$table->hasColumn('invitation_id')) {
				$table->addColumn('invitation_id', Types::INTEGER, [
					'notnull' => false,
					'unsigned' => true,
				]);
			}
		}

		return $schema;
	}
}
