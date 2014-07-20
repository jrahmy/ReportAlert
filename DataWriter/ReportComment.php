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
 * Provides static methods to extend the XenForo API.
 *
 * @author Jeremy P <http://xenforo.com/community/members/jeremy-p.450/>
 */
class ReportComment extends XFCP_ReportComment
{
    /**
     * Actions to perform after the data is saved.
     *
     * Loads parent method, then alerts all commenters who can view a report
     * that another member has commented.
     */
    protected function _postSave()
    {
        parent::_postSave();

        $reportModel = $this->_getReportModel();

        $otherCommenterIds = $reportModel->getReportCommentUserIds(
            $this->get('report_id')
        );

        $otherCommenters = $this->_getUserModel()->getUsersByIds(
            $otherCommenterIds,
            ['join' => \XenForo_Model_User::FETCH_USER_PERMISSIONS]
        );

        foreach ($otherCommenters as $otherCommenter) {
            if ($otherCommenter['user_id'] == $this->get('user_id')) {
                continue;
            }

            if ($otherCommenter['is_moderator']) {
                $otherCommenter['permissions'] = unserialize(
                    $otherCommenter['global_permission_cache']
                );

                $report = $reportModel->getReportById($this->get('report_id'));
                $handler = $reportModel->getReportHandler(
                    $report['content_type']
                );
                $reports = $handler->getVisibleReportsForUser(
                    [$this->get('report_id') => $report],
                    $otherCommenter
                );

                if (!empty($reports)) {
                    \XenForo_Model_Alert::alert(
                        $otherCommenter['user_id'],
                        $this->get('user_id'),
                        $this->get('username'),
                        'report',
                        $this->get('report_id'),
                        'comment'
                    );
                }
            }
        }
    }
}
