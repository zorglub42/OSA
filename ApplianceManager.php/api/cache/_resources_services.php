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
            "path": "/services",
            "description": "Services managements",
            "operations": [
                {
                    "httpMethod": "GET",
                    "nickname": "retrieveAll",
                    "responseClass": "Array[Service]",
                    "parameters": [
                        {
                            "name": "withLog",
                            "description": "[optional] If set to 1 retreive only services with records in logs, If set to retreive only services without records in logs (filter conbination is AND).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "int",
                            "allowableValues": {
                                "valueType": "LIST",
                                "values": [
                                    "0",
                                    "1"
                                ]
                            }
                        },
                        {
                            "name": "serviceNameFilter",
                            "description": "[optional] Only retreive services with serviceName containing that string (filter conbination is AND).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "groupNameFilter",
                            "description": "[optional] Only retreive services with groupName containing that string (filter conbination is AND).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "frontEndEndPointFilter",
                            "description": "[optional] Only retreive services with frontEndEndPoint containing that string (filter conbination is AND).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "backEndEndPointFilter",
                            "description": "[optional] Only retreive services with backEndEndPoint containing that string (filter conbination is AND).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "nodeNameFilter",
                            "description": "[optional] Only retreive services available on that node (filter conbination is AND).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "withQuotas",
                            "description": "[optional] If set to 1 retreive only services with any king of quotas activated, If set to retreive only services without any king of quotas activated (filter conbination is AND).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "int",
                            "allowableValues": {
                                "valueType": "LIST",
                                "values": [
                                    "0",
                                    "1"
                                ]
                            }
                        },
                        {
                            "name": "isIdentityForwardingEnabledFilter",
                            "description": "[optional] If set to 1 retreive only services with identity forwarding enabled, If set to retreive only services with identity forwarding disabled (filter conbination is AND).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "int",
                            "allowableValues": {
                                "valueType": "LIST",
                                "values": [
                                    "0",
                                    "1"
                                ]
                            }
                        },
                        {
                            "name": "isGlobalQuotasEnabledFilter",
                            "description": "[optional] If set to 1 retreive only services with global quotas enabled, If set to retreive only services with global quotas disabled (filter conbination is AND).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "int",
                            "allowableValues": {
                                "valueType": "LIST",
                                "values": [
                                    "0",
                                    "1"
                                ]
                            }
                        },
                        {
                            "name": "isUserQuotasEnabledFilter",
                            "description": "[optional] If set to 1 retreive only services with users quotas enabled, If set to retreive only services with users quotas disabled (filter conbination is AND).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "int",
                            "allowableValues": {
                                "valueType": "LIST",
                                "values": [
                                    "0",
                                    "1"
                                ]
                            }
                        },
                        {
                            "name": "isPublishedFilter",
                            "description": "[optional] If set to 1 retreive only services which are published on nodes, If set to retreive only services which are not published on nodes (filter conbination is AND).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "int",
                            "allowableValues": {
                                "valueType": "LIST",
                                "values": [
                                    "0",
                                    "1"
                                ]
                            }
                        },
                        {
                            "name": "isHitLoggingEnabledFilter",
                            "description": "[optional] If set to 1 retreive only services with logs recording enabled, If set to retreive only services with logs recording disabled (filter conbination is AND).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "int",
                            "allowableValues": {
                                "valueType": "LIST",
                                "values": [
                                    "0",
                                    "1"
                                ]
                            }
                        },
                        {
                            "name": "isUserAuthenticationEnabledFilter",
                            "description": "[optional] If set to 1 retreive only with user authentication enabled, If set to 1 retreive only services with user authentication disabled (filter conbination is AND).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "int",
                            "allowableValues": {
                                "valueType": "LIST",
                                "values": [
                                    "0",
                                    "1"
                                ]
                            }
                        },
                        {
                            "name": "additionalConfigurationFilter",
                            "description": "[optional] Only retreive services with additionalConfiguration containing that string (filter conbination is AND).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "int"
                        }
                    ],
                    "summary": "Get Services&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Get a list of Services",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/services/{serviceName}",
            "description": "Services managements",
            "operations": [
                {
                    "httpMethod": "GET",
                    "nickname": "retrieveOne",
                    "responseClass": "Service",
                    "parameters": [
                        {
                            "name": "serviceName",
                            "description": "Service identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Get a service&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Get details about a particular Service",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/services/{serviceName}/headers-mapping",
            "description": "Services managements",
            "operations": [
                {
                    "httpMethod": "GET",
                    "nickname": "retrieveHeadersMapping",
                    "responseClass": "Array[HeaderMapping]",
                    "parameters": [
                        {
                            "name": "serviceName",
                            "description": "Service identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Get headers mapping&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Get all headers mapping for a particular service",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/services/{serviceName}/headers-mapping/{userProperty}",
            "description": "Services managements",
            "operations": [
                {
                    "httpMethod": "GET",
                    "nickname": "retrieveUserPropertyHeadersMapping",
                    "responseClass": "Array[HeaderMapping]",
                    "parameters": [
                        {
                            "name": "serviceName",
                            "description": "Service identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "userProperty",
                            "description": "Service identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Get property headers mapping&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Get header mapping for a particular service and a particular user property",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/services/{serviceName}/nodes",
            "description": "Services managements",
            "operations": [
                {
                    "httpMethod": "GET",
                    "nickname": "retrieveNodesForService",
                    "responseClass": "void",
                    "parameters": [
                        {
                            "name": "serviceName",
                            "description": "Service identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Get Nodes where service is availables&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/services/{serviceName}/quotas",
            "description": "Services managements",
            "operations": [
                {
                    "httpMethod": "GET",
                    "nickname": "userQuotasForService",
                    "responseClass": "Array[Quota]",
                    "parameters": [
                        {
                            "name": "serviceName",
                            "description": "Service identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Get user quotas&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Get user quotas defined for a particular service",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/services/{serviceName}/quotas/unset",
            "description": "Services managements",
            "operations": [
                {
                    "httpMethod": "GET",
                    "nickname": "retrieveUnsetQuotasForService",
                    "responseClass": "Array[User]",
                    "parameters": [
                        {
                            "name": "serviceName",
                            "description": "",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Get Users without quotas&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Get a list of user for who are allowed to use this Service but User quotas are not set but required",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/services/{serviceName}/quotas/{userName}",
            "description": "Services managements",
            "operations": [
                {
                    "httpMethod": "GET",
                    "nickname": "userQuotasForServiceAndUser",
                    "responseClass": "Array[Quota]",
                    "parameters": [
                        {
                            "name": "serviceName",
                            "description": "Service identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "userName",
                            "description": "User identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Get user quotas&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Get user quotas defined for a particular service",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/services",
            "description": "Services managements",
            "operations": [
                {
                    "httpMethod": "POST",
                    "nickname": "addService_",
                    "responseClass": "Service",
                    "parameters": [
                        {
                            "name": "REQUEST_BODY",
                            "description": "Paste JSON data here with the following properties.<hr/>serviceName : <tag>string</tag>  <i>(required)</i> - Serive identifier.<hr/>frontEndEndPoint : <tag>string</tag>  <i>(required)</i> - URI on frontend node.<hr/>backEndEndPoint : <tag>url</tag>  <i>(required)</i> - URL to backend server.<hr/>isPublished : <tag>int</tag>  - [Optional] Is tis service deployed? (O: no 1: yes, default 1).<hr/>additionalConfiguration : <tag>string</tag>  - [Optional] Additional Apache \\"<Location>\\" tag directives.<hr/>isHitLoggingEnabled : <tag>int</tag>  - [Optional] Is log recording is enabled? (O: no 1: yes, default 0).<hr/>onAllNodes : <tag>string</tag>  - [Optional] Is this service alavaliable on all publshed nodes? (O: no 1: yes, default 1).<hr/>isUserAuthenticationEnabled : <tag>int</tag>  - [Optional] Is user authentication enabled? (O: no 1: yes, default 0).<hr/>groupName : <tag>string</tag>  - [Optional] User must be a member of this group to use this service (required if isUserAuthenticationEnabled=1).<hr/>isIdentityForwardingEnabled : <tag>int</tag>  - [Optional] Is authenticated user\'s identity forwarded to backend system? (O: no 1: yes, default 0).<hr/>isAnonymousAllowed : <tag>int</tag>  - [Optional] Is authentication absolutly required to invoke this service or anonymous access is also possible? (O: no 1: yes, default 0).<hr/>backEndUsername : <tag>string</tag>  - [Optional] username to authenticate against backend system (basic authentication), use \\"%auto%\\" to use credentials on OSA agains backend.<hr/>backEndPassword : <tag>string</tag>  - [Optional] password to authenticate agains backend system.<hr/>loginFormUri : <tag>string</tag>  - [Optional] Login from URL to redirecto to in case of unauthenticated access on a compliant node.<hr/>isGlobalQuotasEnabled : <tag>int</tag>  - [Optional] Is global quotas enabled? (O: no 1: yes, default 0).<hr/>reqSec : <tag>int</tag>  - [Optional] Maximun number of request alloed per second (Required if isGlobalQuotasEnabled=1).<hr/>reqDay : <tag>int</tag>  - [Optional] Maximun number of request alloed per second (Required if isGlobalQuotasEnabled=1).<hr/>reqMonth : <tag>int</tag>  - [Optional] Maximun number of request alloed per second (Required if isGlobalQuotasEnabled=1).<hr/>isUserQuotasEnabled : <tag>int</tag>  - [Optional] Are quotas enabled at user level? (O: no 1: yes, default 0).",
                            "required": true,
                            "defaultValue": "{\\n    \\"serviceName\\": \\"\\",\\n    \\"frontEndEndPoint\\": \\"\\",\\n    \\"backEndEndPoint\\": \\"\\"\\n}",
                            "paramType": "body",
                            "allowMultiple": false,
                            "dataType": "Object"
                        }
                    ],
                    "summary": "Create service&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Create and deplaoy a new Service",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/services/{serviceName}",
            "description": "Services managements",
            "operations": [
                {
                    "httpMethod": "POST",
                    "nickname": "addService",
                    "responseClass": "Service",
                    "parameters": [
                        {
                            "name": "serviceName",
                            "description": "Serive identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "REQUEST_BODY",
                            "description": "Paste JSON data here with the following properties.<hr/>frontEndEndPoint : <tag>string</tag>  <i>(required)</i> - URI on frontend node.<hr/>backEndEndPoint : <tag>url</tag>  <i>(required)</i> - URL to backend server.<hr/>isPublished : <tag>int</tag>  - [Optional] Is tis service deployed? (O: no 1: yes, default 1).<hr/>additionalConfiguration : <tag>string</tag>  - [Optional] Additional Apache \\"<Location>\\" tag directives.<hr/>isHitLoggingEnabled : <tag>int</tag>  - [Optional] Is log recording is enabled? (O: no 1: yes, default 0).<hr/>onAllNodes : <tag>string</tag>  - [Optional] Is this service alavaliable on all publshed nodes? (O: no 1: yes, default 1).<hr/>isUserAuthenticationEnabled : <tag>int</tag>  - [Optional] Is user authentication enabled? (O: no 1: yes, default 0).<hr/>groupName : <tag>string</tag>  - [Optional] User must be a member of this group to use this service (required if isUserAuthenticationEnabled=1).<hr/>isIdentityForwardingEnabled : <tag>int</tag>  - [Optional] Is authenticated user\'s identity forwarded to backend system? (O: no 1: yes, default 0).<hr/>isAnonymousAllowed : <tag>int</tag>  - [Optional] Is authentication absolutly required to invoke this service or anonymous access is also possible? (O: no 1: yes, default 0).<hr/>backEndUsername : <tag>string</tag>  - [Optional] username to authenticate against backend system (basic authentication), use \\"%auto%\\" to use credentials on OSA agains backend.<hr/>backEndPassword : <tag>string</tag>  - [Optional] password to authenticate agains backend system.<hr/>loginFormUri : <tag>string</tag>  - [Optional] Login from URL to redirecto to in case of unauthenticated access on a compliant node.<hr/>isGlobalQuotasEnabled : <tag>int</tag>  - [Optional] Is global quotas enabled? (O: no 1: yes, default 0).<hr/>reqSec : <tag>int</tag>  - [Optional] Maximun number of request alloed per second (Required if isGlobalQuotasEnabled=1).<hr/>reqDay : <tag>int</tag>  - [Optional] Maximun number of request alloed per second (Required if isGlobalQuotasEnabled=1).<hr/>reqMonth : <tag>int</tag>  - [Optional] Maximun number of request alloed per second (Required if isGlobalQuotasEnabled=1).<hr/>isUserQuotasEnabled : <tag>int</tag>  - [Optional] Are quotas enabled at user level? (O: no 1: yes, default 0).",
                            "required": true,
                            "defaultValue": "{\\n    \\"frontEndEndPoint\\": \\"\\",\\n    \\"backEndEndPoint\\": \\"\\"\\n}",
                            "paramType": "body",
                            "allowMultiple": false,
                            "dataType": "Object"
                        }
                    ],
                    "summary": "Create service&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Create and deplaoy a new Service",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/services/{serviceName}/headers-mapping",
            "description": "Services managements",
            "operations": [
                {
                    "httpMethod": "POST",
                    "nickname": "setHeadersMappings",
                    "responseClass": "Array[HeaderMapping]",
                    "parameters": [
                        {
                            "name": "serviceName",
                            "description": "Service identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "mapping",
                            "description": "Paste JSON data here with an array of objects with the following properties.<hr/>headerName : <tag>string</tag>  - headerName HTTP Header name<hr/>userProperty : <tag>string</tag>  - userProperty corresponding user property",
                            "paramType": "body",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "Array[HeaderMappingCreation]",
                            "defaultValue": "[\\n    {\\n        \\"headerName\\": \\"\\",\\n        \\"userProperty\\": \\"\\"\\n    }\\n]"
                        }
                    ],
                    "summary": "Create headers mapping&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Create headers mapping for alist of user properties for a particular header",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/services/{serviceName}/headers-mapping/{userProperty}",
            "description": "Services managements",
            "operations": [
                {
                    "httpMethod": "POST",
                    "nickname": "createHeadersMapping",
                    "responseClass": "HeaderMapping",
                    "parameters": [
                        {
                            "name": "serviceName",
                            "description": "service identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "userProperty",
                            "description": "User property to map.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "REQUEST_BODY",
                            "description": "Paste JSON data here with the following property.<hr/>headerName : <tag>string</tag>  <i>(required)</i> - HTTP header name.",
                            "required": true,
                            "defaultValue": "{\\n    \\"headerName\\": \\"\\"\\n}",
                            "paramType": "body",
                            "allowMultiple": false,
                            "dataType": "Object"
                        }
                    ],
                    "summary": "Create header mapping&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Create header mapping form a particular service and a particular property",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/services/{serviceName}/nodes",
            "description": "Services managements",
            "operations": [
                {
                    "httpMethod": "POST",
                    "nickname": "defineNodesForService",
                    "responseClass": "Array",
                    "parameters": [
                        {
                            "name": "serviceName",
                            "description": "Service identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "REQUEST_BODY",
                            "description": "Paste JSON data here with the following properties.<hr/>nodes : <tag>Array[string]</tag>  <i>(required)</i> - Nodes identifiers list.<hr/>noApply : <tag>int</tag>  - Apply configuration immediatly? (0: no, 1: yes, default 1).",
                            "required": true,
                            "defaultValue": "{\\n    \\"nodes\\": \\"\\"\\n}",
                            "paramType": "body",
                            "allowMultiple": false,
                            "dataType": "Object"
                        }
                    ],
                    "summary": "Publish on Nodes&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Publish a particular Service on a Node lsit",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/services/{serviceName}/quotas/{userName}",
            "description": "Services managements",
            "operations": [
                {
                    "httpMethod": "POST",
                    "nickname": "addUserQuotasForService",
                    "responseClass": "Quota",
                    "parameters": [
                        {
                            "name": "serviceName",
                            "description": "Service identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "userName",
                            "description": "User identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "REQUEST_BODY",
                            "description": "Paste JSON data here with the following properties.<hr/>reqSec : <tag>string</tag>  <i>(required)</i> - Maximum number of allowed requests per seconds.<hr/>reqDay : <tag>string</tag>  <i>(required)</i> - Maximum number of allowed requests per days.<hr/>reqMonth : <tag>string</tag>  <i>(required)</i> - Maximum number of allowed requests per months.",
                            "required": true,
                            "defaultValue": "{\\n    \\"reqSec\\": \\"\\",\\n    \\"reqDay\\": \\"\\",\\n    \\"reqMonth\\": \\"\\"\\n}",
                            "paramType": "body",
                            "allowMultiple": false,
                            "dataType": "Object"
                        }
                    ],
                    "summary": "Create user quotas&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Create quotas for a particular user and a particular service",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/services/{serviceName}",
            "description": "Services managements",
            "operations": [
                {
                    "httpMethod": "PUT",
                    "nickname": "update",
                    "responseClass": "Service",
                    "parameters": [
                        {
                            "name": "serviceName",
                            "description": "Serive identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "REQUEST_BODY",
                            "description": "Paste JSON data here with the following properties.<hr/>frontEndEndPoint : <tag>string</tag>  <i>(required)</i> - URI on frontend node.<hr/>backEndEndPoint : <tag>url</tag>  <i>(required)</i> - URL to backend server.<hr/>isPublished : <tag>int</tag>  - [Optional] Is tis service deployed? (O: no 1: yes, default 1).<hr/>additionalConfiguration : <tag>string</tag>  - [Optional] Additional Apache \\"<Location>\\" tag directives.<hr/>isHitLoggingEnabled : <tag>int</tag>  - [Optional] Is log recording is enabled? (O: no 1: yes, default 0).<hr/>onAllNodes : <tag>string</tag>  - [Optional] Is this service alavaliable on all publshed nodes? (O: no 1: yes, default 1).<hr/>isUserAuthenticationEnabled : <tag>int</tag>  - [Optional] Is user authentication enabled? (O: no 1: yes, default 0).<hr/>groupName : <tag>string</tag>  - [Optional] User must be a member of this group to use this service (required if isUserAuthenticationEnabled=1).<hr/>isIdentityForwardingEnabled : <tag>int</tag>  - [Optional] Is authenticated user\'s identity forwarded to backend system? (O: no 1: yes, default 0).<hr/>isAnonymousAllowed : <tag>int</tag>  - [Optional] Is authentication absolutly required to invoke this service or anonymous access is also possible? (O: no 1: yes, default 0).<hr/>backEndUsername : <tag>string</tag>  - [Optional] username to authenticate against backend system (basic authentication), use \\"%auto%\\" to use credentials on OSA agains backend.<hr/>backEndPassword : <tag>string</tag>  - [Optional] password to authenticate agains backend system.<hr/>loginFormUri : <tag>string</tag>  - [Optional] Login from URL to redirecto to in case of unauthenticated access on a compliant node.<hr/>isGlobalQuotasEnabled : <tag>int</tag>  - [Optional] Is global quotas enabled? (O: no 1: yes, default 0).<hr/>reqSec : <tag>int</tag>  - [Optional] Maximun number of request alloed per second (Required if isGlobalQuotasEnabled=1).<hr/>reqDay : <tag>int</tag>  - [Optional] Maximun number of request alloed per second (Required if isGlobalQuotasEnabled=1).<hr/>reqMonth : <tag>int</tag>  - [Optional] Maximun number of request alloed per second (Required if isGlobalQuotasEnabled=1).<hr/>isUserQuotasEnabled : <tag>int</tag>  - [Optional] Are quotas enabled at user level? (O: no 1: yes, default 0).",
                            "required": true,
                            "defaultValue": "{\\n    \\"frontEndEndPoint\\": \\"\\",\\n    \\"backEndEndPoint\\": \\"\\"\\n}",
                            "paramType": "body",
                            "allowMultiple": false,
                            "dataType": "Object"
                        }
                    ],
                    "summary": "Update a service&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/services/{serviceName}/quotas/{userName}",
            "description": "Services managements",
            "operations": [
                {
                    "httpMethod": "PUT",
                    "nickname": "updateUserQuotasForService",
                    "responseClass": "Quota",
                    "parameters": [
                        {
                            "name": "serviceName",
                            "description": "Service identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "userName",
                            "description": "User identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "REQUEST_BODY",
                            "description": "Paste JSON data here with the following properties.<hr/>reqSec : <tag>string</tag>  <i>(required)</i> - Maximum number of allowed requests per seconds.<hr/>reqDay : <tag>string</tag>  <i>(required)</i> - Maximum number of allowed requests per days.<hr/>reqMonth : <tag>string</tag>  <i>(required)</i> - Maximum number of allowed requests per months.",
                            "required": true,
                            "defaultValue": "{\\n    \\"reqSec\\": \\"\\",\\n    \\"reqDay\\": \\"\\",\\n    \\"reqMonth\\": \\"\\"\\n}",
                            "paramType": "body",
                            "allowMultiple": false,
                            "dataType": "Object"
                        }
                    ],
                    "summary": "Update user quotas&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Update quotas for a particular user and a particular service",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/services/{serviceName}",
            "description": "Services managements",
            "operations": [
                {
                    "httpMethod": "DELETE",
                    "nickname": "remove",
                    "responseClass": "Service",
                    "parameters": [
                        {
                            "name": "serviceName",
                            "description": "Service identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Delete Service&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Remove and undeploy a particular service",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/services/{serviceName}/headers-mapping",
            "description": "Services managements",
            "operations": [
                {
                    "httpMethod": "DELETE",
                    "nickname": "removeHeadersMapping",
                    "responseClass": "void",
                    "parameters": [
                        {
                            "name": "serviceName",
                            "description": "Service identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Delete headers mapping&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Delete all headers mapping for a particular service",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/services/{serviceName}/quotas/{userName}",
            "description": "Services managements",
            "operations": [
                {
                    "httpMethod": "DELETE",
                    "nickname": "removeUserQuotasForService",
                    "responseClass": "Quota",
                    "parameters": [
                        {
                            "name": "serviceName",
                            "description": "Service identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "userName",
                            "description": "User identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Delete users quotas&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Delete quotzas for a particular service and a particular user",
                    "errorResponses": []
                }
            ]
        }
    ],
    "resourcePath": "/services",
    "models": {
        "HeaderMapping": {
            "id": "HeaderMapping",
            "properties": {
                "serviceName": {
                    "type": "string",
                    "description": "serviceName Service identifier"
                },
                "headerName": {
                    "type": "string",
                    "description": "headerName HTTP Header name"
                },
                "userProperty": {
                    "type": "string",
                    "description": "userProperty corresponding user property"
                },
                "uri": {
                    "type": "url",
                    "description": "uri"
                }
            }
        },
        "HeaderMappingCreation": {
            "id": "HeaderMappingCreation",
            "properties": {
                "headerName": {
                    "type": "string",
                    "description": "headerName HTTP Header name"
                },
                "userProperty": {
                    "type": "string",
                    "description": "userProperty corresponding user property"
                }
            }
        },
        "Service": {
            "id": "Service",
            "properties": {
                "serviceName": {
                    "type": "string",
                    "description": "serviceName service identifier"
                },
                "groupName": {
                    "type": "string",
                    "description": "groupName Users have to be member of this group to use this service"
                },
                "reqSec": {
                    "type": "int",
                    "description": "reqSec maximun number of request allowed per seconds"
                },
                "reqDay": {
                    "type": "int",
                    "description": "reqSec maximun number of request allowed per days"
                },
                "reqMonth": {
                    "type": "int",
                    "description": "reqSec maximun number of request allowed per months"
                },
                "isGlobalQuotasEnabled": {
                    "type": "int",
                    "description": "isGlobalQuotasEnabled Is there global quotas management on this service? (O: no, 1: yes)"
                },
                "isUserQuotasEnabled": {
                    "type": "int",
                    "description": "isGlobalQuotasEnabled Is there quotas management at user level on this service? (O: no, 1: yes)"
                },
                "isIdentityForwardingEnabled": {
                    "type": "int",
                    "description": "isIdentityForwardingEnabled Authenticated user identity is forwarded to backend? (O: no, 1: yes)"
                },
                "isPublished": {
                    "type": "int",
                    "description": "isPublished Is this server currently available on nodes? (O: no, 1: yes)"
                },
                "frontEndEndPoint": {
                    "type": "url",
                    "description": "frontEndEndPoint URI on frontend node"
                },
                "backEndEndPoint": {
                    "type": "url",
                    "description": "backEndEndPoint URL to backend server"
                },
                "backEndUserName": {
                    "type": "string",
                    "description": "username to authenticate against backend server (basic auth)"
                },
                "backEndPassword": {
                    "type": "string",
                    "description": "password to authenticate against backend server (basic auth)"
                },
                "isUserAuthenticationEnabled": {
                    "type": "int",
                    "description": "isUserAuthenticationEnabled Is authentication enabled for this service? (O: no, 1: yes)"
                },
                "isHitLoggingEnabled": {
                    "type": "int",
                    "description": "isHitLoggingEnabled IS log recording activiated for this service? (O: no, 1: yes)"
                },
                "additionalConfiguration": {
                    "type": "string",
                    "description": "additionalConfiguration Additionnal Apache configuration directive (for \\"Location\\" tag)"
                },
                "onAllNodes": {
                    "type": "int",
                    "description": "isHitLoggingEnabled Is this service available for all running nodes? (O: no, 1: yes)"
                },
                "isAnonymousAllowed": {
                    "type": "int",
                    "description": "isAnonymousAllowed Is authentication absolutly required to invoke this service or anonymous access is also possible? (O: no, 1: yes)"
                },
                "loginFormUri": {
                    "type": "url",
                    "description": "loginFormUri Login form url to redirect to for unauthenticated access"
                },
                "uri": {
                    "type": "url",
                    "description": "uri"
                }
            }
        },
        "User": {
            "id": "User",
            "properties": {
                "userName": {
                    "type": "string",
                    "description": "userName users\'s identifier",
                    "required": true
                },
                "password": {
                    "type": "string",
                    "description": "password users\'s password",
                    "required": true
                },
                "email": {
                    "type": "email",
                    "description": "email users\'s email"
                },
                "firstName": {
                    "type": "string",
                    "description": "firstName email users\'s first name"
                },
                "lastName": {
                    "type": "string",
                    "description": "lastName email users\'s last name"
                },
                "entity": {
                    "type": "string",
                    "description": "entity users\'s entity"
                },
                "endDate": {
                    "type": "string",
                    "description": "endDate users\'s validity end date in ISO 8601 full format"
                },
                "extra": {
                    "type": "string",
                    "description": "extra users\'s extra data in free format"
                },
                "uri": {
                    "type": "url",
                    "description": "uri"
                }
            }
        },
        "Quota": {
            "id": "Quota",
            "properties": {
                "serviceName": {
                    "type": "string",
                    "description": "relative service identifier"
                },
                "serviceUri": {
                    "type": "url",
                    "description": "relative service uri"
                },
                "userName": {
                    "type": "string",
                    "description": "relative user identifier"
                },
                "userUri": {
                    "type": "url",
                    "description": "relative user uri"
                },
                "reqSec": {
                    "type": "int",
                    "description": "reqSec maximum number of request per seconds allowed"
                },
                "reqDay": {
                    "type": "int",
                    "description": "reqDay maximum number of request per days allowed"
                },
                "reqMonth": {
                    "type": "int",
                    "description": "reqMonth maximum number of request pre months allowed"
                },
                "uri": {
                    "type": "url",
                    "description": "uri"
                }
            }
        }
    }
}';