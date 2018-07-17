
	AP_INIT_RAW_ARGS("OSAAuthName", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, authName),
	OR_AUTHCFG | RSRC_CONF, "Realm value for basic auth"),

	AP_INIT_TAKE1("OSAReqMonthField", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, reqMonthField),
	OR_AUTHCFG | RSRC_CONF, "max number of request per month field"),

	AP_INIT_TAKE1("OSAReqDayField", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, reqDayField),
	OR_AUTHCFG | RSRC_CONF, "max number of request per day field"),

	AP_INIT_TAKE1("OSAReqSecField", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, reqSecField),
	OR_AUTHCFG | RSRC_CONF, "max number of request per second field"),


	AP_INIT_TAKE1("OSAUserTable", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, osapwtable),
	OR_AUTHCFG | RSRC_CONF, "mysql user table name"),

	AP_INIT_TAKE1("OSAGroupTable", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, osagrptable),
	OR_AUTHCFG | RSRC_CONF, "mysql group table name"),

	AP_INIT_TAKE1("OSANameField", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, osaNameField),
	OR_AUTHCFG | RSRC_CONF, "mysql User ID field name within User table"),

	AP_INIT_TAKE1("OSAGroupField", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, osaGroupField),
	OR_AUTHCFG | RSRC_CONF, "mysql Group field name within table"),

	AP_INIT_TAKE1("OSAGroupUserNameField", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, osaGroupUserNameField),
	OR_AUTHCFG | RSRC_CONF, "mysql User ID field name within Group table"),

	AP_INIT_TAKE1("OSAPasswordField", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, osaPasswordField),
	OR_AUTHCFG | RSRC_CONF, "mysql Password field name within table"),

	AP_INIT_TAKE1("OSAPwEncryption", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, osaEncryptionField),
	OR_AUTHCFG | RSRC_CONF, "mysql password encryption method"),

	AP_INIT_TAKE1("OSASaltField", ap_set_string_slot,
	(void*) APR_OFFSETOF(osa_config_rec, osaSaltField),
	OR_AUTHCFG | RSRC_CONF, "mysql salfe field name within table"),

	AP_INIT_FLAG("OSAAuthoritative", ap_set_flag_slot,
	(void *) APR_OFFSETOF(osa_config_rec, osaAuthoritative),
	OR_AUTHCFG | RSRC_CONF, "mysql lookup is authoritative if On"),

	AP_INIT_FLAG("OSANoPasswd", ap_set_flag_slot,
	(void *) APR_OFFSETOF(osa_config_rec, osaNoPasswd),
	OR_AUTHCFG | RSRC_CONF, "If On, only check if user exists; ignore password"),

	AP_INIT_FLAG("OSAEnable", ap_set_flag_slot,
	(void *) APR_OFFSETOF(osa_config_rec, osaEnable),
	OR_AUTHCFG | RSRC_CONF, "enable mysql authorization"),

	AP_INIT_TAKE1("OSAUserCondition", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, osaUserCondition),
	OR_AUTHCFG | RSRC_CONF, "condition to add to user where-clause"),

	AP_INIT_TAKE1("OSAGroupCondition", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, osaGroupCondition),
	OR_AUTHCFG | RSRC_CONF, "condition to add to group where-clause"),

	AP_INIT_TAKE1("OSACharacterSet", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, osaCharacterSet),
	OR_AUTHCFG | RSRC_CONF, "mysql character set to be used"),

	AP_INIT_FLAG("OSALogHit", ap_set_flag_slot,
	(void *) APR_OFFSETOF(osa_config_rec, logHit),
	OR_AUTHCFG | RSRC_CONF, "log hit in DB"),

	AP_INIT_TAKE1("OSACookieAuthTable", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, cookieAuthTable),
	OR_AUTHCFG | RSRC_CONF, "table name containing authentication tokens default=authtoken"),

	AP_INIT_TAKE1("OSACookieAuthUsernameField", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, cookieAuthUsernameField),
	OR_AUTHCFG | RSRC_CONF, "field name in OSACookieAuthTable containing authenticated used default=userName"),

	AP_INIT_TAKE1("OSACookieAuthTokenField", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, cookieAuthTokenField),
	OR_AUTHCFG | RSRC_CONF, "field name in OSACookieAuthTable containing generated token default=token"),

	AP_INIT_TAKE1("OSACookieInitialAuthTokenField", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, cookieInitialAuthTokenField),
	OR_AUTHCFG | RSRC_CONF, "field name in OSACookieAuthTable containing 1st generated token (sesion) default=intialToken"),

	AP_INIT_TAKE1("OSACookieAuthValidityField", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, cookieAuthValidityField),
	OR_AUTHCFG | RSRC_CONF, "field name in OSACookieAuthTable containing validity date for generated token default=validUntil"),

	AP_INIT_TAKE1("OSACookieAuthBurnedField", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, cookieAuthBurnedField),
	OR_AUTHCFG | RSRC_CONF, "field name in OSACookieAuthTable containing a maked to konw if token has already been consumed (cache related) default=burned"),

	AP_INIT_FLAG("OSACookieAuthEnable", ap_set_flag_slot,
	(void *) APR_OFFSETOF(osa_config_rec, cookieAuthEnable),
	OR_AUTHCFG | RSRC_CONF, "enable authentication/authorization from cookie"),

	AP_INIT_TAKE1("OSACookieAuthName", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, cookieAuthName),
	OR_AUTHCFG | RSRC_CONF, "cookie name for authentication"),

	AP_INIT_TAKE1("OSACookieAuthLoginForm", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, cookieAuthLoginForm),
	OR_AUTHCFG | RSRC_CONF, "login form URI to redirect to login if OSACookieAuthEnable is enable and not OSABasicAuthEnable"),

	AP_INIT_FLAG("OSACookieAuthBurn", ap_set_flag_slot,
	(void *) APR_OFFSETOF(osa_config_rec, cookieAuthBurn),
	OR_AUTHCFG | RSRC_CONF, "burn auth cookie after usage"),

	AP_INIT_TAKE1("OSACookieAuthDomain", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, cookieAuthDomain),
	OR_AUTHCFG | RSRC_CONF, "cookie name for authentication"),

	AP_INIT_TAKE1("OSACookieAuthTTL", ap_set_int_slot,
	(void *) APR_OFFSETOF(osa_config_rec, cookieAuthTTL),
	OR_AUTHCFG | RSRC_CONF, "Time To Live for authentication cookie"),

	AP_INIT_TAKE1("OSACookieAuthCacheTTL", ap_set_int_slot,
	(void *) APR_OFFSETOF(osa_config_rec, cookieCacheTime),
	OR_AUTHCFG | RSRC_CONF, "usable cache duration after cookie burning (in sec)"),

	AP_INIT_FLAG("OSABasicAuthEnable", ap_set_flag_slot,
	(void *) APR_OFFSETOF(osa_config_rec, basicAuthEnable),
	OR_AUTHCFG | RSRC_CONF, "enable authentication/authorization with basic authentication"),

	AP_INIT_RAW_ARGS("OSARequire", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, require),
	OR_AUTHCFG | RSRC_CONF, "Required authorization"),

	AP_INIT_FLAG("OSAAllowAnonymous", ap_set_flag_slot,
	(void *) APR_OFFSETOF(osa_config_rec, allowAnonymous),
	OR_AUTHCFG | RSRC_CONF, "Allow unauthenticated access even if (OSARequire && (OSABasicAuthEnable||OSACookieAuthEnable)) are set. In such a case, Identity is forwarded"),

	AP_INIT_FLAG("OSACheckGlobalQuotas", ap_set_flag_slot,
	(void *) APR_OFFSETOF(osa_config_rec, checkGlobalQuotas),
	OR_AUTHCFG | RSRC_CONF, "check global quotas for resource"),

	AP_INIT_FLAG("OSACheckUserQuotas", ap_set_flag_slot,
	(void *) APR_OFFSETOF(osa_config_rec, checkUserQuotas),
	OR_AUTHCFG | RSRC_CONF, "check per user quotas for resource"),

	AP_INIT_TAKE1("OSAResourceName", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, resourceName),
	OR_AUTHCFG | RSRC_CONF, "resource on witch quotas are check"),

	AP_INIT_TAKE1("OSAResourceNameField", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, osaResourceNameField),
	OR_AUTHCFG | RSRC_CONF, "column containing resource name"),

	AP_INIT_TAKE1("OSAPerSecField", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, osaPerSecField),
	OR_AUTHCFG | RSRC_CONF, "column containing per second quota"),

	AP_INIT_TAKE1("OSAPerDayField", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, osaPerDayField),
	OR_AUTHCFG | RSRC_CONF, "column containing per day quota"),

	AP_INIT_TAKE1("OSAPerMonthField", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, osaPerMonthField),
	OR_AUTHCFG | RSRC_CONF, "column containing per month quota"),

	AP_INIT_TAKE1("OSAGlobalQuotasTable", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, osaGlobalQuotasTable),
	OR_AUTHCFG | RSRC_CONF, "table containing global quotas"),

	AP_INIT_TAKE1("OSAGlobalQuotasCondition", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, osaGlobalQuotasCondition),
	OR_AUTHCFG | RSRC_CONF, "condition to add to GlobalQuotas query"),

	AP_INIT_TAKE1("OSAUserQuotasTable", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, osaUserQuotasTable),
	OR_AUTHCFG | RSRC_CONF, "table containing global quotas"),

	AP_INIT_TAKE1("OSAUserQuotasCondition", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, osaUserQuotasCondition),
	OR_AUTHCFG | RSRC_CONF, "condition to add to UserQuotas query"),

	AP_INIT_TAKE1("OSACountersTable", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, countersTable),
	OR_AUTHCFG | RSRC_CONF, "Table containing counters"),

	AP_INIT_TAKE1("OSACounterNameField", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, counterNameField),
	OR_AUTHCFG | RSRC_CONF, "Column containting counter name"),

	AP_INIT_TAKE1("OSACounterValueField", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, counterValueField),
	OR_AUTHCFG | RSRC_CONF, "Column containting counter value"),

	AP_INIT_TAKE1("OSAIdentityHeadersMapping", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, indentityHeadersMapping),
	OR_AUTHCFG | RSRC_CONF, "forward user identity as HTTP Headers"),

	AP_INIT_TAKE1("OSAIdentityExtendedHeadersMapping", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, indentityHeadersExtendedMapping),
	OR_AUTHCFG | RSRC_CONF, "forward user additional identity attributes as HTTP Headers"),

	AP_INIT_TAKE1("OSAUserAttributeTable", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, userAttributesTable),
	OR_AUTHCFG | RSRC_CONF, "forward user additional identity attributes as HTTP Headers"),

	AP_INIT_TAKE1("OSAUserAttributeNameField", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, userAttributeNameField),
	OR_AUTHCFG | RSRC_CONF, "field in OSAUserAttributeTable for attribute name"),

	AP_INIT_TAKE1("OSAUserAttributeValueField", ap_set_string_slot,
	(void *) APR_OFFSETOF(osa_config_rec, userAttributeValueField),
	OR_AUTHCFG | RSRC_CONF, "field in OSAUserAttributeTable for attribute value"),
