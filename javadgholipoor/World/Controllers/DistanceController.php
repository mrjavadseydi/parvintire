<?php

namespace LaraBase\World\Controllers;

use LaraBase\CoreController;
use LaraBase\World\Models\Distance;

class DistanceController extends CoreController {
    
    private $headers = [];
    private $output = "json";
    private $origin = null;
    private $destination = null;
    private $key = null;
    private $url = null;
    private $api = 'neshan';
    public $apis = [
        'google' => [
            'key'   => 'AIzaSyCKGoA1RHC7ZfCsYBJOHqwm389qoLehmNk',
            'url'   => 'https://maps.googleapis.com/maps/api/directions/'
        ],
        'neshan' => [
            'key'   => 'service.1PFcDc7VTVhmZYxCwnagdyaVD9ZLp4kqUZCxcKt6',
            'url'   => 'https://api.neshan.org/v2/direction?'
        ]
    ];
    
    public function __construct() {
       $this->key = $this->apis[$this->api]['key'];
       $this->url = $this->apis[$this->api]['url'];
       
       $configMethod = $this->api."Config";
       if (method_exists($this, $configMethod))
           $this->$configMethod();
    }
    
    public function index() {
    
        return;
        $cUrl = curl_init();
    
        curl_setopt_array(
            $cUrl, array(
            CURLOPT_HTTPHEADER     => [
                "Api-Key: service.rDGHWUZ77kAZUjhxNOSPTNzRdmuuFoE72kOYfO5n"
            ],
            CURLOPT_URL            => "https://api.neshan.org/v1/distance-matrix?origins=32.878959914669394,59.21509891748428&destinations=34.006374,58.157130|34.102359482984184,58.29445577688557|32.8553553,59.2919588",
            CURLOPT_POSTFIELDS     => [],
            CURLOPT_POST           => false,
            CURLOPT_RETURNTRANSFER => true,
        ));
    
        if (!curl_error($cUrl)) {
            $result = curl_exec($cUrl);
            curl_close($cUrl);
            dd($result);
        }
    
        return [
            'result'  => false,
            'message' => 'curl error'
        ];
        
//        $distance = $this->coordinateToPost('32.78106532556652', '58.89184489048489', PostLocation::where('post_id', 28)->first());
//        dd($distance);
        //        $this->neshanTest();
    }
    
    public function reportNotCatchedProvinceToPost() {
        $publishedPostIds = Post::published()->pluck('id')->toArray();
        $distancesPostIds = Distance::where(['from_type' => '1', 'to_type' => '4'])->pluck('to')->toArray();
        $notCatchedPosts = array_diff($publishedPostIds, array_intersect($distancesPostIds, $publishedPostIds));
        foreach ($notCatchedPosts as $postId) {
            $post = Post::where('id', $postId)->first();
            $oldNeedChange = $post->getMeta('needChange');
            $message = '';
            if ($oldNeedChange != null) {
                $message .= $oldNeedChange->value . '<br>';
            }
            
            $message .= "فاصله تا مرکز استان ثبت نشده است.";
            $post->updateMeta('needChange', $message);
            $post->update(['verify_status' => 'needChange']);
        }
        $count = count($notCatchedPosts);
        Debug::string("فاصله {$count} مطلب تا مرکز استان ثبت نشده است.")->tags([
            'distance', 'provinceToPostDistance', 'notCatchDistance'
        ])->send(true);
    }
    
    public function reportNotCatchedCityToPost() {
        $publishedPostIds = Post::published()->pluck('id')->toArray();
        $distancesPostIds = Distance::where(['from_type' => '2', 'to_type' => '4'])->pluck('to')->toArray();
        $notCatchedPosts = array_diff($publishedPostIds, array_intersect($distancesPostIds, $publishedPostIds));
        foreach ($notCatchedPosts as $postId) {
            $post = Post::where('id', $postId)->first();
            $oldNeedChange = $post->getMeta('needChange');
            $message = '';
            if ($oldNeedChange != null) {
                $message .= $oldNeedChange->value . '<br>';
            }
            $message .= "فاصله تا مرکز شهرستان ثبت نشده است.";
            $post->updateMeta('needChange', $message);
            $post->update(['verify_status' => 'needChange']);
        }
        $count = count($notCatchedPosts);
        Debug::string("فاصله {$count} مطلب تا مرکز شهرستان ثبت نشده است.")->tags([
            'distance', 'cityToPostDistance', 'notCatchDistance'
        ])->send(true);
    }
    
