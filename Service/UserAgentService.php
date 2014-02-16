<?php
/*
* This file is part of the UserAgentString bundle.
*
* (c) Andrés Montañez <andres@andresmontanez.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace AndresMontanez\UserAgentStringBundle\Service;

use AndresMontanez\UserAgentStringBundle\Entity\UserAgent;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * UserAgent Service
 *
 * @author Andrés Montañez <andres@andresmontanez.com>
 */
class UserAgentService
{
    /**
     * The processed UserAgent Definitions
     * @var array
     */
    protected $data;

    /**
     * Indicates if Robots are parsed
     * @var boolean
     */
    protected $parseRobots;

    /**
     * The current parsed UserAgent information
     * @var \AndresMontanez\UserAgentStringBundle\Entity\UserAgent
     */
    protected $currentUserAgent;

    /**
     * Request Stack for accessing the Master Request
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    protected $requestStack;

    /**
     * Constructor
     * @param RequestStack           $requestStack
     * @param UserAgentLoaderService $loader
     * @param string                 $sourceFile
     * @param boolean                $parseRobots
     */
    public function __construct(RequestStack $requestStack, UserAgentLoaderService $loader, $sourceFile, $parseRobots = false)
    {
        $this->requestStack = $requestStack;
        $this->parseRobots = $parseRobots;
        $this->data = $loader->load($sourceFile, $this->parseRobots);
    }

    /**
     * Parses a User Agent String and returns a UserAgent entity for access the parsed information
     * @param  string                                                 $userAgentString
     * @return \AndresMontanez\UserAgentStringBundle\Entity\UserAgent
     */
    public function parse($userAgentString)
    {
        $userAgent = new UserAgent();

        // Check if Robots are to be parsed
        if ($this->parseRobots === true) {
            $userAgentHash = sha1($userAgentString);
            if (isset($this->data['robots'][$userAgentHash])) {
                $robotData = $this->data['robots'][$userAgentHash];
                $userAgent->setType('Robot')
                          ->setFamily($robotData['family'])
                          ->setName($robotData['name'])
                          ->setUrl($robotData['url'])
                          ->setCompany($robotData['company'])
                          ->setCompanyUrl($robotData['company_url'])
                          ->setIcon($robotData['icon'])
                          ->setDeviceType(UserAgent::DEVICE_OTHER);

                return $userAgent;
            }
        }

        // Detect Browser by Regular Expression
        $browserId = null;
        foreach ($this->data['browsers_reg'] as $browserRegEx) {
            $info = null;
            if (@preg_match($browserRegEx['regstring'], $userAgentString, $info)) { // $info may contain version
                $browserId = $browserRegEx['browser_id'];
                break;
            }
        }

        // If browser was detected, fill data with definitions
        if ($browserId) {
            $browserData = $this->data['browsers'][$browserId];

            // Set Browser Type, if available
            if ($this->data['browser_types'][$browserData['type']]['type']) {
                $userAgent->setType($this->data['browser_types'][$browserData['type']]['type']);
            }

            // Set Browser Version, if available
            if (isset($info[1])) {
                $userAgent->setVersion($info[1]);
            }

            $userAgent->setName($browserData['name']);
            $userAgent->setUrl($browserData['url']);
            $userAgent->setCompany($browserData['company']);
            $userAgent->setCompanyUrl($browserData['url_company']);
            $userAgent->setIcon($browserData['icon']);
            $userAgent->setInfoUrl($browserData['browser_info_url']);
        }

        // Check if the Browser has a relation with an Operating System
        $osFound = false;
        if (isset($this->data['browsers_os'][$browserId])) {
            $osFound = true;
            $osData = $this->data['operating_systems'][$this->data['browsers_os'][$browserId]];

            $userAgent->setOperatingSystemFamily($osData['family']);
            $userAgent->setOperatingSystemName($osData['name']);
            $userAgent->setOperatingSystemUrl($osData['url']);
            $userAgent->setOperatingSystemCompany($osData['company']);
            $userAgent->setOperatingSystemCompanyUrl($osData['url_company']);
            $userAgent->setOperatingSystemIcon($osData['icon']);
        }

        // Detect Operating System by Regular Expression
        if (!$osFound) {
            foreach ($this->data['operating_systems_reg'] as $operatingSystemRegEx) {
                if (@preg_match($operatingSystemRegEx['regstring'], $userAgentString)) {
                    $osId = $operatingSystemRegEx['os_id'];
                    break;
                }
            }
        }

        // A valid Operating System was found
        if ($osId) {
            $osData = $this->data['operating_systems'][$osId];

            $userAgent->setOperatingSystemFamily($osData['family']);
            $userAgent->setOperatingSystemName($osData['name']);
            $userAgent->setOperatingSystemUrl($osData['url']);
            $userAgent->setOperatingSystemCompany($osData['company']);
            $userAgent->setOperatingSystemCompanyUrl($osData['url_company']);
            $userAgent->setOperatingSystemIcon($osData['icon']);
            $userAgent->setOperatingSystemInfoUrl($osData['os_info_url']);
        }

        // Detect Device by Regular Expression
        $deviceId = false;
        foreach ($this->data['devices_reg'] as $deviceRegEx) {
            if (@preg_match($deviceRegEx['regstring'], $userAgentString)) {
                $deviceId = $deviceRegEx['device_id'];
                break;
            }
        }

        // A valid Device wasn't found, infer by Browser Type
        if (!$deviceId) {
            if (in_array($userAgent->getType(), array('Other', 'Library', 'Validator', 'Useragent Anonymizer'))) {
                $deviceId = 1;
            } elseif (in_array($userAgent->getType(), array('Mobile Browser', 'Wap Browser'))) {
                $deviceId = 3;
            } else {
                $deviceId = 2;
            }
        }

        // Fill Device infomration
        $deviceData = $this->data['devices'][$deviceId];
        $userAgent->setDeviceId($deviceId);
        $userAgent->setDeviceType($deviceData['name']);
        $userAgent->setDeviceIcon($deviceData['icon']);
        $userAgent->setDeviceInfoUrl($deviceData['device_info_url']);

        return $userAgent;
    }

    /**
     * Returns the current UserAgent (from the Master Request)
     * @return \AndresMontanez\UserAgentStringBundle\Entity\UserAgent
     */
    public function getCurrent()
    {
        if ($this->currentUserAgent === null) {
            $this->currentUserAgent = $this->parse($this->requestStack->getMasterRequest()->headers->get('User-Agent'));
        }

        return $this->currentUserAgent;
    }

}
