<?php

/**
 * Argentina Regions
 *
 * @category   Mugar
 * @package    Mugar_ArgentinaRegions
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author     Mugar (https://www.mugar.io/)
 */

namespace Mugar\ArgentinaRegions\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class UpgradeData implements UpgradeDataInterface
{
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.1.0') < 0) {
            $data = [
                ['BA', 'AR-B'],
                ['CABA', 'AR-C'],
                ['CT', 'AR-K'],
                ['CC', 'AR-H'],
                ['CH', 'AR-U'],
                ['CD', 'AR-X'],
                ['CR', 'AR-W'],
                ['ER', 'AR-E'],
                ['FO', 'AR-P'],
                ['JY', 'AR-Y'],
                ['LP', 'AR-L'],
                ['LR', 'AR-F'],
                ['MZ', 'AR-M'],
                ['MN', 'AR-N'],
                ['NQ', 'AR-Q'],
                ['RN', 'AR-R'],
                ['SA', 'AR-A'],
                ['SJ', 'AR-J'],
                ['SL', 'AR-D'],
                ['SC', 'AR-Z'],
                ['SF', 'AR-S'],
                ['SE', 'AR-G'],
                ['TF', 'AR-V'],
                ['TM', 'AR-T']
            ];

            foreach ($data as $row) {
                $setup->getConnection()->update(
                    $setup->getTable('directory_country_region'),
                    ['code' => $row[1]],
                    ['code = ?' => $row[0], 'country_id = ?' => 'AR']
                );
            }
        }

        $setup->endSetup();
    }

}
