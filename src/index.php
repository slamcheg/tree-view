<?php
use Application\App;

require_once "bootstrap.php";
$config = require_once "config/local.php";

$application = new App($config);

$results = (new \Application\Components\QueryBuilder())
    ->select(['*'])
    ->from('client')
    ->join('client_user', 'client_id', 'LEFT')
    ->join('user', 'user_id', 'LEFT')
    ->join('user_info', 'user_id', 'LEFT')
    ->build()
    ->execute($application->getConnection()->db);

foreach ($results as $result) {
    print_r(\Application\Helpers\ArrayHelper::toArray($result, [
        'client_id',
        'unique_number',
        'user' => [
            'name',
            'info' => [
                'phone',
                'address'
            ]
        ]
    ]));
}
