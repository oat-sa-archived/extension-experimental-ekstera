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

class SlicedPlan implements Plan
{
    private $storage;
    
    public function __construct(tao_models_classes_service_StorageDirectory $storage)
    {
        $this->setStorage($storage);
    }
    
    protected function getStorage()
    {
        return $this->storage;
    }
    
    protected function setStorage(tao_models_classes_service_StorageDirectory $storage)
    {
        $this->storage = $storage;
    }
    
    public function instantiateRoute()
    {
        return new SlicedRoute($this);
    }
    
    public function restoreRoute($stateString)
    {
        return new SlicedRoute($this, $stateString);
    }
    
    public function persistRoute(Route $route)
    {
        return $route->getCurrentItem();
    }
    
    public function restoreItemRunner($itemIdentifier)
    {
        $fileName = $this->getStorage()->getPath() . SlicedModel::ASSEMBLY_ITEMRUNNERS_DIRNAME . DIRECTORY_SEPARATOR . str_replace('X', $itemIdentifier, SlicedModel::ASSEMBLY_ITEMRUNNERS_FILENAME);
        $strServiceCall = file_get_contents($fileName);

        return tao_models_classes_service_ServiceCall::fromString($strServiceCall);
    }
    
    public function getItemCount()
    {
        $path = $this->getStorage()->getPath() . SlicedModel::ASSEMBLY_ITEMRUNNERS_DIRNAME . DIRECTORY_SEPARATOR;
        $pattern = '*.ird';
        return count(glob("${path}${pattern}"));
    }
    
    public function __toPhpCode()
    {
        $storageId = $this->getStorage()->getId();
        return 'new \\oat\\ekstera\\model\\sliced\\SlicedPlan(\\tao_models_classes_service_FileStorage::singleton()->getDirectoryById("' . $storageId . '"))';
    }
}