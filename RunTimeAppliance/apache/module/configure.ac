# Process this file with autoconf to produce a configure script.
AC_PREREQ(2.59)
AC_INIT(mod_osa, 0.1)
AM_INIT_AUTOMAKE

AC_ARG_WITH(apxs, AC_HELP_STRING([--with-apxs=NAME],
            [name of the apxs executable [[apxs]]]),
            [APXS="$with_apxs"])

if test -z "${APXS}"; then
    AC_PATH_PROGS(APXS, apxs2 apxs, [apxs],
                   [$PATH:/usr/local/apache/bin:/usr/sbin])
fi

AC_SUBST(APXS)



AC_ARG_WITH(mysql-include-path,
  [AS_HELP_STRING([--with-mysql-include-path],
    [location of the MySQL headers, defaults to /usr/include/mysql])],
  [MYSQL_CFLAGS="-I$withval"],
  [MYSQL_CFLAGS='-I/usr/include/mysql'])
 AC_MSG_RESULT([include mysql is $MYSQL_CFLAGS])
AC_SUBST(MYSQL_CFLAGS)

uname -a| grep x86_64
if test $? -eq 0  ; then
        MYSQL_LIB_DIR="-L/usr/lib64/mysql"
else
        MYSQL_LIB_DIR="-L/usr/lib/mysql"
fi
AC_MSG_RESULT([mysql lib dir  is $MYSQL_LIB_DIR])
AC_SUBST(MYSQL_LIB_DIR)


AC_ARG_WITH([mysql-lib-path],
  [AS_HELP_STRING([--with-mysql-lib-path], [location of the MySQL libraries])],
  [MYSQL_LIBS="-L$withval -lmysqlclient"],
  [MYSQL_LIBS='-lmysqlclient'])
 AC_MSG_RESULT([mysqllibs is $MYSQL_LIBS])
AC_SUBST(MYSQL_LIBS)

CPPFLAGS=""
AC_SUBST(CPPFLAGS)


CFLAGS="-lm -lz"
AC_SUBST(CFLAGS)
AC_SUBST(LDFLAGS)
AC_SUBST(LDLIBS)

LIBEXECDIR="`${APXS} -q LIBEXECDIR`"
SYSCONFDIR="`${APXS} -q SYSCONFDIR`"
AC_SUBST(LIBEXECDIR)
AC_SUBST(SYSCONFDIR)
AC_OUTPUT(Makefile)

