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

use Magento\Framework\Setup\UninstallInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

/**
 * @codeCoverageIgnore
 */
class Uninstall implements UninstallInterface
{

    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {

        $setup->startSetup();

        $setup->getConnection()->delete(
            $setup->getTable('directory_country_region'),
            ['country_id = ?' => 'AR']
        );

        $setup->endSetup();
    }

}
