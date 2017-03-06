<?php $o = array();

// ** THIS IS AN AUTO GENERATED FILE. DO NOT EDIT MANUALLY ** 

//==================== v1 ====================

$o['v1'] = array();

//==== v1 resources/{s0} ====

$o['v1']['resources/{s0}'] = array (
    'GET' => 
    array (
        'url' => 'resources/{id}',
        'className' => 'Luracast\\Restler\\Resources',
        'path' => 'resources',
        'methodName' => 'get',
        'arguments' => 
        array (
            'id' => 0,
        ),
        'defaults' => 
        array (
            0 => '',
        ),
        'metadata' => 
        array (
            'description' => '',
            'longDescription' => '',
            'access' => 'hybrid',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'name' => 'id',
                    'label' => 'Id',
                    'default' => '',
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'throws' => 
            array (
                0 => 
                array (
                    'code' => 500,
                    'reason' => 'RestException',
                ),
            ),
            'return' => 
            array (
                'type' => 
                array (
                    0 => 'null',
                    1 => 'stdClass',
                ),
                'description' => '',
            ),
            'url' => 'GET {id}',
            'category' => 'Framework',
            'package' => 'Restler',
            'author' => 
            array (
                0 => 
                array (
                    'email' => 'arul@luracast.com',
                    'name' => 'R.Arul Kumaran',
                ),
            ),
            'copyright' => '2010 Luracast',
            'license' => 'http://www.opensource.org/licenses/lgpl-license.php LGPL',
            'link' => 
            array (
                0 => 'http://luracast.com/products/restler/',
            ),
            'version' => '3.0.0rc5',
            'scope' => 
            array (
                '*' => 'Luracast\\Restler\\',
                'Text' => 'Luracast\\Restler\\Data\\Text',
                'Scope' => 'Luracast\\Restler\\Scope',
                'stdClass' => 'stdClass',
            ),
            'resourcePath' => 'resources/',
            'classDescription' => 'API Class to create Swagger Spec 1.1 compatible id and operation listing',
        ),
        'accessLevel' => 1,
    ),
);

//==== v1 resources ====

$o['v1']['resources'] = array (
    'GET' => 
    array (
        'url' => 'resources',
        'className' => 'Luracast\\Restler\\Resources',
        'path' => 'resources',
        'methodName' => 'index',
        'arguments' => 
        array (
        ),
        'defaults' => 
        array (
        ),
        'metadata' => 
        array (
            'description' => '',
            'longDescription' => '',
            'access' => 'hybrid',
            'return' => 
            array (
                'type' => 'stdClass',
                'description' => '',
                'children' => 
                array (
                ),
            ),
            'category' => 'Framework',
            'package' => 'Restler',
            'author' => 
            array (
                0 => 
                array (
                    'email' => 'arul@luracast.com',
                    'name' => 'R.Arul Kumaran',
                ),
            ),
            'copyright' => '2010 Luracast',
            'license' => 'http://www.opensource.org/licenses/lgpl-license.php LGPL',
            'link' => 
            array (
                0 => 'http://luracast.com/products/restler/',
            ),
            'version' => '3.0.0rc5',
            'scope' => 
            array (
                '*' => 'Luracast\\Restler\\',
                'Text' => 'Luracast\\Restler\\Data\\Text',
                'Scope' => 'Luracast\\Restler\\Scope',
                'stdClass' => 'stdClass',
            ),
            'resourcePath' => 'resources/',
            'classDescription' => 'API Class to create Swagger Spec 1.1 compatible id and operation listing',
            'param' => 
            array (
            ),
        ),
        'accessLevel' => 1,
    ),
);

//==== v1 resources/index ====

$o['v1']['resources/index'] = array (
    'GET' => 
    array (
        'url' => 'resources',
        'className' => 'Luracast\\Restler\\Resources',
        'path' => 'resources',
        'methodName' => 'index',
        'arguments' => 
        array (
        ),
        'defaults' => 
        array (
        ),
        'metadata' => 
        array (
            'description' => '',
            'longDescription' => '',
            'access' => 'hybrid',
            'return' => 
            array (
                'type' => 'stdClass',
                'description' => '',
                'children' => 
                array (
                ),
            ),
            'category' => 'Framework',
            'package' => 'Restler',
            'author' => 
            array (
                0 => 
                array (
                    'email' => 'arul@luracast.com',
                    'name' => 'R.Arul Kumaran',
                ),
            ),
            'copyright' => '2010 Luracast',
            'license' => 'http://www.opensource.org/licenses/lgpl-license.php LGPL',
            'link' => 
            array (
                0 => 'http://luracast.com/products/restler/',
            ),
            'version' => '3.0.0rc5',
            'scope' => 
            array (
                '*' => 'Luracast\\Restler\\',
                'Text' => 'Luracast\\Restler\\Data\\Text',
                'Scope' => 'Luracast\\Restler\\Scope',
                'stdClass' => 'stdClass',
            ),
            'resourcePath' => 'resources/',
            'classDescription' => 'API Class to create Swagger Spec 1.1 compatible id and operation listing',
            'param' => 
            array (
            ),
        ),
        'accessLevel' => 1,
    ),
);

//==== v1 resources/verifyaccess ====

