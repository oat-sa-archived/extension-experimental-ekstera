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

namespace oat\ekstera\model\sliced;

use \oat\irtTest\model\routing\Plan;
use \oat\irtTest\model\routing\Route;
use tao_models_classes_service_StorageDirectory;
use tao_models_classes_service_ServiceCall;

/**
 * This class represents what is the plan to be respected to run a Sliced Test.
 * 
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 * @see oat\ekstera\model\sliced\SlicedModel For more information about how the Sliced Test Model works.
 *
 */
class SlicedPlan implements Plan
{
    /**
     * An access to the directory where compiled data are stored.
     * These compiled data are used at test delivery time.
     * 
     * @var tao_models_classes_service_StorageDirectory
     */
    private $storage;
    
    /**
     * Create a new SlicedPlan object.
     * 
     * @param tao_models_classes_service_StorageDirectory $storage An access to the persistent directory where compiled data can be read for test delivery.
     */
    public function __construct(tao_models_classes_service_StorageDirectory $storage)
    {
        $this->setStorage($storage);
    }
    
    /**
     * Get an access to the persistent directory where complied data is stored.
     * 
     * @return tao_models_classes_service_StorageDirectory
     */
    protected function getStorage()
    {
        return $this->storage;
    }
    
    /**
     * Set the access to the persistent directory where compiled data is stored.
     * 
     * @param tao_models_classes_service_StorageDirectory $storage
     */
    protected function setStorage(tao_models_classes_service_StorageDirectory $storage)
    {
        $this->storage = $storage;
    }
    
    /**
     * Instantiate an appropriate Route implementation. In this case, it will
     * be a SlicedRoute which is suitable to SlicedPlan, which is suitable to
     * SlicedModel.
     * 
     * @return \oat\ekstera\model\sliced\SlicedRoute
     */
    public function instantiateRoute()
    {
        return new SlicedRoute($this);
    }
    
    /**
     * Restore a SlicedRoute object from its string representation.
     * 
     * @param string $stateString
     * @return \oat\ekstera\model\sliced\SlicedRoute
     */
    public function restoreRoute($stateString)
    {
        return new SlicedRoute($this, intval($stateString));
    }
    
    /**
     * Persist a SlicedRoute in a string. This string is the persistent representation
     * of the given $route object.
     * 
     * @param Route $route
     * @return string
     */
    public function persistRoute(Route $route)
    {
        return strval($route->getCurrentSlice());
    }
    
    /**
     * Restore a Service Call object from a given $itemIdentifier. This Service Call object
     * will be used by the IRT Test Driver to call the Item represented by $itemIdentifier as
     * a TAO service.
     * 
     * @param string $itemIdentifier The identifier of the item you want to restore the Service Call definition.
     * @return tao_models_classes_service_ServiceCall
     */
    public function restoreItemRunner($itemIdentifier)
    {
        $fileName = $this->getStorage()->getPath() . SlicedModel::ASSEMBLY_ITEMRUNNERS_DIRNAME . DIRECTORY_SEPARATOR . str_replace('X', $itemIdentifier, SlicedModel::ASSEMBLY_ITEMRUNNERS_FILENAME);
        $strServiceCall = file_get_contents($fileName);

        return tao_models_classes_service_ServiceCall::fromString($strServiceCall);
    }
    
    /**
     * Get the number of Items composing the Item Pool in use.
     * 
     * @return integer
     */
    public function getItemCount()
    {
        $path = $this->getStorage()->getPath() . SlicedModel::ASSEMBLY_ITEMRUNNERS_DIRNAME . DIRECTORY_SEPARATOR;
        $pattern = '*.ird';
        return count(glob("${path}${pattern}"));
    }
    
    /**
     * Returns a PHP Code serialization of the SlicedPlan. This representation
     * will be stored in the compilation directory for lightning fast retrieval,
     * using PHP's include() function.
     * 
     * @return string
     */
    public function __toPhpCode()
    {
        $storageId = $this->getStorage()->getId();
        return 'new \\oat\\ekstera\\model\\sliced\\SlicedPlan(\\tao_models_classes_service_FileStorage::singleton()->getDirectoryById("' . $storageId . '"))';
    }
}
