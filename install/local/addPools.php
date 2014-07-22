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

use oat\taoQtiItem\model\qti\ImportService;
use oat\irtTest\model\TestContent;

$itemClass	= taoItems_models_classes_ItemsService::singleton()->getRootClass();

// Defines possible pools.
$files = array();
// $files['pool-integers-5-local.zip'] = $itemClass->createSubclass('Int 5 - Local');
// $files['pool-integers-5-dummy.zip'] = $itemClass->createSubclass('Int 5 - Dummy');
$files['pool-integers-5-remote.zip'] = $itemClass->createSubclass('Int 5 - Remote');
// $files['pool-integers-10-local.zip'] = $itemClass->createSubclass('Int 10 - Local');
// $files['pool-integers-10-dummy.zip'] = $itemClass->createSubclass('Int 10 - Dummy');
// $files['pool-integers-15-local.zip'] = $itemClass->createSubclass('Int 15 - Local');
// $files['pool-integers-15-dummy.zip'] = $itemClass->createSubclass('Int 15 - Dummy');
// $files['pool-integers-20-local.zip'] = $itemClass->createSubclass('Int 20 - Local');
// $files['pool-integers-20-dummy.zip'] = $itemClass->createSubclass('Int 20 - Dummy');
// $files['pool-integers-50-local.zip'] = $itemClass->createSubclass('Int 50 - Local');
// $files['pool-integers-50-dummy.zip'] = $itemClass->createSubclass('Int 50 - Dummy');

$testModels = array(array('Sliced', INSTANCE_TESTMODEL_EKSTERA_SLICED, 'oat\\ekstera\\model\\sliced\\SlicedModel'));

$service = ImportService::singleton();
$testClass = new core_kernel_classes_Class(TAO_TEST_CLASS);
$testModelProperty = new core_kernel_classes_Property(PROPERTY_TEST_TESTMODEL);
$testContentProperty = new core_kernel_classes_Property(TEST_TESTCONTENT_PROP);
$irtTestContentClass = new core_kernel_classes_Class(CLASS_IRT_TEST_CONTENT);
$irtTestContentProperty = new core_kernel_classes_Property(PROPERTY_IRT_TEST_CONTENT_ITEMS);

foreach ($files as $file => $clazz) {
    try {
        $path = dirname(__FILE__) . DIRECTORY_SEPARATOR . $file;
        $service->importQTIPACKFile($path, $clazz, false);
        
        // Get all the instances of $clazz and bind them to a test
        // for all available ekstera test models.
        foreach ($testModels as $testModel)
        {
            $irtTestContentResource = $irtTestContentClass->createInstance('IRT Test Content');
            $testResource = $testClass->createInstance(ucfirst(str_replace('.zip', '', $file)) . ' - ' . $testModel[0]);
            $testResource->setPropertyValue($testContentProperty, $irtTestContentResource);
            $testResource->setPropertyValue($testModelProperty, $testModel[1]);
            
            $model = new $testModel[2]();
            $model->prepareContent($testResource, $clazz->getInstances());
        }
    }
    catch (Exception $e){
        common_Logger::e("An error occured while importing an itemPool '${file}': " . $e->getMessage());
        throw $e;
    }
}
