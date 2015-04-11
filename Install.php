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
 * Installs custom content type and content type fields so that add-ons may
 * use custom XenForo handlers for alerts, reports, spam, likes, and others.
 *
 * @author Jeremy P <http://xenforo.com/community/members/jeremy-p.450/>
 */
class Install
{
    /**
     * An array of content types and fields associated with this add-on.
     *
     * @var array
     */
    public static $contentTypes = [
        'report' => [
            'alert_handler_class' => 'Jrahmy\ReportCommentAlert\AlertHandler\Report'
        ]
    ];

    /**
     * Adds content type fields to database.
     *
     * @param  mixed $existingAddOn The current addon version, if upgrading
     * @param  array $addOnData     The current addon data
     *
     * @return bool Returns true if successful
     */
    public static function install($existingAddOn, array $addOnData)
    {
        self::installContentTypes($addOnData['addon_id']);

        return true;
    }

    /**
     * Removes content type fields from the database.
     *
     * @param  array $existingAddOn The current addon version, if upgrading
     *
     * @return bool Returns true if successful
     */
    public static function uninstall(array $existingAddOn)
    {
        self::uninstallContentTypes();

        return true;
    }

    /**
     * Installs content types using class metadata by inserting rows into
     * the database and rebuilding the internal cache.
     *
     * @param string $addOnId The ID of the add-on
     */
    public static function installContentTypes($addOnId)
    {
        foreach (self::$contentTypes as $contentType => $fields) {
            self::installContentType($addOnId, $contentType, $fields);
        }

        /* @var $contentTypeModel \XenForo_Model_ContentType */
        $contentTypeModel = \XenForo_Model::create('XenForo_Model_ContentType');
        $contentTypeModel->rebuildContentTypeCache();
    }

    /**
     * Installs a content type.
     *
     * @param string $addOnId     The ID of the add-on
     * @param string $contentType The content type to install
     * @param array  $fields      An array of fields associated with the type
     */
    public static function installContentType($addOnId, $contentType, array $fields)
    {
        foreach ($fields as $name => $value) {
            self::installContentTypeField($contentType, $name, $value);
        }

        self::insertRow([
            'table'  => 'xf_content_type',
            'fields' => '`content_type`, `addon_id`, `fields`',
            'values' => "'{$contentType}', '{$addOnId}', ''"
        ]);
    }

    /**
     * Installs a content type field.
     *
     * @param string $contentType The content type of the field
     * @param string $name        The name of the field
     * @param string $value       The value of the field
     */
    public static function installContentTypeField($contentType, $name, $value)
    {
        $value = addslashes($value);

        self::insertRow([
            'table'      => 'xf_content_type_field',
            'fields'     => '`content_type`, `field_name`, `field_value`',
            'values'     => "'{$contentType}', '{$name}', '{$value}'"
        ]);
    }

    /**
     * Removes a content type using class metadata by removing rows from the
     * database and rebuilding the internal cache.
     */
    public static function uninstallContentTypes()
    {
        foreach (self::$contentTypes as $contentType => $fields) {
            self::uninstallContentType($contentType, $fields);
        }

        /* @var $contentTypeModel \XenForo_Model_ContentType */
        $contentTypeModel = \XenForo_Model::create('XenForo_Model_ContentType');
        $contentTypeModel->rebuildContentTypeCache();
    }

    /**
     * Removes a content type.
     *
     * @param string $contentType The content type to remove
     * @param array  $fields      An array of fields associated with the type
     */
    public static function uninstallContentType($contentType, array $fields)
    {
        foreach ($fields as $value) {
            self::uninstallContentTypeField($value);
        }

        self::deleteRow([
            'table'      => 'xf_content_type',
            'identifier' => "xf_content_type.content_type = '{$contentType}'"
        ]);
    }

    /**
     * Removes a content type field.
     *
     * @param string $value The value of the field being removed
     */
    public static function uninstallContentTypeField($value)
    {
        $value = addslashes($value);

        self::deleteRow([
            'table'      => 'xf_content_type_field',
            'identifier' => "xf_content_type_field.field_value = '{$value}'"
        ]);
    }

    /**
     * Inserts a row into the database.
     *
     * @param array $row Data for row being inserted
     */
    public static function insertRow(array $row)
    {
        $database = \XenForo_Application::getDb();

        $database->query(
            "INSERT IGNORE INTO `{$row['table']}`
                ({$row['fields']})
            VALUES
                ({$row['values']})"
        );
    }

    /**
     * Removes a row from the database.
     *
     * @param array $row Data for row being removed
     */
    public static function deleteRow(array $row)
    {
        $database = \XenForo_Application::getDb();

        $database->query(
            "DELETE FROM `{$row['table']}`
            WHERE {$row['identifier']}"
        );
    }
}
