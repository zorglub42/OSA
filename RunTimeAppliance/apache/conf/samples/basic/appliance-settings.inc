	AuthName "Open source appliance for  APIs publishing"
	AuthType Basic
	AuthMYSQLEnable on
	
	AuthMySQLUser user-to-connect-mysql-db
	AuthMySQLPassword mysql-user-password
	AuthMySQLHost mysql-host
	AuthMySQLDB mysql-DB
	AuthMySQLUserTable users
	AuthMySQLNameField userName
	AuthMySQLPasswordField md5Password
	AuthMySQLPwEncryption md5
	AuthMySQLGroupTable usersgroups
	AuthMySQLGroupField groupName
	AuthMySQLUserCondition endDate>now()
	
	
	AuthMySQLResourceNameField serviceName
	
	
	AuthMySQLCountersTable counters
	AuthMySQLCounterNameField counterName
	AuthMySQLCounterValueField value
	
	AuthMySQLPerSecField reqSec
	AuthMySQLPerDayField reqDay
	AuthMySQLPerMonthField reqMonth
	
	
	
	AuthMySQLGlobalQuotasTable services
	AuthMySQLUserQuotasTable usersquotas