$o['v1']['resources/verifyaccess'] = array (
    'GET' => 
    array (
        'url' => 'resources/verifyaccess',
        'className' => 'Luracast\\Restler\\Resources',
        'path' => 'resources',
        'methodName' => 'verifyAccess',
        'arguments' => 
        array (
            'route' => 0,
        ),
        'defaults' => 
        array (
            0 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Verifies that the requesting user is allowed to view the docs for this API',
            'longDescription' => '',
            'param' => 
            array (
                0 => 
                array (
                    'name' => 'route',
                    'type' => 'mixed',
                    'label' => 'Route',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'boolean',
                'description' => 'True if the user should be able to view this API\'s docs',
            ),
            'category' => 'Framework',
            'package' => 'Restler',
            'author' => 
            array (
                0 => 
                array (
                    'email' => 'arul@luracast.com',
                    'name' => 'R.Arul Kumaran',
                ),
            ),
            'copyright' => '2010 Luracast',
            'license' => 'http://www.opensource.org/licenses/lgpl-license.php LGPL',
            'link' => 
            array (
                0 => 'http://luracast.com/products/restler/',
            ),
            'version' => '3.0.0rc5',
            'scope' => 
            array (
                '*' => 'Luracast\\Restler\\',
                'Text' => 'Luracast\\Restler\\Data\\Text',
                'Scope' => 'Luracast\\Restler\\Scope',
                'stdClass' => 'stdClass',
            ),
            'resourcePath' => 'resources/',
            'classDescription' => 'API Class to create Swagger Spec 1.1 compatible id and operation listing',
        ),
        'accessLevel' => 3,
    ),
);

//==== v1 counters/excedeed ====

$o['v1']['counters/excedeed'] = array (
    'GET' => 
    array (
        'url' => 'counters/excedeed',
        'className' => 'Counters',
        'path' => 'counters',
        'methodName' => 'getExcedeed',
        'arguments' => 
        array (
            'resourceNameFilter' => 0,
            'userNameFilter' => 1,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Get excedeed',
            'longDescription' => 'Get all excedeed counters',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => '[optional] Only retreive counters with resourceName containing that string (filter conbination is OR)',
                    'name' => 'resourceNameFilter',
                    'label' => 'Resource Name Filter',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
                1 => 
                array (
                    'type' => 'string',
                    'description' => '[optional] Only retreive counters with userName containing that string (filter conbination is OR)',
                    'name' => 'userNameFilter',
                    'label' => 'User Name Filter',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
            ),
            'url' => 'GET excedeed',
            'return' => 
            array (
                'type' => 'ExcedeedCounter',
                'description' => '',
                'children' => 
                array (
                    'maxValue' => 
                    array (
                        'name' => 'maxValue',
                        'type' => 'string',
                        'label' => 'Max Value',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'counterName' => 
                    array (
                        'name' => 'counterName',
                        'type' => 'string',
                        'description' => 'counter name',
                        'label' => 'Counter Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'resourceName' => 
                    array (
                        'name' => 'resourceName',
                        'type' => 'string',
                        'description' => 'controled resource (service)',
                        'label' => 'Resource Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'timeUnit' => 
                    array (
                        'name' => 'timeUnit',
                        'type' => 'string',
                        'description' => 'time unit for this counter (S: second, D: day, M: Month)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => 'S',
                                1 => 'D',
                                2 => 'M',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Time Unit',
                    ),
                    'timeValue' => 
                    array (
                        'name' => 'timeValue',
                        'type' => 'string',
                        'description' => 'timeValue reference time for tis counter',
                        'label' => 'Time Value',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'value' => 
                    array (
                        'name' => 'value',
                        'type' => 'int',
                        'description' => 'value counter value',
                        'label' => 'Value',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'userName' => 
                    array (
                        'name' => 'userName',
                        'type' => 'string',
                        'description' => 'userName relative user',
                        'label' => 'User Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'uri' => 
                    array (
                        'name' => 'uri',
                        'type' => 'url',
                        'description' => 'uri',
                        'label' => 'Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'counters/',
        ),
        'accessLevel' => 0,
    ),
);

//==== v1 counters/{s0} ====

$o['v1']['counters/{s0}'] = array (
    'GET' => 
    array (
        'url' => 'counters/{counterName}',
        'className' => 'Counters',
        'path' => 'counters',
        'methodName' => 'getOne',
        'arguments' => 
        array (
            'counterName' => 0,
        ),
        'defaults' => 
        array (
            0 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Get a counter',
            'longDescription' => 'Get a particular counter',
            'url' => 'GET :counterName',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'counter identifier',
                    'name' => 'counterName',
                    'label' => 'Counter Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'Counter',
                'description' => 'requested counter',
                'children' => 
                array (
                    'counterName' => 
                    array (
                        'name' => 'counterName',
                        'type' => 'string',
                        'description' => 'counter name',
                        'label' => 'Counter Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'resourceName' => 
                    array (
                        'name' => 'resourceName',
                        'type' => 'string',
                        'description' => 'controled resource (service)',
                        'label' => 'Resource Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'timeUnit' => 
                    array (
                        'name' => 'timeUnit',
                        'type' => 'string',
                        'description' => 'time unit for this counter (S: second, D: day, M: Month)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => 'S',
                                1 => 'D',
                                2 => 'M',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Time Unit',
                    ),
                    'timeValue' => 
                    array (
                        'name' => 'timeValue',
                        'type' => 'string',
                        'description' => 'timeValue reference time for tis counter',
                        'label' => 'Time Value',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'value' => 
                    array (
                        'name' => 'value',
                        'type' => 'int',
                        'description' => 'value counter value',
                        'label' => 'Value',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'userName' => 
                    array (
                        'name' => 'userName',
                        'type' => 'string',
                        'description' => 'userName relative user',
                        'label' => 'User Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'uri' => 
                    array (
                        'name' => 'uri',
                        'type' => 'url',
                        'description' => 'uri',
                        'label' => 'Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'counters/',
        ),
        'accessLevel' => 0,
    ),
    'DELETE' => 
    array (
        'url' => 'counters/{counterName}',
        'className' => 'Counters',
        'path' => 'counters',
        'methodName' => 'delete',
        'arguments' => 
        array (
            'counterName' => 0,
        ),
        'defaults' => 
        array (
            0 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Delete',
            'longDescription' => 'Delete a counter',
            'url' => 'DELETE :counterName',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'counter identifier to remove',
                    'name' => 'counterName',
                    'label' => 'Counter Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'Counter',
                'description' => '',
                'children' => 
                array (
                    'counterName' => 
                    array (
                        'name' => 'counterName',
                        'type' => 'string',
                        'description' => 'counter name',
                        'label' => 'Counter Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'resourceName' => 
                    array (
                        'name' => 'resourceName',
                        'type' => 'string',
                        'description' => 'controled resource (service)',
                        'label' => 'Resource Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'timeUnit' => 
                    array (
                        'name' => 'timeUnit',
                        'type' => 'string',
                        'description' => 'time unit for this counter (S: second, D: day, M: Month)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => 'S',
                                1 => 'D',
                                2 => 'M',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Time Unit',
                    ),
                    'timeValue' => 
                    array (
                        'name' => 'timeValue',
                        'type' => 'string',
                        'description' => 'timeValue reference time for tis counter',
                        'label' => 'Time Value',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'value' => 
                    array (
                        'name' => 'value',
                        'type' => 'int',
                        'description' => 'value counter value',
                        'label' => 'Value',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'userName' => 
                    array (
                        'name' => 'userName',
                        'type' => 'string',
                        'description' => 'userName relative user',
                        'label' => 'User Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'uri' => 
                    array (
                        'name' => 'uri',
                        'type' => 'url',
                        'description' => 'uri',
                        'label' => 'Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'counters/',
        ),
        'accessLevel' => 0,
    ),
    'PUT' => 
    array (
        'url' => 'counters/{counterName}',
        'className' => 'Counters',
        'path' => 'counters',
        'methodName' => 'update',
        'arguments' => 
        array (
            'counterName' => 0,
            'value' => 1,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Update a counter',
            'longDescription' => 'Update counter value',
            'url' => 'PUT :counterName',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'identifier',
                    'name' => 'counterName',
                    'label' => 'Counter Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
                1 => 
                array (
                    'type' => 'int',
                    'description' => 'value to set',
                    'name' => 'value',
                    'label' => 'Value',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'Counter',
                'description' => 'updated counter',
                'children' => 
                array (
                    'counterName' => 
                    array (
                        'name' => 'counterName',
                        'type' => 'string',
                        'description' => 'counter name',
                        'label' => 'Counter Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'resourceName' => 
                    array (
                        'name' => 'resourceName',
                        'type' => 'string',
                        'description' => 'controled resource (service)',
                        'label' => 'Resource Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'timeUnit' => 
                    array (
                        'name' => 'timeUnit',
                        'type' => 'string',
                        'description' => 'time unit for this counter (S: second, D: day, M: Month)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => 'S',
                                1 => 'D',
                                2 => 'M',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Time Unit',
                    ),
                    'timeValue' => 
                    array (
                        'name' => 'timeValue',
                        'type' => 'string',
                        'description' => 'timeValue reference time for tis counter',
                        'label' => 'Time Value',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'value' => 
                    array (
                        'name' => 'value',
                        'type' => 'int',
                        'description' => 'value counter value',
                        'label' => 'Value',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'userName' => 
                    array (
                        'name' => 'userName',
                        'type' => 'string',
                        'description' => 'userName relative user',
                        'label' => 'User Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'uri' => 
                    array (
                        'name' => 'uri',
                        'type' => 'url',
                        'description' => 'uri',
                        'label' => 'Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'counters/',
        ),
        'accessLevel' => 0,
    ),
);

//==== v1 counters ====

$o['v1']['counters'] = array (
    'GET' => 
    array (
        'url' => 'counters',
        'className' => 'Counters',
        'path' => 'counters',
        'methodName' => 'getAll',
        'arguments' => 
        array (
            'resourceName' => 0,
            'userName' => 1,
            'timeUnit' => 2,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
            2 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Get counters',
            'longDescription' => 'Get counters list',
            'url' => 'GET',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'related resource identifier filter',
                    'name' => 'resourceName',
                    'label' => 'Resource Name',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
                1 => 
                array (
                    'type' => 'string',
                    'description' => 'related user identifier filter',
                    'name' => 'userName',
                    'label' => 'User Name',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
                2 => 
                array (
                    'type' => 'string',
                    'description' => 'related time timeUnit (S: Second, D: Day, M: Month)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => 'S',
                            1 => 'D',
                            2 => 'M',
                        ),
                        'from' => 'query',
                    ),
                    'name' => 'timeUnit',
                    'label' => 'Time Unit',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'array',
                'description' => 'Counters list',
                'properties' => 
                array (
                    'type' => 'Counter',
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'counters/',
        ),
        'accessLevel' => 0,
    ),
);

//==== v1 groups ====

$o['v1']['groups'] = array (
    'GET' => 
    array (
        'url' => 'groups',
        'className' => 'Groups',
        'path' => 'groups',
        'methodName' => 'getAll',
        'arguments' => 
        array (
            'groupNameFilter' => 0,
            'groupDescritpionFilter' => 1,
            'order' => 2,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
            2 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Get groups list',
            'longDescription' => 'Get informations about groups',
            'url' => 'GET',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => '[optional] Only retreive groups with groupName containing that string (filter conbination is AND)',
                    'name' => 'groupNameFilter',
                    'label' => 'Group Name Filter',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
                1 => 
                array (
                    'type' => 'string',
                    'description' => '[optional] Only retreive groups with description containing that string (filter conbination is AND)',
                    'name' => 'groupDescritpionFilter',
                    'label' => 'Group Descritpion Filter',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
                2 => 
                array (
                    'type' => 'string',
                    'description' => '[optional] "SQL Like" order clause based on Group properties',
                    'name' => 'order',
                    'label' => 'Order',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'array',
                'description' => '',
                'properties' => 
                array (
                    'type' => 'Group',
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'groups/',
            'classDescription' => 'Groups management',
        ),
        'accessLevel' => 0,
    ),
    'POST' => 
    array (
        'url' => 'groups',
        'className' => 'Groups',
        'path' => 'groups',
        'methodName' => 'create',
        'arguments' => 
        array (
            'groupName' => 0,
            'description' => 1,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Create a group',
            'longDescription' => 'Add a new users group to the system',
            'url' => 'POST',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'group identifier',
                    'name' => 'groupName',
                    'label' => 'Group Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                1 => 
                array (
                    'type' => 'string',
                    'description' => '[Optional] group description',
                    'name' => 'description',
                    'label' => 'Description',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'Group',
                'description' => 'newly created Group',
                'children' => 
                array (
                    'groupName' => 
                    array (
                        'name' => 'groupName',
                        'type' => 'string',
                        'description' => 'groupName group identifier',
                        'label' => 'Group Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'description' => 
                    array (
                        'name' => 'description',
                        'type' => 'string',
                        'description' => 'description group description',
                        'label' => 'Description',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'uri' => 
                    array (
                        'name' => 'uri',
                        'type' => 'url',
                        'description' => 'uri',
                        'label' => 'Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'groups/',
            'classDescription' => 'Groups management',
        ),
        'accessLevel' => 0,
    ),
);

//==== v1 groups/{s0} ====

$o['v1']['groups/{s0}'] = array (
    'GET' => 
    array (
        'url' => 'groups/{groupName}',
        'className' => 'Groups',
        'path' => 'groups',
        'methodName' => 'getOne',
        'arguments' => 
        array (
            'groupName' => 0,
        ),
        'defaults' => 
        array (
            0 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Get a group',
            'longDescription' => 'Get informations about a group',
            'url' => 'GET :groupName',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'group identifer',
                    'name' => 'groupName',
                    'label' => 'Group Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'Group',
                'description' => '',
                'children' => 
                array (
                    'groupName' => 
                    array (
                        'name' => 'groupName',
                        'type' => 'string',
                        'description' => 'groupName group identifier',
                        'label' => 'Group Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'description' => 
                    array (
                        'name' => 'description',
                        'type' => 'string',
                        'description' => 'description group description',
                        'label' => 'Description',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'uri' => 
                    array (
                        'name' => 'uri',
                        'type' => 'url',
                        'description' => 'uri',
                        'label' => 'Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'groups/',
            'classDescription' => 'Groups management',
        ),
        'accessLevel' => 0,
    ),
    'DELETE' => 
    array (
        'url' => 'groups/{groupName}',
        'className' => 'Groups',
        'path' => 'groups',
        'methodName' => 'delete',
        'arguments' => 
        array (
            'groupName' => 0,
        ),
        'defaults' => 
        array (
            0 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Delete a group',
            'longDescription' => 'Remove a group from the system',
            'url' => 'DELETE :groupName',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'group identifer',
                    'name' => 'groupName',
                    'label' => 'Group Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'Group',
                'description' => 'deleted group',
                'children' => 
                array (
                    'groupName' => 
                    array (
                        'name' => 'groupName',
                        'type' => 'string',
                        'description' => 'groupName group identifier',
                        'label' => 'Group Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'description' => 
                    array (
                        'name' => 'description',
                        'type' => 'string',
                        'description' => 'description group description',
                        'label' => 'Description',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'uri' => 
                    array (
                        'name' => 'uri',
                        'type' => 'url',
                        'description' => 'uri',
                        'label' => 'Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'groups/',
            'classDescription' => 'Groups management',
        ),
        'accessLevel' => 0,
    ),
    'PUT' => 
    array (
        'url' => 'groups/{groupName}',
        'className' => 'Groups',
        'path' => 'groups',
        'methodName' => 'update',
        'arguments' => 
        array (
            'groupName' => 0,
            'description' => 1,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Update a group',
            'longDescription' => 'Update an particular group properties',
            'url' => 'PUT :groupName',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'group identifier',
                    'name' => 'groupName',
                    'label' => 'Group Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
                1 => 
                array (
                    'type' => 'string',
                    'description' => '[Optional] group description',
                    'name' => 'description',
                    'label' => 'Description',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'Group',
                'description' => 'newly created Group',
                'children' => 
                array (
                    'groupName' => 
                    array (
                        'name' => 'groupName',
                        'type' => 'string',
                        'description' => 'groupName group identifier',
                        'label' => 'Group Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'description' => 
                    array (
                        'name' => 'description',
                        'type' => 'string',
                        'description' => 'description group description',
                        'label' => 'Description',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'uri' => 
                    array (
                        'name' => 'uri',
                        'type' => 'url',
                        'description' => 'uri',
                        'label' => 'Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'groups/',
            'classDescription' => 'Groups management',
        ),
        'accessLevel' => 0,
    ),
);

//==== v1 groups/{s0}/members ====

$o['v1']['groups/{s0}/members'] = array (
    'GET' => 
    array (
        'url' => 'groups/{groupName}/members',
        'className' => 'Groups',
        'path' => 'groups',
        'methodName' => 'getMembers',
        'arguments' => 
        array (
            'groupName' => 0,
            'withLog' => 1,
            'userNameFilter' => 2,
            'firstNameFilter' => 3,
            'lastNameFilter' => 4,
            'emailAddressFilter' => 5,
            'entityFilter' => 6,
            'extraFilter' => 7,
            'order' => 8,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
            2 => NULL,
            3 => NULL,
            4 => NULL,
            5 => NULL,
            6 => NULL,
            7 => NULL,
            8 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Membership',
            'longDescription' => 'Get users of a particular group',
            'url' => 'GET :groupName/members',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'group identifier',
                    'name' => 'groupName',
                    'label' => 'Group Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
                1 => 
                array (
                    'type' => 'int',
                    'description' => '[optional] If set to 1 retreive only users with records in logs, If set to 1 retreive only users without records in logs',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'query',
                    ),
                    'name' => 'withLog',
                    'label' => 'With Log',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                2 => 
                array (
                    'type' => 'string',
                    'description' => '[optional] Only retreive user with userName containing that string (filter conbination is AND)',
                    'name' => 'userNameFilter',
                    'label' => 'User Name Filter',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
                3 => 
                array (
                    'type' => 'string',
                    'description' => '[optional] Only retreive user with first name containing that string (filter conbination is AND)',
                    'name' => 'firstNameFilter',
                    'label' => 'First Name Filter',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
                4 => 
                array (
                    'type' => 'string',
                    'description' => '[optional] Only retreive user with last name containing that string (filter conbination is AND)',
                    'name' => 'lastNameFilter',
                    'label' => 'Last Name Filter',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
                5 => 
                array (
                    'type' => 'string',
                    'description' => '[optional] Only retreive user with email address containing that string (filter conbination is AND)',
                    'name' => 'emailAddressFilter',
                    'label' => 'Email Address Filter',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
                6 => 
                array (
                    'type' => 'string',
                    'description' => '[optional] Only retreive user with entity containing that string (filter conbination is AND)',
                    'name' => 'entityFilter',
                    'label' => 'Entity Filter',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
                7 => 
                array (
                    'type' => 'string',
                    'description' => '[optional] Only retreive user with extra data containing that string (filter conbination is AND)',
                    'name' => 'extraFilter',
                    'label' => 'Extra Filter',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
                8 => 
                array (
                    'type' => 'string',
                    'description' => '[optional] "SQL Like" order clause based on User properties',
                    'name' => 'order',
                    'label' => 'Order',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'array',
                'description' => 'Group members',
                'properties' => 
                array (
                    'type' => 'User',
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'groups/',
            'classDescription' => 'Groups management',
        ),
        'accessLevel' => 0,
    ),
);

//==== v1 groups/{s0}/users/{s1} ====

$o['v1']['groups/{s0}/users/{s1}'] = array (
    'PUT' => 
    array (
        'url' => 'groups/{groupName}/users/{userName}',
        'className' => 'Groups',
        'path' => 'groups',
        'methodName' => 'addGroupMember',
        'arguments' => 
        array (
            'groupName' => 0,
            'userName' => 1,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Membership',
            'longDescription' => 'Add a particular user to a particular group',
            'url' => 'PUT :groupName/users/:userName',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'group identifier',
                    'name' => 'groupName',
                    'label' => 'Group Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
                1 => 
                array (
                    'type' => 'string',
                    'description' => 'user identifier',
                    'name' => 'userName',
                    'label' => 'User Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'Group',
                'description' => 'updated group',
                'children' => 
                array (
                    'groupName' => 
                    array (
                        'name' => 'groupName',
                        'type' => 'string',
                        'description' => 'groupName group identifier',
                        'label' => 'Group Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'description' => 
                    array (
                        'name' => 'description',
                        'type' => 'string',
                        'description' => 'description group description',
                        'label' => 'Description',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'uri' => 
                    array (
                        'name' => 'uri',
                        'type' => 'url',
                        'description' => 'uri',
                        'label' => 'Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'groups/',
            'classDescription' => 'Groups management',
        ),
        'accessLevel' => 0,
    ),
    'DELETE' => 
    array (
        'url' => 'groups/{groupName}/users/{userName}',
        'className' => 'Groups',
        'path' => 'groups',
        'methodName' => 'removeGroupMember',
        'arguments' => 
        array (
            'groupName' => 0,
            'userName' => 1,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Cancel membership',
            'longDescription' => 'Remove a particular user from a particular group',
            'url' => 'DELETE :groupName/users/:userName',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'group identifier',
                    'name' => 'groupName',
                    'label' => 'Group Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
                1 => 
                array (
                    'type' => 'string',
                    'description' => 'user idenfier',
                    'name' => 'userName',
                    'label' => 'User Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'Group',
                'description' => 'group updated',
                'children' => 
                array (
                    'groupName' => 
                    array (
                        'name' => 'groupName',
                        'type' => 'string',
                        'description' => 'groupName group identifier',
                        'label' => 'Group Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'description' => 
                    array (
                        'name' => 'description',
                        'type' => 'string',
                        'description' => 'description group description',
                        'label' => 'Description',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'uri' => 
                    array (
                        'name' => 'uri',
                        'type' => 'url',
                        'description' => 'uri',
                        'label' => 'Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'groups/',
            'classDescription' => 'Groups management',
        ),
        'accessLevel' => 0,
    ),
);

//==== v1 logs/{n0} ====

$o['v1']['logs/{n0}'] = array (
    'GET' => 
    array (
        'url' => 'logs/{id}',
        'className' => 'Logs',
        'path' => 'logs',
        'methodName' => 'getOne',
        'arguments' => 
        array (
            'id' => 0,
        ),
        'defaults' => 
        array (
            0 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Get a Log',
            'longDescription' => 'Get a particular log details',
            'url' => 'GET :id',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'int',
                    'description' => 'Log identifier',
                    'name' => 'id',
                    'label' => 'Id',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'Log',
                'description' => '',
                'children' => 
                array (
                    'id' => 
                    array (
                        'name' => 'id',
                        'type' => 'int',
                        'description' => 'id log identifier',
                        'label' => 'Id',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'message' => 
                    array (
                        'name' => 'message',
                        'type' => 'string',
                        'description' => 'message message Logged message',
                        'label' => 'Message',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'frontEndUri' => 
                    array (
                        'name' => 'frontEndUri',
                        'type' => 'uri',
                        'description' => 'front end uri invoked',
                        'label' => 'Front End Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'status' => 
                    array (
                        'name' => 'status',
                        'type' => 'int',
                        'description' => 'status HTTP Response status',
                        'label' => 'Status',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'serviceName' => 
                    array (
                        'name' => 'serviceName',
                        'type' => 'string',
                        'description' => 'serviceName service invoked',
                        'label' => 'Service Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'userName' => 
                    array (
                        'name' => 'userName',
                        'type' => 'string',
                        'description' => 'userName [optional] authentifed user',
                        'label' => 'User Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'timeStamp' => 
                    array (
                        'name' => 'timeStamp',
                        'type' => 'string',
                        'description' => 'timeStamp hit date in ISO 8601 full format',
                        'label' => 'Time Stamp',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'uri' => 
                    array (
                        'name' => 'uri',
                        'type' => 'url',
                        'description' => 'uri',
                        'label' => 'Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'logs/',
            'classDescription' => 'Logs management',
        ),
        'accessLevel' => 0,
    ),
);

//==== v1 logs ====

$o['v1']['logs'] = array (
    'GET' => 
    array (
        'url' => 'logs',
        'className' => 'Logs',
        'path' => 'logs',
        'methodName' => 'getAll',
        'arguments' => 
        array (
            'serviceName' => 0,
            'userName' => 1,
            'status' => 2,
            'message' => 3,
            'frontEndEndPoint' => 4,
            'from' => 5,
            'until' => 6,
            'offset' => 7,
            'order' => 8,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
            2 => NULL,
            3 => NULL,
            4 => NULL,
            5 => NULL,
            6 => NULL,
            7 => NULL,
            8 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Get Logs',
            'longDescription' => 'Get paginated logs',
            'url' => 'GET',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => '[optional] Only retreive logs with serviceName containing that string (filter conbination is AND)',
                    'name' => 'serviceName',
                    'label' => 'Service Name',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
                1 => 
                array (
                    'type' => 'string',
                    'description' => '[optional] Only retreive logs with userName containing that string (filter conbination is AND)',
                    'name' => 'userName',
                    'label' => 'User Name',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
                2 => 
                array (
                    'type' => 'int',
                    'description' => '[optional] Only retreive logs with HTTP return status equals to this parameter (filter conbination is AND)',
                    'name' => 'status',
                    'label' => 'Status',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
                3 => 
                array (
                    'type' => 'string',
                    'description' => '[optional] Only retreive logs with message containing that string (filter conbination is AND)',
                    'name' => 'message',
                    'label' => 'Message',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
                4 => 
                array (
                    'type' => 'string',
                    'description' => '[optional] Only retreive logs with frontEndEndPoint containing that string (filter conbination is AND)',
                    'name' => 'frontEndEndPoint',
                    'label' => 'Front End End Point',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
                5 => 
                array (
                    'type' => 'string',
                    'description' => '[optional] Only retreive logs from this date in ISO 8601 full format (filter conbination is AND)',
                    'name' => 'from',
                    'label' => 'From',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
                6 => 
                array (
                    'type' => 'string',
                    'description' => '[optional] Only retreive logs untill this date in ISO 8601 full format (filter conbination is AND)',
                    'name' => 'until',
                    'label' => 'Until',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
                7 => 
                array (
                    'type' => 'int',
                    'description' => 'page number',
                    'name' => 'offset',
                    'label' => 'Offset',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
                8 => 
                array (
                    'type' => 'string',
                    'description' => '[optional] "SQL Like" order clause based on Log properties',
                    'name' => 'order',
                    'label' => 'Order',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'LogsPage',
                'description' => '',
                'children' => 
                array (
                    'length' => 
                    array (
                        'name' => 'length',
                        'type' => 'int',
                        'description' => 'length total logs count',
                        'label' => 'Length',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'previous' => 
                    array (
                        'name' => 'previous',
                        'type' => 'uri',
                        'description' => 'previous link to previous page',
                        'label' => 'Previous',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'logs' => 
                    array (
                        'name' => 'logs',
                        'type' => 'array',
                        'description' => 'List of Networks (see /networks/{netId} resource for details)',
                        'properties' => 
                        array (
                            'type' => 'Log',
                            'required' => true,
                        ),
                        'label' => 'Logs',
                        'contentType' => 'Log',
                        'children' => 
                        array (
                            'id' => 
                            array (
                                'name' => 'id',
                                'type' => 'int',
                                'description' => 'id log identifier',
                                'label' => 'Id',
                                'properties' => 
                                array (
                                    'required' => true,
                                ),
                            ),
                            'message' => 
                            array (
                                'name' => 'message',
                                'type' => 'string',
                                'description' => 'message message Logged message',
                                'label' => 'Message',
                                'properties' => 
                                array (
                                    'required' => true,
                                ),
                            ),
                            'frontEndUri' => 
                            array (
                                'name' => 'frontEndUri',
                                'type' => 'uri',
                                'description' => 'front end uri invoked',
                                'label' => 'Front End Uri',
                                'properties' => 
                                array (
                                    'required' => true,
                                ),
                            ),
                            'status' => 
                            array (
                                'name' => 'status',
                                'type' => 'int',
                                'description' => 'status HTTP Response status',
                                'label' => 'Status',
                                'properties' => 
                                array (
                                    'required' => true,
                                ),
                            ),
                            'serviceName' => 
                            array (
                                'name' => 'serviceName',
                                'type' => 'string',
                                'description' => 'serviceName service invoked',
                                'label' => 'Service Name',
                                'properties' => 
                                array (
                                    'required' => true,
                                ),
                            ),
                            'userName' => 
                            array (
                                'name' => 'userName',
                                'type' => 'string',
                                'description' => 'userName [optional] authentifed user',
                                'label' => 'User Name',
                                'properties' => 
                                array (
                                    'required' => true,
                                ),
                            ),
                            'timeStamp' => 
                            array (
                                'name' => 'timeStamp',
                                'type' => 'string',
                                'description' => 'timeStamp hit date in ISO 8601 full format',
                                'label' => 'Time Stamp',
                                'properties' => 
                                array (
                                    'required' => true,
                                ),
                            ),
                            'uri' => 
                            array (
                                'name' => 'uri',
                                'type' => 'url',
                                'description' => 'uri',
                                'label' => 'Uri',
                                'properties' => 
                                array (
                                    'required' => true,
                                ),
                            ),
                        ),
                    ),
                    'next' => 
                    array (
                        'name' => 'next',
                        'type' => 'uri',
                        'description' => 'previous link to next page',
                        'label' => 'Next',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'logs/',
            'classDescription' => 'Logs management',
        ),
        'accessLevel' => 0,
    ),
);

//==== v1 nodes/{s0}/services ====

$o['v1']['nodes/{s0}/services'] = array (
    'GET' => 
    array (
        'url' => 'nodes/{nodeName}/services',
        'className' => 'Nodes',
        'path' => 'nodes',
        'methodName' => 'publishedServices',
        'arguments' => 
        array (
            'nodeName' => 0,
        ),
        'defaults' => 
        array (
            0 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Get node services',
            'longDescription' => 'Get services available on this node',
            'url' => 'GET :nodeName/services',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'Node identifier',
                    'name' => 'nodeName',
                    'label' => 'Node Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'array',
                'description' => 'services published on this node',
                'properties' => 
                array (
                    'type' => 'Service',
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'nodes/',
        ),
        'accessLevel' => 0,
    ),
);

//==== v1 nodes/{s0}/ca ====

$o['v1']['nodes/{s0}/ca'] = array (
    'DELETE' => 
    array (
        'url' => 'nodes/{nodeName}/ca',
        'className' => 'Nodes',
        'path' => 'nodes',
        'methodName' => 'removeCa',
        'arguments' => 
        array (
            'nodeName' => 0,
        ),
        'defaults' => 
        array (
            0 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Remove CA',
            'longDescription' => 'Remove certification autority certificate from a Node',
            'url' => 'DELETE :nodeName/ca',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'Node identifier',
                    'name' => 'nodeName',
                    'label' => 'Node Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'string',
                'description' => 'Certificate',
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'nodes/',
        ),
        'accessLevel' => 0,
    ),
    'GET' => 
    array (
        'url' => 'nodes/{nodeName}/ca',
        'className' => 'Nodes',
        'path' => 'nodes',
        'methodName' => 'getCa',
        'arguments' => 
        array (
            'nodeName' => 0,
        ),
        'defaults' => 
        array (
            0 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Get Certification authority',
            'longDescription' => 'Get Certification authority certificate',
            'url' => 'GET :nodeName/ca',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'node identifier',
                    'name' => 'nodeName',
                    'label' => 'Node Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'string',
                'description' => 'certificate',
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'nodes/',
        ),
        'accessLevel' => 0,
    ),
);

//==== v1 nodes/{s0}/chain ====

$o['v1']['nodes/{s0}/chain'] = array (
    'DELETE' => 
    array (
        'url' => 'nodes/{nodeName}/chain',
        'className' => 'Nodes',
        'path' => 'nodes',
        'methodName' => 'removeChain',
        'arguments' => 
        array (
            'nodeName' => 0,
        ),
        'defaults' => 
        array (
            0 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Remove Certication chain',
            'longDescription' => 'Remove intermediate certification autority certificate from a Node',
            'url' => 'DELETE :nodeName/chain',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'Node identifier',
                    'name' => 'nodeName',
                    'label' => 'Node Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'string',
                'description' => 'Certificate',
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'nodes/',
        ),
        'accessLevel' => 0,
    ),
    'POST' => 
    array (
        'url' => 'nodes/{nodeName}/chain',
        'className' => 'Nodes',
        'path' => 'nodes',
        'methodName' => 'uploadChain',
        'arguments' => 
        array (
            'nodeName' => 0,
        ),
        'defaults' => 
        array (
            0 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Upload Chain',
            'longDescription' => 'Upload intermediate certification authority certificates Expect Certificates as multipart/form-data; Uploaded File (files name collection name: files)',
            'url' => 'POST :nodeName/chain',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'identifier',
                    'name' => 'nodeName',
                    'label' => 'Node Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'nodes/',
            'return' => 
            array (
                'type' => 'array',
            ),
        ),
        'accessLevel' => 0,
    ),
    'GET' => 
    array (
        'url' => 'nodes/{nodeName}/chain',
        'className' => 'Nodes',
        'path' => 'nodes',
        'methodName' => 'getChain',
        'arguments' => 
        array (
            'nodeName' => 0,
        ),
        'defaults' => 
        array (
            0 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Get Certification chain',
            'longDescription' => 'Get intermediate certification authority certificated',
            'url' => 'GET :nodeName/chain',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'node identifier',
                    'name' => 'nodeName',
                    'label' => 'Node Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'string',
                'description' => 'certificate',
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'nodes/',
        ),
        'accessLevel' => 0,
    ),
);

//==== v1 nodes/{s0}/cert ====

$o['v1']['nodes/{s0}/cert'] = array (
    'DELETE' => 
    array (
        'url' => 'nodes/{nodeName}/cert',
        'className' => 'Nodes',
        'path' => 'nodes',
        'methodName' => 'removeCert',
        'arguments' => 
        array (
            'nodeName' => 0,
        ),
        'defaults' => 
        array (
            0 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Remove certificate',
            'longDescription' => 'Remove server certificate from a Node',
            'url' => 'DELETE :nodeName/cert',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'Node identifier',
                    'name' => 'nodeName',
                    'label' => 'Node Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'string',
                'description' => 'Certificate',
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'nodes/',
        ),
        'accessLevel' => 0,
    ),
    'POST' => 
    array (
        'url' => 'nodes/{nodeName}/cert',
        'className' => 'Nodes',
        'path' => 'nodes',
        'methodName' => 'uploadCert',
        'arguments' => 
        array (
            'nodeName' => 0,
        ),
        'defaults' => 
        array (
            0 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Upload certificate',
            'longDescription' => 'Upload server certificate Expect Certificates as multipart/form-data; Uploaded File (files name collection name: files)',
            'url' => 'POST :nodeName/cert',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'identifier',
                    'name' => 'nodeName',
                    'label' => 'Node Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'nodes/',
            'return' => 
            array (
                'type' => 'array',
            ),
        ),
        'accessLevel' => 0,
    ),
    'GET' => 
    array (
        'url' => 'nodes/{nodeName}/cert',
        'className' => 'Nodes',
        'path' => 'nodes',
        'methodName' => 'getCert',
        'arguments' => 
        array (
            'nodeName' => 0,
        ),
        'defaults' => 
        array (
            0 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Get server certificate',
            'longDescription' => 'Get server certificate',
            'url' => 'GET :nodeName/cert',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'node identifier',
                    'name' => 'nodeName',
                    'label' => 'Node Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'string',
                'description' => 'certificate',
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'nodes/',
        ),
        'accessLevel' => 0,
    ),
);

//==== v1 nodes/{s0}/privatekey ====

$o['v1']['nodes/{s0}/privatekey'] = array (
    'POST' => 
    array (
        'url' => 'nodes/{nodeName}/privatekey',
        'className' => 'Nodes',
        'path' => 'nodes',
        'methodName' => 'uploadPrivateKey',
        'arguments' => 
        array (
            'nodeName' => 0,
        ),
        'defaults' => 
        array (
            0 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Upload Private key',
            'longDescription' => 'Upload private key Expect private key as multipart/form-data; Uploaded File (files name collection name: files)',
            'url' => 'POST :nodeName/privatekey',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'identifier',
                    'name' => 'nodeName',
                    'label' => 'Node Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'nodes/',
            'return' => 
            array (
                'type' => 'array',
            ),
        ),
        'accessLevel' => 0,
    ),
    'DELETE' => 
    array (
        'url' => 'nodes/{nodeName}/privatekey',
        'className' => 'Nodes',
        'path' => 'nodes',
        'methodName' => 'removePrivateKey',
        'arguments' => 
        array (
            'nodeName' => 0,
        ),
        'defaults' => 
        array (
            0 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Remove private key',
            'longDescription' => 'Remove private key from a Node',
            'url' => 'DELETE :nodeName/privatekey',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'Node identifier',
                    'name' => 'nodeName',
                    'label' => 'Node Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'string',
                'description' => 'Certificate',
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'nodes/',
        ),
        'accessLevel' => 0,
    ),
    'GET' => 
    array (
        'url' => 'nodes/{nodeName}/privatekey',
        'className' => 'Nodes',
        'path' => 'nodes',
        'methodName' => 'getPrivateKey',
        'arguments' => 
        array (
            'nodeName' => 0,
        ),
        'defaults' => 
        array (
            0 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Get private key',
            'longDescription' => 'Get server private key',
            'url' => 'GET :nodeName/privatekey',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'node identifier',
                    'name' => 'nodeName',
                    'label' => 'Node Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'string',
                'description' => 'private key',
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'nodes/',
        ),
        'accessLevel' => 0,
    ),
);

//==== v1 nodes ====

$o['v1']['nodes'] = array (
    'GET' => 
    array (
        'url' => 'nodes',
        'className' => 'Nodes',
        'path' => 'nodes',
        'methodName' => 'getAll',
        'arguments' => 
        array (
            'nodeNameFilter' => 0,
            'nodeDescriptionFilter' => 1,
            'localIPFilter' => 2,
            'portFilter' => 3,
            'serverFQDNFilter' => 4,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
            2 => NULL,
            3 => NULL,
            4 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Get nodes',
            'longDescription' => 'Get all nodes',
            'url' => 'GET',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => '[optional] Only retreive nodes with nodeName address containing that string (filter conbination is AND)',
                    'name' => 'nodeNameFilter',
                    'label' => 'Node Name Filter',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
                1 => 
                array (
                    'type' => 'string',
                    'description' => '[optional] Only retreive nodes with nodeDescription address containing that string (filter conbination is AND)',
                    'name' => 'nodeDescriptionFilter',
                    'label' => 'Node Description Filter',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
                2 => 
                array (
                    'type' => 'string',
                    'description' => '[optional] Only retreive nodes with localIP address containing that string (filter conbination is AND)',
                    'name' => 'localIPFilter',
                    'label' => 'Local IPFilter',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
                3 => 
                array (
                    'type' => 'int',
                    'description' => '[optional] Only retreive nodes with listening on that port (filter conbination is AND)',
                    'name' => 'portFilter',
                    'label' => 'Port Filter',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
                4 => 
                array (
                    'type' => 'string',
                    'description' => 'Only retreive nodes with nodeName serverFQDN containing that string (filter conbination is AND)',
                    'name' => 'serverFQDNFilter',
                    'label' => 'Server FQDNFilter',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'array',
                'description' => 'Nodes list',
                'properties' => 
                array (
                    'type' => 'Node',
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'nodes/',
        ),
        'accessLevel' => 0,
    ),
    'POST' => 
    array (
        'url' => 'nodes',
        'className' => 'Nodes',
        'path' => 'nodes',
        'methodName' => 'create',
        'arguments' => 
        array (
            'nodeName' => 0,
            'serverFQDN' => 1,
            'localIP' => 2,
            'port' => 3,
            'isHTTPS' => 4,
            'nodeDescription' => 5,
            'isBasicAuthEnabled' => 6,
            'isCookieAuthEnabled' => 7,
            'additionalConfiguration' => 8,
            'apply' => 9,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
            2 => NULL,
            3 => NULL,
            4 => NULL,
            5 => NULL,
            6 => NULL,
            7 => NULL,
            8 => NULL,
            9 => 1,
        ),
        'metadata' => 
        array (
            'description' => 'Create',
            'longDescription' => 'Create and deploy a new Node',
            'url' => 'POST',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'Node identifier',
                    'name' => 'nodeName',
                    'label' => 'Node Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                1 => 
                array (
                    'type' => 'string',
                    'description' => 'Public server FQDN',
                    'name' => 'serverFQDN',
                    'label' => 'Server FQDN',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                2 => 
                array (
                    'type' => 'string',
                    'description' => 'Listening IP (IP, hostname or * for all available interfaces)',
                    'name' => 'localIP',
                    'label' => 'Local IP',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                3 => 
                array (
                    'type' => 'int',
                    'description' => 'port Listeing port',
                    'name' => 'port',
                    'label' => 'Port',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                4 => 
                array (
                    'type' => 'int',
                    'description' => 'Does this node use HTTPS? (O: no, 1: yes)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'isHTTPS',
                    'label' => 'Is HTTPS',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                ),
                5 => 
                array (
                    'type' => 'string',
                    'description' => 'Node description',
                    'name' => 'nodeDescription',
                    'label' => 'Node Description',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                6 => 
                array (
                    'type' => 'int',
                    'description' => 'Does this node handle basic authentication? (O: no, 1: yes)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'isBasicAuthEnabled',
                    'label' => 'Is Basic Auth Enabled',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                7 => 
                array (
                    'type' => 'int',
                    'description' => 'Does this not handel cookie based authentication? (O: no, 1: yes)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'isCookieAuthEnabled',
                    'label' => 'Is Cookie Auth Enabled',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                8 => 
                array (
                    'type' => 'string',
                    'description' => 'additionnal apache directive for this virtualHost/node',
                    'name' => 'additionalConfiguration',
                    'label' => 'Additional Configuration',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                9 => 
                array (
                    'type' => 'int',
                    'description' => 'Apply this configuration immediatly? (O: no, 1: yes)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'apply',
                    'label' => 'Apply',
                    'default' => 1,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'Node',
                'description' => 'newly created Node',
                'children' => 
                array (
                    'nodeName' => 
                    array (
                        'name' => 'nodeName',
                        'type' => 'string',
                        'description' => 'nodeName node identifier',
                        'label' => 'Node Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'nodeDescription' => 
                    array (
                        'name' => 'nodeDescription',
                        'type' => 'string',
                        'description' => 'nodeDescription description of this node',
                        'label' => 'Node Description',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'isHTTPS' => 
                    array (
                        'name' => 'isHTTPS',
                        'type' => 'int',
                        'description' => 'isHTTPS Does this node use HTTPS? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is HTTPS',
                    ),
                    'isBasicAuthEnabled' => 
                    array (
                        'name' => 'isBasicAuthEnabled',
                        'type' => 'int',
                        'description' => 'isBasicAuthEnabled Does this node handle basic authentication? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is Basic Auth Enabled',
                    ),
                    'iscookieAuthEnabled' => 
                    array (
                        'name' => 'iscookieAuthEnabled',
                        'type' => 'int',
                        'description' => 'iscookieAuthEnabled Does this not handel cookie based authentication? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Iscookie Auth Enabled',
                    ),
                    'serverFQDN' => 
                    array (
                        'name' => 'serverFQDN',
                        'type' => 'string',
                        'description' => 'serverFQDN public FQDN for this node',
                        'label' => 'Server FQDN',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'localIP' => 
                    array (
                        'name' => 'localIP',
                        'type' => 'string',
                        'description' => 'loalIP local listening IP (or *) of this note',
                        'label' => 'Local IP',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'port' => 
                    array (
                        'name' => 'port',
                        'type' => 'int',
                        'description' => 'port listening port',
                        'label' => 'Port',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'publicKey' => 
                    array (
                        'name' => 'publicKey',
                        'type' => 'string',
                        'description' => 'privateKey for HTTPS',
                        'label' => 'Public Key',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'cert' => 
                    array (
                        'name' => 'cert',
                        'type' => 'string',
                        'description' => 'cert server certificate for HTTPS',
                        'label' => 'Cert',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'ca' => 
                    array (
                        'name' => 'ca',
                        'type' => 'string',
                        'description' => 'ca Certification authority certificate',
                        'label' => 'Ca',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'caChain' => 
                    array (
                        'name' => 'caChain',
                        'type' => 'string',
                        'description' => 'intermediate certification authority certificates',
                        'label' => 'Ca Chain',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'additionalConfiguration' => 
                    array (
                        'name' => 'additionalConfiguration',
                        'type' => 'string',
                        'description' => 'additionalConfiguration additionnal apache directive for this virtualHost/node',
                        'label' => 'Additional Configuration',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'isPublished' => 
                    array (
                        'name' => 'isPublished',
                        'type' => 'int',
                        'description' => 'isPublished Is this node published? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is Published',
                    ),
                    'uri' => 
                    array (
                        'name' => 'uri',
                        'type' => 'url',
                        'description' => 'uri',
                        'label' => 'Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'nodes/',
        ),
        'accessLevel' => 0,
    ),
    'PUT' => 
    array (
        'url' => 'nodes',
        'className' => 'Nodes',
        'path' => 'nodes',
        'methodName' => 'update',
        'arguments' => 
        array (
            'nodeName' => 0,
            'serverFQDN' => 1,
            'localIP' => 2,
            'port' => 3,
            'isHTTPS' => 4,
            'nodeDescription' => 5,
            'isBasicAuthEnabled' => 6,
            'isCookieAuthEnabled' => 7,
            'additionalConfiguration' => 8,
            'apply' => 9,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
            2 => NULL,
            3 => NULL,
            4 => NULL,
            5 => NULL,
            6 => NULL,
            7 => NULL,
            8 => NULL,
            9 => 1,
        ),
        'metadata' => 
        array (
            'description' => 'Update Node',
            'longDescription' => 'Update and deploy Node',
            'url' => 0,
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'Node identifier',
                    'name' => 'nodeName',
                    'label' => 'Node Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                1 => 
                array (
                    'type' => 'string',
                    'description' => 'Public server FQDN',
                    'name' => 'serverFQDN',
                    'label' => 'Server FQDN',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                2 => 
                array (
                    'type' => 'string',
                    'description' => 'Listening IP (IP, hostname or * for all available interfaces)',
                    'name' => 'localIP',
                    'label' => 'Local IP',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                3 => 
                array (
                    'type' => 'int',
                    'description' => 'port Listeing port',
                    'name' => 'port',
                    'label' => 'Port',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                4 => 
                array (
                    'type' => 'int',
                    'description' => 'Does this node use HTTPS? (O: no, 1: yes)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'isHTTPS',
                    'label' => 'Is HTTPS',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                ),
                5 => 
                array (
                    'type' => 'string',
                    'description' => 'Node description',
                    'name' => 'nodeDescription',
                    'label' => 'Node Description',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                6 => 
                array (
                    'type' => 'int',
                    'description' => 'Does this node handle basic authentication? (O: no, 1: yes)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'isBasicAuthEnabled',
                    'label' => 'Is Basic Auth Enabled',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                7 => 
                array (
                    'type' => 'int',
                    'description' => 'Does this not handel cookie based authentication? (O: no, 1: yes)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'isCookieAuthEnabled',
                    'label' => 'Is Cookie Auth Enabled',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                8 => 
                array (
                    'type' => 'string',
                    'description' => 'additionnal apache directive for this virtualHost/node',
                    'name' => 'additionalConfiguration',
                    'label' => 'Additional Configuration',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                9 => 
                array (
                    'type' => 'int',
                    'description' => 'Apply this configuration immediatly? (O: no, 1: yes)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'apply',
                    'label' => 'Apply',
                    'default' => 1,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
            ),
            '' => 'return Node updated Node',
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'nodes/',
            'return' => 
            array (
                'type' => 'array',
            ),
        ),
        'accessLevel' => 0,
    ),
);

//==== v1 nodes/{s0} ====

$o['v1']['nodes/{s0}'] = array (
    'GET' => 
    array (
        'url' => 'nodes/{nodeName}',
        'className' => 'Nodes',
        'path' => 'nodes',
        'methodName' => 'getOn',
        'arguments' => 
        array (
            'nodeName' => 0,
        ),
        'defaults' => 
        array (
            0 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Get a Node',
            'longDescription' => 'Get description of a particular Node',
            'url' => 'GET :nodeName',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'Node identifier',
                    'name' => 'nodeName',
                    'label' => 'Node Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'Node',
                'description' => 'Requested Node',
                'children' => 
                array (
                    'nodeName' => 
                    array (
                        'name' => 'nodeName',
                        'type' => 'string',
                        'description' => 'nodeName node identifier',
                        'label' => 'Node Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'nodeDescription' => 
                    array (
                        'name' => 'nodeDescription',
                        'type' => 'string',
                        'description' => 'nodeDescription description of this node',
                        'label' => 'Node Description',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'isHTTPS' => 
                    array (
                        'name' => 'isHTTPS',
                        'type' => 'int',
                        'description' => 'isHTTPS Does this node use HTTPS? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is HTTPS',
                    ),
                    'isBasicAuthEnabled' => 
                    array (
                        'name' => 'isBasicAuthEnabled',
                        'type' => 'int',
                        'description' => 'isBasicAuthEnabled Does this node handle basic authentication? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is Basic Auth Enabled',
                    ),
                    'iscookieAuthEnabled' => 
                    array (
                        'name' => 'iscookieAuthEnabled',
                        'type' => 'int',
                        'description' => 'iscookieAuthEnabled Does this not handel cookie based authentication? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Iscookie Auth Enabled',
                    ),
                    'serverFQDN' => 
                    array (
                        'name' => 'serverFQDN',
                        'type' => 'string',
                        'description' => 'serverFQDN public FQDN for this node',
                        'label' => 'Server FQDN',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'localIP' => 
                    array (
                        'name' => 'localIP',
                        'type' => 'string',
                        'description' => 'loalIP local listening IP (or *) of this note',
                        'label' => 'Local IP',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'port' => 
                    array (
                        'name' => 'port',
                        'type' => 'int',
                        'description' => 'port listening port',
                        'label' => 'Port',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'publicKey' => 
                    array (
                        'name' => 'publicKey',
                        'type' => 'string',
                        'description' => 'privateKey for HTTPS',
                        'label' => 'Public Key',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'cert' => 
                    array (
                        'name' => 'cert',
                        'type' => 'string',
                        'description' => 'cert server certificate for HTTPS',
                        'label' => 'Cert',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'ca' => 
                    array (
                        'name' => 'ca',
                        'type' => 'string',
                        'description' => 'ca Certification authority certificate',
                        'label' => 'Ca',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'caChain' => 
                    array (
                        'name' => 'caChain',
                        'type' => 'string',
                        'description' => 'intermediate certification authority certificates',
                        'label' => 'Ca Chain',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'additionalConfiguration' => 
                    array (
                        'name' => 'additionalConfiguration',
                        'type' => 'string',
                        'description' => 'additionalConfiguration additionnal apache directive for this virtualHost/node',
                        'label' => 'Additional Configuration',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'isPublished' => 
                    array (
                        'name' => 'isPublished',
                        'type' => 'int',
                        'description' => 'isPublished Is this node published? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is Published',
                    ),
                    'uri' => 
                    array (
                        'name' => 'uri',
                        'type' => 'url',
                        'description' => 'uri',
                        'label' => 'Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'nodes/',
        ),
        'accessLevel' => 0,
    ),
    'PUT' => 
    array (
        'url' => 'nodes/{nodeName}',
        'className' => 'Nodes',
        'path' => 'nodes',
        'methodName' => 'update',
        'arguments' => 
        array (
            'nodeName' => 0,
            'serverFQDN' => 1,
            'localIP' => 2,
            'port' => 3,
            'isHTTPS' => 4,
            'nodeDescription' => 5,
            'isBasicAuthEnabled' => 6,
            'isCookieAuthEnabled' => 7,
            'additionalConfiguration' => 8,
            'apply' => 9,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
            2 => NULL,
            3 => NULL,
            4 => NULL,
            5 => NULL,
            6 => NULL,
            7 => NULL,
            8 => NULL,
            9 => 1,
        ),
        'metadata' => 
        array (
            'description' => 'Update Node',
            'longDescription' => 'Update and deploy Node',
            'url' => 0,
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'Node identifier',
                    'name' => 'nodeName',
                    'label' => 'Node Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
                1 => 
                array (
                    'type' => 'string',
                    'description' => 'Public server FQDN',
                    'name' => 'serverFQDN',
                    'label' => 'Server FQDN',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                2 => 
                array (
                    'type' => 'string',
                    'description' => 'Listening IP (IP, hostname or * for all available interfaces)',
                    'name' => 'localIP',
                    'label' => 'Local IP',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                3 => 
                array (
                    'type' => 'int',
                    'description' => 'port Listeing port',
                    'name' => 'port',
                    'label' => 'Port',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                4 => 
                array (
                    'type' => 'int',
                    'description' => 'Does this node use HTTPS? (O: no, 1: yes)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'isHTTPS',
                    'label' => 'Is HTTPS',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                ),
                5 => 
                array (
                    'type' => 'string',
                    'description' => 'Node description',
                    'name' => 'nodeDescription',
                    'label' => 'Node Description',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                6 => 
                array (
                    'type' => 'int',
                    'description' => 'Does this node handle basic authentication? (O: no, 1: yes)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'isBasicAuthEnabled',
                    'label' => 'Is Basic Auth Enabled',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                7 => 
                array (
                    'type' => 'int',
                    'description' => 'Does this not handel cookie based authentication? (O: no, 1: yes)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'isCookieAuthEnabled',
                    'label' => 'Is Cookie Auth Enabled',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                8 => 
                array (
                    'type' => 'string',
                    'description' => 'additionnal apache directive for this virtualHost/node',
                    'name' => 'additionalConfiguration',
                    'label' => 'Additional Configuration',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                9 => 
                array (
                    'type' => 'int',
                    'description' => 'Apply this configuration immediatly? (O: no, 1: yes)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'apply',
                    'label' => 'Apply',
                    'default' => 1,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
            ),
            '' => 'return Node updated Node',
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'nodes/',
            'return' => 
            array (
                'type' => 'array',
            ),
        ),
        'accessLevel' => 0,
    ),
    'DELETE' => 
    array (
        'url' => 'nodes/{nodeName}',
        'className' => 'Nodes',
        'path' => 'nodes',
        'methodName' => 'delete',
        'arguments' => 
        array (
            'nodeName' => 0,
            'apply' => 1,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Delete Node',
            'longDescription' => 'Delete and undeploy a Node',
            'url' => 'DELETE :nodeName',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'Node identifier',
                    'name' => 'nodeName',
                    'label' => 'Node Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
                1 => 
                array (
                    'type' => 'int',
                    'description' => 'Apply this configuration immediatly? (O: no, 1: yes)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'query',
                    ),
                    'name' => 'apply',
                    'label' => 'Apply',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'nodes/',
            'return' => 
            array (
                'type' => 'array',
            ),
        ),
        'accessLevel' => 0,
    ),
);

//==== v1 nodes/{s0}/virtualHost ====

$o['v1']['nodes/{s0}/virtualHost'] = array (
    'GET' => 
    array (
        'url' => 'nodes/{nodeName}/virtualHost',
        'className' => 'Nodes',
        'path' => 'nodes',
        'methodName' => 'generateVirtualHost',
        'arguments' => 
        array (
            'nodeName' => 0,
        ),
        'defaults' => 
        array (
            0 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Get Apache VirtualHost',
            'longDescription' => 'Get corresponding Apache VirtualHost',
            'url' => 'GET :nodeName/virtualHost',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'Node identifier',
                    'name' => 'nodeName',
                    'label' => 'Node Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'string',
                'description' => 'VirtualHost file',
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'nodes/',
        ),
        'accessLevel' => 0,
    ),
    'POST' => 
    array (
        'url' => 'nodes/{nodeName}/virtualHost',
        'className' => 'Nodes',
        'path' => 'nodes',
        'methodName' => 'applyConf',
        'arguments' => 
        array (
            'nodeName' => 0,
        ),
        'defaults' => 
        array (
            0 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Apply configuration',
            'longDescription' => '',
            'url' => 'POST :nodeName/virtualHost',
            'return' => 
            array (
                'type' => 'Node',
                'description' => 'requested Node',
                'children' => 
                array (
                    'nodeName' => 
                    array (
                        'name' => 'nodeName',
                        'type' => 'string',
                        'description' => 'nodeName node identifier',
                        'label' => 'Node Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'nodeDescription' => 
                    array (
                        'name' => 'nodeDescription',
                        'type' => 'string',
                        'description' => 'nodeDescription description of this node',
                        'label' => 'Node Description',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'isHTTPS' => 
                    array (
                        'name' => 'isHTTPS',
                        'type' => 'int',
                        'description' => 'isHTTPS Does this node use HTTPS? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is HTTPS',
                    ),
                    'isBasicAuthEnabled' => 
                    array (
                        'name' => 'isBasicAuthEnabled',
                        'type' => 'int',
                        'description' => 'isBasicAuthEnabled Does this node handle basic authentication? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is Basic Auth Enabled',
                    ),
                    'iscookieAuthEnabled' => 
                    array (
                        'name' => 'iscookieAuthEnabled',
                        'type' => 'int',
                        'description' => 'iscookieAuthEnabled Does this not handel cookie based authentication? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Iscookie Auth Enabled',
                    ),
                    'serverFQDN' => 
                    array (
                        'name' => 'serverFQDN',
                        'type' => 'string',
                        'description' => 'serverFQDN public FQDN for this node',
                        'label' => 'Server FQDN',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'localIP' => 
                    array (
                        'name' => 'localIP',
                        'type' => 'string',
                        'description' => 'loalIP local listening IP (or *) of this note',
                        'label' => 'Local IP',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'port' => 
                    array (
                        'name' => 'port',
                        'type' => 'int',
                        'description' => 'port listening port',
                        'label' => 'Port',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'publicKey' => 
                    array (
                        'name' => 'publicKey',
                        'type' => 'string',
                        'description' => 'privateKey for HTTPS',
                        'label' => 'Public Key',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'cert' => 
                    array (
                        'name' => 'cert',
                        'type' => 'string',
                        'description' => 'cert server certificate for HTTPS',
                        'label' => 'Cert',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'ca' => 
                    array (
                        'name' => 'ca',
                        'type' => 'string',
                        'description' => 'ca Certification authority certificate',
                        'label' => 'Ca',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'caChain' => 
                    array (
                        'name' => 'caChain',
                        'type' => 'string',
                        'description' => 'intermediate certification authority certificates',
                        'label' => 'Ca Chain',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'additionalConfiguration' => 
                    array (
                        'name' => 'additionalConfiguration',
                        'type' => 'string',
                        'description' => 'additionalConfiguration additionnal apache directive for this virtualHost/node',
                        'label' => 'Additional Configuration',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'isPublished' => 
                    array (
                        'name' => 'isPublished',
                        'type' => 'int',
                        'description' => 'isPublished Is this node published? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is Published',
                    ),
                    'uri' => 
                    array (
                        'name' => 'uri',
                        'type' => 'url',
                        'description' => 'uri',
                        'label' => 'Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'nodes/',
            'param' => 
            array (
                0 => 
                array (
                    'name' => 'nodeName',
                    'label' => 'Node Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                    'type' => 'string',
                ),
            ),
        ),
        'accessLevel' => 0,
    ),
);

//==== v1 nodes/{s0}/status ====

$o['v1']['nodes/{s0}/status'] = array (
    'POST' => 
    array (
        'url' => 'nodes/{nodeName}/status',
        'className' => 'Nodes',
        'path' => 'nodes',
        'methodName' => 'setPublished',
        'arguments' => 
        array (
            'nodeName' => 0,
            'published' => 1,
            'reload' => 2,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
            2 => 'yes',
        ),
        'metadata' => 
        array (
            'description' => 'Enable/disable',
            'longDescription' => 'Enable or disable a Node',
            'url' => 'POST :nodeName/status',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'node identifier',
                    'name' => 'nodeName',
                    'label' => 'Node Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
                1 => 
                array (
                    'type' => 'int',
                    'description' => '0: not published, 1: published',
                    'properties' => 
                    array (
                        'choce' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'published',
                    'label' => 'Published',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                ),
                2 => 
                array (
                    'type' => 'string',
                    'description' => ', default: yes. Apply configuration.',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => 'yes',
                            1 => 'no',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'reload',
                    'label' => 'Reload',
                    'default' => 'yes',
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'Node',
                'description' => 'updated Node',
                'children' => 
                array (
                    'nodeName' => 
                    array (
                        'name' => 'nodeName',
                        'type' => 'string',
                        'description' => 'nodeName node identifier',
                        'label' => 'Node Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'nodeDescription' => 
                    array (
                        'name' => 'nodeDescription',
                        'type' => 'string',
                        'description' => 'nodeDescription description of this node',
                        'label' => 'Node Description',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'isHTTPS' => 
                    array (
                        'name' => 'isHTTPS',
                        'type' => 'int',
                        'description' => 'isHTTPS Does this node use HTTPS? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is HTTPS',
                    ),
                    'isBasicAuthEnabled' => 
                    array (
                        'name' => 'isBasicAuthEnabled',
                        'type' => 'int',
                        'description' => 'isBasicAuthEnabled Does this node handle basic authentication? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is Basic Auth Enabled',
                    ),
                    'iscookieAuthEnabled' => 
                    array (
                        'name' => 'iscookieAuthEnabled',
                        'type' => 'int',
                        'description' => 'iscookieAuthEnabled Does this not handel cookie based authentication? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Iscookie Auth Enabled',
                    ),
                    'serverFQDN' => 
                    array (
                        'name' => 'serverFQDN',
                        'type' => 'string',
                        'description' => 'serverFQDN public FQDN for this node',
                        'label' => 'Server FQDN',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'localIP' => 
                    array (
                        'name' => 'localIP',
                        'type' => 'string',
                        'description' => 'loalIP local listening IP (or *) of this note',
                        'label' => 'Local IP',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'port' => 
                    array (
                        'name' => 'port',
                        'type' => 'int',
                        'description' => 'port listening port',
                        'label' => 'Port',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'publicKey' => 
                    array (
                        'name' => 'publicKey',
                        'type' => 'string',
                        'description' => 'privateKey for HTTPS',
                        'label' => 'Public Key',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'cert' => 
                    array (
                        'name' => 'cert',
                        'type' => 'string',
                        'description' => 'cert server certificate for HTTPS',
                        'label' => 'Cert',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'ca' => 
                    array (
                        'name' => 'ca',
                        'type' => 'string',
                        'description' => 'ca Certification authority certificate',
                        'label' => 'Ca',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'caChain' => 
                    array (
                        'name' => 'caChain',
                        'type' => 'string',
                        'description' => 'intermediate certification authority certificates',
                        'label' => 'Ca Chain',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'additionalConfiguration' => 
                    array (
                        'name' => 'additionalConfiguration',
                        'type' => 'string',
                        'description' => 'additionalConfiguration additionnal apache directive for this virtualHost/node',
                        'label' => 'Additional Configuration',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'isPublished' => 
                    array (
                        'name' => 'isPublished',
                        'type' => 'int',
                        'description' => 'isPublished Is this node published? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is Published',
                    ),
                    'uri' => 
                    array (
                        'name' => 'uri',
                        'type' => 'url',
                        'description' => 'uri',
                        'label' => 'Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'nodes/',
        ),
        'accessLevel' => 0,
    ),
);

//==== v1 users/me/password ====

$o['v1']['users/me/password'] = array (
    'PUT' => 
    array (
        'url' => 'users/me/password',
        'className' => 'Users',
        'path' => 'users',
        'methodName' => 'resetPassword',
        'arguments' => 
        array (
            'oldPassword' => 0,
            'newPassword' => 1,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Change password',
            'longDescription' => 'Change connected user password',
            'url' => 'PUT me/password',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'new password',
                    'name' => 'oldPassword',
                    'label' => 'Old Password',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                1 => 
                array (
                    'type' => 'string',
                    'description' => 'new password',
                    'name' => 'newPassword',
                    'label' => 'New Password',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'User',
                'description' => '',
                'children' => 
                array (
                    'userName' => 
                    array (
                        'name' => 'userName',
                        'type' => 'string',
                        'description' => 'userName users\'s identifier',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                        'label' => 'User Name',
                    ),
                    'password' => 
                    array (
                        'name' => 'password',
                        'type' => 'string',
                        'description' => 'password users\'s password',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                        'label' => 'Password',
                    ),
                    'email' => 
                    array (
                        'name' => 'email',
                        'type' => 'email',
                        'description' => 'email users\'s email',
                        'label' => 'Email',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'firstName' => 
                    array (
                        'name' => 'firstName',
                        'type' => 'string',
                        'description' => 'firstName email users\'s first name',
                        'label' => 'First Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'lastName' => 
                    array (
                        'name' => 'lastName',
                        'type' => 'string',
                        'description' => 'lastName email users\'s last name',
                        'label' => 'Last Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'entity' => 
                    array (
                        'name' => 'entity',
                        'type' => 'string',
                        'description' => 'entity users\'s entity',
                        'label' => 'Entity',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'endDate' => 
                    array (
                        'name' => 'endDate',
                        'type' => 'string',
                        'description' => 'endDate users\'s validity end date in ISO 8601 full format',
                        'label' => 'End Date',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'extra' => 
                    array (
                        'name' => 'extra',
                        'type' => 'string',
                        'description' => 'extra users\'s extra data in free format',
                        'label' => 'Extra',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'uri' => 
                    array (
                        'name' => 'uri',
                        'type' => 'url',
                        'description' => 'uri',
                        'label' => 'Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'users/',
            'classDescription' => 'Users management',
        ),
        'accessLevel' => 0,
    ),
);

//==== v1 users/me ====

$o['v1']['users/me'] = array (
    'GET' => 
    array (
        'url' => 'users/me',
        'className' => 'Users',
        'path' => 'users',
        'methodName' => 'whoAmI',
        'arguments' => 
        array (
        ),
        'defaults' => 
        array (
        ),
        'metadata' => 
        array (
            'description' => 'Get current user',
            'longDescription' => 'Get connected user description',
            'url' => 'GET me',
            'return' => 
            array (
                'type' => 'User',
                'description' => '',
                'children' => 
                array (
                    'userName' => 
                    array (
                        'name' => 'userName',
                        'type' => 'string',
                        'description' => 'userName users\'s identifier',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                        'label' => 'User Name',
                    ),
                    'password' => 
                    array (
                        'name' => 'password',
                        'type' => 'string',
                        'description' => 'password users\'s password',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                        'label' => 'Password',
                    ),
                    'email' => 
                    array (
                        'name' => 'email',
                        'type' => 'email',
                        'description' => 'email users\'s email',
                        'label' => 'Email',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'firstName' => 
                    array (
                        'name' => 'firstName',
                        'type' => 'string',
                        'description' => 'firstName email users\'s first name',
                        'label' => 'First Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'lastName' => 
                    array (
                        'name' => 'lastName',
                        'type' => 'string',
                        'description' => 'lastName email users\'s last name',
                        'label' => 'Last Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'entity' => 
                    array (
                        'name' => 'entity',
                        'type' => 'string',
                        'description' => 'entity users\'s entity',
                        'label' => 'Entity',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'endDate' => 
                    array (
                        'name' => 'endDate',
                        'type' => 'string',
                        'description' => 'endDate users\'s validity end date in ISO 8601 full format',
                        'label' => 'End Date',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'extra' => 
                    array (
                        'name' => 'extra',
                        'type' => 'string',
                        'description' => 'extra users\'s extra data in free format',
                        'label' => 'Extra',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'uri' => 
                    array (
                        'name' => 'uri',
                        'type' => 'url',
                        'description' => 'uri',
                        'label' => 'Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'users/',
            'classDescription' => 'Users management',
            'param' => 
            array (
            ),
        ),
        'accessLevel' => 0,
    ),
);

//==== v1 users/{s0}/quotas/unset ====

$o['v1']['users/{s0}/quotas/unset'] = array (
    'GET' => 
    array (
        'url' => 'users/{userName}/quotas/unset',
        'className' => 'Users',
        'path' => 'users',
        'methodName' => 'getUnsetQuotaForUSer',
        'arguments' => 
        array (
            'userName' => 0,
        ),
        'defaults' => 
        array (
            0 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Get unset quotas',
            'longDescription' => 'Get quotas witch are not yet defined for a particular user (based on services requiring users quotas settings)',
            'url' => 'GET :userName/quotas/unset',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'user identifer',
                    'name' => 'userName',
                    'label' => 'User Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'array',
                'description' => 'List of potentials quotas',
                'properties' => 
                array (
                    'type' => 'Quota',
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'users/',
            'classDescription' => 'Users management',
        ),
        'accessLevel' => 0,
    ),
);

//==== v1 users/{s0}/quotas ====

$o['v1']['users/{s0}/quotas'] = array (
    'GET' => 
    array (
        'url' => 'users/{userName}/quotas',
        'className' => 'Users',
        'path' => 'users',
        'methodName' => 'getAllQuotasForUser',
        'arguments' => 
        array (
            'userName' => 0,
        ),
        'defaults' => 
        array (
            0 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Get user\'s quotas',
            'longDescription' => 'Reteive all defined quotas for a particular user',
            'url' => 'GET :userName/quotas',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'user identifier',
                    'name' => 'userName',
                    'label' => 'User Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'array',
                'description' => 'list of defined quotas for this user',
                'properties' => 
                array (
                    'type' => 'Quota',
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'users/',
            'classDescription' => 'Users management',
        ),
        'accessLevel' => 0,
    ),
);

//==== v1 users/{s0}/quotas/{s1} ====

$o['v1']['users/{s0}/quotas/{s1}'] = array (
    'GET' => 
    array (
        'url' => 'users/{userName}/quotas/{serviceName}',
        'className' => 'Users',
        'path' => 'users',
        'methodName' => 'getQuotasForUserAndService',
        'arguments' => 
        array (
            'userName' => 0,
            'serviceName' => 1,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Get user\'s quota for a service',
            'longDescription' => 'Reteive defined quotas for a particular user and a particular service',
            'url' => 'GET :userName/quotas/:serviceName',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'user identifier',
                    'name' => 'userName',
                    'label' => 'User Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
                1 => 
                array (
                    'type' => 'string',
                    'description' => 'service identifier',
                    'name' => 'serviceName',
                    'label' => 'Service Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'Quota',
                'description' => 'quotas for this user and this service',
                'properties' => 
                array (
                    'type' => 'Quota',
                ),
                'children' => 
                array (
                    'serviceName' => 
                    array (
                        'name' => 'serviceName',
                        'type' => 'string',
                        'description' => 'relative service identifier',
                        'label' => 'Service Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'serviceUri' => 
                    array (
                        'name' => 'serviceUri',
                        'type' => 'url',
                        'description' => 'relative service uri',
                        'label' => 'Service Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'userName' => 
                    array (
                        'name' => 'userName',
                        'type' => 'string',
                        'description' => 'relative user identifier',
                        'label' => 'User Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'userUri' => 
                    array (
                        'name' => 'userUri',
                        'type' => 'url',
                        'description' => 'relative user uri',
                        'label' => 'User Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'reqSec' => 
                    array (
                        'name' => 'reqSec',
                        'type' => 'int',
                        'description' => 'reqSec maximum number of request per seconds allowed',
                        'label' => 'Req Sec',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'reqDay' => 
                    array (
                        'name' => 'reqDay',
                        'type' => 'int',
                        'description' => 'reqDay maximum number of request per days allowed',
                        'label' => 'Req Day',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'reqMonth' => 
                    array (
                        'name' => 'reqMonth',
                        'type' => 'int',
                        'description' => 'reqMonth maximum number of request pre months allowed',
                        'label' => 'Req Month',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'uri' => 
                    array (
                        'name' => 'uri',
                        'type' => 'url',
                        'description' => 'uri',
                        'label' => 'Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'users/',
            'classDescription' => 'Users management',
        ),
        'accessLevel' => 0,
    ),
    'POST' => 
    array (
        'url' => 'users/{userName}/quotas/{serviceName}',
        'className' => 'Users',
        'path' => 'users',
        'methodName' => 'createQuotaForUser',
        'arguments' => 
        array (
            'userName' => 0,
            'serviceName' => 1,
            'reqSec' => 2,
            'reqDay' => 3,
            'reqMonth' => 4,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
            2 => NULL,
            3 => NULL,
            4 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Add quotas',
            'longDescription' => 'Add quotas on a particular service to a particular user',
            'url' => 'POST :userName/quotas/:serviceName',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'user identifier',
                    'name' => 'userName',
                    'label' => 'User Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
                1 => 
                array (
                    'type' => 'string',
                    'description' => 'group identifier',
                    'name' => 'serviceName',
                    'label' => 'Service Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
                2 => 
                array (
                    'type' => 'int',
                    'description' => 'maximum number of request per seconds allowed',
                    'name' => 'reqSec',
                    'label' => 'Req Sec',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                3 => 
                array (
                    'type' => 'int',
                    'description' => 'maximum number of request per days allowed',
                    'name' => 'reqDay',
                    'label' => 'Req Day',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                4 => 
                array (
                    'type' => 'int',
                    'description' => 'maximum number of request per months allowed',
                    'name' => 'reqMonth',
                    'label' => 'Req Month',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'Quota',
                'description' => 'added quota',
                'children' => 
                array (
                    'serviceName' => 
                    array (
                        'name' => 'serviceName',
                        'type' => 'string',
                        'description' => 'relative service identifier',
                        'label' => 'Service Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'serviceUri' => 
                    array (
                        'name' => 'serviceUri',
                        'type' => 'url',
                        'description' => 'relative service uri',
                        'label' => 'Service Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'userName' => 
                    array (
                        'name' => 'userName',
                        'type' => 'string',
                        'description' => 'relative user identifier',
                        'label' => 'User Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'userUri' => 
                    array (
                        'name' => 'userUri',
                        'type' => 'url',
                        'description' => 'relative user uri',
                        'label' => 'User Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'reqSec' => 
                    array (
                        'name' => 'reqSec',
                        'type' => 'int',
                        'description' => 'reqSec maximum number of request per seconds allowed',
                        'label' => 'Req Sec',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'reqDay' => 
                    array (
                        'name' => 'reqDay',
                        'type' => 'int',
                        'description' => 'reqDay maximum number of request per days allowed',
                        'label' => 'Req Day',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'reqMonth' => 
                    array (
                        'name' => 'reqMonth',
                        'type' => 'int',
                        'description' => 'reqMonth maximum number of request pre months allowed',
                        'label' => 'Req Month',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'uri' => 
                    array (
                        'name' => 'uri',
                        'type' => 'url',
                        'description' => 'uri',
                        'label' => 'Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'users/',
            'classDescription' => 'Users management',
        ),
        'accessLevel' => 0,
    ),
);

//==== v1 users/{s0}/groups/available ====

$o['v1']['users/{s0}/groups/available'] = array (
    'GET' => 
    array (
        'url' => 'users/{userName}/groups/available',
        'className' => 'Users',
        'path' => 'users',
        'methodName' => 'getAvailableGroupForUser',
        'arguments' => 
        array (
            'userName' => 0,
        ),
        'defaults' => 
        array (
            0 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Get available groups',
            'longDescription' => 'Get groups where user is not yet a member',
            'url' => 'GET :userName/groups/available',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'user identifier',
                    'name' => 'userName',
                    'label' => 'User Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'array',
                'description' => 'group list',
                'properties' => 
                array (
                    'type' => 'Group',
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'users/',
            'classDescription' => 'Users management',
        ),
        'accessLevel' => 0,
    ),
);

//==== v1 users/{s0}/groups ====

$o['v1']['users/{s0}/groups'] = array (
    'GET' => 
    array (
        'url' => 'users/{userName}/groups',
        'className' => 'Users',
        'path' => 'users',
        'methodName' => 'geListOfGroupForUser',
        'arguments' => 
        array (
            'userName' => 0,
        ),
        'defaults' => 
        array (
            0 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Get groups membership',
            'longDescription' => 'Get list of group where the user is a member',
            'url' => 'GET :userName/groups Get groups membership for a particular user',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'user identifier',
                    'name' => 'userName',
                    'label' => 'User Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'array',
                'description' => 'List of user\'s groups',
                'properties' => 
                array (
                    'type' => 'Group',
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'users/',
            'classDescription' => 'Users management',
        ),
        'accessLevel' => 0,
    ),
);

//==== v1 users/{s0}/groups/{s1} ====

$o['v1']['users/{s0}/groups/{s1}'] = array (
    'GET' => 
    array (
        'url' => 'users/{userName}/groups/{groupName}',
        'className' => 'Users',
        'path' => 'users',
        'methodName' => 'getGroupForUser',
        'arguments' => 
        array (
            'userName' => 0,
            'groupName' => 1,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Get group membership',
            'longDescription' => 'Get a particular group membership for a particular user',
            'url' => 'GET :userName/groups/:groupName',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'user identifier',
                    'name' => 'userName',
                    'label' => 'User Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
                1 => 
                array (
                    'type' => 'string',
                    'description' => 'group identifier',
                    'name' => 'groupName',
                    'label' => 'Group Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'Group',
                'description' => 'List of user\'s groups',
                'children' => 
                array (
                    'groupName' => 
                    array (
                        'name' => 'groupName',
                        'type' => 'string',
                        'description' => 'groupName group identifier',
                        'label' => 'Group Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'description' => 
                    array (
                        'name' => 'description',
                        'type' => 'string',
                        'description' => 'description group description',
                        'label' => 'Description',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'uri' => 
                    array (
                        'name' => 'uri',
                        'type' => 'url',
                        'description' => 'uri',
                        'label' => 'Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'users/',
            'classDescription' => 'Users management',
        ),
        'accessLevel' => 0,
    ),
    'DELETE' => 
    array (
        'url' => 'users/{userName}/groups/{groupName}',
        'className' => 'Users',
        'path' => 'users',
        'methodName' => 'removeUserGroup',
        'arguments' => 
        array (
            'userName' => 0,
            'groupName' => 1,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Remove group',
            'longDescription' => 'Remove a particular user from a particular group',
            'url' => 'DELETE :userName/groups/:groupName',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'user identifier',
                    'name' => 'userName',
                    'label' => 'User Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
                1 => 
                array (
                    'type' => 'string',
                    'description' => 'group identifier',
                    'name' => 'groupName',
                    'label' => 'Group Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'Group',
                'description' => 'removed group',
                'children' => 
                array (
                    'groupName' => 
                    array (
                        'name' => 'groupName',
                        'type' => 'string',
                        'description' => 'groupName group identifier',
                        'label' => 'Group Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'description' => 
                    array (
                        'name' => 'description',
                        'type' => 'string',
                        'description' => 'description group description',
                        'label' => 'Description',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'uri' => 
                    array (
                        'name' => 'uri',
                        'type' => 'url',
                        'description' => 'uri',
                        'label' => 'Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'users/',
            'classDescription' => 'Users management',
        ),
        'accessLevel' => 0,
    ),
    'POST' => 
    array (
        'url' => 'users/{userName}/groups/{groupName}',
        'className' => 'Users',
        'path' => 'users',
        'methodName' => 'addUserGroup',
        'arguments' => 
        array (
            'userName' => 0,
            'groupName' => 1,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Add group',
            'longDescription' => 'Add a paraticular user to a particular group',
            'url' => 'POST :userName/groups/:groupName',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'name' => 'userName',
                    'description' => 'user identifier',
                    'label' => 'User Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
                1 => 
                array (
                    'type' => 'string',
                    'name' => 'groupName',
                    'description' => 'group identifier',
                    'label' => 'Group Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'Group',
                'description' => 'added group',
                'children' => 
                array (
                    'groupName' => 
                    array (
                        'name' => 'groupName',
                        'type' => 'string',
                        'description' => 'groupName group identifier',
                        'label' => 'Group Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'description' => 
                    array (
                        'name' => 'description',
                        'type' => 'string',
                        'description' => 'description group description',
                        'label' => 'Description',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'uri' => 
                    array (
                        'name' => 'uri',
                        'type' => 'url',
                        'description' => 'uri',
                        'label' => 'Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'users/',
            'classDescription' => 'Users management',
        ),
        'accessLevel' => 0,
    ),
);

//==== v1 users/{s0} ====

$o['v1']['users/{s0}'] = array (
    'GET' => 
    array (
        'url' => 'users/{userName}',
        'className' => 'Users',
        'path' => 'users',
        'methodName' => 'getOne',
        'arguments' => 
        array (
            'userName' => 0,
        ),
        'defaults' => 
        array (
            0 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Get a user',
            'longDescription' => 'Get informations about a user',
            'url' => 'GET :userName',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'user\'s identifer',
                    'name' => 'userName',
                    'label' => 'User Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'User',
                'description' => '',
                'children' => 
                array (
                    'userName' => 
                    array (
                        'name' => 'userName',
                        'type' => 'string',
                        'description' => 'userName users\'s identifier',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                        'label' => 'User Name',
                    ),
                    'password' => 
                    array (
                        'name' => 'password',
                        'type' => 'string',
                        'description' => 'password users\'s password',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                        'label' => 'Password',
                    ),
                    'email' => 
                    array (
                        'name' => 'email',
                        'type' => 'email',
                        'description' => 'email users\'s email',
                        'label' => 'Email',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'firstName' => 
                    array (
                        'name' => 'firstName',
                        'type' => 'string',
                        'description' => 'firstName email users\'s first name',
                        'label' => 'First Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'lastName' => 
                    array (
                        'name' => 'lastName',
                        'type' => 'string',
                        'description' => 'lastName email users\'s last name',
                        'label' => 'Last Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'entity' => 
                    array (
                        'name' => 'entity',
                        'type' => 'string',
                        'description' => 'entity users\'s entity',
                        'label' => 'Entity',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'endDate' => 
                    array (
                        'name' => 'endDate',
                        'type' => 'string',
                        'description' => 'endDate users\'s validity end date in ISO 8601 full format',
                        'label' => 'End Date',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'extra' => 
                    array (
                        'name' => 'extra',
                        'type' => 'string',
                        'description' => 'extra users\'s extra data in free format',
                        'label' => 'Extra',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'uri' => 
                    array (
                        'name' => 'uri',
                        'type' => 'url',
                        'description' => 'uri',
                        'label' => 'Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'users/',
            'classDescription' => 'Users management',
        ),
        'accessLevel' => 0,
    ),
    'POST' => 
    array (
        'url' => 'users/{userName}',
        'className' => 'Users',
        'path' => 'users',
        'methodName' => 'create',
        'arguments' => 
        array (
            'userName' => 0,
            'password' => 1,
            'email' => 2,
            'endDate' => 3,
            'firstName' => 4,
            'lastName' => 5,
            'entity' => 6,
            'extra' => 7,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
            2 => NULL,
            3 => NULL,
            4 => NULL,
            5 => NULL,
            6 => NULL,
            7 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Create user',
            'longDescription' => 'Create a new user into the system',
            'url' => 0,
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'user identitfier',
                    'name' => 'userName',
                    'label' => 'User Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
                1 => 
                array (
                    'type' => 'string',
                    'description' => 'password to authenticate against OSA',
                    'name' => 'password',
                    'label' => 'Password',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                2 => 
                array (
                    'type' => 'email',
                    'description' => 'user\'s mail address',
                    'name' => 'email',
                    'label' => 'Email',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                3 => 
                array (
                    'type' => 'string',
                    'description' => 'users\'s validity end date in ISO 8601 full format',
                    'name' => 'endDate',
                    'label' => 'End Date',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                4 => 
                array (
                    'type' => 'string',
                    'description' => '[Optional] user\'s first name',
                    'name' => 'firstName',
                    'label' => 'First Name',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                5 => 
                array (
                    'type' => 'string',
                    'description' => '[Optional] user\'s last name',
                    'name' => 'lastName',
                    'label' => 'Last Name',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                6 => 
                array (
                    'type' => 'string',
                    'description' => '[Optional] user\'s entity',
                    'name' => 'entity',
                    'label' => 'Entity',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                7 => 
                array (
                    'type' => 'string',
                    'description' => '[Optional] users\'s extra data in free format',
                    'name' => 'extra',
                    'label' => 'Extra',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'User',
                'description' => 'newly created user',
                'children' => 
                array (
                    'userName' => 
                    array (
                        'name' => 'userName',
                        'type' => 'string',
                        'description' => 'userName users\'s identifier',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                        'label' => 'User Name',
                    ),
                    'password' => 
                    array (
                        'name' => 'password',
                        'type' => 'string',
                        'description' => 'password users\'s password',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                        'label' => 'Password',
                    ),
                    'email' => 
                    array (
                        'name' => 'email',
                        'type' => 'email',
                        'description' => 'email users\'s email',
                        'label' => 'Email',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'firstName' => 
                    array (
                        'name' => 'firstName',
                        'type' => 'string',
                        'description' => 'firstName email users\'s first name',
                        'label' => 'First Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'lastName' => 
                    array (
                        'name' => 'lastName',
                        'type' => 'string',
                        'description' => 'lastName email users\'s last name',
                        'label' => 'Last Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'entity' => 
                    array (
                        'name' => 'entity',
                        'type' => 'string',
                        'description' => 'entity users\'s entity',
                        'label' => 'Entity',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'endDate' => 
                    array (
                        'name' => 'endDate',
                        'type' => 'string',
                        'description' => 'endDate users\'s validity end date in ISO 8601 full format',
                        'label' => 'End Date',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'extra' => 
                    array (
                        'name' => 'extra',
                        'type' => 'string',
                        'description' => 'extra users\'s extra data in free format',
                        'label' => 'Extra',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'uri' => 
                    array (
                        'name' => 'uri',
                        'type' => 'url',
                        'description' => 'uri',
                        'label' => 'Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'users/',
            'classDescription' => 'Users management',
        ),
        'accessLevel' => 0,
    ),
    'PUT' => 
    array (
        'url' => 'users/{userName}',
        'className' => 'Users',
        'path' => 'users',
        'methodName' => 'update',
        'arguments' => 
        array (
            'userName' => 0,
            'password' => 1,
            'email' => 2,
            'endDate' => 3,
            'firstName' => 4,
            'lastName' => 5,
            'entity' => 6,
            'extra' => 7,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
            2 => NULL,
            3 => NULL,
            4 => NULL,
            5 => NULL,
            6 => NULL,
            7 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Update',
            'longDescription' => 'Update user properties',
            'url' => 'PUT :userName',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'user identitfier',
                    'name' => 'userName',
                    'label' => 'User Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
                1 => 
                array (
                    'type' => 'string',
                    'description' => 'password to authenticate against OSA',
                    'name' => 'password',
                    'label' => 'Password',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                2 => 
                array (
                    'type' => 'email',
                    'description' => 'user\'s mail address',
                    'name' => 'email',
                    'label' => 'Email',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                3 => 
                array (
                    'type' => 'string',
                    'description' => 'users\'s validity end date in ISO 8601 full format',
                    'name' => 'endDate',
                    'label' => 'End Date',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                4 => 
                array (
                    'type' => 'string',
                    'description' => '[Optional] user\'s first name',
                    'name' => 'firstName',
                    'label' => 'First Name',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                5 => 
                array (
                    'type' => 'string',
                    'description' => '[Optional] user\'s last name',
                    'name' => 'lastName',
                    'label' => 'Last Name',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                6 => 
                array (
                    'type' => 'string',
                    'description' => '[Optional] user\'s entity',
                    'name' => 'entity',
                    'label' => 'Entity',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                7 => 
                array (
                    'type' => 'string',
                    'description' => '[Optional] users\'s extra data in free format',
                    'name' => 'extra',
                    'label' => 'Extra',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'User',
                'description' => 'updated user',
                'children' => 
                array (
                    'userName' => 
                    array (
                        'name' => 'userName',
                        'type' => 'string',
                        'description' => 'userName users\'s identifier',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                        'label' => 'User Name',
                    ),
                    'password' => 
                    array (
                        'name' => 'password',
                        'type' => 'string',
                        'description' => 'password users\'s password',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                        'label' => 'Password',
                    ),
                    'email' => 
                    array (
                        'name' => 'email',
                        'type' => 'email',
                        'description' => 'email users\'s email',
                        'label' => 'Email',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'firstName' => 
                    array (
                        'name' => 'firstName',
                        'type' => 'string',
                        'description' => 'firstName email users\'s first name',
                        'label' => 'First Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'lastName' => 
                    array (
                        'name' => 'lastName',
                        'type' => 'string',
                        'description' => 'lastName email users\'s last name',
                        'label' => 'Last Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'entity' => 
                    array (
                        'name' => 'entity',
                        'type' => 'string',
                        'description' => 'entity users\'s entity',
                        'label' => 'Entity',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'endDate' => 
                    array (
                        'name' => 'endDate',
                        'type' => 'string',
                        'description' => 'endDate users\'s validity end date in ISO 8601 full format',
                        'label' => 'End Date',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'extra' => 
                    array (
                        'name' => 'extra',
                        'type' => 'string',
                        'description' => 'extra users\'s extra data in free format',
                        'label' => 'Extra',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'uri' => 
                    array (
                        'name' => 'uri',
                        'type' => 'url',
                        'description' => 'uri',
                        'label' => 'Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'users/',
            'classDescription' => 'Users management',
        ),
        'accessLevel' => 0,
    ),
    'DELETE' => 
    array (
        'url' => 'users/{userName}',
        'className' => 'Users',
        'path' => 'users',
        'methodName' => 'delete',
        'arguments' => 
        array (
            'userName' => 0,
        ),
        'defaults' => 
        array (
            0 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Delete user',
            'longDescription' => 'Remove user form the system',
            'url' => 'DELETE :userName',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'user identifier',
                    'name' => 'userName',
                    'label' => 'User Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'User',
                'description' => 'deleted user',
                'children' => 
                array (
                    'userName' => 
                    array (
                        'name' => 'userName',
                        'type' => 'string',
                        'description' => 'userName users\'s identifier',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                        'label' => 'User Name',
                    ),
                    'password' => 
                    array (
                        'name' => 'password',
                        'type' => 'string',
                        'description' => 'password users\'s password',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                        'label' => 'Password',
                    ),
                    'email' => 
                    array (
                        'name' => 'email',
                        'type' => 'email',
                        'description' => 'email users\'s email',
                        'label' => 'Email',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'firstName' => 
                    array (
                        'name' => 'firstName',
                        'type' => 'string',
                        'description' => 'firstName email users\'s first name',
                        'label' => 'First Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'lastName' => 
                    array (
                        'name' => 'lastName',
                        'type' => 'string',
                        'description' => 'lastName email users\'s last name',
                        'label' => 'Last Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'entity' => 
                    array (
                        'name' => 'entity',
                        'type' => 'string',
                        'description' => 'entity users\'s entity',
                        'label' => 'Entity',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'endDate' => 
                    array (
                        'name' => 'endDate',
                        'type' => 'string',
                        'description' => 'endDate users\'s validity end date in ISO 8601 full format',
                        'label' => 'End Date',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'extra' => 
                    array (
                        'name' => 'extra',
                        'type' => 'string',
                        'description' => 'extra users\'s extra data in free format',
                        'label' => 'Extra',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'uri' => 
                    array (
                        'name' => 'uri',
                        'type' => 'url',
                        'description' => 'uri',
                        'label' => 'Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'users/',
            'classDescription' => 'Users management',
        ),
        'accessLevel' => 0,
    ),
);

//==== v1 users ====

$o['v1']['users'] = array (
    'GET' => 
    array (
        'url' => 'users',
        'className' => 'Users',
        'path' => 'users',
        'methodName' => 'getAll',
        'arguments' => 
        array (
            'withLog' => 0,
            'userNameFilter' => 1,
            'firstNameFilter' => 2,
            'lastNameFilter' => 3,
            'emailAddressFilter' => 4,
            'entityFilter' => 5,
            'extraFilter' => 6,
            'order' => 7,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
            2 => NULL,
            3 => NULL,
            4 => NULL,
            5 => NULL,
            6 => NULL,
            7 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Get users list',
            'longDescription' => 'Get informations about users',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'int',
                    'description' => '[optional] If set to 1 retreive only users with records in logs, If set to retreive only users without records in logs (filter conbination is AND)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'query',
                    ),
                    'name' => 'withLog',
                    'label' => 'With Log',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                1 => 
                array (
                    'type' => 'string',
                    'description' => '[optional] Only retreive user with userName containing that string (filter conbination is AND)',
                    'name' => 'userNameFilter',
                    'label' => 'User Name Filter',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
                2 => 
                array (
                    'type' => 'string',
                    'description' => '[optional] Only retreive user with first name containing that string (filter conbination is AND)',
                    'name' => 'firstNameFilter',
                    'label' => 'First Name Filter',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
                3 => 
                array (
                    'type' => 'string',
                    'description' => '[optional] Only retreive user with last name containing that string (filter conbination is AND)',
                    'name' => 'lastNameFilter',
                    'label' => 'Last Name Filter',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
                4 => 
                array (
                    'type' => 'string',
                    'description' => '[optional] Only retreive user with email address containing that string (filter conbination is AND)',
                    'name' => 'emailAddressFilter',
                    'label' => 'Email Address Filter',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
                5 => 
                array (
                    'type' => 'string',
                    'description' => '[optional] Only retreive user with entity containing that string (filter conbination is AND)',
                    'name' => 'entityFilter',
                    'label' => 'Entity Filter',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
                6 => 
                array (
                    'type' => 'string',
                    'description' => '[optional] Only retreive user with extra data containing that string (filter conbination is AND)',
                    'name' => 'extraFilter',
                    'label' => 'Extra Filter',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
                7 => 
                array (
                    'type' => 'string',
                    'description' => '[optional] "SQL Like" order clause based on User properties',
                    'name' => 'order',
                    'label' => 'Order',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
            ),
            'url' => 'GET',
            'return' => 
            array (
                'type' => 'array',
                'description' => '',
                'properties' => 
                array (
                    'type' => 'User',
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'users/',
            'classDescription' => 'Users management',
        ),
        'accessLevel' => 0,
    ),
    'POST' => 
    array (
        'url' => 'users',
        'className' => 'Users',
        'path' => 'users',
        'methodName' => 'create',
        'arguments' => 
        array (
            'userName' => 0,
            'password' => 1,
            'email' => 2,
            'endDate' => 3,
            'firstName' => 4,
            'lastName' => 5,
            'entity' => 6,
            'extra' => 7,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
            2 => NULL,
            3 => NULL,
            4 => NULL,
            5 => NULL,
            6 => NULL,
            7 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Create user',
            'longDescription' => 'Create a new user into the system',
            'url' => 0,
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'user identitfier',
                    'name' => 'userName',
                    'label' => 'User Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                1 => 
                array (
                    'type' => 'string',
                    'description' => 'password to authenticate against OSA',
                    'name' => 'password',
                    'label' => 'Password',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                2 => 
                array (
                    'type' => 'email',
                    'description' => 'user\'s mail address',
                    'name' => 'email',
                    'label' => 'Email',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                3 => 
                array (
                    'type' => 'string',
                    'description' => 'users\'s validity end date in ISO 8601 full format',
                    'name' => 'endDate',
                    'label' => 'End Date',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                4 => 
                array (
                    'type' => 'string',
                    'description' => '[Optional] user\'s first name',
                    'name' => 'firstName',
                    'label' => 'First Name',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                5 => 
                array (
                    'type' => 'string',
                    'description' => '[Optional] user\'s last name',
                    'name' => 'lastName',
                    'label' => 'Last Name',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                6 => 
                array (
                    'type' => 'string',
                    'description' => '[Optional] user\'s entity',
                    'name' => 'entity',
                    'label' => 'Entity',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                7 => 
                array (
                    'type' => 'string',
                    'description' => '[Optional] users\'s extra data in free format',
                    'name' => 'extra',
                    'label' => 'Extra',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'User',
                'description' => 'newly created user',
                'children' => 
                array (
                    'userName' => 
                    array (
                        'name' => 'userName',
                        'type' => 'string',
                        'description' => 'userName users\'s identifier',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                        'label' => 'User Name',
                    ),
                    'password' => 
                    array (
                        'name' => 'password',
                        'type' => 'string',
                        'description' => 'password users\'s password',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                        'label' => 'Password',
                    ),
                    'email' => 
                    array (
                        'name' => 'email',
                        'type' => 'email',
                        'description' => 'email users\'s email',
                        'label' => 'Email',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'firstName' => 
                    array (
                        'name' => 'firstName',
                        'type' => 'string',
                        'description' => 'firstName email users\'s first name',
                        'label' => 'First Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'lastName' => 
                    array (
                        'name' => 'lastName',
                        'type' => 'string',
                        'description' => 'lastName email users\'s last name',
                        'label' => 'Last Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'entity' => 
                    array (
                        'name' => 'entity',
                        'type' => 'string',
                        'description' => 'entity users\'s entity',
                        'label' => 'Entity',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'endDate' => 
                    array (
                        'name' => 'endDate',
                        'type' => 'string',
                        'description' => 'endDate users\'s validity end date in ISO 8601 full format',
                        'label' => 'End Date',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'extra' => 
                    array (
                        'name' => 'extra',
                        'type' => 'string',
                        'description' => 'extra users\'s extra data in free format',
                        'label' => 'Extra',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'uri' => 
                    array (
                        'name' => 'uri',
                        'type' => 'url',
                        'description' => 'uri',
                        'label' => 'Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'users/',
            'classDescription' => 'Users management',
        ),
        'accessLevel' => 0,
    ),
);

//==== v1 services/{s0}/headers-mapping/{s1} ====

$o['v1']['services/{s0}/headers-mapping/{s1}'] = array (
    'POST' => 
    array (
        'url' => 'services/{serviceName}/headers-mapping/{userProperty}',
        'className' => 'Services',
        'path' => 'services',
        'methodName' => 'createHeadersMapping',
        'arguments' => 
        array (
            'serviceName' => 0,
            'userProperty' => 1,
            'headerName' => 2,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
            2 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Create header mapping',
            'longDescription' => 'Create header mapping form a particular service and a particular property',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'service identifier',
                    'name' => 'serviceName',
                    'label' => 'Service Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
                1 => 
                array (
                    'type' => 'string',
                    'description' => 'User property to map',
                    'name' => 'userProperty',
                    'label' => 'User Property',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
                2 => 
                array (
                    'type' => 'string',
                    'description' => 'HTTP header name',
                    'name' => 'headerName',
                    'label' => 'Header Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
            ),
            'url' => 'POST :serviceName/headers-mapping/:userProperty',
            'return' => 
            array (
                'type' => 'HeaderMapping',
                'description' => 'Created header',
                'children' => 
                array (
                    'serviceName' => 
                    array (
                        'name' => 'serviceName',
                        'type' => 'string',
                        'description' => 'serviceName Service identifier',
                        'label' => 'Service Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'headerName' => 
                    array (
                        'name' => 'headerName',
                        'type' => 'string',
                        'description' => 'headerName HTTP Header name',
                        'label' => 'Header Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'userProperty' => 
                    array (
                        'name' => 'userProperty',
                        'type' => 'string',
                        'description' => 'userProperty corresponding user property',
                        'label' => 'User Property',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'uri' => 
                    array (
                        'name' => 'uri',
                        'type' => 'url',
                        'description' => 'uri',
                        'label' => 'Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'services/',
            'classDescription' => 'Services managements',
        ),
        'accessLevel' => 0,
    ),
    'GET' => 
    array (
        'url' => 'services/{serviceName}/headers-mapping/{userProperty}',
        'className' => 'Services',
        'path' => 'services',
        'methodName' => 'getUserPropertyHeadersMapping',
        'arguments' => 
        array (
            'serviceName' => 0,
            'userProperty' => 1,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Get property headers mapping',
            'longDescription' => 'Get header mapping for a particular service and a particular user property',
            'url' => 'GET :serviceName/headers-mapping/:userProperty',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'Service identifier',
                    'name' => 'serviceName',
                    'label' => 'Service Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
                1 => 
                array (
                    'type' => 'string',
                    'description' => 'Service identifier',
                    'name' => 'userProperty',
                    'label' => 'User Property',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'array',
                'description' => 'Requested Header (array of 1 item)',
                'properties' => 
                array (
                    'type' => 'HeaderMapping',
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'services/',
            'classDescription' => 'Services managements',
        ),
        'accessLevel' => 0,
    ),
);

//==== v1 services/{s0}/headers-mapping ====

$o['v1']['services/{s0}/headers-mapping'] = array (
    'POST' => 
    array (
        'url' => 'services/{serviceName}/headers-mapping',
        'className' => 'Services',
        'path' => 'services',
        'methodName' => 'setHeadersMappings',
        'arguments' => 
        array (
            'serviceName' => 0,
            'mapping' => 1,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Create headers mapping',
            'longDescription' => 'Create headers mapping for alist of user properties for a particular header',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'Service identifier',
                    'name' => 'serviceName',
                    'label' => 'Service Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
                1 => 
                array (
                    'type' => 'array',
                    'description' => 'Headers to map',
                    'properties' => 
                    array (
                        'type' => 'HeaderMappingCreation',
                        'from' => 'body',
                    ),
                    'name' => 'mapping',
                    'label' => 'Mapping',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                        'headerName' => 
                        array (
                            'name' => 'headerName',
                            'type' => 'string',
                            'description' => 'headerName HTTP Header name',
                            'label' => 'Header Name',
                            'properties' => 
                            array (
                                'required' => true,
                            ),
                        ),
                        'userProperty' => 
                        array (
                            'name' => 'userProperty',
                            'type' => 'string',
                            'description' => 'userProperty corresponding user property',
                            'label' => 'User Property',
                            'properties' => 
                            array (
                                'required' => true,
                            ),
                        ),
                    ),
                ),
            ),
            'url' => 'POST :serviceName/headers-mapping/',
            'return' => 
            array (
                'type' => 'array',
                'description' => 'Created headers',
                'properties' => 
                array (
                    'type' => 'HeaderMapping',
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'services/',
            'classDescription' => 'Services managements',
        ),
        'accessLevel' => 0,
    ),
    'GET' => 
    array (
        'url' => 'services/{serviceName}/headers-mapping',
        'className' => 'Services',
        'path' => 'services',
        'methodName' => 'getHeadersMapping',
        'arguments' => 
        array (
            'serviceName' => 0,
        ),
        'defaults' => 
        array (
            0 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Get headers mapping',
            'longDescription' => 'Get all headers mapping for a particular service',
            'url' => 'GET :serviceName/headers-mapping',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'Service identifier',
                    'name' => 'serviceName',
                    'label' => 'Service Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'array',
                'description' => 'Headers',
                'properties' => 
                array (
                    'type' => 'HeaderMapping',
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'services/',
            'classDescription' => 'Services managements',
        ),
        'accessLevel' => 0,
    ),
    'DELETE' => 
    array (
        'url' => 'services/{serviceName}/headers-mapping',
        'className' => 'Services',
        'path' => 'services',
        'methodName' => 'deleteHeadersMapping',
        'arguments' => 
        array (
            'serviceName' => 0,
        ),
        'defaults' => 
        array (
            0 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Delete headers mapping',
            'longDescription' => 'Delete all headers mapping for a particular service',
            'url' => 'DELETE :serviceName/headers-mapping',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'Service identifier',
                    'name' => 'serviceName',
                    'label' => 'Service Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'arry',
                'description' => 'Currint list of Headers for this service',
                'properties' => 
                array (
                    'type' => 'HeaderMapping',
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'services/',
            'classDescription' => 'Services managements',
        ),
        'accessLevel' => 0,
    ),
);

//==== v1 services/{s0} ====

$o['v1']['services/{s0}'] = array (
    'GET' => 
    array (
        'url' => 'services/{serviceName}',
        'className' => 'Services',
        'path' => 'services',
        'methodName' => 'getOne',
        'arguments' => 
        array (
            'serviceName' => 0,
        ),
        'defaults' => 
        array (
            0 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Get a service',
            'longDescription' => 'Get details about a particular Service',
            'url' => 'GET :serviceName',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'Service identifier',
                    'name' => 'serviceName',
                    'label' => 'Service Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'Service',
                'description' => 'requested Service',
                'children' => 
                array (
                    'serviceName' => 
                    array (
                        'name' => 'serviceName',
                        'type' => 'string',
                        'description' => 'serviceName service identifier',
                        'label' => 'Service Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'groupName' => 
                    array (
                        'name' => 'groupName',
                        'type' => 'string',
                        'description' => 'groupName Users have to be member of this group to use this service',
                        'label' => 'Group Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'reqSec' => 
                    array (
                        'name' => 'reqSec',
                        'type' => 'int',
                        'description' => 'reqSec maximun number of request allowed per seconds',
                        'label' => 'Req Sec',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'reqDay' => 
                    array (
                        'name' => 'reqDay',
                        'type' => 'int',
                        'description' => 'reqSec maximun number of request allowed per days',
                        'label' => 'Req Day',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'reqMonth' => 
                    array (
                        'name' => 'reqMonth',
                        'type' => 'int',
                        'description' => 'reqSec maximun number of request allowed per months',
                        'label' => 'Req Month',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'isGlobalQuotasEnabled' => 
                    array (
                        'name' => 'isGlobalQuotasEnabled',
                        'type' => 'int',
                        'description' => 'isGlobalQuotasEnabled Is there global quotas management on this service? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is Global Quotas Enabled',
                    ),
                    'isUserQuotasEnabled' => 
                    array (
                        'name' => 'isUserQuotasEnabled',
                        'type' => 'int',
                        'description' => 'isGlobalQuotasEnabled Is there quotas management at user level on this service? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is User Quotas Enabled',
                    ),
                    'isIdentityForwardingEnabled' => 
                    array (
                        'name' => 'isIdentityForwardingEnabled',
                        'type' => 'int',
                        'description' => 'isIdentityForwardingEnabled Authenticated user identity is forwarded to backend? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is Identity Forwarding Enabled',
                    ),
                    'isPublished' => 
                    array (
                        'name' => 'isPublished',
                        'type' => 'int',
                        'description' => 'isPublished Is this server currently available on nodes? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is Published',
                    ),
                    'frontEndEndPoint' => 
                    array (
                        'name' => 'frontEndEndPoint',
                        'type' => 'url',
                        'description' => 'frontEndEndPoint URI on frontend node',
                        'label' => 'Front End End Point',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'backEndEndPoint' => 
                    array (
                        'name' => 'backEndEndPoint',
                        'type' => 'url',
                        'description' => 'backEndEndPoint URL to backend server',
                        'label' => 'Back End End Point',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'backEndUserName' => 
                    array (
                        'name' => 'backEndUserName',
                        'type' => 'string',
                        'description' => 'username to authenticate against backend server (basic auth)',
                        'label' => 'Back End User Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'backEndPassword' => 
                    array (
                        'name' => 'backEndPassword',
                        'type' => 'string',
                        'description' => 'password to authenticate against backend server (basic auth)',
                        'label' => 'Back End Password',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'isUserAuthenticationEnabled' => 
                    array (
                        'name' => 'isUserAuthenticationEnabled',
                        'type' => 'int',
                        'description' => 'isUserAuthenticationEnabled Is authentication enabled for this service? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is User Authentication Enabled',
                    ),
                    'isHitLoggingEnabled' => 
                    array (
                        'name' => 'isHitLoggingEnabled',
                        'type' => 'int',
                        'description' => 'isHitLoggingEnabled IS log recording activiated for this service? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is Hit Logging Enabled',
                    ),
                    'additionalConfiguration' => 
                    array (
                        'name' => 'additionalConfiguration',
                        'type' => 'string',
                        'description' => 'additionalConfiguration Additionnal Apache configuration directive (for "Location" tag)',
                        'label' => 'Additional Configuration',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'onAllNodes' => 
                    array (
                        'name' => 'onAllNodes',
                        'type' => 'int',
                        'description' => 'isHitLoggingEnabled Is this service available for all running nodes? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'On All Nodes',
                    ),
                    'isAnonymousAllowed' => 
                    array (
                        'name' => 'isAnonymousAllowed',
                        'type' => 'int',
                        'description' => 'isAnonymousAllowed Is authentication absolutly required to invoke this service or anonymous access is also possible? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is Anonymous Allowed',
                    ),
                    'loginFormUri' => 
                    array (
                        'name' => 'loginFormUri',
                        'type' => 'url',
                        'description' => 'loginFormUri Login form url to redirect to for unauthenticated access',
                        'label' => 'Login Form Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'uri' => 
                    array (
                        'name' => 'uri',
                        'type' => 'url',
                        'description' => 'uri',
                        'label' => 'Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'services/',
            'classDescription' => 'Services managements',
        ),
        'accessLevel' => 0,
    ),
    'POST' => 
    array (
        'url' => 'services/{serviceName}',
        'className' => 'Services',
        'path' => 'services',
        'methodName' => 'addService',
        'arguments' => 
        array (
            'serviceName' => 0,
            'frontEndEndPoint' => 1,
            'backEndEndPoint' => 2,
            'isPublished' => 3,
            'additionalConfiguration' => 4,
            'isHitLoggingEnabled' => 5,
            'onAllNodes' => 6,
            'isUserAuthenticationEnabled' => 7,
            'groupName' => 8,
            'isIdentityForwardingEnabled' => 9,
            'isAnonymousAllowed' => 10,
            'backEndUsername' => 11,
            'backEndPassword' => 12,
            'loginFormUri' => 13,
            'isGlobalQuotasEnabled' => 14,
            'reqSec' => 15,
            'reqDay' => 16,
            'reqMonth' => 17,
            'isUserQuotasEnabled' => 18,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
            2 => NULL,
            3 => NULL,
            4 => NULL,
            5 => NULL,
            6 => NULL,
            7 => NULL,
            8 => NULL,
            9 => NULL,
            10 => NULL,
            11 => NULL,
            12 => NULL,
            13 => NULL,
            14 => NULL,
            15 => NULL,
            16 => NULL,
            17 => NULL,
            18 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Create service',
            'longDescription' => 'Create and deplaoy a new Service',
            'url' => 0,
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'Serive identifier',
                    'name' => 'serviceName',
                    'label' => 'Service Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
                1 => 
                array (
                    'type' => 'string',
                    'description' => 'URI on frontend node',
                    'name' => 'frontEndEndPoint',
                    'label' => 'Front End End Point',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                2 => 
                array (
                    'type' => 'url',
                    'description' => 'URL to backend server',
                    'name' => 'backEndEndPoint',
                    'label' => 'Back End End Point',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                3 => 
                array (
                    'type' => 'int',
                    'description' => '[Optional] Is tis service deployed? (O: no 1: yes, default 1)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'isPublished',
                    'label' => 'Is Published',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                4 => 
                array (
                    'type' => 'string',
                    'description' => '[Optional] Additional Apache "<Location>" tag directives',
                    'name' => 'additionalConfiguration',
                    'label' => 'Additional Configuration',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                5 => 
                array (
                    'type' => 'int',
                    'description' => '[Optional] Is log recording is enabled? (O: no 1: yes, default 0)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'isHitLoggingEnabled',
                    'label' => 'Is Hit Logging Enabled',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                6 => 
                array (
                    'type' => 'string',
                    'description' => '[Optional] Is this service alavaliable on all publshed nodes? (O: no 1: yes, default 1)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'onAllNodes',
                    'label' => 'On All Nodes',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                7 => 
                array (
                    'type' => 'int',
                    'description' => '[Optional] Is user authentication enabled? (O: no 1: yes, default 0)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'isUserAuthenticationEnabled',
                    'label' => 'Is User Authentication Enabled',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                8 => 
                array (
                    'type' => 'string',
                    'description' => '[Optional] User must be a member of this group to use this service (required if isUserAuthenticationEnabled=1)',
                    'name' => 'groupName',
                    'label' => 'Group Name',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                9 => 
                array (
                    'type' => 'int',
                    'description' => '[Optional] Is authenticated user\'s identity forwarded to backend system? (O: no 1: yes, default 0)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'isIdentityForwardingEnabled',
                    'label' => 'Is Identity Forwarding Enabled',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                10 => 
                array (
                    'type' => 'int',
                    'description' => '[Optional] Is authentication absolutly required to invoke this service or anonymous access is also possible? (O: no 1: yes, default 0)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'isAnonymousAllowed',
                    'label' => 'Is Anonymous Allowed',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                11 => 
                array (
                    'type' => 'string',
                    'description' => '[Optional] username to authenticate against backend system (basic authentication), use "%auto%" to use credentials on OSA agains backend',
                    'name' => 'backEndUsername',
                    'label' => 'Back End Username',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                12 => 
                array (
                    'type' => 'string',
                    'description' => '[Optional] password to authenticate agains backend system',
                    'name' => 'backEndPassword',
                    'label' => 'Back End Password',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                13 => 
                array (
                    'type' => 'string',
                    'description' => '[Optional] Login from URL to redirecto to in case of unauthenticated access on a compliant node',
                    'name' => 'loginFormUri',
                    'label' => 'Login Form Uri',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                14 => 
                array (
                    'type' => 'int',
                    'description' => '[Optional] Is global quotas enabled? (O: no 1: yes, default 0)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'isGlobalQuotasEnabled',
                    'label' => 'Is Global Quotas Enabled',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                15 => 
                array (
                    'type' => 'int',
                    'description' => '[Optional] Maximun number of request alloed per second (Required if isGlobalQuotasEnabled=1)',
                    'name' => 'reqSec',
                    'label' => 'Req Sec',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                16 => 
                array (
                    'type' => 'int',
                    'description' => '[Optional] Maximun number of request alloed per second (Required if isGlobalQuotasEnabled=1)',
                    'name' => 'reqDay',
                    'label' => 'Req Day',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                17 => 
                array (
                    'type' => 'int',
                    'description' => '[Optional] Maximun number of request alloed per second (Required if isGlobalQuotasEnabled=1)',
                    'name' => 'reqMonth',
                    'label' => 'Req Month',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                18 => 
                array (
                    'type' => 'int',
                    'description' => '[Optional] Are quotas enabled at user level? (O: no 1: yes, default 0)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'isUserQuotasEnabled',
                    'label' => 'Is User Quotas Enabled',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'Service',
                'description' => 'created service',
                'children' => 
                array (
                    'serviceName' => 
                    array (
                        'name' => 'serviceName',
                        'type' => 'string',
                        'description' => 'serviceName service identifier',
                        'label' => 'Service Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'groupName' => 
                    array (
                        'name' => 'groupName',
                        'type' => 'string',
                        'description' => 'groupName Users have to be member of this group to use this service',
                        'label' => 'Group Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'reqSec' => 
                    array (
                        'name' => 'reqSec',
                        'type' => 'int',
                        'description' => 'reqSec maximun number of request allowed per seconds',
                        'label' => 'Req Sec',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'reqDay' => 
                    array (
                        'name' => 'reqDay',
                        'type' => 'int',
                        'description' => 'reqSec maximun number of request allowed per days',
                        'label' => 'Req Day',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'reqMonth' => 
                    array (
                        'name' => 'reqMonth',
                        'type' => 'int',
                        'description' => 'reqSec maximun number of request allowed per months',
                        'label' => 'Req Month',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'isGlobalQuotasEnabled' => 
                    array (
                        'name' => 'isGlobalQuotasEnabled',
                        'type' => 'int',
                        'description' => 'isGlobalQuotasEnabled Is there global quotas management on this service? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is Global Quotas Enabled',
                    ),
                    'isUserQuotasEnabled' => 
                    array (
                        'name' => 'isUserQuotasEnabled',
                        'type' => 'int',
                        'description' => 'isGlobalQuotasEnabled Is there quotas management at user level on this service? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is User Quotas Enabled',
                    ),
                    'isIdentityForwardingEnabled' => 
                    array (
                        'name' => 'isIdentityForwardingEnabled',
                        'type' => 'int',
                        'description' => 'isIdentityForwardingEnabled Authenticated user identity is forwarded to backend? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is Identity Forwarding Enabled',
                    ),
                    'isPublished' => 
                    array (
                        'name' => 'isPublished',
                        'type' => 'int',
                        'description' => 'isPublished Is this server currently available on nodes? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is Published',
                    ),
                    'frontEndEndPoint' => 
                    array (
                        'name' => 'frontEndEndPoint',
                        'type' => 'url',
                        'description' => 'frontEndEndPoint URI on frontend node',
                        'label' => 'Front End End Point',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'backEndEndPoint' => 
                    array (
                        'name' => 'backEndEndPoint',
                        'type' => 'url',
                        'description' => 'backEndEndPoint URL to backend server',
                        'label' => 'Back End End Point',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'backEndUserName' => 
                    array (
                        'name' => 'backEndUserName',
                        'type' => 'string',
                        'description' => 'username to authenticate against backend server (basic auth)',
                        'label' => 'Back End User Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'backEndPassword' => 
                    array (
                        'name' => 'backEndPassword',
                        'type' => 'string',
                        'description' => 'password to authenticate against backend server (basic auth)',
                        'label' => 'Back End Password',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'isUserAuthenticationEnabled' => 
                    array (
                        'name' => 'isUserAuthenticationEnabled',
                        'type' => 'int',
                        'description' => 'isUserAuthenticationEnabled Is authentication enabled for this service? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is User Authentication Enabled',
                    ),
                    'isHitLoggingEnabled' => 
                    array (
                        'name' => 'isHitLoggingEnabled',
                        'type' => 'int',
                        'description' => 'isHitLoggingEnabled IS log recording activiated for this service? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is Hit Logging Enabled',
                    ),
                    'additionalConfiguration' => 
                    array (
                        'name' => 'additionalConfiguration',
                        'type' => 'string',
                        'description' => 'additionalConfiguration Additionnal Apache configuration directive (for "Location" tag)',
                        'label' => 'Additional Configuration',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'onAllNodes' => 
                    array (
                        'name' => 'onAllNodes',
                        'type' => 'int',
                        'description' => 'isHitLoggingEnabled Is this service available for all running nodes? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'On All Nodes',
                    ),
                    'isAnonymousAllowed' => 
                    array (
                        'name' => 'isAnonymousAllowed',
                        'type' => 'int',
                        'description' => 'isAnonymousAllowed Is authentication absolutly required to invoke this service or anonymous access is also possible? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is Anonymous Allowed',
                    ),
                    'loginFormUri' => 
                    array (
                        'name' => 'loginFormUri',
                        'type' => 'url',
                        'description' => 'loginFormUri Login form url to redirect to for unauthenticated access',
                        'label' => 'Login Form Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'uri' => 
                    array (
                        'name' => 'uri',
                        'type' => 'url',
                        'description' => 'uri',
                        'label' => 'Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'services/',
            'classDescription' => 'Services managements',
        ),
        'accessLevel' => 0,
    ),
    'PUT' => 
    array (
        'url' => 'services/{serviceName}',
        'className' => 'Services',
        'path' => 'services',
        'methodName' => 'update',
        'arguments' => 
        array (
            'serviceName' => 0,
            'frontEndEndPoint' => 1,
            'backEndEndPoint' => 2,
            'isPublished' => 3,
            'additionalConfiguration' => 4,
            'isHitLoggingEnabled' => 5,
            'onAllNodes' => 6,
            'isUserAuthenticationEnabled' => 7,
            'groupName' => 8,
            'isIdentityForwardingEnabled' => 9,
            'isAnonymousAllowed' => 10,
            'backEndUsername' => 11,
            'backEndPassword' => 12,
            'loginFormUri' => 13,
            'isGlobalQuotasEnabled' => 14,
            'reqSec' => 15,
            'reqDay' => 16,
            'reqMonth' => 17,
            'isUserQuotasEnabled' => 18,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
            2 => NULL,
            3 => NULL,
            4 => NULL,
            5 => NULL,
            6 => NULL,
            7 => NULL,
            8 => NULL,
            9 => NULL,
            10 => NULL,
            11 => NULL,
            12 => NULL,
            13 => NULL,
            14 => NULL,
            15 => NULL,
            16 => NULL,
            17 => NULL,
            18 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Update a service',
            'longDescription' => '',
            'url' => 'PUT :serviceName',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'Serive identifier',
                    'name' => 'serviceName',
                    'label' => 'Service Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
                1 => 
                array (
                    'type' => 'string',
                    'description' => 'URI on frontend node',
                    'name' => 'frontEndEndPoint',
                    'label' => 'Front End End Point',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                2 => 
                array (
                    'type' => 'url',
                    'description' => 'URL to backend server',
                    'name' => 'backEndEndPoint',
                    'label' => 'Back End End Point',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                3 => 
                array (
                    'type' => 'int',
                    'description' => '[Optional] Is tis service deployed? (O: no 1: yes, default 1)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'isPublished',
                    'label' => 'Is Published',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                4 => 
                array (
                    'type' => 'string',
                    'description' => '[Optional] Additional Apache "<Location>" tag directives',
                    'name' => 'additionalConfiguration',
                    'label' => 'Additional Configuration',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                5 => 
                array (
                    'type' => 'int',
                    'description' => '[Optional] Is log recording is enabled? (O: no 1: yes, default 0)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'isHitLoggingEnabled',
                    'label' => 'Is Hit Logging Enabled',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                6 => 
                array (
                    'type' => 'string',
                    'description' => '[Optional] Is this service alavaliable on all publshed nodes? (O: no 1: yes, default 1)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'onAllNodes',
                    'label' => 'On All Nodes',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                7 => 
                array (
                    'type' => 'int',
                    'description' => '[Optional] Is user authentication enabled? (O: no 1: yes, default 0)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'isUserAuthenticationEnabled',
                    'label' => 'Is User Authentication Enabled',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                8 => 
                array (
                    'type' => 'string',
                    'description' => '[Optional] User must be a member of this group to use this service (required if isUserAuthenticationEnabled=1)',
                    'name' => 'groupName',
                    'label' => 'Group Name',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                9 => 
                array (
                    'type' => 'int',
                    'description' => '[Optional] Is authenticated user\'s identity forwarded to backend system? (O: no 1: yes, default 0)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'isIdentityForwardingEnabled',
                    'label' => 'Is Identity Forwarding Enabled',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                10 => 
                array (
                    'type' => 'int',
                    'description' => '[Optional] Is authentication absolutly required to invoke this service or anonymous access is also possible? (O: no 1: yes, default 0)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'isAnonymousAllowed',
                    'label' => 'Is Anonymous Allowed',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                11 => 
                array (
                    'type' => 'string',
                    'description' => '[Optional] username to authenticate against backend system (basic authentication), use "%auto%" to use credentials on OSA agains backend',
                    'name' => 'backEndUsername',
                    'label' => 'Back End Username',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                12 => 
                array (
                    'type' => 'string',
                    'description' => '[Optional] password to authenticate agains backend system',
                    'name' => 'backEndPassword',
                    'label' => 'Back End Password',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                13 => 
                array (
                    'type' => 'string',
                    'description' => '[Optional] Login from URL to redirecto to in case of unauthenticated access on a compliant node',
                    'name' => 'loginFormUri',
                    'label' => 'Login Form Uri',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                14 => 
                array (
                    'type' => 'int',
                    'description' => '[Optional] Is global quotas enabled? (O: no 1: yes, default 0)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'isGlobalQuotasEnabled',
                    'label' => 'Is Global Quotas Enabled',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                15 => 
                array (
                    'type' => 'int',
                    'description' => '[Optional] Maximun number of request alloed per second (Required if isGlobalQuotasEnabled=1)',
                    'name' => 'reqSec',
                    'label' => 'Req Sec',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                16 => 
                array (
                    'type' => 'int',
                    'description' => '[Optional] Maximun number of request alloed per second (Required if isGlobalQuotasEnabled=1)',
                    'name' => 'reqDay',
                    'label' => 'Req Day',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                17 => 
                array (
                    'type' => 'int',
                    'description' => '[Optional] Maximun number of request alloed per second (Required if isGlobalQuotasEnabled=1)',
                    'name' => 'reqMonth',
                    'label' => 'Req Month',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                18 => 
                array (
                    'type' => 'int',
                    'description' => '[Optional] Are quotas enabled at user level? (O: no 1: yes, default 0)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'isUserQuotasEnabled',
                    'label' => 'Is User Quotas Enabled',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'Service',
                'description' => 'Updated service',
                'children' => 
                array (
                    'serviceName' => 
                    array (
                        'name' => 'serviceName',
                        'type' => 'string',
                        'description' => 'serviceName service identifier',
                        'label' => 'Service Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'groupName' => 
                    array (
                        'name' => 'groupName',
                        'type' => 'string',
                        'description' => 'groupName Users have to be member of this group to use this service',
                        'label' => 'Group Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'reqSec' => 
                    array (
                        'name' => 'reqSec',
                        'type' => 'int',
                        'description' => 'reqSec maximun number of request allowed per seconds',
                        'label' => 'Req Sec',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'reqDay' => 
                    array (
                        'name' => 'reqDay',
                        'type' => 'int',
                        'description' => 'reqSec maximun number of request allowed per days',
                        'label' => 'Req Day',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'reqMonth' => 
                    array (
                        'name' => 'reqMonth',
                        'type' => 'int',
                        'description' => 'reqSec maximun number of request allowed per months',
                        'label' => 'Req Month',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'isGlobalQuotasEnabled' => 
                    array (
                        'name' => 'isGlobalQuotasEnabled',
                        'type' => 'int',
                        'description' => 'isGlobalQuotasEnabled Is there global quotas management on this service? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is Global Quotas Enabled',
                    ),
                    'isUserQuotasEnabled' => 
                    array (
                        'name' => 'isUserQuotasEnabled',
                        'type' => 'int',
                        'description' => 'isGlobalQuotasEnabled Is there quotas management at user level on this service? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is User Quotas Enabled',
                    ),
                    'isIdentityForwardingEnabled' => 
                    array (
                        'name' => 'isIdentityForwardingEnabled',
                        'type' => 'int',
                        'description' => 'isIdentityForwardingEnabled Authenticated user identity is forwarded to backend? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is Identity Forwarding Enabled',
                    ),
                    'isPublished' => 
                    array (
                        'name' => 'isPublished',
                        'type' => 'int',
                        'description' => 'isPublished Is this server currently available on nodes? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is Published',
                    ),
                    'frontEndEndPoint' => 
                    array (
                        'name' => 'frontEndEndPoint',
                        'type' => 'url',
                        'description' => 'frontEndEndPoint URI on frontend node',
                        'label' => 'Front End End Point',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'backEndEndPoint' => 
                    array (
                        'name' => 'backEndEndPoint',
                        'type' => 'url',
                        'description' => 'backEndEndPoint URL to backend server',
                        'label' => 'Back End End Point',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'backEndUserName' => 
                    array (
                        'name' => 'backEndUserName',
                        'type' => 'string',
                        'description' => 'username to authenticate against backend server (basic auth)',
                        'label' => 'Back End User Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'backEndPassword' => 
                    array (
                        'name' => 'backEndPassword',
                        'type' => 'string',
                        'description' => 'password to authenticate against backend server (basic auth)',
                        'label' => 'Back End Password',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'isUserAuthenticationEnabled' => 
                    array (
                        'name' => 'isUserAuthenticationEnabled',
                        'type' => 'int',
                        'description' => 'isUserAuthenticationEnabled Is authentication enabled for this service? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is User Authentication Enabled',
                    ),
                    'isHitLoggingEnabled' => 
                    array (
                        'name' => 'isHitLoggingEnabled',
                        'type' => 'int',
                        'description' => 'isHitLoggingEnabled IS log recording activiated for this service? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is Hit Logging Enabled',
                    ),
                    'additionalConfiguration' => 
                    array (
                        'name' => 'additionalConfiguration',
                        'type' => 'string',
                        'description' => 'additionalConfiguration Additionnal Apache configuration directive (for "Location" tag)',
                        'label' => 'Additional Configuration',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'onAllNodes' => 
                    array (
                        'name' => 'onAllNodes',
                        'type' => 'int',
                        'description' => 'isHitLoggingEnabled Is this service available for all running nodes? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'On All Nodes',
                    ),
                    'isAnonymousAllowed' => 
                    array (
                        'name' => 'isAnonymousAllowed',
                        'type' => 'int',
                        'description' => 'isAnonymousAllowed Is authentication absolutly required to invoke this service or anonymous access is also possible? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is Anonymous Allowed',
                    ),
                    'loginFormUri' => 
                    array (
                        'name' => 'loginFormUri',
                        'type' => 'url',
                        'description' => 'loginFormUri Login form url to redirect to for unauthenticated access',
                        'label' => 'Login Form Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'uri' => 
                    array (
                        'name' => 'uri',
                        'type' => 'url',
                        'description' => 'uri',
                        'label' => 'Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'services/',
            'classDescription' => 'Services managements',
        ),
        'accessLevel' => 0,
    ),
    'DELETE' => 
    array (
        'url' => 'services/{serviceName}',
        'className' => 'Services',
        'path' => 'services',
        'methodName' => 'delete',
        'arguments' => 
        array (
            'serviceName' => 0,
        ),
        'defaults' => 
        array (
            0 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Delete Service',
            'longDescription' => 'Remove and undeploy a particular service',
            'url' => 'DELETE :serviceName',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'Service identifier',
                    'name' => 'serviceName',
                    'label' => 'Service Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'Service',
                'description' => 'Deleted Service',
                'children' => 
                array (
                    'serviceName' => 
                    array (
                        'name' => 'serviceName',
                        'type' => 'string',
                        'description' => 'serviceName service identifier',
                        'label' => 'Service Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'groupName' => 
                    array (
                        'name' => 'groupName',
                        'type' => 'string',
                        'description' => 'groupName Users have to be member of this group to use this service',
                        'label' => 'Group Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'reqSec' => 
                    array (
                        'name' => 'reqSec',
                        'type' => 'int',
                        'description' => 'reqSec maximun number of request allowed per seconds',
                        'label' => 'Req Sec',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'reqDay' => 
                    array (
                        'name' => 'reqDay',
                        'type' => 'int',
                        'description' => 'reqSec maximun number of request allowed per days',
                        'label' => 'Req Day',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'reqMonth' => 
                    array (
                        'name' => 'reqMonth',
                        'type' => 'int',
                        'description' => 'reqSec maximun number of request allowed per months',
                        'label' => 'Req Month',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'isGlobalQuotasEnabled' => 
                    array (
                        'name' => 'isGlobalQuotasEnabled',
                        'type' => 'int',
                        'description' => 'isGlobalQuotasEnabled Is there global quotas management on this service? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is Global Quotas Enabled',
                    ),
                    'isUserQuotasEnabled' => 
                    array (
                        'name' => 'isUserQuotasEnabled',
                        'type' => 'int',
                        'description' => 'isGlobalQuotasEnabled Is there quotas management at user level on this service? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is User Quotas Enabled',
                    ),
                    'isIdentityForwardingEnabled' => 
                    array (
                        'name' => 'isIdentityForwardingEnabled',
                        'type' => 'int',
                        'description' => 'isIdentityForwardingEnabled Authenticated user identity is forwarded to backend? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is Identity Forwarding Enabled',
                    ),
                    'isPublished' => 
                    array (
                        'name' => 'isPublished',
                        'type' => 'int',
                        'description' => 'isPublished Is this server currently available on nodes? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is Published',
                    ),
                    'frontEndEndPoint' => 
                    array (
                        'name' => 'frontEndEndPoint',
                        'type' => 'url',
                        'description' => 'frontEndEndPoint URI on frontend node',
                        'label' => 'Front End End Point',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'backEndEndPoint' => 
                    array (
                        'name' => 'backEndEndPoint',
                        'type' => 'url',
                        'description' => 'backEndEndPoint URL to backend server',
                        'label' => 'Back End End Point',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'backEndUserName' => 
                    array (
                        'name' => 'backEndUserName',
                        'type' => 'string',
                        'description' => 'username to authenticate against backend server (basic auth)',
                        'label' => 'Back End User Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'backEndPassword' => 
                    array (
                        'name' => 'backEndPassword',
                        'type' => 'string',
                        'description' => 'password to authenticate against backend server (basic auth)',
                        'label' => 'Back End Password',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'isUserAuthenticationEnabled' => 
                    array (
                        'name' => 'isUserAuthenticationEnabled',
                        'type' => 'int',
                        'description' => 'isUserAuthenticationEnabled Is authentication enabled for this service? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is User Authentication Enabled',
                    ),
                    'isHitLoggingEnabled' => 
                    array (
                        'name' => 'isHitLoggingEnabled',
                        'type' => 'int',
                        'description' => 'isHitLoggingEnabled IS log recording activiated for this service? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is Hit Logging Enabled',
                    ),
                    'additionalConfiguration' => 
                    array (
                        'name' => 'additionalConfiguration',
                        'type' => 'string',
                        'description' => 'additionalConfiguration Additionnal Apache configuration directive (for "Location" tag)',
                        'label' => 'Additional Configuration',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'onAllNodes' => 
                    array (
                        'name' => 'onAllNodes',
                        'type' => 'int',
                        'description' => 'isHitLoggingEnabled Is this service available for all running nodes? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'On All Nodes',
                    ),
                    'isAnonymousAllowed' => 
                    array (
                        'name' => 'isAnonymousAllowed',
                        'type' => 'int',
                        'description' => 'isAnonymousAllowed Is authentication absolutly required to invoke this service or anonymous access is also possible? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is Anonymous Allowed',
                    ),
                    'loginFormUri' => 
                    array (
                        'name' => 'loginFormUri',
                        'type' => 'url',
                        'description' => 'loginFormUri Login form url to redirect to for unauthenticated access',
                        'label' => 'Login Form Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'uri' => 
                    array (
                        'name' => 'uri',
                        'type' => 'url',
                        'description' => 'uri',
                        'label' => 'Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'services/',
            'classDescription' => 'Services managements',
        ),
        'accessLevel' => 0,
    ),
);

//==== v1 services ====

$o['v1']['services'] = array (
    'GET' => 
    array (
        'url' => 'services',
        'className' => 'Services',
        'path' => 'services',
        'methodName' => 'getAll',
        'arguments' => 
        array (
            'withLog' => 0,
            'serviceNameFilter' => 1,
            'groupNameFilter' => 2,
            'frontEndEndPointFilter' => 3,
            'backEndEndPointFilter' => 4,
            'nodeNameFilter' => 5,
            'withQuotas' => 6,
            'isIdentityForwardingEnabledFilter' => 7,
            'isGlobalQuotasEnabledFilter' => 8,
            'isUserQuotasEnabledFilter' => 9,
            'isPublishedFilter' => 10,
            'isHitLoggingEnabledFilter' => 11,
            'isUserAuthenticationEnabledFilter' => 12,
            'additionalConfigurationFilter' => 13,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
            2 => NULL,
            3 => NULL,
            4 => NULL,
            5 => NULL,
            6 => NULL,
            7 => NULL,
            8 => NULL,
            9 => NULL,
            10 => NULL,
            11 => NULL,
            12 => NULL,
            13 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Get Services',
            'longDescription' => 'Get a list of Services',
            'url' => 'GET',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'int',
                    'description' => '[optional] If set to 1 retreive only services with records in logs, If set to retreive only services without records in logs (filter conbination is AND)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'query',
                    ),
                    'name' => 'withLog',
                    'label' => 'With Log',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                1 => 
                array (
                    'type' => 'string',
                    'description' => '[optional] Only retreive services with serviceName containing that string (filter conbination is AND)',
                    'name' => 'serviceNameFilter',
                    'label' => 'Service Name Filter',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
                2 => 
                array (
                    'type' => 'string',
                    'description' => '[optional] Only retreive services with groupName containing that string (filter conbination is AND)',
                    'name' => 'groupNameFilter',
                    'label' => 'Group Name Filter',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
                3 => 
                array (
                    'type' => 'string',
                    'description' => '[optional] Only retreive services with frontEndEndPoint containing that string (filter conbination is AND)',
                    'name' => 'frontEndEndPointFilter',
                    'label' => 'Front End End Point Filter',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
                4 => 
                array (
                    'type' => 'string',
                    'description' => '[optional] Only retreive services with backEndEndPoint containing that string (filter conbination is AND)',
                    'name' => 'backEndEndPointFilter',
                    'label' => 'Back End End Point Filter',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
                5 => 
                array (
                    'type' => 'string',
                    'description' => '[optional] Only retreive services available on that node (filter conbination is AND)',
                    'name' => 'nodeNameFilter',
                    'label' => 'Node Name Filter',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
                6 => 
                array (
                    'type' => 'int',
                    'description' => '[optional] If set to 1 retreive only services with any king of quotas activated, If set to retreive only services without any king of quotas activated (filter conbination is AND)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'query',
                    ),
                    'name' => 'withQuotas',
                    'label' => 'With Quotas',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                7 => 
                array (
                    'type' => 'int',
                    'description' => '[optional] If set to 1 retreive only services with identity forwarding enabled, If set to retreive only services with identity forwarding disabled (filter conbination is AND)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'query',
                    ),
                    'name' => 'isIdentityForwardingEnabledFilter',
                    'label' => 'Is Identity Forwarding Enabled Filter',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                8 => 
                array (
                    'type' => 'int',
                    'description' => '[optional] If set to 1 retreive only services with global quotas enabled, If set to retreive only services with global quotas disabled (filter conbination is AND)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'query',
                    ),
                    'name' => 'isGlobalQuotasEnabledFilter',
                    'label' => 'Is Global Quotas Enabled Filter',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                9 => 
                array (
                    'type' => 'int',
                    'description' => '[optional] If set to 1 retreive only services with users quotas enabled, If set to retreive only services with users quotas disabled (filter conbination is AND)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'query',
                    ),
                    'name' => 'isUserQuotasEnabledFilter',
                    'label' => 'Is User Quotas Enabled Filter',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                10 => 
                array (
                    'type' => 'int',
                    'description' => '[optional] If set to 1 retreive only services which are published on nodes, If set to retreive only services which are not published on nodes (filter conbination is AND)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'query',
                    ),
                    'name' => 'isPublishedFilter',
                    'label' => 'Is Published Filter',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                11 => 
                array (
                    'type' => 'int',
                    'description' => '[optional] If set to 1 retreive only services with logs recording enabled, If set to retreive only services with logs recording disabled (filter conbination is AND)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'query',
                    ),
                    'name' => 'isHitLoggingEnabledFilter',
                    'label' => 'Is Hit Logging Enabled Filter',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                12 => 
                array (
                    'type' => 'int',
                    'description' => '[optional] If set to 1 retreive only with user authentication enabled, If set to 1 retreive only services with user authentication disabled (filter conbination is AND)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'query',
                    ),
                    'name' => 'isUserAuthenticationEnabledFilter',
                    'label' => 'Is User Authentication Enabled Filter',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                13 => 
                array (
                    'type' => 'int',
                    'description' => '[optional] Only retreive services with additionalConfiguration containing that string (filter conbination is AND)',
                    'name' => 'additionalConfigurationFilter',
                    'label' => 'Additional Configuration Filter',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'query',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'array',
                'description' => 'List of Services',
                'properties' => 
                array (
                    'type' => 'Service',
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'services/',
            'classDescription' => 'Services managements',
        ),
        'accessLevel' => 0,
    ),
    'POST' => 
    array (
        'url' => 'services',
        'className' => 'Services',
        'path' => 'services',
        'methodName' => 'addService',
        'arguments' => 
        array (
            'serviceName' => 0,
            'frontEndEndPoint' => 1,
            'backEndEndPoint' => 2,
            'isPublished' => 3,
            'additionalConfiguration' => 4,
            'isHitLoggingEnabled' => 5,
            'onAllNodes' => 6,
            'isUserAuthenticationEnabled' => 7,
            'groupName' => 8,
            'isIdentityForwardingEnabled' => 9,
            'isAnonymousAllowed' => 10,
            'backEndUsername' => 11,
            'backEndPassword' => 12,
            'loginFormUri' => 13,
            'isGlobalQuotasEnabled' => 14,
            'reqSec' => 15,
            'reqDay' => 16,
            'reqMonth' => 17,
            'isUserQuotasEnabled' => 18,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
            2 => NULL,
            3 => NULL,
            4 => NULL,
            5 => NULL,
            6 => NULL,
            7 => NULL,
            8 => NULL,
            9 => NULL,
            10 => NULL,
            11 => NULL,
            12 => NULL,
            13 => NULL,
            14 => NULL,
            15 => NULL,
            16 => NULL,
            17 => NULL,
            18 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Create service',
            'longDescription' => 'Create and deplaoy a new Service',
            'url' => 0,
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'Serive identifier',
                    'name' => 'serviceName',
                    'label' => 'Service Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                1 => 
                array (
                    'type' => 'string',
                    'description' => 'URI on frontend node',
                    'name' => 'frontEndEndPoint',
                    'label' => 'Front End End Point',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                2 => 
                array (
                    'type' => 'url',
                    'description' => 'URL to backend server',
                    'name' => 'backEndEndPoint',
                    'label' => 'Back End End Point',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                3 => 
                array (
                    'type' => 'int',
                    'description' => '[Optional] Is tis service deployed? (O: no 1: yes, default 1)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'isPublished',
                    'label' => 'Is Published',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                4 => 
                array (
                    'type' => 'string',
                    'description' => '[Optional] Additional Apache "<Location>" tag directives',
                    'name' => 'additionalConfiguration',
                    'label' => 'Additional Configuration',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                5 => 
                array (
                    'type' => 'int',
                    'description' => '[Optional] Is log recording is enabled? (O: no 1: yes, default 0)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'isHitLoggingEnabled',
                    'label' => 'Is Hit Logging Enabled',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                6 => 
                array (
                    'type' => 'string',
                    'description' => '[Optional] Is this service alavaliable on all publshed nodes? (O: no 1: yes, default 1)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'onAllNodes',
                    'label' => 'On All Nodes',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                7 => 
                array (
                    'type' => 'int',
                    'description' => '[Optional] Is user authentication enabled? (O: no 1: yes, default 0)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'isUserAuthenticationEnabled',
                    'label' => 'Is User Authentication Enabled',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                8 => 
                array (
                    'type' => 'string',
                    'description' => '[Optional] User must be a member of this group to use this service (required if isUserAuthenticationEnabled=1)',
                    'name' => 'groupName',
                    'label' => 'Group Name',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                9 => 
                array (
                    'type' => 'int',
                    'description' => '[Optional] Is authenticated user\'s identity forwarded to backend system? (O: no 1: yes, default 0)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'isIdentityForwardingEnabled',
                    'label' => 'Is Identity Forwarding Enabled',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                10 => 
                array (
                    'type' => 'int',
                    'description' => '[Optional] Is authentication absolutly required to invoke this service or anonymous access is also possible? (O: no 1: yes, default 0)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'isAnonymousAllowed',
                    'label' => 'Is Anonymous Allowed',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                11 => 
                array (
                    'type' => 'string',
                    'description' => '[Optional] username to authenticate against backend system (basic authentication), use "%auto%" to use credentials on OSA agains backend',
                    'name' => 'backEndUsername',
                    'label' => 'Back End Username',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                12 => 
                array (
                    'type' => 'string',
                    'description' => '[Optional] password to authenticate agains backend system',
                    'name' => 'backEndPassword',
                    'label' => 'Back End Password',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                13 => 
                array (
                    'type' => 'string',
                    'description' => '[Optional] Login from URL to redirecto to in case of unauthenticated access on a compliant node',
                    'name' => 'loginFormUri',
                    'label' => 'Login Form Uri',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                14 => 
                array (
                    'type' => 'int',
                    'description' => '[Optional] Is global quotas enabled? (O: no 1: yes, default 0)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'isGlobalQuotasEnabled',
                    'label' => 'Is Global Quotas Enabled',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
                15 => 
                array (
                    'type' => 'int',
                    'description' => '[Optional] Maximun number of request alloed per second (Required if isGlobalQuotasEnabled=1)',
                    'name' => 'reqSec',
                    'label' => 'Req Sec',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                16 => 
                array (
                    'type' => 'int',
                    'description' => '[Optional] Maximun number of request alloed per second (Required if isGlobalQuotasEnabled=1)',
                    'name' => 'reqDay',
                    'label' => 'Req Day',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                17 => 
                array (
                    'type' => 'int',
                    'description' => '[Optional] Maximun number of request alloed per second (Required if isGlobalQuotasEnabled=1)',
                    'name' => 'reqMonth',
                    'label' => 'Req Month',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                18 => 
                array (
                    'type' => 'int',
                    'description' => '[Optional] Are quotas enabled at user level? (O: no 1: yes, default 0)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'isUserQuotasEnabled',
                    'label' => 'Is User Quotas Enabled',
                    'default' => NULL,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'Service',
                'description' => 'created service',
                'children' => 
                array (
                    'serviceName' => 
                    array (
                        'name' => 'serviceName',
                        'type' => 'string',
                        'description' => 'serviceName service identifier',
                        'label' => 'Service Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'groupName' => 
                    array (
                        'name' => 'groupName',
                        'type' => 'string',
                        'description' => 'groupName Users have to be member of this group to use this service',
                        'label' => 'Group Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'reqSec' => 
                    array (
                        'name' => 'reqSec',
                        'type' => 'int',
                        'description' => 'reqSec maximun number of request allowed per seconds',
                        'label' => 'Req Sec',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'reqDay' => 
                    array (
                        'name' => 'reqDay',
                        'type' => 'int',
                        'description' => 'reqSec maximun number of request allowed per days',
                        'label' => 'Req Day',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'reqMonth' => 
                    array (
                        'name' => 'reqMonth',
                        'type' => 'int',
                        'description' => 'reqSec maximun number of request allowed per months',
                        'label' => 'Req Month',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'isGlobalQuotasEnabled' => 
                    array (
                        'name' => 'isGlobalQuotasEnabled',
                        'type' => 'int',
                        'description' => 'isGlobalQuotasEnabled Is there global quotas management on this service? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is Global Quotas Enabled',
                    ),
                    'isUserQuotasEnabled' => 
                    array (
                        'name' => 'isUserQuotasEnabled',
                        'type' => 'int',
                        'description' => 'isGlobalQuotasEnabled Is there quotas management at user level on this service? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is User Quotas Enabled',
                    ),
                    'isIdentityForwardingEnabled' => 
                    array (
                        'name' => 'isIdentityForwardingEnabled',
                        'type' => 'int',
                        'description' => 'isIdentityForwardingEnabled Authenticated user identity is forwarded to backend? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is Identity Forwarding Enabled',
                    ),
                    'isPublished' => 
                    array (
                        'name' => 'isPublished',
                        'type' => 'int',
                        'description' => 'isPublished Is this server currently available on nodes? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is Published',
                    ),
                    'frontEndEndPoint' => 
                    array (
                        'name' => 'frontEndEndPoint',
                        'type' => 'url',
                        'description' => 'frontEndEndPoint URI on frontend node',
                        'label' => 'Front End End Point',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'backEndEndPoint' => 
                    array (
                        'name' => 'backEndEndPoint',
                        'type' => 'url',
                        'description' => 'backEndEndPoint URL to backend server',
                        'label' => 'Back End End Point',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'backEndUserName' => 
                    array (
                        'name' => 'backEndUserName',
                        'type' => 'string',
                        'description' => 'username to authenticate against backend server (basic auth)',
                        'label' => 'Back End User Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'backEndPassword' => 
                    array (
                        'name' => 'backEndPassword',
                        'type' => 'string',
                        'description' => 'password to authenticate against backend server (basic auth)',
                        'label' => 'Back End Password',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'isUserAuthenticationEnabled' => 
                    array (
                        'name' => 'isUserAuthenticationEnabled',
                        'type' => 'int',
                        'description' => 'isUserAuthenticationEnabled Is authentication enabled for this service? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is User Authentication Enabled',
                    ),
                    'isHitLoggingEnabled' => 
                    array (
                        'name' => 'isHitLoggingEnabled',
                        'type' => 'int',
                        'description' => 'isHitLoggingEnabled IS log recording activiated for this service? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is Hit Logging Enabled',
                    ),
                    'additionalConfiguration' => 
                    array (
                        'name' => 'additionalConfiguration',
                        'type' => 'string',
                        'description' => 'additionalConfiguration Additionnal Apache configuration directive (for "Location" tag)',
                        'label' => 'Additional Configuration',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'onAllNodes' => 
                    array (
                        'name' => 'onAllNodes',
                        'type' => 'int',
                        'description' => 'isHitLoggingEnabled Is this service available for all running nodes? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'On All Nodes',
                    ),
                    'isAnonymousAllowed' => 
                    array (
                        'name' => 'isAnonymousAllowed',
                        'type' => 'int',
                        'description' => 'isAnonymousAllowed Is authentication absolutly required to invoke this service or anonymous access is also possible? (O: no, 1: yes)',
                        'properties' => 
                        array (
                            'choice' => 
                            array (
                                0 => '0',
                                1 => '1',
                            ),
                            'required' => true,
                        ),
                        'label' => 'Is Anonymous Allowed',
                    ),
                    'loginFormUri' => 
                    array (
                        'name' => 'loginFormUri',
                        'type' => 'url',
                        'description' => 'loginFormUri Login form url to redirect to for unauthenticated access',
                        'label' => 'Login Form Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'uri' => 
                    array (
                        'name' => 'uri',
                        'type' => 'url',
                        'description' => 'uri',
                        'label' => 'Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'services/',
            'classDescription' => 'Services managements',
        ),
        'accessLevel' => 0,
    ),
);

//==== v1 services/{s0}/quotas/unset ====

$o['v1']['services/{s0}/quotas/unset'] = array (
    'GET' => 
    array (
        'url' => 'services/{serviceName}/quotas/unset',
        'className' => 'Services',
        'path' => 'services',
        'methodName' => 'getUnsetQuotasForService',
        'arguments' => 
        array (
            'serviceName' => 0,
        ),
        'defaults' => 
        array (
            0 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Get Users without quotas',
            'longDescription' => 'Get a list of user for who are allowed to use this Service but User quotas are not set but required',
            'url' => 'GET :serviceName/quotas/unset',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'name' => 'serviceName',
                    'label' => 'Service Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'array',
                'description' => 'Users list',
                'properties' => 
                array (
                    'type' => 'User',
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'services/',
            'classDescription' => 'Services managements',
        ),
        'accessLevel' => 0,
    ),
);

//==== v1 services/{s0}/quotas ====

$o['v1']['services/{s0}/quotas'] = array (
    'GET' => 
    array (
        'url' => 'services/{serviceName}/quotas',
        'className' => 'Services',
        'path' => 'services',
        'methodName' => 'userQuotasForService',
        'arguments' => 
        array (
            'serviceName' => 0,
        ),
        'defaults' => 
        array (
            0 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Get user quotas',
            'longDescription' => 'Get user quotas defined for a particular service',
            'url' => 'GET :serviceName/quotas',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'Service identifier',
                    'name' => 'serviceName',
                    'label' => 'Service Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'array',
                'description' => '',
                'properties' => 
                array (
                    'type' => 'Quota',
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'services/',
            'classDescription' => 'Services managements',
        ),
        'accessLevel' => 0,
    ),
);

//==== v1 services/{s0}/quotas/{s1} ====

$o['v1']['services/{s0}/quotas/{s1}'] = array (
    'GET' => 
    array (
        'url' => 'services/{serviceName}/quotas/{userName}',
        'className' => 'Services',
        'path' => 'services',
        'methodName' => 'userQuotasForServiceAndUser',
        'arguments' => 
        array (
            'serviceName' => 0,
            'userName' => 1,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Get user quotas',
            'longDescription' => 'Get user quotas defined for a particular service',
            'url' => 'GET :serviceName/quotas/:userName',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'Service identifier',
                    'name' => 'serviceName',
                    'label' => 'Service Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
                1 => 
                array (
                    'type' => 'string',
                    'description' => 'User identifier',
                    'name' => 'userName',
                    'label' => 'User Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'array',
                'description' => '',
                'properties' => 
                array (
                    'type' => 'Quota',
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'services/',
            'classDescription' => 'Services managements',
        ),
        'accessLevel' => 0,
    ),
    'POST' => 
    array (
        'url' => 'services/{serviceName}/quotas/{userName}',
        'className' => 'Services',
        'path' => 'services',
        'methodName' => 'addUserQuotasForService',
        'arguments' => 
        array (
            'serviceName' => 0,
            'userName' => 1,
            'reqSec' => 2,
            'reqDay' => 3,
            'reqMonth' => 4,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
            2 => NULL,
            3 => NULL,
            4 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Create user quotas',
            'longDescription' => 'Create quotas for a particular user and a particular service',
            'url' => 'POST :serviceName/quotas/:userName',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'Service identifier',
                    'name' => 'serviceName',
                    'label' => 'Service Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
                1 => 
                array (
                    'type' => 'string',
                    'description' => 'User identifier',
                    'name' => 'userName',
                    'label' => 'User Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
                2 => 
                array (
                    'type' => 'string',
                    'description' => 'Maximum number of allowed requests per seconds',
                    'name' => 'reqSec',
                    'label' => 'Req Sec',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                3 => 
                array (
                    'type' => 'string',
                    'description' => 'Maximum number of allowed requests per days',
                    'name' => 'reqDay',
                    'label' => 'Req Day',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                4 => 
                array (
                    'type' => 'string',
                    'description' => 'Maximum number of allowed requests per months',
                    'name' => 'reqMonth',
                    'label' => 'Req Month',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'Quota',
                'description' => 'Created Quota',
                'children' => 
                array (
                    'serviceName' => 
                    array (
                        'name' => 'serviceName',
                        'type' => 'string',
                        'description' => 'relative service identifier',
                        'label' => 'Service Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'serviceUri' => 
                    array (
                        'name' => 'serviceUri',
                        'type' => 'url',
                        'description' => 'relative service uri',
                        'label' => 'Service Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'userName' => 
                    array (
                        'name' => 'userName',
                        'type' => 'string',
                        'description' => 'relative user identifier',
                        'label' => 'User Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'userUri' => 
                    array (
                        'name' => 'userUri',
                        'type' => 'url',
                        'description' => 'relative user uri',
                        'label' => 'User Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'reqSec' => 
                    array (
                        'name' => 'reqSec',
                        'type' => 'int',
                        'description' => 'reqSec maximum number of request per seconds allowed',
                        'label' => 'Req Sec',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'reqDay' => 
                    array (
                        'name' => 'reqDay',
                        'type' => 'int',
                        'description' => 'reqDay maximum number of request per days allowed',
                        'label' => 'Req Day',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'reqMonth' => 
                    array (
                        'name' => 'reqMonth',
                        'type' => 'int',
                        'description' => 'reqMonth maximum number of request pre months allowed',
                        'label' => 'Req Month',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'uri' => 
                    array (
                        'name' => 'uri',
                        'type' => 'url',
                        'description' => 'uri',
                        'label' => 'Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'services/',
            'classDescription' => 'Services managements',
        ),
        'accessLevel' => 0,
    ),
    'PUT' => 
    array (
        'url' => 'services/{serviceName}/quotas/{userName}',
        'className' => 'Services',
        'path' => 'services',
        'methodName' => 'updateUserQuotasForService',
        'arguments' => 
        array (
            'serviceName' => 0,
            'userName' => 1,
            'reqSec' => 2,
            'reqDay' => 3,
            'reqMonth' => 4,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
            2 => NULL,
            3 => NULL,
            4 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Update user quotas',
            'longDescription' => 'Update quotas for a particular user and a particular service',
            'url' => 'PUT :serviceName/quotas/:userName',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'Service identifier',
                    'name' => 'serviceName',
                    'label' => 'Service Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
                1 => 
                array (
                    'type' => 'string',
                    'description' => 'User identifier',
                    'name' => 'userName',
                    'label' => 'User Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
                2 => 
                array (
                    'type' => 'string',
                    'description' => 'Maximum number of allowed requests per seconds',
                    'name' => 'reqSec',
                    'label' => 'Req Sec',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                3 => 
                array (
                    'type' => 'string',
                    'description' => 'Maximum number of allowed requests per days',
                    'name' => 'reqDay',
                    'label' => 'Req Day',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
                4 => 
                array (
                    'type' => 'string',
                    'description' => 'Maximum number of allowed requests per months',
                    'name' => 'reqMonth',
                    'label' => 'Req Month',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'body',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'Quota',
                'description' => 'Created Quota',
                'children' => 
                array (
                    'serviceName' => 
                    array (
                        'name' => 'serviceName',
                        'type' => 'string',
                        'description' => 'relative service identifier',
                        'label' => 'Service Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'serviceUri' => 
                    array (
                        'name' => 'serviceUri',
                        'type' => 'url',
                        'description' => 'relative service uri',
                        'label' => 'Service Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'userName' => 
                    array (
                        'name' => 'userName',
                        'type' => 'string',
                        'description' => 'relative user identifier',
                        'label' => 'User Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'userUri' => 
                    array (
                        'name' => 'userUri',
                        'type' => 'url',
                        'description' => 'relative user uri',
                        'label' => 'User Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'reqSec' => 
                    array (
                        'name' => 'reqSec',
                        'type' => 'int',
                        'description' => 'reqSec maximum number of request per seconds allowed',
                        'label' => 'Req Sec',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'reqDay' => 
                    array (
                        'name' => 'reqDay',
                        'type' => 'int',
                        'description' => 'reqDay maximum number of request per days allowed',
                        'label' => 'Req Day',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'reqMonth' => 
                    array (
                        'name' => 'reqMonth',
                        'type' => 'int',
                        'description' => 'reqMonth maximum number of request pre months allowed',
                        'label' => 'Req Month',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'uri' => 
                    array (
                        'name' => 'uri',
                        'type' => 'url',
                        'description' => 'uri',
                        'label' => 'Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'services/',
            'classDescription' => 'Services managements',
        ),
        'accessLevel' => 0,
    ),
    'DELETE' => 
    array (
        'url' => 'services/{serviceName}/quotas/{userName}',
        'className' => 'Services',
        'path' => 'services',
        'methodName' => 'deleteUserQuotasForService',
        'arguments' => 
        array (
            'serviceName' => 0,
            'userName' => 1,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Delete users quotas',
            'longDescription' => 'Delete quotzas for a particular service and a particular user',
            'url' => 'DELETE :serviceName/quotas/:userName',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'Service identifier',
                    'name' => 'serviceName',
                    'label' => 'Service Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
                1 => 
                array (
                    'type' => 'string',
                    'description' => 'User identifier',
                    'name' => 'userName',
                    'label' => 'User Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'Quota',
                'description' => 'Deleted quota',
                'children' => 
                array (
                    'serviceName' => 
                    array (
                        'name' => 'serviceName',
                        'type' => 'string',
                        'description' => 'relative service identifier',
                        'label' => 'Service Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'serviceUri' => 
                    array (
                        'name' => 'serviceUri',
                        'type' => 'url',
                        'description' => 'relative service uri',
                        'label' => 'Service Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'userName' => 
                    array (
                        'name' => 'userName',
                        'type' => 'string',
                        'description' => 'relative user identifier',
                        'label' => 'User Name',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'userUri' => 
                    array (
                        'name' => 'userUri',
                        'type' => 'url',
                        'description' => 'relative user uri',
                        'label' => 'User Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'reqSec' => 
                    array (
                        'name' => 'reqSec',
                        'type' => 'int',
                        'description' => 'reqSec maximum number of request per seconds allowed',
                        'label' => 'Req Sec',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'reqDay' => 
                    array (
                        'name' => 'reqDay',
                        'type' => 'int',
                        'description' => 'reqDay maximum number of request per days allowed',
                        'label' => 'Req Day',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'reqMonth' => 
                    array (
                        'name' => 'reqMonth',
                        'type' => 'int',
                        'description' => 'reqMonth maximum number of request pre months allowed',
                        'label' => 'Req Month',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    'uri' => 
                    array (
                        'name' => 'uri',
                        'type' => 'url',
                        'description' => 'uri',
                        'label' => 'Uri',
                        'properties' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'services/',
            'classDescription' => 'Services managements',
        ),
        'accessLevel' => 0,
    ),
);

//==== v1 services/{s0}/nodes ====

$o['v1']['services/{s0}/nodes'] = array (
    'GET' => 
    array (
        'url' => 'services/{serviceName}/nodes',
        'className' => 'Services',
        'path' => 'services',
        'methodName' => 'getNodesForService',
        'arguments' => 
        array (
            'serviceName' => 0,
        ),
        'defaults' => 
        array (
            0 => NULL,
        ),
        'metadata' => 
        array (
            'description' => 'Get Nodes where service is availables',
            'longDescription' => '',
            'url' => 'GET :serviceName/nodes',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'Service identifier',
                    'name' => 'serviceName',
                    'label' => 'Service Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'ServiceNode',
                'description' => 'All Nodes with pulication indicator',
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'services/',
            'classDescription' => 'Services managements',
        ),
        'accessLevel' => 0,
    ),
    'POST' => 
    array (
        'url' => 'services/{serviceName}/nodes',
        'className' => 'Services',
        'path' => 'services',
        'methodName' => 'defineNodesForService',
        'arguments' => 
        array (
            'serviceName' => 0,
            'nodes' => 1,
            'noApply' => 2,
        ),
        'defaults' => 
        array (
            0 => NULL,
            1 => NULL,
            2 => 0,
        ),
        'metadata' => 
        array (
            'description' => 'Publish on Nodes',
            'longDescription' => 'Publish a particular Service on a Node lsit',
            'url' => 'POST :serviceName/nodes',
            'param' => 
            array (
                0 => 
                array (
                    'type' => 'string',
                    'description' => 'Service identifier',
                    'name' => 'serviceName',
                    'label' => 'Service Name',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                    'properties' => 
                    array (
                        'from' => 'path',
                    ),
                ),
                1 => 
                array (
                    'type' => 'array',
                    'description' => 'Nodes identifiers list',
                    'properties' => 
                    array (
                        'type' => 'string',
                        'from' => 'body',
                    ),
                    'name' => 'nodes',
                    'label' => 'Nodes',
                    'default' => NULL,
                    'required' => true,
                    'children' => 
                    array (
                    ),
                ),
                2 => 
                array (
                    'type' => 'int',
                    'description' => 'Apply configuration immediatly? (0: no, 1: yes, default 1)',
                    'properties' => 
                    array (
                        'choice' => 
                        array (
                            0 => '0',
                            1 => '1',
                        ),
                        'from' => 'body',
                    ),
                    'name' => 'noApply',
                    'label' => 'No Apply',
                    'default' => 0,
                    'required' => false,
                    'children' => 
                    array (
                    ),
                ),
            ),
            'return' => 
            array (
                'type' => 'array',
                'description' => 'Node on which servie is available',
                'properties' => 
                array (
                    'type' => 'ServiceNode',
                ),
            ),
            'scope' => 
            array (
                '*' => '',
            ),
            'resourcePath' => 'services/',
            'classDescription' => 'Services managements',
        ),
        'accessLevel' => 0,
    ),
);

//==================== apiVersionMap ====================

$o['apiVersionMap'] = array();

//==== apiVersionMap Luracast\Restler\Resources ====

$o['apiVersionMap']['Luracast\Restler\Resources'] = array (
    1 => 'Luracast\\Restler\\Resources',
);

//==== apiVersionMap Counters ====

$o['apiVersionMap']['Counters'] = array (
    1 => 'Counters',
);

//==== apiVersionMap Groups ====

$o['apiVersionMap']['Groups'] = array (
    1 => 'Groups',
);

//==== apiVersionMap Logs ====

$o['apiVersionMap']['Logs'] = array (
    1 => 'Logs',
);

//==== apiVersionMap Nodes ====

$o['apiVersionMap']['Nodes'] = array (
    1 => 'Nodes',
);

//==== apiVersionMap Users ====

$o['apiVersionMap']['Users'] = array (
    1 => 'Users',
);

//==== apiVersionMap Services ====

$o['apiVersionMap']['Services'] = array (
    1 => 'Services',
);
return $o;