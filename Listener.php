<?php

/*
 * This file is part of a XenForo add-on.
 *
 * (c) Jeremy P <http://xenforo.com/community/members/jeremy-p.450/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jrahmy\ReportAlert;

/**
 * Provides static methods to extend the XenForo API.
 *
 * @author Jeremy P <http://xenforo.com/community/members/jeremy-p.450/>
 */
class Listener
{
    /**
     * This method fires when the \XenForo_DataWriter_ReportComment class is
     * loaded.
     *
     * It extends it with our custom class.
     *
     * @param string $class  The class being loaded
     * @param array  $extend An array of classes to extend it with
     */
    public static function loadClassDatawriter($class, array &$extend)
    {
        $extend[] = 'Jrahmy\ReportAlert\DataWriter\ReportComment';
    }

    /**
     * This method fires when the \XenForo_Model_Report class is loaded.
     *
     * It extends it with a custom class.
     *
     * @param string $class  The class being loaded
     * @param array  $extend An array of classes to extend it with
     */
    public static function loadClassModel($class, array &$extend)
    {
        $extend[] = 'Jrahmy\ReportAlert\Model\Report';
    }

    /**
     * Adds filesums to the XenForo File Health Check.
     *
     * @param \XenForo_ControllerAdmin_Abstract $controller The current admin controller
     * @param array                             $hashes     An array of filesums
     */
    public static function fileHealthCheck(\XenForo_ControllerAdmin_Abstract $controller, array &$hashes)
    {
        $hashes += FileSums::getHashes();
    }
}
