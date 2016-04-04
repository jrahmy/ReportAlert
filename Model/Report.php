<?php

/*
 * This file is part of a XenForo add-on.
 *
 * (c) Jeremy P <https://xenforo.com/community/members/jeremy-p.450/>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Jrahmy\ReportAlert\Model;

/**
 * Extends \XenForo_Model_Report to add custom methods to support our
 * alert handling.
 *
 * @author Jeremy P <https://xenforo.com/community/members/jeremy-p.450/>
 */
class Report extends XFCP_Report
{
    /**
     * Gets reports by IDs.
     *
     * This has been given a 'special' name because it conflicts with Digital
     * Point's search add-on.
     *
     * @param array $reportIds An array of report IDs
     *
     * @return array An array of reports
     */
    public function getReportsByIdsToo(array $reportIds)
    {
        if (!$reportIds) {
            return [];
        }

        return $this->fetchAllKeyed(
            'SELECT
                    report.*,
                    user.*,
                    assigned.username AS assigned_username
                FROM xf_report AS report
                LEFT JOIN xf_user AS assigned ON (
                    assigned.user_id = report.assigned_user_id
                )
                LEFT JOIN xf_user AS user ON (
                    user.user_id = report.content_user_id
                )
                WHERE report.report_id IN (' . $this->_getDb()->quote($reportIds) . ')',
            'report_id'
        );
    }

    /**
     * Send alerts to report moderators when a comment is made.
     *
     * @param array $comment The comment
     * @param array $report  The report it is attached to
     *
     * @return array Empty or user ids alerted
     */
    public function sendAlertToModeratorsOnComment(array $comment, array $report)
    {
        $reportModerators = $this->getUsersWhoCanViewReport($report);

        $alertedUserIds = [];

        foreach ($reportModerators as $reportModerator) {
            // don't send an alert to the user who made the comment
            if ($reportModerator['user_id'] == $comment['user_id']) {
                continue;
            }

            // don't send an alert if there is already an unread alert
            if ($this->hasUnreadReportAlertByUserIdAndReportId(
                $reportModerator['user_id'],
                $report['report_id']
            )) {
                continue;
            }

            $alertType = 'comment';

            if ($comment['state_change']) {
                $alertType = $comment['state_change'];
            }

            if ($comment['is_report']) {
                $alertType = 'report';
            }

            \XenForo_Model_Alert::alert(
                $reportModerator['user_id'],
                $comment['user_id'],
                $comment['username'],
                'report',
                $report['report_id'],
                $alertType
            );

            $alertedUserIds[] = $reportModerator['user_id'];
        }

        return $alertedUserIds;
    }

    /**
     * Checks for unread alert for a report.
     *
     * @param int $userId   The ID of the user to check
     * @param int $reportId The ID of the report check for
     *
     * @return bool False if there is no unread alert, true otherwise
     */
    public function hasUnreadReportAlertByUserIdAndReportId($userId, $reportId)
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
