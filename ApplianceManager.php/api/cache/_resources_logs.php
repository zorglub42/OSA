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
            "path": "/logs",
            "description": "Logs management",
            "operations": [
                {
                    "httpMethod": "GET",
                    "nickname": "retrieveAll",
                    "responseClass": "LogsPage",
                    "parameters": [
                        {
                            "name": "serviceName",
                            "description": "[optional] Only retreive logs with serviceName containing that string (filter conbination is AND).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "userName",
                            "description": "[optional] Only retreive logs with userName containing that string (filter conbination is AND).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "status",
                            "description": "[optional] Only retreive logs with HTTP return status equals to this parameter (filter conbination is AND).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "int"
                        },
                        {
                            "name": "message",
                            "description": "[optional] Only retreive logs with message containing that string (filter conbination is AND).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "frontEndEndPoint",
                            "description": "[optional] Only retreive logs with frontEndEndPoint containing that string (filter conbination is AND).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "from",
                            "description": "[optional] Only retreive logs from this date in ISO 8601 full format (filter conbination is AND).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "until",
                            "description": "[optional] Only retreive logs untill this date in ISO 8601 full format (filter conbination is AND).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "offset",
                            "description": "page number.",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "int"
                        },
                        {
                            "name": "order",
                            "description": "[optional] \\"SQL Like\\" order clause based on Log properties.",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Get Logs&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Get paginated logs",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/logs/{id}",
            "description": "Logs management",
            "operations": [
                {
                    "httpMethod": "GET",
                    "nickname": "retrieveOne",
                    "responseClass": "Log",
                    "parameters": [
                        {
                            "name": "id",
                            "description": "Log identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "int"
                        }
                    ],
                    "summary": "Get a Log&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Get a particular log details",
                    "errorResponses": []
                }
            ]
        }
    ],
    "resourcePath": "/logs",
    "models": {
        "Log": {
            "id": "Log",
            "properties": {
                "id": {
                    "type": "int",
                    "description": "id log identifier"
                },
                "message": {
                    "type": "string",
                    "description": "message message Logged message"
                },
                "frontEndUri": {
                    "type": "uri",
                    "description": "front end uri invoked"
                },
                "status": {
                    "type": "int",
                    "description": "status HTTP Response status"
                },
                "serviceName": {
                    "type": "string",
                    "description": "serviceName service invoked"
                },
                "userName": {
                    "type": "string",
                    "description": "userName [optional] authentifed user"
                },
                "timeStamp": {
                    "type": "string",
                    "description": "timeStamp hit date in ISO 8601 full format"
                },
                "uri": {
                    "type": "url",
                    "description": "uri"
                }
            }
        },
        "LogsPage": {
            "id": "LogsPage",
            "properties": {
                "length": {
                    "type": "int",
                    "description": "length total logs count"
                },
                "previous": {
                    "type": "uri",
                    "description": "previous link to previous page"
                },
                "logs": {
                    "type": "Array",
                    "description": "List of Networks (see /networks/{netId} resource for details)",
                    "item": {
                        "type": "Log"
                    }
                },
                "next": {
                    "type": "uri",
                    "description": "previous link to next page"
                }
            }
        }
    }
}';