    public function reportNotCatchedTownToPost() {
        $publishedPostIds = Post::published()->pluck('id')->toArray();
        $distancesPostIds = Distance::where(['from_type' => '3', 'to_type' => '4'])->pluck('to')->toArray();
        $notCatchedPosts = array_diff($publishedPostIds, array_intersect($distancesPostIds, $publishedPostIds));
        foreach ($notCatchedPosts as $postId) {
            $post = Post::where('id', $postId)->first();
            $oldNeedChange = $post->getMeta('needChange');
            $message = '';
            if ($oldNeedChange != null) {
                $message .= $oldNeedChange->value . '<br>';
            }
            $message .= "فاصله تا مرکز شهر ثبت نشده است.";
            $post->updateMeta('needChange', $message);
            $post->update(['verify_status' => 'needChange']);
        }
        $count = count($notCatchedPosts);
        Debug::string("فاصله {$count} مطلب تا مرکز شهر ثبت نشده است.")->tags([
            'distance', 'cityToPostDistance', 'notCatchDistance'
        ])->send(true);
    }
    
    public function provinceToProvincePost() { // فاصله مرکز استان به پست های همان استان
    
        $oldProvince = Option::where([
            'key' => 'catchingDistancesProvinceToPostProvince',
        ])->pluck('value')->toArray();

        $province = Province::whereNotIn('id', $oldProvince)->first();
        
        if ($province != null) {
            
            $provinceId = $province->id;
            
            $catched = Distance::where([
                'from_type' => '1',
                'to_type'   => '4',
                'from'      => $provinceId,
            ])->pluck('to')->toArray();
            
            $notCatched = PostLocation::where('province_id', $provinceId)->whereNotIn('post_id', $catched)->get();
            foreach ($notCatched as $location) {
                
                $this->setOrigin(floatval(str_replace('&rlm; ', '', $province->latitude)) . ',' . floatval(str_replace('&rlm; ', '', $province->longitude)));
                $this->setDestination(floatval(str_replace('&rlm; ', '', $location->latitude)) . ',' . floatval(str_replace('&rlm; ', '', $location->longitude)));
    
                $data = [
                    'from'      => $provinceId,
                    'to'        => $location->post_id,
                    'from_type' => '1',
                    'to_type'   => '4',
                ];
                
                if (!Distance::where($data)->exists()) {
                    
                    $response = $this->request();
                    if (isJson($response)) {
                        $response = json_decode($response);
                        if ($response->status == 'OK') {
                            $this->googleSave($response, $data);
                        }
                    }
                    
                }
                
            }
            
            Option::create([
                'key'   => 'catchingDistancesProvinceToPostProvince',
                'value' => $provinceId
            ]);
            
            Debug::code([
                'key'       => 'کش کردن مسیر استان به جاذبه های استان',
                'province'  => "استان {$province->name}",
                'count'     => $notCatched->count(),
            ])->tags([
                'catch',
                'provinceToPostProvince'
            ])->send(true);
            
        }
    }
    
