
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
`sourcecode`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`repo` varchar(200) DEFAULT NULL,
`gestures_multitouch`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`file`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`accessibility`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`opensource`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`license`  varchar(200) DEFAULT NULL,
`accelerometer`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`jsx`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`bada`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`lua`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`storage`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`uno`  varchar(200) DEFAULT NULL,
`nativeevents`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`python`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`camera`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`code_sharing`  varchar(200) DEFAULT NULL,
`compass`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`hybridapp`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`androidtv`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`vibration`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`book`  varchar(200) DEFAULT NULL,
`cplusplus`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`nativejavascript`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`css`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`contacts`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`basic`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`maemo`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`announced`  varchar(200) DEFAULT NULL,
`tizen`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`webapp`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`animations`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`windowsphone`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`free`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`csharp`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`appshowcase`  varchar(200) DEFAULT NULL,
`wup`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`publ_assist`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`swift`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`actionscript`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`javascript_tool`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`integrate_with_existing_app`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`meego`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`js`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`xml`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`widgets`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`hired_help`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`php`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`time_delayed_supp`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`cd`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`windows`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`osx`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`blackberry`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`allows_prototyping`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`games`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`framework_current_version`  varchar(200) DEFAULT NULL,
`android`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`webtonative`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`phone_supp`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`ios`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`appfactory`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`geolocation`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`qml`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`multi_screen`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`objc`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`comparison_data_last_update`  varchar(200) DEFAULT NULL,
`kindle`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`capture`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`livesync`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`trial`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`connection`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`webos`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`sdk`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`community_supp`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`ads`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`symbian`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`url`  varchar(200) DEFAULT NULL,
`remoteupdate`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`mobilewebsite`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`framework`  varchar(200) DEFAULT NULL,
`runtime`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`onsite_supp`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`MXML`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`stackoverflow`  varchar(200) DEFAULT NULL,
`html`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`watchos`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`iteration_speed`  varchar(200) DEFAULT NULL,
`bluetooth`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`status`  varchar(200) DEFAULT NULL,
`messages_telephone`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`market`  varchar(200) DEFAULT NULL,
`ruby`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`clouddev`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`encryption`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`javame`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`learning_curve`  varchar(200) DEFAULT NULL,
`windowsmobile`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`tutorial_url`  varchar(200) DEFAULT NULL,
`notification`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`java`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`twitter`  varchar(200) DEFAULT NULL,
`device`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`visualeditor`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`firefoxos`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`video_url`  varchar(200) DEFAULT NULL,
`perf_overhead`  varchar(200) DEFAULT NULL,
`nfc`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`appletv`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`nativeapp`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`UXMarkup`  enum('true','false','via','soon','partially','UNDEF')  DEFAULT NULL,
`documentation_url`  varchar(200) DEFAULT NULL) ENGINE=MyISAM AUTO_INCREMENT=115 DEFAULT CHARSET=latin1;
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
