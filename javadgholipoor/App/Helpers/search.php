<?php

use LaraBase\Helpers\MobileDetect;
use LaraBase\Posts\Models\Search;

function addSearch($keyword, $count)
{
    if (!isset($_GET['noLog'])) {

        $mobileDetected = new MobileDetect();

        if(!empty($keyword)) {
            $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
            $bots = [
                'bot',
                'checkmarknetwork'
            ];
            $addSearch = true;
            foreach ($bots as $bot) {
                if (strpos($agent, $bot)) {
                    $addSearch = false;
                    break;
                }
            }
            if ($addSearch) {
                Search::create([
                    'user_id' => (auth()->check() ? auth()->id() : null),
                    'os' => ($mobileDetected->isMobile() ? 'mobile' : ($mobileDetected->isTablet() ? 'tablet' : 'desktop')),
                    'keyword' => $keyword,
                    'ip' => ip(),
                    'url' => url()->full(),
                    'count' => $count,
                    'check' => ($count > 0 ? '1' : '0'),
                    'server' => json_encode($_SERVER),
                    'agent' => $mobileDetected->getUserAgent()
                ]);
            }
        }
    }
}