    public function CityToCityPost() { // فاصله مرکز شهرستان به پست های همان شهرستان
    
        $key = 'catchingDistancesCityToCityPost';
        $old = Option::where('key', $key)->pluck('value')->toArray();
        $getOlds = City::whereNotIn('id', $old)->first();
        
        
        if ($getOlds != null) {
            
            $id = $getOlds->id;
            
            $catched = Distance::where([
                'from_type' => '2',
                'to_type'   => '4',
                'from'      => $id,
            ])->pluck('to')->toArray();
            
            $notCatched = PostLocation::where('city_id', $id)->whereNotIn('post_id', $catched)->get();
            foreach ($notCatched as $location) {
                
                $this->setOrigin(floatval(str_replace('&rlm; ', '', $getOlds->latitude)) . ',' . floatval(str_replace('&rlm; ', '', $getOlds->longitude)));
                $this->setDestination(floatval(str_replace('&rlm; ', '', $location->latitude)) . ',' . floatval(str_replace('&rlm; ', '', $location->longitude)));
    
                $data = [
                    'from'      => $id,
                    'to'        => $location->post_id,
                    'from_type' => '2',
                    'to_type'   => '4',
                ];
    
                if (!Distance::where($data)->exists()) {
                    
                    $response = $this->request();
                    if (isJson($response)) {
                        $response = json_decode($response);
                        if ($response->status == 'OK') {
                            $this->googleSave($response, $data);
                        }
                    }
                    
                }
                
            }
            
            Option::create([
                'key'   => $key,
                'value' => $id
            ]);
            
            Debug::code([
                'province'  => "شهرستان {$getOlds->name}",
                'count'     => $notCatched->count(),
            ])->tags([
                'catch',
                'CityToCityPost',
                'city_id_' . $id
            ])->send(true);
            
        }
    }
    
    public function TownToTownPost() { // فاصله مرکز شهرستان به پست های همان شهرستان
    
        $key = 'catchingDistancesTownToTownPost';
        $old = Option::where('key', $key)->pluck('value')->toArray();
        $getOlds = Town::whereNotIn('id', $old)->first();
        
        if ($getOlds != null) {
            
            $id = $getOlds->id;
            
            $catched = Distance::where([
                'from_type' => '3',
                'to_type'   => '4',
                'from'      => $id,
            ])->pluck('to')->toArray();
            
            $notCatched = PostLocation::where('town_id', $id)->whereNotIn('post_id', $catched)->get();
            foreach ($notCatched as $location) {
                
                $this->setOrigin(floatval(str_replace('&rlm; ', '', $getOlds->latitude)) . ',' . floatval(str_replace('&rlm; ', '', $getOlds->longitude)));
                $this->setDestination(floatval(str_replace('&rlm; ', '', $location->latitude)) . ',' . floatval(str_replace('&rlm; ', '', $location->longitude)));
    
                $data = [
                    'from'      => $id,
                    'to'        => $location->post_id,
                    'from_type' => '3',
                    'to_type'   => '4',
                ];
    
                if (!Distance::where($data)->exists()) {
                    
                    $response = $this->request();
                    if (isJson($response)) {
                        $response = json_decode($response);
                        if ($response->status == 'OK') {
                            $this->googleSave($response, $data);
                        }
                    }
                    
                }
                
            }
            
            Option::create([
                'key'   => $key,
                'value' => $id
            ]);
            
            Debug::code([
                'province'  => "شهر {$getOlds->name}",
                'count'     => $notCatched->count(),
            ])->tags([
                'catch',
                'TownToTownPost',
                'town_id_' . $id
            ])->send(true);
            
        }
    }
    
    public function provinceToProvince() { // فاصله مرکز استان به استان های دیگر
        
        $provinces = [];
        $getProvinces = Province::all();
        foreach ($getProvinces as $getProvince) {
            $provinces[$getProvince->id] = "{$getProvince->latitude},{$getProvince->longitude}";
        }
        
        $all = [];
        foreach ($provinces as $i1 => $p1) {
            foreach ($provinces as $i2 => $p2) {
                if ($i1 < $i2) {
                    $all["{$i1}-{$i2}"] = [
                        'from'        => $i1,
                        'to'          => $i2,
                        'origin'      => $p1,
                        'destination' => $p2
                    ];
                }
            }
            
            unset($provinces[$i1]);
        }
        
        foreach ($all as $item) {
            $this->setOrigin($item['origin']);
            $this->setDestination($item['destination']);
            
            $data = [
                'from'      => $item['from'],
                'to'        => $item['to'],
                'from_type' => '1',
                'to_type'   => '1',
            ];
            
            if (!Distance::where($data)->exists()) {
                
                $response = $this->request();
                if (isJson($response)) {
                    $response = json_decode($response);
                    $this->googleSave($response, $data);
                }
                
            }
            
        }
        
        dd("success");
        
    }
    
