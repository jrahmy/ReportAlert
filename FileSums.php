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
     * @return array An associative array of filesums.
     */
    public static function getHashes()
    {
        return [
                'library/Jrahmy/ReportCommentAlert/DataWriter/ReportComment.php' => 'c07d59cc0b933764e011bbbc448f747e',
                'library/Jrahmy/ReportCommentAlert/Listener.php' => 'a818df76120aea84d1c01c8f321b56f9',
                'library/Jrahmy/ReportCommentAlert/Install.php' => '2a8d44fb0b36e0b55d3e28cd6c75e870',
                'library/Jrahmy/ReportCommentAlert/Model/Report.php' => '46422f9ca1daa6a53e57bf737c1dfba7',
                'library/Jrahmy/ReportCommentAlert/AlertHandler/Report.php' => 'dfe02acff588a53ce9a5f4ecb034ee7c',

        ];
    }
}
