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
                'library/Jrahmy/ReportCommentAlert/DataWriter/ReportComment.php' => 'c2629b4501ffd0e4f27bbbd8dd0f46a8',
                'library/Jrahmy/ReportCommentAlert/Listener.php' => 'dfa28344e816bbba88e17236a8f8047b',
                'library/Jrahmy/ReportCommentAlert/Install.php' => 'c255efc31363c810b8e0ea2430951026',
                'library/Jrahmy/ReportCommentAlert/Model/Report.php' => '46422f9ca1daa6a53e57bf737c1dfba7',
                'library/Jrahmy/ReportCommentAlert/Model/Alert.php' => '29123411e0124a21a2a4d6d9650b0287',
                'library/Jrahmy/ReportCommentAlert/AlertHandler/Report.php' => 'dfe02acff588a53ce9a5f4ecb034ee7c',

        ];
    }
}
