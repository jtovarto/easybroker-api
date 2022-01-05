<?php

namespace EasyBroker;

require_once __DIR__.'/EasyBrokerProperties.php';

//Manage the amount of properties to obtain
$limit = 5;

//Create the instance
$apiProperties = new EasyBrokerProperties;

$apiProperties->getProperties(['limit' => $limit]);
$apiProperties->printProperties();

//Test that the records obtained have been obtained
count($apiProperties->properties) === $limit
    ? print('The test has been successful')
    : print('The test has failed');