    public function PostToPosts($postId, $posts, $idsWithotPluck = false) {
    
        $ids = [];
        if ($idsWithotPluck) {
            foreach ($posts as $post) {
                $ids[] = $post->id;
            }
        } else {
            $ids = $posts->pluck('id')->toArray();
        }
        
        $getDistances = Distance::where([
            'from_type' => '4',
            'to_type'   => '4',
        ])->where(function ($item) use ($postId) {
            $item->where('from', $postId);
            $item->orWhere('to', $postId);
        })->where(function ($item) use ($ids) {
            $item->whereIn('to', $ids);
            $item->orWhereIn('from', $ids);
        })->get();
    
        $distances = [];
        foreach ($getDistances as $getDistance) {
            $key = "{$getDistance->from}-{$getDistance->to}";
            if ($getDistance->from > $getDistance->to) {
                $key = "{$getDistance->to}-{$getDistance->from}";
            }
            $distances[$key] = convertDistance($getDistance->distance);
        }
    
        foreach ($ids as $id) {
            $min = $postId;
            $max = $id;
            if ($postId > $id) {
                $min = $id;
                $max = $postId;
            }
            
            $key = "{$min}-{$max}";
            
            if (!isset($distances[$key])) {
                
                $data = [
                    'from'      => $min,
                    'to'        => $max,
                    'from_type' => '4',
                    'to_type'   => '4',
                ];
    
                if (!Distance::where($data)->exists()) {
                    
                    $post = PostLocation::where('post_id', $postId)->first();
                    
                    if ($post != null) {
                        
                        $thisPost = PostLocation::where('post_id', $id)->first();
    
                        if ($thisPost != null) {
    
                            $this->setOrigin($this->latLng($post->latitude, $post->longitude));
                            $this->setDestination($this->latLng($thisPost->latitude, $thisPost->longitude));
    
                            $distances[$key] = '-';
                            if ($post->post_id != $thisPost->post_id) {
                                $response = $this->httpRequest();
                                if (isJson($response)) {
                                    $response = json_decode($response);
                                    $saveMethod = $this->api . "Save";
                                    if (method_exists($this, $saveMethod)) {
                                        $distances[$key] = convertDistance($this->$saveMethod($data, $response));
                                    }
                                }
                            }
                            
                        }
                        
                    }
                    
                }
                
            }
            
        }

        return $distances;
        
    }
    
    public function provinceToPostsDistances($provinceId, $postIds) {
        
        $distances = Distance::where([
            'from_type' => '1',
            'to_type'   => '4',
            'from'      => $provinceId,
        ])->whereIn('to', $postIds)->get();
        $distances = $distances->keyBy('to')->toArray();
        return $distances;
    }
    
    public function cityToPostsDistances($cityId, $postIds) {
        
        $distances = Distance::where([
            'from_type' => '2',
            'to_type'   => '4',
            'from'      => $cityId,
        ])->whereIn('to', $postIds)->get();
        $distances = $distances->keyBy('to')->toArray();
        return $distances;
    }
    
    public function townToPostsDistances($townId, $postIds) {
        
        $distances = Distance::where([
            'from_type' => '3',
            'to_type'   => '4',
            'from'      => $townId,
        ])->whereIn('to', $postIds)->get();
        
        $distances = $distances->keyBy('to')->toArray();
        return $distances;
    }
    
    public function coordinateToPost($latitude, $longitude, $post) {
        $this->setOrigin($this->latLng($latitude, $longitude));
        $this->setDestination($this->latLng($post->latitude, $post->longitude));
        $response = $this->httpRequest();
        if (isJson($response)) {
            $response = json_decode($response);
            if (isset($response->status)) {
                if ($response->status != 'ERROR') {
                    return '-';
                }
            }
            if (isset($response->routes)) {
                $leg = $response->routes[0]->legs[0];
                return $leg->distance->value;
            }
        }
        
        return '-';
    }
    
    public function latLng($lat, $lng) {
        return floatval(str_replace('&rlm; ', '', $lat)) . ',' . floatval(str_replace('&rlm; ', '', $lng));
    }
    
