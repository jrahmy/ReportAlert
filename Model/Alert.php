<?php

/*
 * This file is part of a XenForo add-on.
 *
 * (c) Jeremy P <http://xenforo.com/community/members/jeremy-p.450/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jrahmy\ReportAlert\Model;

/**
 * Extends \XenForo_Model_Alert to add custom methods to support our
 * alert handling.
 *
 * @author Jeremy P <http://xenforo.com/community/members/jeremy-p.450/>
 */
class Alert extends XFCP_Alert
{
    /**
     * Checks for unread alert for comments on a report.
     *
     * @param int $userId   The ID of the user to check
     * @param int $reportId The ID of the report check for
     *
     * @return bool False if there is no unread alert, true otherwise
     */
    public function hasUnreadReportCommentAlertByUserIdAndReportId($userId, $reportId)
    {
        $alert = $this->_getDb()->fetchRow('
            SELECT alert_id
            FROM xf_user_alert
            WHERE alerted_user_id = ?
                AND content_type = "report"
                AND content_id = ?
                AND view_date = 0
        ', [$userId, $reportId]);

        if (empty($alert)) {
            return false;
        }

        return true;
    }
}
