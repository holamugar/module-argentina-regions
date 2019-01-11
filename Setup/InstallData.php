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

use Magento\Directory\Helper\Data;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;


class InstallData implements InstallDataInterface
{

    /**
     * Directory data
     *
     * @var Data
     */
    protected $directoryData;

    /**
     * Init
     *
     * @param Data $directoryData
     */
    public function __construct(Data $directoryData)
    {
        $this->directoryData = $directoryData;
    }


    /**
     * Install Data
     *
     * @param ModuleDataSetupInterface $setup   Module Data Setup
     * @param ModuleContextInterface   $context Module Context
     *
     * @return void
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
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
            $setup->getConnection()->insert($setup->getTable('directory_country_region'), $bind);
            $regionId = $setup->getConnection()->lastInsertId($setup->getTable('directory_country_region'));

            $bind = ['locale' => 'en_US', 'region_id' => $regionId, 'name' => $row[2]];
            $setup->getConnection()->insert($setup->getTable('directory_country_region_name'), $bind);
        }
    }

}
