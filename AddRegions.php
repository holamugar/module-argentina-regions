<?php
/**
 * Argentina Regions
 *
 * @category   Mugar
 * @package    Mugar_ArgentinaRegions
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author     Mugar (https://www.mugar.io/)
 */

declare(strict_types=1);

namespace Mugar\ArgentinaRegions\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class AddRegions implements DataPatchInterface
{
    /**
     * ModuleDataSetupInterface
     *
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        /**
         * Fill table directory/country_region
         * Fill table directory/country_region_name for en_US locale
         */
        $data = [
            ['AR', 'AR-B', 'Buenos Aires'],
            ['AR', 'AR-C', 'Ciudad Autónoma de Buenos Aires'],
            ['AR', 'AR-K', 'Catamarca'],
            ['AR', 'AR-H', 'Chaco'],
            ['AR', 'AR-U', 'Chubut'],
            ['AR', 'AR-X', 'Córdoba'],
            ['AR', 'AR-W', 'Corrientes'],
            ['AR', 'AR-E', 'Entre Ríos'],
            ['AR', 'AR-P', 'Formosa'],
            ['AR', 'AR-Y', 'Jujuy'],
            ['AR', 'AR-L', 'La Pampa'],
            ['AR', 'AR-F', 'La Rioja'],
            ['AR', 'AR-M', 'Mendoza'],
            ['AR', 'AR-N', 'Misiones'],
            ['AR', 'AR-Q', 'Neuquén'],
            ['AR', 'AR-R', 'Río Negro'],
            ['AR', 'AR-A', 'Salta'],
            ['AR', 'AR-J', 'San Juan'],
            ['AR', 'AR-D', 'San Luis'],
            ['AR', 'AR-Z', 'Santa Cruz'],
            ['AR', 'AR-S', 'Santa Fe'],
            ['AR', 'AR-G', 'Santiago del Estero'],
            ['AR', 'AR-V', 'Tierra del Fuego, Antártida e Islas del Atlántico Sur'],
            ['AR', 'AR-T', 'Tucumán']
        ];

        foreach ($data as $row) {
            $bind = ['country_id' => $row[0], 'code' => $row[1], 'default_name' => $row[2]];
            $this->moduleDataSetup->getConnection()->insert(
                $this->moduleDataSetup->getTable('directory_country_region'),
                $bind
            );

            $regionId = $this->moduleDataSetup->getConnection()->lastInsertId(
                $this->moduleDataSetup->getTable('directory_country_region')
            );

            $bind = ['locale' => 'en_US', 'region_id' => $regionId, 'name' => $row[2]];
            $this->moduleDataSetup->getConnection()->insert(
                $this->moduleDataSetup->getTable('directory_country_region_name'),
                $bind
            );
        }

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * Revert patches
     * @see https://devdocs.magento.com/guides/v2.3/extension-dev-guide/declarative-schema/data-patches.html#revertingDataPatches
     */
    public function revert()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $this->moduleDataSetup->getConnection()->delete(
            $this->moduleDataSetup->getTable('directory_country_region_name'),
            ['region_id IN (SELECT region_id FROM directory_country_region WHERE country_id = ?)' => 'AR']
        );

        $this->moduleDataSetup->getConnection()->delete(
            $this->moduleDataSetup->getTable('directory_country_region'),
            ['country_id = ?' => 'AR']
        );

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}
