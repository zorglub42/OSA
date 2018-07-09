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



AC_ARG_WITH(sqlite3-include-path,
  [AS_HELP_STRING([--with-sqlite3-include-path],
    [location of the SQLite3 headers, defaults to /usr/include/sqlite3])],
  [SQLITE3_CFLAGS="-I$withval"],
  [SQLITE3_CFLAGS='-I/usr/include/sqlite3'])
 AC_MSG_RESULT([include sqlite3 is $SQLITE3_CFLAGS])
AC_SUBST(SQLITE3_CFLAGS)

AC_ARG_WITH([sqlite3-lib-path],
  [AS_HELP_STRING([--with-sqlite3-lib-path], [location of the SQLite3 libraries])],
  [SQLITE3_LIBS="-L$withval -lsqlite3"],
  [SQLITE3_LIBS='-lsqlite3'])
 AC_MSG_RESULT([sqlite3libs is $SQLITE3_LIBS])
AC_SUBST(SQLITE3_LIBS)

AC_ARG_WITH([jsonc-lib-path],
  [AS_HELP_STRING([--with-jsonc-lib-path], [location of the JSON-C libraries])],
  [JSONC_LIBS="-L$withval -ljson-c"],
  [JSONC_LIBS='-ljson-c'])
 AC_MSG_RESULT([jsonclibs is $JSONC_LIBS])
AC_SUBST(JSONC_LIBS)


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

