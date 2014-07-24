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

namespace oat\ekstera\model;

use \Exception;

/**
 * Exception to be thrown when an error occurs
 * at the level of item identifier mapping.
 * 
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 * @see \oat\ekstera\model\ItemMapper The ItemMapper interface, which uses this exception type.
 */
class ItemMappingException extends Exception
{
    /**
     * Create a new ItemMappingException object.
     * 
     * @param string $message A human-readable message.
     * @param integer $code A machine-understandable code.
     * @param \Exception $previous An optional previous exception.
     */
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}