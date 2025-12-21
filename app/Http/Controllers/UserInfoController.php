<?php

namespace App\Http\Controllers;

use App\Services\UserAgentParser;
use Illuminate\Http\Request;

class UserInfoController extends Controller
{
    /**
     * Get user's IP address and device information.
     */
    public function info(Request $request)
    {
        $parser = new UserAgentParser($request->header('User-Agent'));
        
        $info = [
            'ip' => $this->getClientIp($request),
            'device' => $parser->getInfo(),
            'user_agent' => $request->header('User-Agent'),
            'languages' => $request->getLanguages(),
        ];

        return response()->json($info);
    }

    /**
     * Get the real client IP address.
     */
    private function getClientIp(Request $request): string
    {
        // Check for proxies and get real IP
        if ($request->header('CF-Connecting-IP')) {
            return $request->header('CF-Connecting-IP'); 
        }
        
        if ($request->header('X-Real-IP')) {
            return $request->header('X-Real-IP');
        }
        
        if ($request->header('X-Forwarded-For')) {
            $ips = explode(',', $request->header('X-Forwarded-For'));
            return trim($ips[0]);
        }
        
        return $request->ip();
    }
}
