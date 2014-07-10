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

use core_kernel_classes_Resource;
use taoItems_models_classes_ItemsService;
use oat\ekstera\model\ItemMapper;

/**
 * The SlicedItemMapper class maps Item Resources to integer based identifiers,
 * using an internal counter. Examples of successive map() method calls on the
 * same instance:
 * 
 * <code>
 * ...
 * $mapper = new SlicedItemMapper();
 * echo $mapper->map($itemResource1) . ',';
 * echo $mapper->map($itemResource2) . ',';
 * echo $mapper->map($itemResource3);
 * ...
 * 
 * // Will output...
 * // "1, 2, 3"
 * // whatever the Item Resources are.
 * 
 * </code>
 * 
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 *
 */
class SlicedItemMapper implements ItemMapper {
    
    /**
     * Internal counter.
     * 
     * @var integer
     */
    private $count = 0;
    
    /**
     * Get the current value of the internal counter.
     * 
     * @return integer
     */
    protected function getCount() 
    {
        return $this->count;
    }
    
    /**
     * Increment the internal counter by 1.
     */
    protected function incrementCount()
    {
        $this->count++;
    }
    
    /**
     * Map $item into a string identifier corresponding to the string value of the internal counter.
     * 
     * @param core_kernel_classes_Resource $item An Item Resource to mapped into an identifier.
     * @return string
     */
    public function map(core_kernel_classes_Resource $item) 
    {
        $id = strval($this->getCount());
        $this->incrementCount();
        return $id;
    }
}
