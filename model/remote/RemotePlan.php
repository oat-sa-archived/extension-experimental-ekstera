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

namespace oat\ekstera\model\remote;

use oat\ekstera\model\EksteraModel;
use oat\ekstera\model\EksteraPlan;
use oat\irtTest\model\routing\Route;
use tao_models_classes_service_StorageDirectory;
use tao_models_classes_service_ServiceCall;

/**
 * The RemotePlan is an implementation of EksteraPlan aiming at ruling
 * tests ran by a composition of software systems.
 *
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 *
 */
class RemotePlan extends EksteraPlan
{
    /**
     * Instantiate an appropriate Route object aiming at providing
     * the routing logic (external call to the GloomRAT service).
     * 
     * @return \oat\ekstera\model\remote\RemoteRoute
     */
    public function instantiateRoute()
    {
        return new RemoteRoute($this);
    }
    
    /**
     * Restore a previously persisted RemoteRoute object.
     * 
     * @param string $stateString
     * @return \oat\ekstera\model\remote\RemoteRoute
     */
    public function restoreRoute($stateString)
    {
        return new RemoteRoute($this);
    }
    
    /**
     * Persiste a previously instantiated RemoteRoute object.
     * 
     * @param \oat\ekstera\model\remote\RemoteRoute $route
     * @return string
     */
    public function persistRoute(Route $route)
    {
        // There nothing to persist in this case. Indeed,
        // the whole business logic, persistence, ... should
        // be handled by the remote routing system.
        return '';
    }
    
    /**
     * @see \oat\ekstera\model\EksteraPlan::restoreItemRunner()
     */
    public function restoreItemRunner($itemIdentifier)
    {
        $fileName = $this->getStorage()->getPath() . EksteraModel::ASSEMBLY_ITEMRUNNERS_DIRNAME . DIRECTORY_SEPARATOR . str_replace('X', $itemIdentifier, EksteraModel::ASSEMBLY_ITEMRUNNERS_FILENAME);
        $strServiceCall = file_get_contents($fileName);

        return tao_models_classes_service_ServiceCall::fromString($strServiceCall);
    }
    
    /**
     * @see \oat\ekstera\model\EksteraPlan::getItemCount()
     */
    public function getItemCount()
    {
        $path = $this->getStorage()->getPath() . EksteraModel::ASSEMBLY_ITEMRUNNERS_DIRNAME . DIRECTORY_SEPARATOR;
        $pattern = '*.ird';
        return count(glob("${path}${pattern}"));
    }
    
    /**
     * Return a the PHP source code to be evaluated to restore a RemotePlan object.
     * 
     * @return string
     */
    public function __toPhpCode()
    {
        $storageId = $this->getStorage()->getId();
        return 'new \\oat\\ekstera\\model\\remote\\RemotePlan(\\tao_models_classes_service_FileStorage::singleton()->getDirectoryById("' . $storageId . '"))';
    }
}
