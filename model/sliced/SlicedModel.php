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

use \oat\ekstera\model\EksteraModel;
use tao_models_classes_service_FileStorage;
use tao_models_classes_service_StorageDirectory;

/**
 * This implementation of the Ekstera Test Model considers the Item Pool as a series of
 * slices where a single item will be taken randomly from each of the slices, to constitute
 * the flow of Items to be taken by the candidate.
 * 
 * For instance, consider an Item Pool of 50 items, having Q1, Q2, Q3, ... Q50 identifiers and
 * a value of 5 for the slice_size configuration parameter. The Test to be taken will consist of
 * 10 slices (50 / 5). In each slice, an Item to be taken by the candidate is selected randomly.
 * As a result, the candidate might take the following sequences of Items:
 * 
 * * Q4, Q9, Q13, Q19, Q21, Q28, Q34, Q36, Q41, Q48
 * * Q1, Q10, Q12, Q20, Q22, Q26, Q31, Q39, Q45, Q50
 * * etc ... 
 * 
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 *
 */
class SlicedModel extends EksteraModel {

    /**
     * Instantiate the Plan to be respected by the Test in order to make a SlicedPlan object to
     * be used by the IRT Test Driver.
     * 
     * @return oat\ekstera\model\sliced\SlicedPlan
     */
    protected function instantiateRoutingPlan(tao_models_classes_service_StorageDirectory $directory) {
        return new SlicedPlan($directory);
    }
    
    /**
     * Create an appropriate ItemMapper implementation for the Sliced Model.
     * 
     * @return oat\ekstera\model\sliced\SlicedItemMapper
     */
    protected function createItemMapper()
    {
        return new SlicedItemMapper();
    }
}
