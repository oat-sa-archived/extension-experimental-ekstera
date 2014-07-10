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

$extpath = dirname(__FILE__).DIRECTORY_SEPARATOR;
$taopath = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'tao'.DIRECTORY_SEPARATOR;

return array(
	'name' => 'ekstera',
    'label' => 'Ekstera',
	'description' => 'A Proof of Concept implementation of irtTest',
    'license' => 'GPL-2.0',
    'version' => '1.0.0',
	'author' => 'Open Assessment Technologies',
	'requires' => array(
	    'irtTest' => '1.0.0',
	    'kutimo' => '1.0.0',
	    'taoQtiItem' => '>=2.6'
	),
	'install' => array(
		'rdf' => array(
		    dirname(__FILE__).DIRECTORY_SEPARATOR.'install'.DIRECTORY_SEPARATOR.'ontology'.DIRECTORY_SEPARATOR.'ekstera.rdf'
		),
        'php'	=> array(
            dirname(__FILE__) . '/scripts/install/sliced/configSetSliceSize.php',
        )
	),
    'local'	=> array(
        'php'	=> array(
            dirname(__FILE__).'/install/local/addPools.php'
        )
    ),
    'autoload' => array (
        'psr-4' => array(
            'oat\\ekstera\\' => dirname(__FILE__).DIRECTORY_SEPARATOR
        ),
    ),
	'managementRole' => 'http://www.tao.lu/Ontologies/TAOTest.rdf#EksteraManager',
    'acl' => array(),    
	'constants' => array()
);
