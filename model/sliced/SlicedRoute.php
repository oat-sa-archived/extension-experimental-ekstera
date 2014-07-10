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

use \common_ext_ExtensionsManager;
use \oat\irtTest\model\routing\simple\Route;
use \oat\irtTest\model\routing\Plan;

/**
 * An implementation of Route for the Sliced Ekstera Model.
 * 
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 * @see oat\ekstera\model\sliced\SlicedModel For more information about how the Sliced model works.
 *
 */
class SlicedRoute extends Route 
{
    /**
     * The integer index representing the current slice in the test flow.
     * This index begins at 0.
     * 
     * @var integer
     */
    private $currentSlice;
    
    /**
     * Create a new SlicedRoute object.
     * 
     * @param oat\irtTest\model\routing\Plan $plan The Plan to be respected by the Route.
     * @param integer $currentSlice The current slice index.
     * @see oat\ekstera\model\sliced\SlicedRoute::$currentSlice
     */
    public function __construct(Plan $plan, $currentSlice = 0)
    {
        parent::__construct($plan);
        $this->setCurrentSlice($currentSlice);
    }
    
    /**
     * Get the current slice index value.
     * 
     * @return integer
     * @see oat\ekstera\model\sliced\SlicedRoute::$currentSlice
     */
    public function getCurrentSlice()
    {
        return $this->currentSlice;
    }
    
    /**
     * Set the current slice index value.
     * 
     * @param integer $currentSlice
     * @see oat\ekstera\model\sliced\SlicedRoute::$currentSlice
     */
    protected function setCurrentSlice($currentSlice)
    {
        $this->currentSlice = $currentSlice;
    }
    
    /**
     * Get the next Item to be taken by the candidate. The Item to be taken
     * will be selected randomly among the current slice.
     * 
     * In this implementation, the $lastItemScore value is not taken into account for the selection of the next Item.
     * 
     * @param string $lastItemScore The score to the last Item taken by the candidate (optional for the first item to be taken).
     * @return string The identifier of the next item to be taken.
     */
    public function getNextItem($lastItemScore = '')
    {
        $poolSize = $this->getPlan()->getItemCount();
        $sliceSize = $this->retrieveSliceSize();
        $currentSlice = $this->getCurrentSlice();
        $sliceCount = intval(floor($poolSize / $sliceSize));
        
        \common_Logger::i("Current slice is #${currentSlice} -> Slice count is ${sliceCount}. -> Slice size is ${sliceSize}.");

        if ($currentSlice === $sliceCount) {
            // Last slice taken by the candidate -> end of test.
            return '';
        } else {
            // Take the slice!
            $lowerBound = $currentSlice * $sliceSize;
            $upperBound = $lowerBound + $sliceSize - 1;
            $rand = mt_rand($lowerBound, $upperBound);
            \common_Logger::i("Current slice is #${currentSlice} -> Random number in slice is ${rand} from range [${lowerBound},${upperBound}].");
            
            $itemId = strval($rand);
            $this->setCurrentSlice($currentSlice + 1);
            
            return $itemId;
        }
    }
    
    /**
     * Get the string representing the internal state of this SlicedRoute object. This value
     * will be used for persisting the test state.
     * 
     * @return string
     */
    public function getStateString()
    {
        return strval($this->getCurrentSlice());
    }
    
    /**
     * Retrieve the slice_size configuration parameter value, describing how much items are
     * composing a slice in the test.
     * 
     * @return integer
     */
    protected function retrieveSliceSize()
    {
        $ext = common_ext_ExtensionsManager::singleton()->getExtensionById('ekstera');
        return intval($ext->getConfig('sliced.slice_size'));
    }
}
