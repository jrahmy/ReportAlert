<?php

/*
 * This file is part of a XenForo add-on.
 *
 * (c) Jeremy P <http://xenforo.com/community/members/jeremy-p.450/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jrahmy\ReportCommentAlert\Model;

/**
 * Extends \XenForo_Model_Report to add custom methods to support our
 * alert handling.
 *
 * @author Jeremy P <http://xenforo.com/community/members/jeremy-p.450/>
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
     * @return array Returns an array of reports
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
                LEFT JOIN xf_user AS assigned ON (assigned.user_id = report.assigned_user_id)
                LEFT JOIN xf_user AS user ON (user.user_id = report.content_user_id)
                WHERE report.report_id IN (' . $this->_getDb()->quote($reportIds) . ')',
            'report_id'
        );
    }

    /**
     * Gets user IDs of commenters on a report.
     *
     * @param int $reportId The ID of the report to get comment user IDs for
     *
     * @return array Returns an array of user IDs
     */
    public function getReportCommentUserIds($reportId)
    {
        return $this->_getDb()->fetchCol(
            'SELECT DISTINCT user_id
                FROM xf_report_comment
                WHERE report_id = ?',
            $reportId
        );
    }

    /**
     * Send alerts to other report moderators when a new comment is made.
     *
     * @param array $comment The new comment
     * @param array $report  The report it was added to
     *
     * @return array Empty or user ids alerted
     */
    public function sendAlertToOtherCommentersOnComment(array $comment, array $report)
    {
        $reportCommenterIds = $this->getReportCommentUserIds(
            $report['report_id']
        );

        $reportCommenters = $this->getUserModel()->getUsersByIds(
            $reportCommenterIds,
            ['join' => \XenForo_Model_User::FETCH_USER_PERMISSIONS]
        );

        $alertedUserIds = [];

        foreach ($reportCommenters as $reportCommenter) {
            // don't send an alert to the user who made the comment
            if ($reportCommenter['user_id'] == $comment['user_id']) {
                continue;
            }

            // don't send an alert to a non-moderator
            if (!$reportCommenter['is_moderator']) {
                continue;
            }

            $reportCommenter['permissions'] = unserialize(
                $reportCommenter['global_permission_cache']
            );

            $reportHandler = $this->getReportHandler($report['content_type']);
            $visibleReports = $reportHandler->getVisibleReportsForUser(
                [$report['report_id'] => $report],
                $reportCommenter
            );

            // don't send an alert if this report is not visible to a moderator
            if (empty($visibleReports)) {
                continue;
            }

            // don't send an alert if there is already an unread alert
            if ($this->getAlertModel()
                ->hasUnreadReportCommentAlertByUserIdAndReportId(
                    $reportCommenter['user_id'],
                    $report['report_id']
                )
            ) {
                continue;
            }

            \XenForo_Model_Alert::alert(
                $reportCommenter['user_id'],
                $comment['user_id'],
                $comment['username'],
                'report',
                $report['report_id'],
                'comment'
            );

            $alertedUserIds[] = $reportCommenter['user_id'];
        }

        return $alertedUserIds;
    }

    /**
     * @return \XenForo_Model_Alert
     */
    protected function getAlertModel()
    {
        return $this->getModelFromCache('XenForo_Model_Alert');
    }

    /**
     * @return \XenForo_Model_User
     */
    protected function getUserModel()
    {
        return $this->getModelFromCache('XenForo_Model_User');
    }
}
