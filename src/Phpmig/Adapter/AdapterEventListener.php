<?php
/**
 * @package    Phpmig
 * @subpackage Phpmig\Adapter
 */
namespace Phpmig\Adapter;

use Phpmig\Adapter\AdapterInterface;

interface AdapterEventListener {
    /**
     * Called if up migration succeeded
     *
     * @param AdapterInterface $adapter
     */
    public function upSuccessEvent(AdapterInterface $adapter);

    /**
     * Called if down migration succeeded
     */
    public function downSuccessEvent(AdapterInterface $adapter);

    /**
     * Called if up migration failed
     */
    public function upFailEvent(AdapterInterface $adapter);

    /**
     * Called if down migration failed
     */
    public function downFailEvent(AdapterInterface $adapter);
}