    public function googleSave($response, $args = []) {
        
        $parent = null;
        $summary = $response->routes[0]->summary;
        
        foreach ($response->routes[0]->legs as $leg) {
            
            $distance = $leg->distance->value;
            $duration = $leg->duration->value;
            
            $record = Distance::create([
                'from'      => $args['from'],
                'to'        => $args['to'],
                'from_type' => $args['from_type'],
                'to_type'   => $args['to_type'],
                'summary'   => $summary,
                'distance'  => $distance,
                'duration'  => $duration,
                'parent'    => $parent,
            ]);
            
            if ($record == null)
                $parent = $record->id;
            
        }
        
        return $response->routes[0]->legs[0]->distance->value;
        
    }
    
    public function setDestination($destination) {
        $this->destination = $destination;
    }
    
    public function setOrigin($origin) {
        $this->origin = $origin;
    }
    
    public function url() {
        
        $urlMethod = $this->api."Url";
        if (method_exists($this, $urlMethod))
            return $this->$urlMethod();
        
        return "{$this->url}{$this->output}?origin={$this->origin}&destination={$this->destination}&key={$this->key}";
    }
    
    public function request() {
        return file_get_contents($this->url());
    }
    
    public function cache($data, $origin, $destination) {
        
        $this->setOrigin($this->latLng($origin['latitude'], $origin['longitude']));
        $this->setDestination($this->latLng($destination['latitude'], $destination['longitude']));
        
        $response = $this->httpRequest();
        if (isJson($response)) {
            $response = json_decode($response);
            $saveMethod = $this->api . "Save";
            if (method_exists($this, $saveMethod))
                return $this->$saveMethod($data, $response);
        }
       
    }
    
    public function save($data, $route) {
        $old = Distance::where($data)->first();
        if ($old == null) {
            Distance::create(array_merge($data, [
                'summary'   => $route['summary'],
                'distance'  => $route['distance'],
            ]));
        } else {
            $old->update(array_merge($data, [
                'summary'   => $route['summary'],
                'distance'  => $route['distance'],
            ]));
        }
    }
    
    public function googleSave2() {
        if ($response->status == 'OK') {
        
            $leg      = $response->routes[0]->legs[0];
            $summary  = $response->routes[0]->summary;
            $distance = $leg->distance->value;
            $duration = $leg->duration->value;
        
            $old = Distance::where($data)->first();
            if ($old == null) {
            
                Distance::create(array_merge($data, [
                    'summary'   => $summary,
                    'distance'  => $distance,
                    'duration'  => $duration,
                ]));
            
            } else {
            
                $old->update(array_merge($data, [
                    'summary'   => $summary,
                    'distance'  => $distance,
                    'duration'  => $duration,
                ]));
            
            }
        
            return $response->routes[0]->legs[0]->distance->value;
        
        }
    }
    
    public function neshanSave($data, $response) {
    
        if (isset($response->status)) {
            if ($response->status == 'ERROR') {
                return false;
            }
        }
        
        if (isset($response->routes)) {
    
            $leg = $response->routes[0]->legs[0];
            $this->save($data, [
                'summary'  => $leg->summary,
                'distance' => $leg->distance->value
            ]);
        
            return $leg->distance->value;
            
        }
        
    }
    
    public function neshanConfig() {
        $this->setHeader(null, "Api-Key: {$this->key}");
    }
    
    public function neshanUrl() {
        return $this->url . "origin={$this->origin}&destination={$this->destination}";
    }
    
    public function setHeader($key, $value) {
        if ($key == null)
            $this->headers[] = $value;
        else
            $this->headers[$key] = $value;
    }
    
    public function neshanTest() {
        $this->setOrigin($this->latLng('38.09210042855369', '46.287404009523584'));
        $this->setDestination($this->latLng('32.649484644384145', '51.67216130713064'));
        echo $this->httpRequest();
    }
    
    public function httpRequest()
    {
        
        $cUrl = curl_init();
        
        curl_setopt_array(
            $cUrl, array(
            CURLOPT_HTTPHEADER     => $this->headers,
            CURLOPT_URL            => $this->url(),
            CURLOPT_POSTFIELDS     => [],
            CURLOPT_POST           => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 3
        ));
        
        if (!curl_error($cUrl)) {
            $result = curl_exec($cUrl);
            curl_close($cUrl);
            return $result;
        }
        
        return [
            'result'  => false,
            'message' => 'curl error'
        ];
        
    }
    
    
}
