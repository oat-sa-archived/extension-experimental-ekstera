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

use \oat\ekstera\model\EksteraModel;
use tao_models_classes_service_StorageDirectory;

/**
 * Implementation of EksteraModel aiming at demonstrating the use of
 * TAO in conjunction with remote Routing and Scoring systems through
 * RESTful web services.
 * 
 * The RemoteModel is an implementation of EksteraModel aiming at ruling
 * tests ran by a composition of software systems which are:
 * 
 * * TAO Platform, through the use of the Ekstera and Kutimo extensions.
 * * Korekton, an experimental dummy RESTful web service aiming at scoring items.
 * * GloomRAT, an experimental dummy RESTful web service aiming at selecting items to be taken by a candidate.
 * 
 * These 3 systems agree 'a priori' on an given Item Pool and QTI Item identifiers to work in harmony.
 * 
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 *
 */
class RemoteModel extends EksteraModel {

    /**
     * Instantiate a Plan object that rules a Remote Test.
     * 
     * @param tao_models_classes_service_StorageDirectory $directory
     * @return \oat\ekstera\model\remote\RemotePlan
     */
    protected function instantiateRoutingPlan(tao_models_classes_service_StorageDirectory $directory) {
        return new RemotePlan($directory);
    }
    
    /**
     * Create an appropriate ItemMapper object aiming at reaching an agreement about
     * shared item identifiers accross the multiple systems composing a Remote Test.
     * 
     * @return \oat\ekstera\model\remote\RemoteItemMapper
     */
    protected function createItemMapper()
    {
        return new RemoteItemMapper();
    }
}
