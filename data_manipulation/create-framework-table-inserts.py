#!/usr/bin/python
import json
import os
import time

# Read the contents of the json file
with open('frameworks_v2.json') as data_file:
    frameworks = json.load(data_file)
numOfElements = len(frameworks)

labels=""
values=""
snippet=""
content=""

for i in range(0, numOfElements):
    labels=""
    values=""
    for key, value in frameworks[i].iteritems():
        keyTemp = key.replace("-","_")
        print keyTemp + " => " + value
        labels += keyTemp + ","
        values += "'" + value + "',"       

    # Strip las "," from labels and values 
    labels = labels[:-1]
    values = values[:-1]

    snippet = "INSERT INTO frameworks_v2 (" + labels + ") VALUES(" + values + ");\n"
    print snippet
    content += snippet
  
  

with open('frameworksTable_inserts.sql', 'w') as output:
    output.write(content)