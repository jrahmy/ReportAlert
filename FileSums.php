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
                'library/Jrahmy/ReportAlert/DataWriter/ReportComment.php' => '6167b941c4232ce4893c9a50aa080a9f',
                'library/Jrahmy/ReportAlert/Listener.php' => '7247a2d6f2cec9c0a0371d412d024080',
                'library/Jrahmy/ReportAlert/Install.php' => '599d264ddcfcb684b36e6200767f8c08',
                'library/Jrahmy/ReportAlert/Model/Report.php' => '041fa3dc33603bd58c525459b54d2344',
                'library/Jrahmy/ReportAlert/AlertHandler/Report.php' => 'ae7f26c1634948ca339c4978f0dfa45c',

        ];
    }
}
