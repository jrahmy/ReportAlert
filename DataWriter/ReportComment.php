<?php

/*
 * This file is part of a XenForo add-on.
 *
 * (c) Jeremy P <http://xenforo.com/community/members/jeremy-p.450/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jrahmy\ReportCommentAlert\DataWriter;

/**
 * Extends \XenForo_DataWriter_ReportComment to modify post-save actions.
 *
 * @author Jeremy P <http://xenforo.com/community/members/jeremy-p.450/>
 */
class ReportComment extends XFCP_ReportComment
{
    /**
     * Actions to perform after the transaction is committed.
     *
     * Loads parent method, then alerts all other report moderators that
     * another moderator has commented.
     */
    protected function _postSaveAfterTransaction()
    {
        parent::_postSave();

        if ($this->isInsert()) {
            $reportModel = $this->_getReportModel();

            $comment = $this->getMergedData();
            $report  = $reportModel->getReportById($comment['report_id']);

            $reportModel->sendAlertToOtherCommentersOnComment($comment, $report);
        }
    }
}
