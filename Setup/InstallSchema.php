<?php
/**
 * Hgati
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Hgati.com license that is
 * available through the world-wide-web at this URL:
 * https://hgati.com/terms
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Hgati
 * @package    Hgati_ProductReviews
 * @copyright  Copyright (c) 2021 Hgati (https://www.hgati.com/)
 * @license    https://hgati.com/terms
 */

namespace Hgati\ProductReviews\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        /**
         * Create table 'hgati_review_customize'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('hgati_review_customize')
        )->addColumn(
            'review_customize_id',
            Table::TYPE_BIGINT,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Review Customize Id'
        )
            ->addColumn(
                'advantages',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false, 'default' => ''],
                'Advantages'
            )
            ->addColumn(
                'disadvantages',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false, 'default' => ''],
                'Disadvantages'
            )
            ->addColumn(
                'average',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'default' => '0'],
                'Rating Average'
            )
            ->addColumn(
                'count_helpful',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'default' => '0'],
                'Counting Helpful'
            )
            ->addColumn(
                'count_unhelpful',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'default' => '0'],
                'UnHelpful'
            )
            ->addColumn(
                'total_helpful',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'default' => '0'],
                'Total Helpful'
            )
            ->addColumn(
                'report_abuse',
                Table::TYPE_BOOLEAN,
                null,
                ['nullable' => false, 'default' => 0],
                'Report Abuse'
            )
            ->addColumn(
                'review_id',
                Table::TYPE_BIGINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Review Id'
            )
            ->addIndex(
                $installer->getIdxName('hgati_review_customize', ['review_id']),
                ['review_id']
            )
            ->addForeignKey(
                $installer->getFkName('hgati_review_customize', 'review_id', 'review', 'review_id'),
                'review_id',
                $installer->getTable('review'),
                'review_id',
                Table::ACTION_CASCADE
            )
            ->setComment('Hgati Review Customize');
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'hgati_review_report_history'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('hgati_review_report_history')
        )->addColumn(
            'id',
            Table::TYPE_BIGINT,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Id'
        )->addColumn(
            'review_id',
            Table::TYPE_BIGINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'Review Id'
        )
            ->addColumn(
                'customer_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Customer Id'
            )
            ->addColumn(
                'ip_address',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false, 'default' => ''],
                'Customer Ip Address'
            )
            ->addColumn(
                'browser_data',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false, 'default' => ''],
                'Customer Browser'
            )
            ->addColumn(
                'rate_type',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false, 'default' => ''],
                'Rate Type'
            )
            ->addColumn(
                'report_type',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false, 'default' => ''],
                'Report Type'
            )
            ->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Reply create date'
            )
            ->addIndex(
                $installer->getIdxName('hgati_review_report_history', ['review_id']),
                ['review_id']
            )
            ->addForeignKey(
                $installer->getFkName('hgati_review_report_history', 'review_id', 'review', 'review_id'),
                'review_id',
                $installer->getTable('review'),
                'review_id',
                Table::ACTION_CASCADE
            )
            ->setComment('Hgati Review Report History');
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'hgati_review_reply'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('hgati_review_reply')
        )->addColumn(
            'reply_id',
            Table::TYPE_BIGINT,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Reply Id'
        )
            ->addColumn(
                'review_id',
                Table::TYPE_BIGINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Review Id'
            )
            ->addColumn(
                'customer_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Customer Id'
            )
            ->addColumn(
                'reply_title',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false, 'default' => ''],
                'Reply Title'
            )
            ->addColumn(
                'reply_comment',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false, 'default' => ''],
                'Reply Comment'
            )
            ->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Reply create date'
            )
            ->addIndex(
                $installer->getIdxName('hgati_review_reply', ['review_id']),
                ['review_id']
            )
            ->addIndex(
                $installer->getIdxName('hgati_review_reply', ['customer_id']),
                ['customer_id']
            )
            ->addForeignKey(
                $installer->getFkName('hgati_review_reply', 'review_id', 'review', 'review_id'),
                'review_id',
                $installer->getTable('review'),
                'review_id',
                Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('hgati_review_reply', 'customer_id', 'customer_entity', 'entity_id'),
                'customer_id',
                $installer->getTable('customer_entity'),
                'entity_id',
                Table::ACTION_CASCADE
            )
            ->setComment('Hgati Review Reply');
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'hgati_product_reviews_gallery'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('hgati_product_reviews_gallery'))
            ->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Value ID'
            )
            ->addColumn(
                'review_id',
                Table::TYPE_BIGINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Review Id'
            )
            ->addColumn(
                'label',
                Table::TYPE_TEXT,
                255,
                [],
                'Label'
            )
            ->addColumn(
                'value',
                Table::TYPE_TEXT,
                '2M',
                [],
                'Value'
            )
            ->addColumn(
                'status',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '1'],
                'Status'
            )
            ->addIndex(
                $installer->getIdxName('hgati_product_reviews_gallery', ['review_id']),
                ['review_id']
            )
            ->addForeignKey(
                $installer->getFkName('hgati_product_reviews_gallery', 'review_id', 'review', 'review_id'),
                'review_id',
                $installer->getTable('review'),
                'review_id',
                Table::ACTION_CASCADE
            )
            ->setComment('Hgati Product Reviews Gallery Attribute Backend Table');
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'hgati_product_reviews_reminders'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('hgati_product_reviews_reminders'))
            ->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Reminders ID'
            )
            ->addColumn(
                'order_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Order Id'
            )
            ->addColumn(
                'order_increment_id',
                Table::TYPE_TEXT,
                32,
                ['nullable' => true, 'default' => '0'],
                'Order Increment Id'
            )
            ->addColumn(
                'product_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true],
                'Product Id'
            )
            ->addColumn(
                'customer_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Customer Id'
            )
            ->addColumn(
                'name',
                Table::TYPE_TEXT,
                255,
                [],
                'Customer Name'
            )
            ->addColumn(
                'email',
                Table::TYPE_TEXT,
                255,
                [],
                'Customer Email'
            )
            ->addColumn(
                'store_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Store ID'
            )
            ->addColumn(
                'status',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '1'],
                'Status'
            )
            ->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Order created date'
            )
            ->addIndex(
                $installer->getIdxName('hgati_product_reviews_reminders', ['id']),
                ['id']
            )
            ->addForeignKey(
                $installer->getFkName('hgati_product_reviews_reminders', 'order_id', 'sales_order', 'entity_id'),
                'order_id',
                $installer->getTable('sales_order'),
                'entity_id',
                Table::ACTION_CASCADE
            )
            /*->addForeignKey(
                $installer->getFkName('hgati_product_reviews_reminders', 'customer_id', 'customer_entity', 'entity_id'),
                'customer_id',
                $installer->getTable('customer_entity'),
                'entity_id',
                Table::ACTION_CASCADE
            )*/
            ->addForeignKey(
                $installer->getFkName('hgati_product_reviews_reminders', 'store_id', 'store', 'store_id'),
                'store_id',
                $installer->getTable('store'),
                'store_id',
                Table::ACTION_CASCADE
            )
            ->setComment('Hgati Product Reviews Reminders Backend Table');
        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}
