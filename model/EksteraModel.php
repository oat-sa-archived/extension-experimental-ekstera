<?php
/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2014 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 */

namespace oat\ekstera\model;

use \oat\irtTest\model\TestModel;
use core_kernel_classes_Resource;
use tao_models_classes_service_FileStorage;
use tao_models_classes_service_StorageDirectory;

/**
 * An implementation of the irtTest's TestModel, aiming at managing tests that will
 * be run by a composition of systems (TAO = Delivery + External Scoring + External Routing).
 * 
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 *
 */
abstract class EksteraModel extends TestModel {
    
    /**
     * A file name pattern (name + extension) for files aiming at containing
     * a serialized Item Runner ServiceCall representation. The 'X' character
     * in the constant will be replaced by a unique identifier corresponding
     * to the item to be called by the ServiceCall.
     *
     * The extension name is .ird, meaning Item Runner Data.
     *
     * @var string
     */
    const ASSEMBLY_ITEMRUNNERS_FILENAME = 'X.ird';
    
    /**
     * The folder name to be used to contain 'ird' files within the test
     * compilation directory.
     * 
     * @var string
     */
    const ASSEMBLY_ITEMRUNNERS_DIRNAME = 'itemrunners';
    
    abstract protected function instantiateRoutingPlan(tao_models_classes_service_StorageDirectory $directory);
    
    abstract protected function createItemMapper();
    
    public function createRoutingPlan(array $items, tao_models_classes_service_FileStorage $storage)
    {
        // Spawn a dedicated directory for plan (and its assets) storage.
        $private = $storage->spawnDirectory();
    
        // #1. Create and store the items runners in a persistent way, for
        // a later retrieval at delivery time.
        $this->storeItemRunners($items, $private);
    
        return $this->instantiateRoutingPlan($private);
    }
    
    /**
     * Store Item Runner service calls in the given $directory, for a later retrieval at delivery time.
     * 
     * @param array $items
     * @param tao_models_classes_service_StorageDirectory $directory
     */
    protected function storeItemRunners(array $items, tao_models_classes_service_StorageDirectory $directory)
    {
        $itemRunnersDir = $directory->getPath() . self::ASSEMBLY_ITEMRUNNERS_DIRNAME;
        mkdir($itemRunnersDir);
        $itemRunnersDir .= DIRECTORY_SEPARATOR;
        
        $mapper = $this->createItemMapper();
        
        foreach ($items as $item) {
            // Serialize the Item Runner ServiceCalls to a separate file. In this way
            // Item Runner ServiceCalls can be exploited in an atomic way.
            $fileName = $itemRunnersDir . str_replace('X', $mapper->map($item['item']), self::ASSEMBLY_ITEMRUNNERS_FILENAME);
            $strServiceCall = $item['call']->serializeToString();
            file_put_contents($fileName, $strServiceCall);
            
            \common_Logger::i("Item Runner Service Call serialized into ${fileName}.");
        }
    }
}