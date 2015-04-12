<?php

/*
 * This file is part of a XenForo add-on.
 *
 * (c) Jeremy P <http://xenforo.com/community/members/jeremy-p.450/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jrahmy\ReportCommentAlert;

/**
 * Filesums for XenForo File Health Check.
 *
 * @author Jeremy P <http://xenforo.com/community/members/jeremy-p.450/>
 */
class FileSums
{
    /**
     * Provides an associative array of filenames to hashes.
     *
     * @return array An associative array of filesums
     */
    public static function getHashes()
    {
        return [
                'library/Jrahmy/ReportCommentAlert/DataWriter/ReportComment.php' => 'f419e88270f3ee3f4fe17690c13be33e',
                'library/Jrahmy/ReportCommentAlert/Listener.php' => '9ba8a8cf7227a2dea5949ca525180fd5',
                'library/Jrahmy/ReportCommentAlert/Install.php' => '356cb517c5f18f002710a8a50d8b471f',
                'library/Jrahmy/ReportCommentAlert/Model/Report.php' => '11fae16f2f3cb58448caf9e5fae919ca',
                'library/Jrahmy/ReportCommentAlert/Model/Alert.php' => '29123411e0124a21a2a4d6d9650b0287',
                'library/Jrahmy/ReportCommentAlert/AlertHandler/Report.php' => '918d9e5e3c364a622c508e8d86352a79',

        ];
    }
}
