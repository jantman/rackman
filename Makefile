# makefile to update CVS and do anything else needed
# Time-stamp: "2009-02-22 01:02:20 jantman"
# $Id: Makefile,v 1.2 2008/11/03 04:48:57 jantman Exp $
# $Source$

ifdef LOGSTR
        LOG = $(LOGSTR)
else
        LOG = just working on things.
endif

ifdef TAG
	TAGSTR = $(TAG)
else
	TAGSTR = main
endif

cvsupdate:
	-rm *~
	-cd admin/ && rm *~
	-cd bin/ && rm *~
	-cd config/ && rm *~
	-cd handlers/ && rm *~
	-cd help/ && rm *~
	-cd images/ && rm *~
	-cd inc/ && rm *~
	cvs import -m "$(LOG)" rack-mgmt jantman "$(TAGSTR)"
