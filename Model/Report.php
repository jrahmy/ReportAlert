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
}
