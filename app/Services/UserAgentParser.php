<?php

namespace App\Services;

class UserAgentParser
{
    public $regexExp = '([0-9]+(?:\.[0-9]+)?)';
    protected string $userAgent;
    public function __construct(string $userAgent = '')
    { $this->userAgent = $userAgent; }

    public function setUserAgent(string $userAgent): void
    { $this->userAgent = $userAgent; }

    public function getBrowser(): string
    {
        $browsers = [
            '/edg/i' => 'Edge',
            '/chrome|chromium|crios/i' => 'Chrome',
            '/firefox|fxios/i' => 'Firefox',
            '/safari/i' => 'Safari',
            '/opera|opr\//i' => 'Opera',
            '/msie|trident/i' => 'Internet Explorer',
        ];

        foreach ($browsers as $pattern => $name) {
            if (preg_match($pattern, $this->userAgent)) {
                return $name;
            }
        }

        return 'Unknown';
    }

    public function getBrowserVersion(): string
    {
        $patterns = [
            '/edg\/'.$this->regexExp.'/i',
            '/chrome\/'.$this->regexExp.'/i',
            '/firefox\/'.$this->regexExp.'/i',
            '/version\/'.$this->regexExp.'/i',
            '/opera\/'.$this->regexExp.'/i',
            '/msie '.$this->regexExp.'/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $this->userAgent, $matches)) {
                return $matches[1];
            }
        }

        return 'Unknown';
    }

    public function getPlatform(): string
    {
        $platforms = [
            '/windows nt 10/i' => 'Windows 10',
            '/windows nt 11/i' => 'Windows 11',
            '/windows nt 6.3/i' => 'Windows 8.1',
            '/windows nt 6.2/i' => 'Windows 8',
            '/windows nt 6.1/i' => 'Windows 7',
            '/windows/i' => 'Windows',
            '/macintosh|mac os x/i' => 'Mac OS',
            '/mac_powerpc/i' => 'Mac OS',
            '/linux/i' => 'Linux',
            '/ubuntu/i' => 'Ubuntu',
            '/android/i' => 'Android',
            '/iphone/i' => 'iPhone',
            '/ipad/i' => 'iPad',
            '/ipod/i' => 'iPod',
        ];

        foreach ($platforms as $pattern => $name) {
            if (preg_match($pattern, $this->userAgent)) {
                return $name;
            }
        }

        return 'Unknown';
    }

    public function getPlatformVersion(): string
    {
        $patterns = [
            '/windows nt ([0-9.]+)/i',
            '/mac os x ([0-9._]+)/i',
            '/android ([0-9.]+)/i',
            '/os ([0-9_]+) like mac/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $this->userAgent, $matches)) {
                return str_replace('_', '.', $matches[1]);
            }
        }

        return 'Unknown';
    }

    public function getDevice(): string
    {
        if (preg_match('/ipad/i', $this->userAgent)) {
            return 'iPad';
        }

        if (preg_match('/iphone/i', $this->userAgent)) {
            return 'iPhone';
        }

        if (preg_match('/ipod/i', $this->userAgent)) {
            return 'iPod';
        }

        if (preg_match('/android/i', $this->userAgent)) {
            // Try to get device name from Android user agent
            if (preg_match('/;\s*([^;]+)\s+build/i', $this->userAgent, $matches)) {
                return trim($matches[1]);
            }
            return 'Android Device';
        }

        return 'Unknown';
    }

    public function isMobile(): bool
    {
        return (bool) preg_match(
            '/mobile|android|iphone|ipod|opera mini/i',
            $this->userAgent
        );
    }

    public function isTablet(): bool
    {
        return (bool) preg_match('/ipad|android(?!.*mobile)|tablet/i', $this->userAgent);
    }

    public function isDesktop(): bool
    {
        return !$this->isMobile() && !$this->isTablet() && !$this->isRobot();
    }

    public function isRobot(): bool
    {
        $robots = [ // check for crawlers list this might not work also
            'bot', 'crawler', 'spider', 'scraper', 'curl', 'wget',
            'googlebot', 'bingbot', 'yahoo', 'facebookexternalhit'
        ];

        $userAgentLower = strtolower($this->userAgent);
        
        foreach ($robots as $robot) {
            if (str_contains($userAgentLower, $robot)) {
                return true;
            }
        }

        return false;
    }

    public function getDeviceType(): string
    {
        if ($this->isRobot()) {
            return 'Robot';
        }

        if ($this->isTablet()) {
            return 'Tablet';
        }

        if ($this->isMobile()) {
            return 'Mobile';
        }

        if ($this->isDesktop()) {
            return 'Desktop';
        }

        return 'Unknown';
    }

    public function getInfo(): array
    {
        return [
            'type' => $this->getDeviceType(),
            'name' => $this->getDevice(),
            'platform' => $this->getPlatform(),
            'platform_version' => $this->getPlatformVersion(),
            'browser' => $this->getBrowser(),
            'browser_version' => $this->getBrowserVersion(),
            'is_mobile' => $this->isMobile(),
            'is_tablet' => $this->isTablet(),
            'is_desktop' => $this->isDesktop(),
            'is_robot' => $this->isRobot(),
        ];
    }
}
