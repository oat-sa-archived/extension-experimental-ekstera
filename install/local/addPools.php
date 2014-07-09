<?php
/*  
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
 * Copyright (c) 2013 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *               
 * 
 */

use oat\taoQtiItem\model\qti\ImportService;

?>
<?php
$itemClass	= taoItems_models_classes_ItemsService::singleton()->getRootClass();

$files = array('pool-integers-10-local.zip' => $itemClass->createSubclass('Int 10 - Local'),
                'pool-integers-10-dummyremote.zip' => $itemClass->createSubclass('Int10 - Dummy Remote'));

$service = ImportService::singleton();

foreach ($files as $file => $clazz) {
    try {
        $path = dirname(__FILE__) . DIRECTORY_SEPARATOR . $file;
        $service->importQTIPACKFile($path, $clazz, false);
    }
    catch (Exception $e){
        common_Logger::e("An error occured while importing an itemPool '${file}': " . $e->getMessage());
        throw $e;
    }
}
