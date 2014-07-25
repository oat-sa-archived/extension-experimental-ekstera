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
use common_ext_Extension;
use common_ext_ExtensionsManager;
use \DOMDocument;

/**
 * RemoteRoute is an implementation of EksteraRoute that delegates
 * the routing logic to an external Routing Service through a RESTful
 * interface provided by the GloomRAT application.
 * 
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 * @see oat\ekstera\model\remote\RemoteModel
 *
 */
class RemoteRoute extends Route 
{
    /**
     * A reference to the Ekstera extension.
     * 
     * @var common_ext_Extension
     */
    private $extension;
    
    /**
     * Create a new RemoteRoute object.
     * 
     * @param oat\irtTest\model\routing\Plan $plan The Plan to be respected by the Route.
     */
    public function __construct(Plan $plan)
    {
        parent::__construct($plan);
        $this->setExtension(common_ext_ExtensionsManager::singleton()->getExtensionById('ekstera'));
    }
    
    /**
     * Get a reference to the Ekstera extension.
     * 
     * @return common_ext_Extension
     */
    private function getExtension()
    {
        return $this->extension;
    }
    
    /**
     * Set a reference to the Ekstera extension.
     * 
     * @param common_ext_Extension $extension
     */
    private function setExtension(common_ext_Extension $extension)
    {
        $this->extension = $extension;
    }
    
    /**
     * Get the Next Item by asking the GloomRAT service.
     * 
     * @param string $sessionId The identifier of the test session.
     * @param string $candidateId The identifier of the candidate.
     * @param string $lastItemId The last taken item identifier (if any).
     * @param string $lastItemResponse The response given to the last item (if any).
     * @param string $lastItemScore The score to the last Item taken by the candidate (optional for the first item to be taken).
     * @return string The identifier of the next item to be taken or an empty string if it's the end of the test.
     */
    public function getNextItem($sessionId, $candidateId, $lastItemId = '', $lastItemResponse = '', $lastItemScore = '')
    {
        $url = $this->getEndPoint() . '/next';
        $curl = curl_init($url);
        
        $body  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        
        if (empty($lastItemId) === false) {
            $body .= '<nextRequest xmlns="http://www.taotesting.com/xsd/gloomRatv1p0">' . "\n";
            $body .= '<sessionID>' . $sessionId . '</sessionID>';
            $body .= '<itemID>' . $lastItemId . '</itemID>' . "\n";
            $body .= '<score>' . intval($lastItemScore) . '</score>' . "\n";
            $body .= '<response>' . $lastItemResponse . '</response>' . "\n";
            $body .= '</nextRequest>';
        } else {
            $body .= '<initRequest xmlns="http://www.taotesting.com/xsd/gloomRatv1p0">' . "\n";
            $body .= '<sessionID>' . $sessionId . '</sessionID>' . "\n";
            $body .= '<candidateID>' . $candidateId . '</candidateID>' . "\n";
            $body .= '</initRequest>';
        }
        
        $user = $this->getUser();
        $password = $this->getPassword();
        $timeout = $this->getHttpTimeout();
        
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->getHttpTimeout());
        curl_setopt($curl, CURLOPT_USERPWD, $this->getUser() . ':' . $this->getPassword());
        
        $response = curl_exec($curl);
        curl_close($curl);
        
        return $this->getNextItemIdFromResponse($response);
    }
    
    /**
     * Get the GloomRAT end point to be used from the configuration.
     * 
     * @return string
     */
    private function getEndPoint()
    {
        return $this->getExtension()->getConfig('remote.routing_endpoint');
    }
    
    /**
     * Get the HTTP timeout time from the configuration.
     * 
     * @return string
     */
    private function getHttpTimeout()
    {
        return $this->getExtension()->getConfig('remote.routing_timeout');
    }
    
    /**
     * Get the user name to be used to authenticate against GloomRAT.
     * 
     * @return string
     */
    private function getUser()
    {
        return $this->getExtension()->getConfig('remote.routing_user');
    }
    
    /**
     * Get the password to be used to authenticate against GloomRAT.
     * 
     * @return string
     */
    private function getPassword()
    {
        return $this->getExtension()->getConfig('remote.routing_password');
    }

    /**
     * Extract the item identifier from the string XML response payload.
     * 
     * @param string $response
     * @return string
     */
    private function getNextItemIdFromResponse($response)
    {
        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->loadXML($response);
        return $doc->getElementsByTagName('nextItemID')->item(0)->nodeValue;
    }
}
