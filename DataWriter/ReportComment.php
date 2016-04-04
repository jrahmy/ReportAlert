<?php

/*
 * This file is part of a XenForo add-on.
 *
 * (c) Jeremy P <https://xenforo.com/community/members/jeremy-p.450/>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Jrahmy\ReportAlert\DataWriter;

/**
 * Extends \XenForo_DataWriter_ReportComment to modify post-save actions.
 *
 * @author Jeremy P <https://xenforo.com/community/members/jeremy-p.450/>
 */
class ReportComment extends XFCP_ReportComment
{
    /**
     * Actions to perform after the transaction is committed.
     *
     * Loads parent method, then alerts all report moderators that a user has
     * commented.
     */
    protected function _postSaveAfterTransaction()
    {
        parent::_postSaveAfterTransaction();

        if ($this->isInsert()) {
            $reportModel = $this->_getReportModel();

            $comment = $this->getMergedData();
            $report  = $reportModel->getReportById($comment['report_id']);

            $reportModel->sendAlertToModeratorsOnComment($comment, $report);
        }
    }
}
