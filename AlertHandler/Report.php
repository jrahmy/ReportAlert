<?php

/*
 * This file is part of a XenForo add-on.
 *
 * (c) Jeremy P <http://xenforo.com/community/members/jeremy-p.450/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jrahmy\ReportCommentAlert\AlertHandler;

/**
 * Alert handler for moderator reports.
 *
 * @author Jeremy P <http://xenforo.com/community/members/jeremy-p.450/>
 */
class Report extends \XenForo_AlertHandler_Abstract
{
    /**
     * Fetches the content required by alerts.
     *
     * @param  array                $contentIds  An array of content IDs
     * @param  \XenForo_Model_Alert $model       The invoked alert model
     * @param  int                  $userId      The user ID the alerts are for
     * @param  array                $viewingUser The viewing user's information
     */
    public function getContentByIds(array $contentIds, $model, $userId, array $viewingUser)
    {
        /** @var $reportModel \XenForo_Model_Report */
        $reportModel = $model->getModelFromCache('XenForo_Model_Report');

        return $reportModel->getReportsByIdsToo($contentIds);
    }
}
