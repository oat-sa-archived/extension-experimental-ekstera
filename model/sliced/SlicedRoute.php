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

use \oat\irtTest\model\routing\simple\Route;
use \oat\irtTest\model\routing\Plan;

class SlicedRoute extends Route 
{
    private $currentItem;
    
    public function __construct(Plan $plan, $currentItem = '')
    {
        parent::__construct($plan);
        $this->setCurrentItem($currentItem);
    }
    
    public function getCurrentItem()
    {
        return $this->currentItem;
    }
    
    protected function setCurrentItem($currentItem)
    {
        $this->currentItem = $currentItem;
    }
    
    public function getNextItem($lastItemScore = '')
    {
        $intItem = intval($this->getCurrentItem());
        
        if ($intItem === $this->getPlan()->getItemCount()) {
            return '';
        } else {
            $nextItem = $intItem;
            $this->setCurrentItem($nextItem + 1);
            return strval($nextItem);
        }
    }
    
    public function getStateString()
    {
        return $this->getCurrentItem();
    }
}