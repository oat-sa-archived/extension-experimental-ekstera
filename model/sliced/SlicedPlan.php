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

use oat\ekstera\model\EksteraPlan;
use oat\irtTest\model\routing\Plan;
use oat\irtTest\model\routing\Route;

/**
 * This class represents what is the plan to be respected to run a Sliced Test.
 * 
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 * @see oat\ekstera\model\sliced\SlicedModel For more information about how the Sliced Test Model works.
 *
 */
class SlicedPlan extends EksteraPlan
{
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
