#  Copyright 2007-2008 GRAHAM DUMPLETON
# 
#  Licensed under the Apache License, Version 2.0 (the "License");
#  you may not use this file except in compliance with the License.
#  You may obtain a copy of the License at
# 
#      http://www.apache.org/licenses/LICENSE-2.0
# 
#  Unless required by applicable law or agreed to in writing, software
#  distributed under the License is distributed on an "AS IS" BASIS,
#  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
#  See the License for the specific language governing permissions and
#  limitations under the License.

APXS = @APXS@

DESTDIR =
LIBEXECDIR = @LIBEXECDIR@
SYSCONFDIR = @SYSCONFDIR@
SQLITE3_CFLAGS = @SQLITE3_CFLAGS@
SQLITE3_LIBS = @SQLITE3_LIBS@
JSONC_LIBS = @JSONC_LIBS@

CPPFLAGS = @CPPFLAGS@
CFLAGS = @CFLAGS@
LDFLAGS = @LDFLAGS@
LDLIBS = @LDLIBS@


all : mod_osa.la

mod_osa.la : mod_osa.c ../base/osa_base.h ../base/cmd_config.h ../base/osa_base.c
	$(APXS) -c $(CPPFLAGS) $(CFLAGS) $(LDFLAGS) $(LDLIBS) $(JSONC_LIBS) $(SQLITE3_CFLAGS) $(SQLITE3_LIBS) mod_osa.c ../base/osa_base.c

$(DESTDIR)$(LIBEXECDIR) :
	mkdir -p $@

$(DESTDIR)$(SYSCONFDIR)/mods-available/ :
	mkdir -p $@

install : all $(DESTDIR)$(LIBEXECDIR) $(DESTDIR)$(SYSCONFDIR)/mods-available/
	$(APXS) -i -S LIBEXECDIR=$(DESTDIR)$(LIBEXECDIR) mod_osa.la
	cp osa.load  $(DESTDIR)$(SYSCONFDIR)/mods-available/.
	cp osa.conf  $(DESTDIR)$(SYSCONFDIR)/mods-available/.

clean :
	-rm -rf .libs
	-rm -f mod_osa.o mod_osa.la mod_osa.lo mod_osa.slo mod_osa.loT
	-rm -f config.log config.status
	-rm -rf autom4te.cache

distclean : clean
	-rm -f Makefile Makefile.in

realclean : distclean
	-rm -f configure
