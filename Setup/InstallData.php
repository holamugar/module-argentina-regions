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
            ['AR', 'BA', 'Buenos Aires'],
            ['AR', 'CABA', 'Ciudad Autónoma de Buenos Aires'],
            ['AR', 'CT', 'Catamarca'],
            ['AR', 'CC', 'Chaco'],
            ['AR', 'CH', 'Chubut'],
            ['AR', 'CD', 'Córdoba'],
            ['AR', 'CR', 'Corrientes'],
            ['AR', 'ER', 'Entre Ríos'],
            ['AR', 'FO', 'Formosa'],
            ['AR', 'JY', 'Jujuy'],
            ['AR', 'LP', 'La Pampa'],
            ['AR', 'LR', 'La Rioja'],
            ['AR', 'MZ', 'Mendoza'],
            ['AR', 'MN', 'Misiones'],
            ['AR', 'NQ', 'Neuquén'],
            ['AR', 'RN', 'Río Negro'],
            ['AR', 'SA', 'Salta'],
            ['AR', 'SJ', 'San Juan'],
            ['AR', 'SL', 'San Luis'],
            ['AR', 'SC', 'Santa Cruz'],
            ['AR', 'SF', 'Santa Fe'],
            ['AR', 'SE', 'Santiago del Estero'],
            ['AR', 'TF', 'Tierra del Fuego, Antártida e Islas del Atlántico Sur'],
            ['AR', 'TM', 'Tucumán']
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
