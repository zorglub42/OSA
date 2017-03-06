<?php return '{
    "apiVersion": "1",
    "swaggerVersion": "1.1",
    "basePath": "https://localhost:6443",
    "produces": [
        "application/json",
        "application/x-www-form-urlencoded"
    ],
    "consumes": [
        "application/json",
        "application/x-www-form-urlencoded",
        "multipart/form-data"
    ],
    "apis": [
        {
            "path": "/counters",
            "description": "Counters API",
            "operations": [
                {
                    "httpMethod": "GET",
                    "nickname": "retrieveAll",
                    "responseClass": "Array[Counter]",
                    "parameters": [
                        {
                            "name": "resourceName",
                            "description": "related resource identifier filter.",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "userName",
                            "description": "related user identifier filter.",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "timeUnit",
                            "description": "related time timeUnit (S: Second, D: Day, M: Month).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "string",
                            "allowableValues": {
                                "valueType": "LIST",
                                "values": [
                                    "S",
                                    "D",
                                    "M"
                                ]
                            }
                        }
                    ],
                    "summary": "Get counters&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Get counters list",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/counters/excedeed",
            "description": "Counters API",
            "operations": [
                {
                    "httpMethod": "GET",
                    "nickname": "retrieveExcedeed",
                    "responseClass": "ExcedeedCounter",
                    "parameters": [
                        {
                            "name": "resourceNameFilter",
                            "description": "[optional] Only retreive counters with resourceName containing that string (filter conbination is OR).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "userNameFilter",
                            "description": "[optional] Only retreive counters with userName containing that string (filter conbination is OR).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Get excedeed&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Get all excedeed counters",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/counters/{counterName}",
            "description": "Counters API",
            "operations": [
                {
                    "httpMethod": "GET",
                    "nickname": "retrieveOne",
                    "responseClass": "Counter",
                    "parameters": [
                        {
                            "name": "counterName",
                            "description": "counter identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Get a counter&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Get a particular counter",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/counters/{counterName}",
            "description": "Counters API",
            "operations": [
                {
                    "httpMethod": "PUT",
                    "nickname": "update",
                    "responseClass": "Counter",
                    "parameters": [
                        {
                            "name": "counterName",
                            "description": "identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "REQUEST_BODY",
                            "description": "Paste JSON data here with the following property.<hr/>value : <tag>int</tag>  <i>(required)</i> - value to set.",
                            "required": true,
                            "defaultValue": "{\\n    \\"value\\": \\"\\"\\n}",
                            "paramType": "body",
                            "allowMultiple": false,
                            "dataType": "Object"
                        }
                    ],
                    "summary": "Update a counter&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Update counter value",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/counters/{counterName}",
            "description": "Counters API",
            "operations": [
                {
                    "httpMethod": "DELETE",
                    "nickname": "remove",
                    "responseClass": "Counter",
                    "parameters": [
                        {
                            "name": "counterName",
                            "description": "counter identifier to remove.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Delete&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Delete a counter",
                    "errorResponses": []
                }
            ]
        }
    ],
    "resourcePath": "/counters",
    "models": {
        "ExcedeedCounter": {
            "id": "ExcedeedCounter",
            "properties": {
                "maxValue": {
                    "type": "string",
                    "description": ""
                },
                "counterName": {
                    "type": "string",
                    "description": "counter name"
                },
                "resourceName": {
                    "type": "string",
                    "description": "controled resource (service)"
                },
                "timeUnit": {
                    "type": "string",
                    "description": "time unit for this counter (S: second, D: day, M: Month)"
                },
                "timeValue": {
                    "type": "string",
                    "description": "timeValue reference time for tis counter"
                },
                "value": {
                    "type": "int",
                    "description": "value counter value"
                },
                "userName": {
                    "type": "string",
                    "description": "userName relative user"
                },
                "uri": {
                    "type": "url",
                    "description": "uri"
                }
            }
        },
        "Counter": {
            "id": "Counter",
            "properties": {
                "counterName": {
                    "type": "string",
                    "description": "counter name"
                },
                "resourceName": {
                    "type": "string",
                    "description": "controled resource (service)"
                },
                "timeUnit": {
                    "type": "string",
                    "description": "time unit for this counter (S: second, D: day, M: Month)"
                },
                "timeValue": {
                    "type": "string",
                    "description": "timeValue reference time for tis counter"
                },
                "value": {
                    "type": "int",
                    "description": "value counter value"
                },
                "userName": {
                    "type": "string",
                    "description": "userName relative user"
                },
                "uri": {
                    "type": "url",
                    "description": "uri"
                }
            }
        }
    }
}';