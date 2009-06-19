# makefile to update CVS and do anything else needed
# Time-stamp: "2009-06-19 02:06:19 jantman"
# $Id$
# $Source$

.PHONY: clean add

clean:
	\rm -f *~
	\rm -f inc/*~
	\rm -f handlers/*~
	\rm -f admin/*~
	\rm -f bin/*~
	\rm -f config/*~
	\rm -f help/*~
	\rm -f images/*~

add:
	\svn add *.*
	\svn add inc/*.*
	\svn add handlers/*.*
	\svn add admin/*.*
	\svn add bin/*.*
	\svn add config/*.*
	\svn add help/*.*
	\svn add images/*.*
