<?php

namespace Phpmig\Adapter;

use Phpmig\Migration\Migration,
    Phpmig\Adapter\AdapterInterface;

/**
 * Simple adapter that implements execute
 *
 * @author Edison Nica https://github.com/edisonnica
 */

abstract class SimpleAdapter implements AdapterInterface
{
    /**
     * Execute Migration
     *
     * @param Migration $migration
     * @param string $direction
     * @return Boolean
     */
    public function execute(Migration $migration, $direction) {
        $migration->{$direction}();
        $this->{$direction}($migration);
        return True;
    }
}
