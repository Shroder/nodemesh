-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.5.16


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema properties
--

CREATE DATABASE IF NOT EXISTS properties;
USE properties;

--
-- Definition of table `feature_categories`
--

DROP TABLE IF EXISTS `feature_categories`;
CREATE TABLE `feature_categories` (
  `pk` int(11) NOT NULL AUTO_INCREMENT,
  `context` int(11) DEFAULT NULL,
  `title` varchar(120) DEFAULT NULL,
  `ext_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`pk`),
  KEY `feature_categories_context_constraint` (`context`),
  CONSTRAINT `feature_categories_context_constraint` FOREIGN KEY (`context`) REFERENCES `node_contexts` (`pk`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `feature_categories`
--

/*!40000 ALTER TABLE `feature_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `feature_categories` ENABLE KEYS */;


--
-- Definition of table `feature_categories#features`
--

DROP TABLE IF EXISTS `feature_categories#features`;
CREATE TABLE `feature_categories#features` (
  `pk1` int(11) NOT NULL,
  `pk2` int(11) NOT NULL,
  `label` varchar(15) DEFAULT NULL,
  `direction` enum('BIDI','LTR','RTL') NOT NULL DEFAULT 'BIDI',
  PRIMARY KEY (`pk1`,`pk2`),
  KEY `feature_categories_features_constraint_2` (`pk2`),
  CONSTRAINT `feature_categories_features_constraint_1` FOREIGN KEY (`pk1`) REFERENCES `feature_categories` (`pk`),
  CONSTRAINT `feature_categories_features_constraint_2` FOREIGN KEY (`pk2`) REFERENCES `features` (`pk`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

--
-- Dumping data for table `feature_categories#features`
--

/*!40000 ALTER TABLE `feature_categories#features` DISABLE KEYS */;
/*!40000 ALTER TABLE `feature_categories#features` ENABLE KEYS */;


--
-- Definition of table `features`
--

DROP TABLE IF EXISTS `features`;
CREATE TABLE `features` (
  `pk` int(11) NOT NULL AUTO_INCREMENT,
  `context` int(11) DEFAULT NULL,
  `title` varchar(120) DEFAULT NULL,
  `ext_id` int(11) DEFAULT NULL,
  `code` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`pk`),
  KEY `features_context_constraint` (`context`),
  CONSTRAINT `features_context_constraint` FOREIGN KEY (`context`) REFERENCES `node_contexts` (`pk`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `features`
--

/*!40000 ALTER TABLE `features` DISABLE KEYS */;
/*!40000 ALTER TABLE `features` ENABLE KEYS */;


--
-- Definition of table `features#properties`
--

DROP TABLE IF EXISTS `features#properties`;
CREATE TABLE `features#properties` (
  `pk1` int(11) NOT NULL,
  `pk2` int(11) NOT NULL,
  `label` varchar(15) DEFAULT NULL,
  `direction` enum('BIDI','LTR','RTL') NOT NULL DEFAULT 'BIDI',
  PRIMARY KEY (`pk1`,`pk2`),
  KEY `features_properties_constraint_2` (`pk2`),
  CONSTRAINT `features_properties_constraint_1` FOREIGN KEY (`pk1`) REFERENCES `features` (`pk`),
  CONSTRAINT `features_properties_constraint_2` FOREIGN KEY (`pk2`) REFERENCES `properties` (`pk`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

--
-- Dumping data for table `features#properties`
--

/*!40000 ALTER TABLE `features#properties` DISABLE KEYS */;
/*!40000 ALTER TABLE `features#properties` ENABLE KEYS */;


--
-- Definition of table `form_field_options`
--

DROP TABLE IF EXISTS `form_field_options`;
CREATE TABLE `form_field_options` (
  `pk` int(11) NOT NULL AUTO_INCREMENT,
  `context` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `value` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`pk`),
  KEY `form_field_options_context_constraint` (`context`),
  CONSTRAINT `form_field_options_context_constraint` FOREIGN KEY (`context`) REFERENCES `node_contexts` (`pk`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `form_field_options`
--

/*!40000 ALTER TABLE `form_field_options` DISABLE KEYS */;
/*!40000 ALTER TABLE `form_field_options` ENABLE KEYS */;


--
-- Definition of table `form_field_options#form_fields`
--

DROP TABLE IF EXISTS `form_field_options#form_fields`;
CREATE TABLE `form_field_options#form_fields` (
  `pk1` int(11) NOT NULL,
  `pk2` int(11) NOT NULL,
  `label` varchar(15) DEFAULT NULL,
  `direction` enum('BIDI','LTR','RTL') NOT NULL DEFAULT 'BIDI',
  PRIMARY KEY (`pk1`,`pk2`),
  KEY `form_field_options_form_fields_constraint_2` (`pk2`),
  CONSTRAINT `form_field_options_form_fields_constraint_1` FOREIGN KEY (`pk1`) REFERENCES `form_field_options` (`pk`),
  CONSTRAINT `form_field_options_form_fields_constraint_2` FOREIGN KEY (`pk2`) REFERENCES `form_fields` (`pk`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

--
-- Dumping data for table `form_field_options#form_fields`
--

/*!40000 ALTER TABLE `form_field_options#form_fields` DISABLE KEYS */;
/*!40000 ALTER TABLE `form_field_options#form_fields` ENABLE KEYS */;


--
-- Definition of table `form_fields`
--

DROP TABLE IF EXISTS `form_fields`;
CREATE TABLE `form_fields` (
  `pk` int(11) NOT NULL AUTO_INCREMENT,
  `context` int(11) DEFAULT NULL,
  `label` varchar(120) DEFAULT NULL,
  `default_value` varchar(120) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `style` text,
  `class` varchar(50) DEFAULT NULL,
  `id` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `call_function` varchar(25) NOT NULL,
  PRIMARY KEY (`pk`),
  KEY `form_fields_context_constraint` (`context`),
  CONSTRAINT `form_fields_context_constraint` FOREIGN KEY (`context`) REFERENCES `node_contexts` (`pk`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `form_fields`
--

/*!40000 ALTER TABLE `form_fields` DISABLE KEYS */;
/*!40000 ALTER TABLE `form_fields` ENABLE KEYS */;


--
-- Definition of table `form_fields#forms`
--

DROP TABLE IF EXISTS `form_fields#forms`;
CREATE TABLE `form_fields#forms` (
  `pk1` int(11) NOT NULL,
  `pk2` int(11) NOT NULL,
  `label` varchar(15) DEFAULT NULL,
  `direction` enum('BIDI','LTR','RTL') NOT NULL DEFAULT 'BIDI',
  PRIMARY KEY (`pk1`,`pk2`),
  KEY `form_fields_forms_constraint_2` (`pk2`),
  CONSTRAINT `form_fields_forms_constraint_1` FOREIGN KEY (`pk1`) REFERENCES `form_fields` (`pk`),
  CONSTRAINT `form_fields_forms_constraint_2` FOREIGN KEY (`pk2`) REFERENCES `forms` (`pk`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

--
-- Dumping data for table `form_fields#forms`
--

/*!40000 ALTER TABLE `form_fields#forms` DISABLE KEYS */;
/*!40000 ALTER TABLE `form_fields#forms` ENABLE KEYS */;


--
-- Definition of table `forms`
--

DROP TABLE IF EXISTS `forms`;
CREATE TABLE `forms` (
  `pk` int(11) NOT NULL AUTO_INCREMENT,
  `context` int(11) DEFAULT NULL,
  `name` varchar(120) DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`pk`),
  KEY `forms_context_constraint` (`context`),
  CONSTRAINT `forms_context_constraint` FOREIGN KEY (`context`) REFERENCES `node_contexts` (`pk`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `forms`
--

/*!40000 ALTER TABLE `forms` DISABLE KEYS */;
/*!40000 ALTER TABLE `forms` ENABLE KEYS */;


--
-- Definition of table `images`
--

DROP TABLE IF EXISTS `images`;
CREATE TABLE `images` (
  `pk` int(11) NOT NULL AUTO_INCREMENT,
  `context` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `title` varchar(120) DEFAULT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `alt_text` varchar(120) DEFAULT NULL,
  `mls` int(11) NOT NULL,
  `create_date` date NOT NULL,
  `change_date` date NOT NULL,
  `refresh_date` date NOT NULL,
  PRIMARY KEY (`pk`),
  KEY `images_context_constraint` (`context`),
  CONSTRAINT `images_context_constraint` FOREIGN KEY (`context`) REFERENCES `node_contexts` (`pk`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `images`
--

/*!40000 ALTER TABLE `images` DISABLE KEYS */;
/*!40000 ALTER TABLE `images` ENABLE KEYS */;


--
-- Definition of table `images#properties`
--

DROP TABLE IF EXISTS `images#properties`;
CREATE TABLE `images#properties` (
  `pk1` int(11) NOT NULL,
  `pk2` int(11) NOT NULL,
  `label` varchar(15) DEFAULT NULL,
  `direction` enum('BIDI','LTR','RTL') NOT NULL DEFAULT 'BIDI',
  PRIMARY KEY (`pk1`,`pk2`),
  KEY `images_properties_constraint_2` (`pk2`),
  CONSTRAINT `images_properties_constraint_1` FOREIGN KEY (`pk1`) REFERENCES `images` (`pk`),
  CONSTRAINT `images_properties_constraint_2` FOREIGN KEY (`pk2`) REFERENCES `properties` (`pk`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

--
-- Dumping data for table `images#properties`
--

/*!40000 ALTER TABLE `images#properties` DISABLE KEYS */;
/*!40000 ALTER TABLE `images#properties` ENABLE KEYS */;


--
-- Definition of table `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE `logs` (
  `pk` int(11) NOT NULL AUTO_INCREMENT,
  `context` int(11) DEFAULT NULL,
  `msg` varchar(120) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`pk`),
  KEY `logs_context_constraint` (`context`),
  CONSTRAINT `logs_context_constraint` FOREIGN KEY (`context`) REFERENCES `node_contexts` (`pk`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `logs`
--

/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `logs` ENABLE KEYS */;


--
-- Definition of table `node_contexts`
--

DROP TABLE IF EXISTS `node_contexts`;
CREATE TABLE `node_contexts` (
  `pk` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(31) NOT NULL,
  PRIMARY KEY (`pk`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='latin1_swedish_ci';

--
-- Dumping data for table `node_contexts`
--

/*!40000 ALTER TABLE `node_contexts` DISABLE KEYS */;
/*!40000 ALTER TABLE `node_contexts` ENABLE KEYS */;


--
-- Definition of table `node_history`
--

DROP TABLE IF EXISTS `node_history`;
CREATE TABLE `node_history` (
  `pk1` int(11) NOT NULL,
  `type1` varchar(20) NOT NULL,
  `pk2` int(11) DEFAULT NULL,
  `type2` varchar(20) DEFAULT NULL,
  `action` varchar(20) NOT NULL,
  `date` datetime NOT NULL,
  `trigger_pk` int(11) DEFAULT NULL,
  `trigger_type` varchar(20) DEFAULT NULL,
  `trigger_process` varchar(50) DEFAULT NULL,
  KEY `pk1` (`pk1`),
  KEY `pk2` (`pk2`),
  KEY `trigger_node` (`trigger_pk`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `node_history`
--

/*!40000 ALTER TABLE `node_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `node_history` ENABLE KEYS */;


--
-- Definition of table `properties`
--

DROP TABLE IF EXISTS `properties`;
CREATE TABLE `properties` (
  `pk` int(11) NOT NULL AUTO_INCREMENT,
  `context` int(11) DEFAULT NULL,
  `mls` int(11) DEFAULT NULL,
  `address` varchar(75) DEFAULT NULL,
  `street_number` varchar(50) DEFAULT NULL,
  `street_name` varchar(75) DEFAULT NULL,
  `cross_street` varchar(75) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `zip` varchar(20) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `description` text,
  `listing_long` float DEFAULT NULL,
  `listing_lat` float DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `sale_rent` varchar(20) DEFAULT NULL,
  `bedrooms` int(11) DEFAULT NULL,
  `bathrooms` int(11) DEFAULT NULL,
  `half_baths` float DEFAULT NULL,
  `stories` varchar(20) DEFAULT NULL,
  `stories_info` varchar(50) DEFAULT NULL,
  `water` varchar(50) DEFAULT NULL,
  `sewer` varchar(50) DEFAULT NULL,
  `roads` varchar(50) DEFAULT NULL,
  `power` varchar(50) DEFAULT NULL,
  `zoning` varchar(50) DEFAULT NULL,
  `acreage` float DEFAULT NULL,
  `sqfeet` int(11) DEFAULT NULL,
  `year_built` tinyint(4) DEFAULT NULL,
  `listing_date` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `pushed` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`pk`),
  KEY `properties_context_constraint` (`context`),
  CONSTRAINT `properties_context_constraint` FOREIGN KEY (`context`) REFERENCES `node_contexts` (`pk`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `properties`
--

/*!40000 ALTER TABLE `properties` DISABLE KEYS */;
/*!40000 ALTER TABLE `properties` ENABLE KEYS */;


--
-- Definition of table `properties#property_agents`
--

DROP TABLE IF EXISTS `properties#property_agents`;
CREATE TABLE `properties#property_agents` (
  `pk1` int(11) NOT NULL,
  `pk2` int(11) NOT NULL,
  `label` varchar(15) DEFAULT NULL,
  `direction` enum('BIDI','LTR','RTL') NOT NULL DEFAULT 'BIDI',
  `isPrimary` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`pk1`,`pk2`),
  KEY `properties_property_agents_constraint_2` (`pk2`),
  CONSTRAINT `properties_property_agents_constraint_1` FOREIGN KEY (`pk1`) REFERENCES `properties` (`pk`),
  CONSTRAINT `properties_property_agents_constraint_2` FOREIGN KEY (`pk2`) REFERENCES `property_agents` (`pk`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

--
-- Dumping data for table `properties#property_agents`
--

/*!40000 ALTER TABLE `properties#property_agents` DISABLE KEYS */;
/*!40000 ALTER TABLE `properties#property_agents` ENABLE KEYS */;


--
-- Definition of table `properties#property_searches`
--

DROP TABLE IF EXISTS `properties#property_searches`;
CREATE TABLE `properties#property_searches` (
  `pk1` int(11) NOT NULL,
  `pk2` int(11) NOT NULL,
  `label` varchar(15) DEFAULT NULL,
  `direction` enum('BIDI','LTR','RTL') NOT NULL DEFAULT 'BIDI',
  PRIMARY KEY (`pk1`,`pk2`),
  KEY `properties_property_searches_constraint_2` (`pk2`),
  CONSTRAINT `properties_property_searches_constraint_1` FOREIGN KEY (`pk1`) REFERENCES `properties` (`pk`),
  CONSTRAINT `properties_property_searches_constraint_2` FOREIGN KEY (`pk2`) REFERENCES `property_searches` (`pk`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

--
-- Dumping data for table `properties#property_searches`
--

/*!40000 ALTER TABLE `properties#property_searches` DISABLE KEYS */;
/*!40000 ALTER TABLE `properties#property_searches` ENABLE KEYS */;


--
-- Definition of table `properties#tags`
--

DROP TABLE IF EXISTS `properties#tags`;
CREATE TABLE `properties#tags` (
  `pk1` int(11) NOT NULL,
  `pk2` int(11) NOT NULL,
  `label` varchar(15) DEFAULT NULL,
  `direction` enum('BIDI','LTR','RTL') NOT NULL DEFAULT 'BIDI',
  PRIMARY KEY (`pk1`,`pk2`),
  KEY `properties_tags_constraint_2` (`pk2`),
  CONSTRAINT `properties_tags_constraint_1` FOREIGN KEY (`pk1`) REFERENCES `properties` (`pk`),
  CONSTRAINT `properties_tags_constraint_2` FOREIGN KEY (`pk2`) REFERENCES `tags` (`pk`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

--
-- Dumping data for table `properties#tags`
--

/*!40000 ALTER TABLE `properties#tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `properties#tags` ENABLE KEYS */;


--
-- Definition of table `property_agents`
--

DROP TABLE IF EXISTS `property_agents`;
CREATE TABLE `property_agents` (
  `pk` int(11) NOT NULL AUTO_INCREMENT,
  `context` int(11) DEFAULT NULL,
  `name` varchar(80) DEFAULT NULL,
  `phone` varchar(25) DEFAULT NULL,
  `ext_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`pk`),
  KEY `property_agents_context_constraint` (`context`),
  CONSTRAINT `property_agents_context_constraint` FOREIGN KEY (`context`) REFERENCES `node_contexts` (`pk`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `property_agents`
--

/*!40000 ALTER TABLE `property_agents` DISABLE KEYS */;
/*!40000 ALTER TABLE `property_agents` ENABLE KEYS */;


--
-- Definition of table `property_agents#property_offices`
--

DROP TABLE IF EXISTS `property_agents#property_offices`;
CREATE TABLE `property_agents#property_offices` (
  `pk1` int(11) NOT NULL,
  `pk2` int(11) NOT NULL,
  `label` varchar(15) DEFAULT NULL,
  `direction` enum('BIDI','LTR','RTL') NOT NULL DEFAULT 'BIDI',
  PRIMARY KEY (`pk1`,`pk2`),
  KEY `property_agents_property_offices_constraint_2` (`pk2`),
  CONSTRAINT `property_agents_property_offices_constraint_1` FOREIGN KEY (`pk1`) REFERENCES `property_agents` (`pk`),
  CONSTRAINT `property_agents_property_offices_constraint_2` FOREIGN KEY (`pk2`) REFERENCES `property_offices` (`pk`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

--
-- Dumping data for table `property_agents#property_offices`
--

/*!40000 ALTER TABLE `property_agents#property_offices` DISABLE KEYS */;
/*!40000 ALTER TABLE `property_agents#property_offices` ENABLE KEYS */;


--
-- Definition of table `property_offices`
--

DROP TABLE IF EXISTS `property_offices`;
CREATE TABLE `property_offices` (
  `pk` int(11) NOT NULL AUTO_INCREMENT,
  `context` int(11) DEFAULT NULL,
  `name` varchar(80) DEFAULT NULL,
  `phone` varchar(25) DEFAULT NULL,
  `ext_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`pk`),
  KEY `property_offices_context_constraint` (`context`),
  CONSTRAINT `property_offices_context_constraint` FOREIGN KEY (`context`) REFERENCES `node_contexts` (`pk`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `property_offices`
--

/*!40000 ALTER TABLE `property_offices` DISABLE KEYS */;
/*!40000 ALTER TABLE `property_offices` ENABLE KEYS */;


--
-- Definition of table `property_searches`
--

DROP TABLE IF EXISTS `property_searches`;
CREATE TABLE `property_searches` (
  `pk` int(11) NOT NULL AUTO_INCREMENT,
  `context` int(11) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `keywords` varchar(50) DEFAULT NULL,
  `min_price` int(11) DEFAULT NULL,
  `max_price` int(11) DEFAULT NULL,
  `bedrooms` int(11) DEFAULT NULL,
  `bathrooms` float DEFAULT NULL,
  `sqft` int(11) DEFAULT NULL,
  `min_acres` float DEFAULT NULL,
  `max_acres` float DEFAULT NULL,
  `year_built` tinyint(4) DEFAULT NULL,
  `date_range` int(11) DEFAULT NULL,
  `use_html` tinyint(1) DEFAULT NULL,
  `send_notification` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`pk`),
  KEY `property_searches_context_constraint` (`context`),
  CONSTRAINT `property_searches_context_constraint` FOREIGN KEY (`context`) REFERENCES `node_contexts` (`pk`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `property_searches`
--

/*!40000 ALTER TABLE `property_searches` DISABLE KEYS */;
/*!40000 ALTER TABLE `property_searches` ENABLE KEYS */;


--
-- Definition of table `tags`
--

DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `pk` int(11) NOT NULL AUTO_INCREMENT,
  `context` int(11) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `feed_code` varchar(50) DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  `subtype` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`pk`),
  UNIQUE KEY `code` (`code`),
  KEY `tags_context_constraint` (`context`),
  CONSTRAINT `tags_context_constraint` FOREIGN KEY (`context`) REFERENCES `node_contexts` (`pk`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tags`
--

/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;


--
-- Definition of table `tags#tags`
--

DROP TABLE IF EXISTS `tags#tags`;
CREATE TABLE `tags#tags` (
  `pk1` int(11) NOT NULL,
  `pk2` int(11) NOT NULL,
  `label` varchar(15) DEFAULT NULL,
  `direction` enum('BIDI','LTR','RTL') NOT NULL DEFAULT 'BIDI',
  PRIMARY KEY (`pk1`,`pk2`),
  KEY `tags_tags_constraint_2` (`pk2`),
  CONSTRAINT `tags_tags_constraint_1` FOREIGN KEY (`pk1`) REFERENCES `tags` (`pk`),
  CONSTRAINT `tags_tags_constraint_2` FOREIGN KEY (`pk2`) REFERENCES `tags` (`pk`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;

--
-- Dumping data for table `tags#tags`
--

/*!40000 ALTER TABLE `tags#tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `tags#tags` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
