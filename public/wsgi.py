#!/usr/bin/env python3

import sys
import site

site.addsitedir('/var/www/html/rkbsys/public/face_id/lib/python3.6/site-packages/')

sys.path.insert(0, '/var/www/html/rkbsys/public/face_id/')

from app import app as application