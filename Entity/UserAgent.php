<?php
/*
* This file is part of the UserAgentString bundle.
*
* (c) Andrés Montañez <andres@andresmontanez.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace AndresMontanez\UserAgentStringBundle\Entity;

/**
 * UserAgent Entity for accessing the parsed information
 *
 * @author Andrés Montañez <andres@andresmontanez.com>
 */
class UserAgent
{
	const DEVICE_OTHER = 1;
	const DEVICE_DESKTOP = 2;
	const DEVICE_SMARTPHONE = 3;
	const DEVICE_TABLET = 4;
	const DEVICE_GAME_CONSOLE = 5;
	const DEVICE_SMART_TV = 6;
	const DEVICE_PDA = 7;
	const DEVICE_WERABLE = 8;

    protected $type;
    protected $family;
    protected $name;
    protected $version;
    protected $url;
    protected $company;
    protected $companyUrl;
    protected $icon;
    protected $infoUrl;
    protected $operatingSystemFamily;
    protected $operatingSystemName;
    protected $operatingSystemUrl;
    protected $operatingSystemCompany;
    protected $operatingSystemCompanyUrl;
    protected $operatingSystemIcon;
    protected $operatingSystemInfoUrl;
    protected $deviceId;
    protected $deviceType;
    protected $deviceIcon;
    protected $deviceInfoUrl;

    public function isDesktop()
    {
    	return ($this->getDeviceId() == self::DEVICE_DESKTOP);
    }

    public function isPhone()
    {
    	return ($this->getDeviceId() == self::DEVICE_SMARTPHONE);

    }

    public function isTablet()
    {
    	return in_array($this->getDeviceId(), array(self::DEVICE_TABLET, self::DEVICE_PDA));
    }

    public function isMobile()
    {
    	return in_array($this->getDeviceId(), array(self::DEVICE_SMARTPHONE, self::DEVICE_TABLET, self::DEVICE_WERABLE, self::DEVICE_PDA));
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getFamily()
    {
        return $this->family;
    }

    public function setFamily($family)
    {
        $this->family = $family;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function setCompany($company)
    {
        $this->company = $company;
        return $this;
    }

    public function getCompanyUrl()
    {
        return $this->companyUrl;
    }

    public function setCompanyUrl($companyUrl)
    {
        $this->companyUrl = $companyUrl;
        return $this;
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function setIcon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    public function getInfoUrl()
    {
        return 'http://user-agent-string.info' . $this->infoUrl;
    }

    public function setInfoUrl($infoUrl)
    {
        $this->infoUrl = $infoUrl;
        return $this;
    }

    public function getOperatingSystemFamily()
    {
        return $this->operatingSystemFamily;
    }

    public function setOperatingSystemFamily($operatingSystemFamily)
    {
        $this->operatingSystemFamily = $operatingSystemFamily;
        return $this;
    }

    public function getOperatingSystemName()
    {
        return $this->operatingSystemName;
    }

    public function setOperatingSystemName($operatingSystemName)
    {
        $this->operatingSystemName = $operatingSystemName;
        return $this;
    }

    public function getOperatingSystemUrl()
    {
        return $this->operatingSystemUrl;
    }

    public function setOperatingSystemUrl($operatingSystemUrl)
    {
        $this->operatingSystemUrl = $operatingSystemUrl;
        return $this;
    }

    public function getOperatingSystemCompany()
    {
        return $this->operatingSystemCompany;
    }

    public function setOperatingSystemCompany($operatingSystemCompany)
    {
        $this->operatingSystemCompany = $operatingSystemCompany;
        return $this;
    }

    public function getOperatingSystemCompanyUrl()
    {
        return $this->operatingSystemCompanyUrl;
    }

    public function setOperatingSystemCompanyUrl($operatingSystemCompanyUrl)
    {
        $this->operatingSystemCompanyUrl = $operatingSystemCompanyUrl;
        return $this;
    }

    public function getOperatingSystemIcon()
    {
        return $this->operatingSystemIcon;
    }

    public function setOperatingSystemIcon($operatingSystemIcon)
    {
        $this->operatingSystemIcon = $operatingSystemIcon;
        return $this;
    }

    public function getOperatingSystemInfoUrl()
    {
    	return 'http://user-agent-string.info' . $this->operatingSystemInfoUrl;
    }

    public function setOperatingSystemInfoUrl($operatingSystemInfoUrl)
    {
    	$this->operatingSystemInfoUrl = $operatingSystemInfoUrl;
    	return $this;
    }

    public function getDeviceId()
    {
    	return $this->deviceId;
    }

    public function setDeviceId($deviceId)
    {
    	$this->deviceId = $deviceId;
    	return $this;
    }

    public function getDeviceType()
    {
        return $this->deviceType;
    }

    public function setDeviceType($deviceType)
    {
        $this->deviceType = $deviceType;
        return $this;
    }

    public function getDeviceIcon()
    {
        return $this->deviceIcon;
    }

    public function setDeviceIcon($deviceIcon)
    {
        $this->deviceIcon = $deviceIcon;
        return $this;
    }

    public function getDeviceInfoUrl()
    {
        return 'http://user-agent-string.info' . $this->deviceInfoUrl;
    }

    public function setDeviceInfoUrl($deviceInfoUrl)
    {
        $this->deviceInfoUrl = $deviceInfoUrl;
        return $this;
    }

}
