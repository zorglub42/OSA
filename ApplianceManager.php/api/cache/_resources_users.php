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
            "path": "/users",
            "description": "Users management",
            "operations": [
                {
                    "httpMethod": "GET",
                    "nickname": "retrieveAll",
                    "responseClass": "Array[User]",
                    "parameters": [
                        {
                            "name": "withLog",
                            "description": "[optional] If set to 1 retreive only users with records in logs, If set to retreive only users without records in logs (filter conbination is AND).",
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
                            "name": "userNameFilter",
                            "description": "[optional] Only retreive user with userName containing that string (filter conbination is AND).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "firstNameFilter",
                            "description": "[optional] Only retreive user with first name containing that string (filter conbination is AND).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "lastNameFilter",
                            "description": "[optional] Only retreive user with last name containing that string (filter conbination is AND).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "emailAddressFilter",
                            "description": "[optional] Only retreive user with email address containing that string (filter conbination is AND).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "entityFilter",
                            "description": "[optional] Only retreive user with entity containing that string (filter conbination is AND).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "extraFilter",
                            "description": "[optional] Only retreive user with extra data containing that string (filter conbination is AND).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "order",
                            "description": "[optional] \\"SQL Like\\" order clause based on User properties.",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Get users list&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Get informations about users",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/users/me",
            "description": "Users management",
            "operations": [
                {
                    "httpMethod": "GET",
                    "nickname": "whoAmI",
                    "responseClass": "User",
                    "parameters": [],
                    "summary": "Get current user&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Get connected user description",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/users/{userName}",
            "description": "Users management",
            "operations": [
                {
                    "httpMethod": "GET",
                    "nickname": "retrieveOne",
                    "responseClass": "User",
                    "parameters": [
                        {
                            "name": "userName",
                            "description": "user\'s identifer.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Get a user&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Get informations about a user",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/users/{userName}/groups",
            "description": "Users management",
            "operations": [
                {
                    "httpMethod": "GET",
                    "nickname": "geListOfGroupForUser",
                    "responseClass": "Array[Group]",
                    "parameters": [
                        {
                            "name": "userName",
                            "description": "user identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Get groups membership&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Get list of group where the user is a member",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/users/{userName}/groups/available",
            "description": "Users management",
            "operations": [
                {
                    "httpMethod": "GET",
                    "nickname": "retrieveAvailableGroupForUser",
                    "responseClass": "Array[Group]",
                    "parameters": [
                        {
                            "name": "userName",
                            "description": "user identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Get available groups&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Get groups where user is not yet a member",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/users/{userName}/groups/{groupName}",
            "description": "Users management",
            "operations": [
                {
                    "httpMethod": "GET",
                    "nickname": "retrieveGroupForUser",
                    "responseClass": "Group",
                    "parameters": [
                        {
                            "name": "userName",
                            "description": "user identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "groupName",
                            "description": "group identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Get group membership&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Get a particular group membership for a particular user",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/users/{userName}/quotas",
            "description": "Users management",
            "operations": [
                {
                    "httpMethod": "GET",
                    "nickname": "retrieveAllQuotasForUser",
                    "responseClass": "Array[Quota]",
                    "parameters": [
                        {
                            "name": "userName",
                            "description": "user identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Get user\'s quotas&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Reteive all defined quotas for a particular user",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/users/{userName}/quotas/unset",
            "description": "Users management",
            "operations": [
                {
                    "httpMethod": "GET",
                    "nickname": "retrieveUnsetQuotaForUSer",
                    "responseClass": "Array[Quota]",
                    "parameters": [
                        {
                            "name": "userName",
                            "description": "user identifer.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Get unset quotas&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Get quotas witch are not yet defined for a particular user (based on services requiring users quotas settings)",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/users/{userName}/quotas/{serviceName}",
            "description": "Users management",
            "operations": [
                {
                    "httpMethod": "GET",
                    "nickname": "retrieveQuotasForUserAndService",
                    "responseClass": "Quota",
                    "parameters": [
                        {
                            "name": "userName",
                            "description": "user identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "serviceName",
                            "description": "service identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Get user\'s quota for a service&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Reteive defined quotas for a particular user and a particular service",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/users",
            "description": "Users management",
            "operations": [
                {
                    "httpMethod": "POST",
                    "nickname": "create_",
                    "responseClass": "User",
                    "parameters": [
                        {
                            "name": "REQUEST_BODY",
                            "description": "Paste JSON data here with the following properties.<hr/>userName : <tag>string</tag>  <i>(required)</i> - user identitfier.<hr/>password : <tag>string</tag>  <i>(required)</i> - password to authenticate against OSA.<hr/>email : <tag>email</tag>  <i>(required)</i> - user\'s mail address.<hr/>endDate : <tag>string</tag>  <i>(required)</i> - users\'s validity end date in ISO 8601 full format.<hr/>firstName : <tag>string</tag>  - [Optional] user\'s first name.<hr/>lastName : <tag>string</tag>  - [Optional] user\'s last name.<hr/>entity : <tag>string</tag>  - [Optional] user\'s entity.<hr/>extra : <tag>string</tag>  - [Optional] users\'s extra data in free format.",
                            "required": true,
                            "defaultValue": "{\\n    \\"userName\\": \\"\\",\\n    \\"password\\": \\"\\",\\n    \\"email\\": \\"\\",\\n    \\"endDate\\": \\"\\"\\n}",
                            "paramType": "body",
                            "allowMultiple": false,
                            "dataType": "Object"
                        }
                    ],
                    "summary": "Create user&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Create a new user into the system",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/users/{userName}",
            "description": "Users management",
            "operations": [
                {
                    "httpMethod": "POST",
                    "nickname": "create",
                    "responseClass": "User",
                    "parameters": [
                        {
                            "name": "userName",
                            "description": "user identitfier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "REQUEST_BODY",
                            "description": "Paste JSON data here with the following properties.<hr/>password : <tag>string</tag>  <i>(required)</i> - password to authenticate against OSA.<hr/>email : <tag>email</tag>  <i>(required)</i> - user\'s mail address.<hr/>endDate : <tag>string</tag>  <i>(required)</i> - users\'s validity end date in ISO 8601 full format.<hr/>firstName : <tag>string</tag>  - [Optional] user\'s first name.<hr/>lastName : <tag>string</tag>  - [Optional] user\'s last name.<hr/>entity : <tag>string</tag>  - [Optional] user\'s entity.<hr/>extra : <tag>string</tag>  - [Optional] users\'s extra data in free format.",
                            "required": true,
                            "defaultValue": "{\\n    \\"password\\": \\"\\",\\n    \\"email\\": \\"\\",\\n    \\"endDate\\": \\"\\"\\n}",
                            "paramType": "body",
                            "allowMultiple": false,
                            "dataType": "Object"
                        }
                    ],
                    "summary": "Create user&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Create a new user into the system",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/users/{userName}/groups/{groupName}",
            "description": "Users management",
            "operations": [
                {
                    "httpMethod": "POST",
                    "nickname": "addUserGroup",
                    "responseClass": "Group",
                    "parameters": [
                        {
                            "name": "userName",
                            "description": "user identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "groupName",
                            "description": "group identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Add group&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Add a paraticular user to a particular group",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/users/{userName}/quotas/{serviceName}",
            "description": "Users management",
            "operations": [
                {
                    "httpMethod": "POST",
                    "nickname": "createQuotaForUser",
                    "responseClass": "Quota",
                    "parameters": [
                        {
                            "name": "userName",
                            "description": "user identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "serviceName",
                            "description": "group identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "REQUEST_BODY",
                            "description": "Paste JSON data here with the following properties.<hr/>reqSec : <tag>int</tag>  <i>(required)</i> - maximum number of request per seconds allowed.<hr/>reqDay : <tag>int</tag>  <i>(required)</i> - maximum number of request per days allowed.<hr/>reqMonth : <tag>int</tag>  <i>(required)</i> - maximum number of request per months allowed.",
                            "required": true,
                            "defaultValue": "{\\n    \\"reqSec\\": \\"\\",\\n    \\"reqDay\\": \\"\\",\\n    \\"reqMonth\\": \\"\\"\\n}",
                            "paramType": "body",
                            "allowMultiple": false,
                            "dataType": "Object"
                        }
                    ],
                    "summary": "Add quotas&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Add quotas on a particular service to a particular user",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/users/me/password",
            "description": "Users management",
            "operations": [
                {
                    "httpMethod": "PUT",
                    "nickname": "resetPassword",
                    "responseClass": "User",
                    "parameters": [
                        {
                            "name": "REQUEST_BODY",
                            "description": "Paste JSON data here with the following properties.<hr/>oldPassword : <tag>string</tag>  <i>(required)</i> - new password.<hr/>newPassword : <tag>string</tag>  <i>(required)</i> - new password.",
                            "required": true,
                            "defaultValue": "{\\n    \\"oldPassword\\": \\"\\",\\n    \\"newPassword\\": \\"\\"\\n}",
                            "paramType": "body",
                            "allowMultiple": false,
                            "dataType": "Object"
                        }
                    ],
                    "summary": "Change password&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Change connected user password",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/users/{userName}",
            "description": "Users management",
            "operations": [
                {
                    "httpMethod": "PUT",
                    "nickname": "update",
                    "responseClass": "User",
                    "parameters": [
                        {
                            "name": "userName",
                            "description": "user identitfier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "REQUEST_BODY",
                            "description": "Paste JSON data here with the following properties.<hr/>password : <tag>string</tag>  <i>(required)</i> - password to authenticate against OSA.<hr/>email : <tag>email</tag>  <i>(required)</i> - user\'s mail address.<hr/>endDate : <tag>string</tag>  <i>(required)</i> - users\'s validity end date in ISO 8601 full format.<hr/>firstName : <tag>string</tag>  - [Optional] user\'s first name.<hr/>lastName : <tag>string</tag>  - [Optional] user\'s last name.<hr/>entity : <tag>string</tag>  - [Optional] user\'s entity.<hr/>extra : <tag>string</tag>  - [Optional] users\'s extra data in free format.",
                            "required": true,
                            "defaultValue": "{\\n    \\"password\\": \\"\\",\\n    \\"email\\": \\"\\",\\n    \\"endDate\\": \\"\\"\\n}",
                            "paramType": "body",
                            "allowMultiple": false,
                            "dataType": "Object"
                        }
                    ],
                    "summary": "Update&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Update user properties",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/users/{userName}",
            "description": "Users management",
            "operations": [
                {
                    "httpMethod": "DELETE",
                    "nickname": "remove",
                    "responseClass": "User",
                    "parameters": [
                        {
                            "name": "userName",
                            "description": "user identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Delete user&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Remove user form the system",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/users/{userName}/groups/{groupName}",
            "description": "Users management",
            "operations": [
                {
                    "httpMethod": "DELETE",
                    "nickname": "removeUserGroup",
                    "responseClass": "Group",
                    "parameters": [
                        {
                            "name": "userName",
                            "description": "user identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "groupName",
                            "description": "group identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Remove group&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Remove a particular user from a particular group",
                    "errorResponses": []
                }
            ]
        }
    ],
    "resourcePath": "/users",
    "models": {
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
        },
        "Group": {
            "id": "Group",
            "properties": {
                "groupName": {
                    "type": "string",
                    "description": "groupName group identifier"
                },
                "description": {
                    "type": "string",
                    "description": "description group description"
                },
                "uri": {
                    "type": "url",
                    "description": "uri"
                }
            }
        }
    }
}';