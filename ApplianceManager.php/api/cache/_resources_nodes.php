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
            "path": "/nodes",
            "description": "Nodes API",
            "operations": [
                {
                    "httpMethod": "GET",
                    "nickname": "retrieveAll",
                    "responseClass": "Array[Node]",
                    "parameters": [
                        {
                            "name": "nodeNameFilter",
                            "description": "[optional] Only retreive nodes with nodeName address containing that string (filter conbination is AND).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "nodeDescriptionFilter",
                            "description": "[optional] Only retreive nodes with nodeDescription address containing that string (filter conbination is AND).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "localIPFilter",
                            "description": "[optional] Only retreive nodes with localIP address containing that string (filter conbination is AND).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "portFilter",
                            "description": "[optional] Only retreive nodes with listening on that port (filter conbination is AND).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "int"
                        },
                        {
                            "name": "serverFQDNFilter",
                            "description": "Only retreive nodes with nodeName serverFQDN containing that string (filter conbination is AND).",
                            "paramType": "query",
                            "required": false,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Get nodes&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Get all nodes",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/nodes/{nodeName}",
            "description": "Nodes API",
            "operations": [
                {
                    "httpMethod": "GET",
                    "nickname": "retrieveOn",
                    "responseClass": "Node",
                    "parameters": [
                        {
                            "name": "nodeName",
                            "description": "Node identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Get a Node&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Get description of a particular Node",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/nodes/{nodeName}/ca",
            "description": "Nodes API",
            "operations": [
                {
                    "httpMethod": "GET",
                    "nickname": "retrieveCa",
                    "responseClass": "void",
                    "parameters": [
                        {
                            "name": "nodeName",
                            "description": "node identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Get Certification authority&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Get Certification authority certificate",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/nodes/{nodeName}/cert",
            "description": "Nodes API",
            "operations": [
                {
                    "httpMethod": "GET",
                    "nickname": "retrieveCert",
                    "responseClass": "void",
                    "parameters": [
                        {
                            "name": "nodeName",
                            "description": "node identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Get server certificate&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Get server certificate",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/nodes/{nodeName}/chain",
            "description": "Nodes API",
            "operations": [
                {
                    "httpMethod": "GET",
                    "nickname": "retrieveChain",
                    "responseClass": "void",
                    "parameters": [
                        {
                            "name": "nodeName",
                            "description": "node identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Get Certification chain&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Get intermediate certification authority certificated",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/nodes/{nodeName}/privatekey",
            "description": "Nodes API",
            "operations": [
                {
                    "httpMethod": "GET",
                    "nickname": "retrievePrivateKey",
                    "responseClass": "void",
                    "parameters": [
                        {
                            "name": "nodeName",
                            "description": "node identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Get private key&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Get server private key",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/nodes/{nodeName}/services",
            "description": "Nodes API",
            "operations": [
                {
                    "httpMethod": "GET",
                    "nickname": "publishedServices",
                    "responseClass": "Array[Service]",
                    "parameters": [
                        {
                            "name": "nodeName",
                            "description": "Node identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Get node services&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Get services available on this node",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/nodes/{nodeName}/virtualHost",
            "description": "Nodes API",
            "operations": [
                {
                    "httpMethod": "GET",
                    "nickname": "generateVirtualHost",
                    "responseClass": "void",
                    "parameters": [
                        {
                            "name": "nodeName",
                            "description": "Node identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Get Apache VirtualHost&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Get corresponding Apache VirtualHost",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/nodes",
            "description": "Nodes API",
            "operations": [
                {
                    "httpMethod": "POST",
                    "nickname": "create",
                    "responseClass": "Node",
                    "parameters": [
                        {
                            "name": "REQUEST_BODY",
                            "description": "Paste JSON data here with the following properties.<hr/>nodeName : <tag>string</tag>  <i>(required)</i> - Node identifier.<hr/>serverFQDN : <tag>string</tag>  <i>(required)</i> - Public server FQDN.<hr/>localIP : <tag>string</tag>  <i>(required)</i> - Listening IP (IP, hostname or * for all available interfaces).<hr/>port : <tag>int</tag>  <i>(required)</i> - port Listeing port.<hr/>isHTTPS : <tag>int</tag>  <i>(required)</i> - Does this node use HTTPS? (O: no, 1: yes).<hr/>nodeDescription : <tag>string</tag>  - Node description.<hr/>isBasicAuthEnabled : <tag>int</tag>  - Does this node handle basic authentication? (O: no, 1: yes).<hr/>isCookieAuthEnabled : <tag>int</tag>  - Does this not handel cookie based authentication? (O: no, 1: yes).<hr/>additionalConfiguration : <tag>string</tag>  - additionnal apache directive for this virtualHost/node.<hr/>apply : <tag>int</tag>  - Apply this configuration immediatly? (O: no, 1: yes).",
                            "required": true,
                            "defaultValue": "{\\n    \\"nodeName\\": \\"\\",\\n    \\"serverFQDN\\": \\"\\",\\n    \\"localIP\\": \\"\\",\\n    \\"port\\": \\"\\",\\n    \\"isHTTPS\\": \\"\\"\\n}",
                            "paramType": "body",
                            "allowMultiple": false,
                            "dataType": "Object"
                        }
                    ],
                    "summary": "Create&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Create and deploy a new Node",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/nodes/{nodeName}/cert",
            "description": "Nodes API",
            "operations": [
                {
                    "httpMethod": "POST",
                    "nickname": "uploadCert",
                    "responseClass": "Array",
                    "parameters": [
                        {
                            "name": "nodeName",
                            "description": "identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Upload certificate&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Upload server certificate Expect Certificates as multipart/form-data; Uploaded File (files name collection name: files)",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/nodes/{nodeName}/chain",
            "description": "Nodes API",
            "operations": [
                {
                    "httpMethod": "POST",
                    "nickname": "uploadChain",
                    "responseClass": "Array",
                    "parameters": [
                        {
                            "name": "nodeName",
                            "description": "identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Upload Chain&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Upload intermediate certification authority certificates Expect Certificates as multipart/form-data; Uploaded File (files name collection name: files)",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/nodes/{nodeName}/privatekey",
            "description": "Nodes API",
            "operations": [
                {
                    "httpMethod": "POST",
                    "nickname": "uploadPrivateKey",
                    "responseClass": "Array",
                    "parameters": [
                        {
                            "name": "nodeName",
                            "description": "identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Upload Private key&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Upload private key Expect private key as multipart/form-data; Uploaded File (files name collection name: files)",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/nodes/{nodeName}/status",
            "description": "Nodes API",
            "operations": [
                {
                    "httpMethod": "POST",
                    "nickname": "setPublished",
                    "responseClass": "Node",
                    "parameters": [
                        {
                            "name": "nodeName",
                            "description": "node identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "REQUEST_BODY",
                            "description": "Paste JSON data here with the following properties.<hr/>published : <tag>int</tag>  <i>(required)</i> - 0: not published, 1: published.<hr/>reload : <tag>string</tag>  - , default: yes. Apply configuration..",
                            "required": true,
                            "defaultValue": "{\\n    \\"published\\": \\"\\"\\n}",
                            "paramType": "body",
                            "allowMultiple": false,
                            "dataType": "Object"
                        }
                    ],
                    "summary": "Enable/disable&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Enable or disable a Node",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/nodes/{nodeName}/virtualHost",
            "description": "Nodes API",
            "operations": [
                {
                    "httpMethod": "POST",
                    "nickname": "applyConf",
                    "responseClass": "Node",
                    "parameters": [
                        {
                            "name": "nodeName",
                            "description": "",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Apply configuration&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/nodes",
            "description": "Nodes API",
            "operations": [
                {
                    "httpMethod": "PUT",
                    "nickname": "update",
                    "responseClass": "Array",
                    "parameters": [
                        {
                            "name": "REQUEST_BODY",
                            "description": "Paste JSON data here with the following properties.<hr/>nodeName : <tag>string</tag>  <i>(required)</i> - Node identifier.<hr/>serverFQDN : <tag>string</tag>  <i>(required)</i> - Public server FQDN.<hr/>localIP : <tag>string</tag>  <i>(required)</i> - Listening IP (IP, hostname or * for all available interfaces).<hr/>port : <tag>int</tag>  <i>(required)</i> - port Listeing port.<hr/>isHTTPS : <tag>int</tag>  <i>(required)</i> - Does this node use HTTPS? (O: no, 1: yes).<hr/>nodeDescription : <tag>string</tag>  - Node description.<hr/>isBasicAuthEnabled : <tag>int</tag>  - Does this node handle basic authentication? (O: no, 1: yes).<hr/>isCookieAuthEnabled : <tag>int</tag>  - Does this not handel cookie based authentication? (O: no, 1: yes).<hr/>additionalConfiguration : <tag>string</tag>  - additionnal apache directive for this virtualHost/node.<hr/>apply : <tag>int</tag>  - Apply this configuration immediatly? (O: no, 1: yes).",
                            "required": true,
                            "defaultValue": "{\\n    \\"nodeName\\": \\"\\",\\n    \\"serverFQDN\\": \\"\\",\\n    \\"localIP\\": \\"\\",\\n    \\"port\\": \\"\\",\\n    \\"isHTTPS\\": \\"\\"\\n}",
                            "paramType": "body",
                            "allowMultiple": false,
                            "dataType": "Object"
                        }
                    ],
                    "summary": "Update Node&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Update and deploy Node",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/nodes/{nodeName}",
            "description": "Nodes API",
            "operations": [
                {
                    "httpMethod": "PUT",
                    "nickname": "update_",
                    "responseClass": "Array",
                    "parameters": [
                        {
                            "name": "nodeName",
                            "description": "Node identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "REQUEST_BODY",
                            "description": "Paste JSON data here with the following properties.<hr/>serverFQDN : <tag>string</tag>  <i>(required)</i> - Public server FQDN.<hr/>localIP : <tag>string</tag>  <i>(required)</i> - Listening IP (IP, hostname or * for all available interfaces).<hr/>port : <tag>int</tag>  <i>(required)</i> - port Listeing port.<hr/>isHTTPS : <tag>int</tag>  <i>(required)</i> - Does this node use HTTPS? (O: no, 1: yes).<hr/>nodeDescription : <tag>string</tag>  - Node description.<hr/>isBasicAuthEnabled : <tag>int</tag>  - Does this node handle basic authentication? (O: no, 1: yes).<hr/>isCookieAuthEnabled : <tag>int</tag>  - Does this not handel cookie based authentication? (O: no, 1: yes).<hr/>additionalConfiguration : <tag>string</tag>  - additionnal apache directive for this virtualHost/node.<hr/>apply : <tag>int</tag>  - Apply this configuration immediatly? (O: no, 1: yes).",
                            "required": true,
                            "defaultValue": "{\\n    \\"serverFQDN\\": \\"\\",\\n    \\"localIP\\": \\"\\",\\n    \\"port\\": \\"\\",\\n    \\"isHTTPS\\": \\"\\"\\n}",
                            "paramType": "body",
                            "allowMultiple": false,
                            "dataType": "Object"
                        }
                    ],
                    "summary": "Update Node&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Update and deploy Node",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/nodes/{nodeName}",
            "description": "Nodes API",
            "operations": [
                {
                    "httpMethod": "DELETE",
                    "nickname": "remove",
                    "responseClass": "Array",
                    "parameters": [
                        {
                            "name": "nodeName",
                            "description": "Node identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        },
                        {
                            "name": "apply",
                            "description": "Apply this configuration immediatly? (O: no, 1: yes).",
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
                        }
                    ],
                    "summary": "Delete Node&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Delete and undeploy a Node",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/nodes/{nodeName}/ca",
            "description": "Nodes API",
            "operations": [
                {
                    "httpMethod": "DELETE",
                    "nickname": "removeCa",
                    "responseClass": "void",
                    "parameters": [
                        {
                            "name": "nodeName",
                            "description": "Node identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Remove CA&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Remove certification autority certificate from a Node",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/nodes/{nodeName}/cert",
            "description": "Nodes API",
            "operations": [
                {
                    "httpMethod": "DELETE",
                    "nickname": "removeCert",
                    "responseClass": "void",
                    "parameters": [
                        {
                            "name": "nodeName",
                            "description": "Node identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Remove certificate&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Remove server certificate from a Node",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/nodes/{nodeName}/chain",
            "description": "Nodes API",
            "operations": [
                {
                    "httpMethod": "DELETE",
                    "nickname": "removeChain",
                    "responseClass": "void",
                    "parameters": [
                        {
                            "name": "nodeName",
                            "description": "Node identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Remove Certication chain&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Remove intermediate certification autority certificate from a Node",
                    "errorResponses": []
                }
            ]
        },
        {
            "path": "/nodes/{nodeName}/privatekey",
            "description": "Nodes API",
            "operations": [
                {
                    "httpMethod": "DELETE",
                    "nickname": "removePrivateKey",
                    "responseClass": "void",
                    "parameters": [
                        {
                            "name": "nodeName",
                            "description": "Node identifier.",
                            "paramType": "path",
                            "required": true,
                            "allowMultiple": false,
                            "dataType": "string"
                        }
                    ],
                    "summary": "Remove private key&nbsp; <i class=\\"icon-unlock-alt icon-large\\"></i>",
                    "notes": "Remove private key from a Node",
                    "errorResponses": []
                }
            ]
        }
    ],
    "resourcePath": "/nodes",
    "models": {
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
        "Node": {
            "id": "Node",
            "properties": {
                "nodeName": {
                    "type": "string",
                    "description": "nodeName node identifier"
                },
                "nodeDescription": {
                    "type": "string",
                    "description": "nodeDescription description of this node"
                },
                "isHTTPS": {
                    "type": "int",
                    "description": "isHTTPS Does this node use HTTPS? (O: no, 1: yes)"
                },
                "isBasicAuthEnabled": {
                    "type": "int",
                    "description": "isBasicAuthEnabled Does this node handle basic authentication? (O: no, 1: yes)"
                },
                "iscookieAuthEnabled": {
                    "type": "int",
                    "description": "iscookieAuthEnabled Does this not handel cookie based authentication? (O: no, 1: yes)"
                },
                "serverFQDN": {
                    "type": "string",
                    "description": "serverFQDN public FQDN for this node"
                },
                "localIP": {
                    "type": "string",
                    "description": "loalIP local listening IP (or *) of this note"
                },
                "port": {
                    "type": "int",
                    "description": "port listening port"
                },
                "publicKey": {
                    "type": "string",
                    "description": "privateKey for HTTPS"
                },
                "cert": {
                    "type": "string",
                    "description": "cert server certificate for HTTPS"
                },
                "ca": {
                    "type": "string",
                    "description": "ca Certification authority certificate"
                },
                "caChain": {
                    "type": "string",
                    "description": "intermediate certification authority certificates"
                },
                "additionalConfiguration": {
                    "type": "string",
                    "description": "additionalConfiguration additionnal apache directive for this virtualHost/node"
                },
                "isPublished": {
                    "type": "int",
                    "description": "isPublished Is this node published? (O: no, 1: yes)"
                },
                "uri": {
                    "type": "url",
                    "description": "uri"
                }
            }
        }
    }
}';