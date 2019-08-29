<?php

namespace Phpmig\Adapter\PDO;

use Phpmig\Migration\Migration;

class TransactionalSqlPgsql extends SqlPgsql {

    public function __construct(\PDO $connection, $tableName) {
        parent::__construct($connection, $tableName);
    }

    public function execute(Migration $migration, $direction) {

        $runInTransaction = true; 
        $container = $migration->getContainer();
        $pdo_counter = $container['db'];

        if ($runInTransaction === true) {
            $pdo_counter->beginTransaction();
        }
        $successfulTransaction = true;
        $exception = null;
        $failedReason = null;
        try {
            if (parent::execute($migration, $direction) === false) {
                $failedReason = "Adapter failed to run the migration.";
                $successfulTransaction = false;
            }

            if ($runInTransaction === true) {
                $successfulTransaction = $pdo_counter->commit();
                if ($successfulTransaction === false) {
                    $failedReason = "Adapter failed to commit.";
                }
            }
        } catch (\PDOException $e) {
            $exception = $e;
            $successfulTransaction = false;
            if ($runInTransaction === true) {
                $pdo_counter->rollback();
            }
        }


        $failedOutput = " == <comment>Error in Migration ".$migration->getVersion()." - ".$migration->getName()."</comment>: <error>";

        if ($exception != null) {

            $migration->getOutput()->writeln($failedOutput.$exception->getMessage().'</error>');

            throw $exception;
        } else if ($failedReason != null) {

            $migration->getOutput()->writeln($failedOutput.$failedReason.'</error>');

            throw new \Exception($failedReason);
        }

        return $successfulTransaction;
    }
}
