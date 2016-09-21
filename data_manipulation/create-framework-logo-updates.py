#!/usr/bin/python
import json
import os
import time
import sys
import copy

# Format the name to lowercase and without special characters
def formatString( str ):
    output = str.replace('/','').replace(' ', '').replace('-','').replace('.','')
    return output.lower()

print "-- START update query generation --"

# Read the contents of the json file
with open('frameworks_v2.json') as data_file:
    frameworks = json.load(data_file)
numOfElements = len(frameworks)

# Create list with imageNames from existing logos in folder "../img/logos/"
imgList = []
for path, dirs, files in os.walk("../img/logos"):
  for f in files:
    imgList.append(f)

found = 0
foundImg = ""
frameworkName = ""
snippet = ""
for i in range(0, numOfElements):
    for key, value in frameworks[i].iteritems():
        for img in imgList:
            if(formatString(value) in img):
                foundImg = img
                frameworkName = frameworks[i].get("framework")
                found = 1
    if(found == 1):
        snippet += "UPDATE frameworks_v2 SET logo_img='" + foundImg + "' WHERE framework='" + frameworkName + "';\n"
        found = 0
    else:
        snippet += "UPDATE frameworks_v2 SET logo_img='notfound.png' WHERE framework='" + frameworks[i].get("framework") + "';\n"  

with open('frameworksTable_updateImg.sql', 'w') as output:
    output.write(snippet)