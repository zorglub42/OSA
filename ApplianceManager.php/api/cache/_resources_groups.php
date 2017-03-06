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
            "path": "/groups",
            "description": "Groups management",
            "operations": [
                {
                    "httpMethod": "GET",
                    "nickname": "retrieveAll",
                    "responseClass": "Array[Group]",
                    "parameters": [
                        {
                            "name": "groupNameFilter",
                            "description": "[optional] Only retreive groups with groupName containing that string (filter conbination is AND).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "groupDescritpionFilter",
                            "description": "[optional] Only retreive groups with description containing that string (filter conbination is AND).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "order",
                            "description": "[optional] \\"SQL Like\\" order clause based on Group properties.",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Get groups list&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Get informations about groups",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/groups/{groupName}",
            "description": "Groups management",
            "operations": [
                {
                    "httpMethod": "GET",
                    "nickname": "retrieveOne",
                    "responseClass": "Group",
                    "parameters": [
                        {
                            "name": "groupName",
                            "description": "group identifer.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Get a group&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Get informations about a group",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/groups/{groupName}/members",
            "description": "Groups management",
            "operations": [
                {
                    "httpMethod": "GET",
                    "nickname": "retrieveMembers",
                    "responseClass": "Array[User]",
                    "parameters": [
                        {
                            "name": "groupName",
                            "description": "group identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "withLog",
                            "description": "[optional] If set to 1 retreive only users with records in logs, If set to 1 retreive only users without records in logs.",
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
                    "summary": "Membership&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Get users of a particular group",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/groups",
            "description": "Groups management",
            "operations": [
                {
                    "httpMethod": "POST",
                    "nickname": "create",
                    "responseClass": "Group",
                    "parameters": [
                        {
                            "name": "REQUEST_BODY",
                            "description": "Paste JSON data here with the following properties.<hr/>groupName : <tag>string</tag>  <i>(required)</i> - group identifier.<hr/>description : <tag>string</tag>  - [Optional] group description.",
                            "required": true,
                            "defaultValue": "{\\n    \\"groupName\\": \\"\\"\\n}",
                            "paramType": "body",
                            "allowMultiple": false,
                            "dataType": "Object"
                        }
                    ],
                    "summary": "Create a group&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Add a new users group to the system",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/groups/{groupName}",
            "description": "Groups management",
            "operations": [
                {
                    "httpMethod": "PUT",
                    "nickname": "update",
                    "responseClass": "Group",
                    "parameters": [
                        {
                            "name": "groupName",
                            "description": "group identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "REQUEST_BODY",
                            "description": "Paste JSON data here with the following property.<hr/>description : <tag>string</tag>  - [Optional] group description.",
                            "required": false,
                            "defaultValue": "{}",
                            "paramType": "body",
                            "allowMultiple": false,
                            "dataType": "Object"
                        }
                    ],
                    "summary": "Update a group&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Update an particular group properties",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/groups/{groupName}/users/{userName}",
            "description": "Groups management",
            "operations": [
                {
                    "httpMethod": "PUT",
                    "nickname": "addGroupMember",
                    "responseClass": "Group",
                    "parameters": [
                        {
                            "name": "groupName",
                            "description": "group identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "userName",
                            "description": "user identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Membership&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Add a particular user to a particular group",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/groups/{groupName}",
            "description": "Groups management",
            "operations": [
                {
                    "httpMethod": "DELETE",
                    "nickname": "remove",
                    "responseClass": "Group",
                    "parameters": [
                        {
                            "name": "groupName",
                            "description": "group identifer.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Delete a group&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Remove a group from the system",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/groups/{groupName}/users/{userName}",
            "description": "Groups management",
            "operations": [
                {
                    "httpMethod": "DELETE",
                    "nickname": "removeGroupMember",
                    "responseClass": "Group",
                    "parameters": [
                        {
                            "name": "groupName",
                            "description": "group identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "userName",
                            "description": "user idenfier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Cancel membership&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Remove a particular user from a particular group",
                    "errorResponses": []
                }
            ]
        }
    ],
    "resourcePath": "/groups",
    "models": {
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
        }
    }
}';