<?php
/**
 * Argentina Regions
 
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author     Mugar (https://www.mugar.io/)
 */

declare(strict_types=1);

namespace Mugar\ArgentinaRegions\Setup\Patch\Data;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

class AddRegions implements DataPatchInterface, PatchRevertableInterface
{
    const CONFIG_PATH = 'mugar/argentina-regions/no_installed';

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

        if ($this->hasRegions()) {
            $this->setFlag();
            $this->moduleDataSetup->getConnection()->endSetup();
            return;
        }

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
     * Revert patch
     */
    public function revert()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $tableDirectoryCountryRegionName = $this->moduleDataSetup->getTable('directory_country_region_name');
        $tableDirectoryCountryRegion = $this->moduleDataSetup->getTable('directory_country_region');

        if (!$this->isInstalled()) {
            $this->removeFlag();
            $this->moduleDataSetup->getConnection()->endSetup();
            return;
        }

        $where = [
            'region_id IN (SELECT region_id FROM ' . $tableDirectoryCountryRegion . ' WHERE country_id = ?)' => 'AR'
        ];
        $this->moduleDataSetup->getConnection()->delete(
            $tableDirectoryCountryRegionName,
            $where
        );

        $where = ['country_id = ?' => 'AR'];
        $this->moduleDataSetup->getConnection()->delete(
            $tableDirectoryCountryRegion,
            $where
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

    private function hasRegions()
    {
        $regionCount = $this->moduleDataSetup->getConnection()
            ->fetchOne(
                'SELECT COUNT(*) FROM ' . $this->moduleDataSetup->getTable('directory_country_region') . ' WHERE country_id = ?',
                ['AR']
            );

        return $regionCount > 0;
    }

    private function setFlag()
    {
        $bind = [
            'scope' => 'default',
            'scope_id' => 0,
            'path' => self::CONFIG_PATH,
            'value' => '1',
        ];
        try {
            $this->moduleDataSetup->getConnection()->insert(
                $this->moduleDataSetup->getTable('core_config_data'),
                $bind
            );
        } catch (LocalizedException $e) {}
    }

    private function isInstalled()
    {
        $tableCoreConfig = $this->moduleDataSetup->getTable('core_config_data');

        $configCount = $this->moduleDataSetup->getConnection()
            ->fetchOne(
                'SELECT COUNT(*) FROM ' . $tableCoreConfig . ' WHERE path = ?',
                [self::CONFIG_PATH]
            );

        return $configCount > 0;
    }

    private function removeFlag()
    {
        $tableCoreConfig = $this->moduleDataSetup->getTable('core_config_data');

        $where = ['path = ?' => self::CONFIG_PATH];
        $this->moduleDataSetup->getConnection()->delete(
            $tableCoreConfig,
            $where
        );
    }
}
