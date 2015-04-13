<?php

/*
 * This file is part of a XenForo add-on.
 *
 * (c) Jeremy P <http://xenforo.com/community/members/jeremy-p.450/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jrahmy\ReportAlert;

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
                'library/Jrahmy/ReportAlert/DataWriter/ReportComment.php' => 'e81eaac8445af04961a40fe28101ca33',
                'library/Jrahmy/ReportAlert/Listener.php' => '7247a2d6f2cec9c0a0371d412d024080',
                'library/Jrahmy/ReportAlert/Install.php' => '7d50a8c54a3de50f165d7ce5a173f3cf',
                'library/Jrahmy/ReportAlert/Model/Report.php' => '58df50219ca4aa3d837e4ef79342b544',
                'library/Jrahmy/ReportAlert/AlertHandler/Report.php' => 'ae7f26c1634948ca339c4978f0dfa45c',

        ];
    }
}
