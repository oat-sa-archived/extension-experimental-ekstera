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

use oat\irtTest\model\routing\Plan;
use oat\irtTest\model\routing\simple\Route;

/**
 * 
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 * @see oat\ekstera\model\remote\RemoteModel
 *
 */
class RemoteRoute extends Route 
{
    /**
     * Create a new SlicedRoute object.
     * 
     * @param oat\irtTest\model\routing\Plan $plan The Plan to be respected by the Route.
     */
    public function __construct(Plan $plan)
    {
        parent::__construct($plan);
    }
    
    /**
     * 
     * @param string $lastItemScore The score to the last Item taken by the candidate (optional for the first item to be taken).
     * @return string The identifier of the next item to be taken or an empty string if it's the end of the test.
     */
    public function getNextItem($lastItemScore = '')
    {
        // CURL HERE!
    }
    
    /**
     * 
     * @return string
     */
    public function getStateString()
    {
        return '';
    }
}
