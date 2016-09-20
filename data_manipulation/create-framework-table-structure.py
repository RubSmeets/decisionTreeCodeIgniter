#!/usr/bin/python
import json
import os
import time

varChar = """ varchar(200) DEFAULT NULL,"""
enum = """ enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,"""

template = """
-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 16, 2016 at 03:47 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `comparisonToolDB`
--

-- --------------------------------------------------------

--
-- Table structure for table `frameworks_v2`
--

CREATE TABLE IF NOT EXISTS `frameworks_v2` (
`framework_id` bigint(11) NOT NULL,
"""

template += """`sourcecode` """ + enum + """\n"""
template += """`repo`""" + varChar + """\n"""
template += """`gestures_multitouch` """ + enum + """\n"""
template += """`file` """ + enum + """\n"""
template += """`accessibility` """ + enum + """\n"""
template += """`opensource` """ + enum + """\n"""
template += """`license` """ + varChar + """\n"""
template += """`accelerometer` """ + enum + """\n"""
template += """`jsx` """ + enum + """\n"""
template += """`bada` """ + enum + """\n"""
template += """`lua` """ + enum + """\n"""
template += """`storage` """ + enum + """\n"""
template += """`uno` """ + varChar + """\n"""
template += """`nativeevents` """ + enum + """\n"""
template += """`python` """ + enum + """\n"""
template += """`camera` """ + enum + """\n"""
template += """`code_sharing` """ + varChar + """\n"""
template += """`compass` """ + enum + """\n"""
template += """`hybridapp` """ + enum + """\n"""
template += """`androidtv` """ + enum + """\n"""
template += """`vibration` """ + enum + """\n"""
template += """`book` """ + varChar + """\n"""
template += """`cplusplus` """ + enum + """\n"""
template += """`nativejavascript` """ + enum + """\n"""
template += """`css` """ + enum + """\n"""
template += """`contacts` """ + enum + """\n"""
template += """`basic` """ + enum + """\n"""
template += """`maemo` """ + enum + """\n"""
template += """`announced` """ + varChar + """\n"""
template += """`tizen` """ + enum + """\n"""
template += """`webapp` """ + enum + """\n"""
template += """`animations` """ + enum + """\n"""
template += """`windowsphone` """ + enum + """\n"""
template += """`free` """ + enum + """\n"""
template += """`csharp` """ + enum + """\n"""
template += """`appshowcase` """ + varChar + """\n"""
template += """`wup` """ + enum + """\n"""
template += """`publ_assist` """ + enum + """\n"""
template += """`swift` """ + enum + """\n"""
template += """`actionscript` """ + enum + """\n"""
template += """`javascript_tool` """ + enum + """\n"""
template += """`integrate_with_existing_app` """ + enum + """\n"""
template += """`meego` """ + enum + """\n"""
template += """`js` """ + enum + """\n"""
template += """`xml` """ + enum + """\n"""
template += """`widgets` """ + enum + """\n"""
template += """`hired_help` """ + enum + """\n"""
template += """`php` """ + enum + """\n"""
template += """`time_delayed_supp` """ + enum + """\n"""
template += """`cd` """ + enum + """\n"""
template += """`windows` """ + enum + """\n"""
template += """`osx` """ + enum + """\n"""
template += """`blackberry` """ + enum + """\n"""
template += """`allows_prototyping` """ + enum + """\n"""
template += """`games` """ + enum + """\n"""
template += """`framework_current_version` """ + varChar + """\n"""
template += """`android` """ + enum + """\n"""
template += """`webtonative` """ + enum + """\n"""
template += """`phone_supp` """ + enum + """\n"""
template += """`ios` """ + enum + """\n"""
template += """`appfactory` """ + enum + """\n"""
template += """`geolocation` """ + enum + """\n"""
template += """`qml` """ + enum + """\n"""
template += """`multi_screen` """ + enum + """\n"""
template += """`objc` """ + enum + """\n"""
template += """`comparison_data_last_update` """ + varChar + """\n"""
template += """`kindle` """ + enum + """\n"""
template += """`capture` """ + enum + """\n"""
template += """`livesync` """ + enum + """\n"""
template += """`trial` """ + enum + """\n"""
template += """`connection` """ + enum + """\n"""
template += """`webos` """ + enum + """\n"""
template += """`sdk` """ + enum + """\n"""
template += """`community_supp` """ + enum + """\n"""
template += """`ads` """ + enum + """\n"""
template += """`symbian` """ + enum + """\n"""
template += """`url` """ + varChar + """\n"""
template += """`remoteupdate` """ + enum + """\n"""
template += """`mobilewebsite` """ + enum + """\n"""
template += """`framework` """ + varChar + """\n"""
template += """`runtime` """ + enum + """\n"""
template += """`onsite_supp` """ + enum + """\n"""
template += """`MXML` """ + enum + """\n"""
template += """`stackoverflow` """ + varChar + """\n"""
template += """`html` """ + enum + """\n"""
template += """`watchos` """ + enum + """\n"""
template += """`iteration_speed` """ + varChar + """\n"""
template += """`bluetooth` """ + enum + """\n"""
template += """`status` """ + varChar + """\n"""
template += """`messages_telephone` """ + enum + """\n"""
template += """`market` """ + varChar + """\n"""
template += """`ruby` """ + enum + """\n"""
template += """`clouddev` """ + enum + """\n"""
template += """`encryption` """ + enum + """\n"""
template += """`javame` """ + enum + """\n"""
template += """`learning_curve` """ + varChar + """\n"""
template += """`windowsmobile` """ + enum + """\n"""
template += """`tutorial_url` """ + varChar + """\n"""
template += """`notification` """ + enum + """\n"""
template += """`java` """ + enum + """\n"""
template += """`twitter` """ + varChar + """\n"""
template += """`device` """ + enum + """\n"""
template += """`visualeditor` """ + enum + """\n"""
template += """`firefoxos` """ + enum + """\n"""
template += """`video_url` """ + varChar + """\n"""
template += """`perf_overhead` """ + varChar + """\n"""
template += """`nfc` """ + enum + """\n"""
template += """`appletv` """ + enum + """\n"""
template += """`nativeapp` """ + enum + """\n"""
template += """`UXMarkup` """ + enum + """\n"""
template += """`documentation_url` """ + varChar
template = template[:-1]
template += """) ENGINE=MyISAM AUTO_INCREMENT=115 DEFAULT CHARSET=latin1;
--
-- Indexes for table `frameworks_v2`
--
ALTER TABLE `frameworks_v2`
 ADD PRIMARY KEY (`framework_id`), ADD UNIQUE KEY `framework_id` (`framework_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `frameworks_v2`
--
ALTER TABLE `frameworks_v2`
MODIFY `framework_id` bigint(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=115;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
"""
print template

with open('frameworksTable_structure.sql', 'w') as output:
    output.write(template)