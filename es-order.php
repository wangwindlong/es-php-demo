<?php
/**
 * Created by PhpStorm.
 * User: wangyl
 * Date: 2019-03-22
 * Time: 15:35
 */

use Elasticsearch\ClientBuilder;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

require 'vendor/autoload.php';

$hosts = [
    'localhost'
//    '192.168.0.187:9200',         // IP + Port
//    '192.168.0.187',              // Just IP
//    'mydomain.server.com:9201', // Domain + Port
//    'mydomain2.server.com',     // Just Domain
//    'https://localhost',        // SSL to localhost
//    'https://192.168.1.3:9200'  // SSL to IP + Port
];
//$logger = ClientBuilder::defaultLogger('/Users/wangyl/code/php/workshop/mono.log', Logger::INFO);
$logger = new Logger('name');
$logger->pushHandler(new StreamHandler('/Users/wangyl/code/php/workshop/mono.log', Logger::INFO));
$client = ClientBuilder::create()->setLogger($logger)// Instantiate a new ClientBuilder
->setHosts($hosts)// Set the hosts
->build();


function getFilterMap() {
    $mustMap = array();
    $mustMap[] = array("isshow" => 1);
    return $mustMap;
}

function getCloudMap($keyword) {
    $matchMap = array();
//    $matchMap['orderno'] = $keyword;
    $matchMap['username'] = $keyword;
    return $matchMap;
}

//$client = ClientBuilder::create()->build();
$params = [
    'index' => 'order_index',//
//    'type' => '_doc',
//    '_role_name' => '测试role改变2',
    'body' => [
        'query' => [
            'bool' => [
                'filter' => ['term' => ["isshow" => true]],
                'should' => ['match' => getCloudMap('龙')]
            ]
//            'match' => [
//                '_role_name' => '测试role改变2'
//            ]
        ]
    ]
//['_role_name' => '测试role改变2']
];

$response = $client->search($params);
print_r($response);
$milliseconds = $response['took'];
$maxScore = $response['hits']['max_score'];

$score = $response['hits']['hits'][0]['_score'];
$doc = $response['hits']['hits'][0]['_source'];
print_r('$milliseconds=' . $milliseconds . ' $maxScore=' . $maxScore . ' $score=' . $score . ' $doc=');
print_r($doc);