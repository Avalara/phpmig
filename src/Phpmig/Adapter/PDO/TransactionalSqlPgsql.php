<?php

namespace Phpmig\Adapter\PDO;

use Phpmig\Migration\Migration;

class TransactionalSqlPgsql extends SqlPgsql {

    public function __construct(\PDO $connection, $tableName) {
        parent::__construct($connection, $tableName);
    }

    public function execute(Migration $migration, $direction) {

        $runInTransaction = True; //$migration->runInTransaction();
        $container = $migration->getContainer();
        $pdo_counter = $container['db'];

        if ($runInTransaction === True) {
            $pdo_counter->beginTransaction();
        }
        $successfulTransaction = True;
        $exception = null;
        $failedReason = null;
        try {
            if (parent::execute($migration, $direction) === False) {
                $failedReason = "Adapter failed to run the migration.";
                $successfulTransaction = False;
            }

            if ($runInTransaction === True) {
                $successfulTransaction = $pdo_counter->commit();
                if ($successfulTransaction === False) {
                    $failedReason = "Adapter failed to commit.";
                }
            }
        } catch (\PDOException $e) {
            $exception = $e;
            $successfulTransaction = False;
            if ($runInTransaction === True) {
                $pdo_counter->rollback();
            }
        }

        if ($exception != null) {
            throw $exception;
        } else if ($failedReason != null) {
            throw new \Exception($failedReason);
        }

        return $successfulTransaction;
    }
}