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
                    $alerts = $this->getAlertModel()->getAlertsForUser(
                        $otherCommenter['user_id'],
                        \XenForo_Model_Alert::FETCH_MODE_ALL
                    );
                    $alerts = $alerts['alerts'];

                    foreach ($alerts as $alert) {
                        if ($alert['content_type'] === 'report' and
                            $alert['action']       === 'comment' and
                            $alert['content_id']   === $this->get('report_id') and
                            $alert['view_date']    === 0) {
                                continue 2;
                        }
                    }

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

    /**
     * @return \XenForo_Model_Alert
     */
    protected function getAlertModel()
    {
        return $this->getModelFromCache('XenForo_Model_Alert');
    }
}
