-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 19, 2016 at 01:54 PM
-- Server version: 5.5.47-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `rires`
--

-- --------------------------------------------------------

--
-- Table structure for table `evaluation`
--

CREATE TABLE IF NOT EXISTS `evaluation` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mid` bigint(20) NOT NULL COMMENT 'model id',
  `query_tag` bigint(20) NOT NULL COMMENT 'query set id, e.g. TREC1 or WT2G',
  `submitted_dt` datetime NOT NULL,
  `evaluated_dt` datetime NOT NULL,
  `evaluate_status` smallint(6) NOT NULL DEFAULT '-1',
  `evaluate_msg` mediumtext NOT NULL,
  `performances` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='stores the evaluation information. Basically which model against which query.' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `evaluation`
--

INSERT INTO `evaluation` (`id`, `mid`, `query_tag`, `submitted_dt`, `evaluated_dt`, `evaluate_status`, `evaluate_msg`, `performances`) VALUES
(1, 3, 1, '2016-04-18 15:34:01', '0000-00-00 00:00:00', -1, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `index_paths`
--

CREATE TABLE IF NOT EXISTS `index_paths` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) NOT NULL COMMENT 'the uid that added the index',
  `iname` varchar(255) NOT NULL COMMENT 'index name',
  `path` varchar(255) NOT NULL COMMENT 'index path',
  `add_dt` datetime NOT NULL,
  `notes` mediumtext,
  PRIMARY KEY (`id`),
  KEY `UIDFK` (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `index_paths`
--

INSERT INTO `index_paths` (`id`, `uid`, `iname`, `path`, `add_dt`, `notes`) VALUES
(2, 3, 'disk12', 'disk12', '2016-04-18 21:56:00', 'disk12 is for TREC1,2,3'),
(4, 3, 'disk45', 'disk45', '2016-04-18 21:55:55', 'disk45 is for queries TREC6,7,8 and ROBUST04.'),
(5, 3, 'wt2g', 'wt2g', '2016-04-18 21:51:34', '');

-- --------------------------------------------------------

--
-- Table structure for table `info`
--

CREATE TABLE IF NOT EXISTS `info` (
  `name` varchar(255) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `info`
--

INSERT INTO `info` (`name`, `content`) VALUES
('code_instruction', 'Intro\r\n-----\r\nThe Reproducible Information Retrieval Evaluation System (**RIRES**) is a web based service that enables the users to type and submit their own ranking models, get the models automatically evaluated. \r\n\r\nRIRES mainly depends on Indri toolkit. This means that the users should be familiar with C++ (C98 Standard). Knowing about Indri is a big plus. **_However we have modified the Indri code so you''d better read this instruction thoroughly in order to learn how to add new ranking models._** If you are not familiar with either of them please go to the following website to at least get the general idea.\r\n\r\n[Indri Toolkit](http://www.lemurproject.org/indri.php)\r\n\r\n[C++ Reference](http://www.cplusplus.com/)\r\n\r\nThe Rationale\r\n-----\r\n\r\nThe general ranking process of RIRES is like the following:\r\n\r\n1. For each query term, go to its inverted index and collect the collection-wise statistics about the term.\r\n2. Run the constructor of the ranking model (you can effectively insert some _pre-computing code_ into the constructor of your ranking model to get things done faster). \r\n3. Go through every document in the inverted list and compute the document score for this query term.\r\n4. For each document, add the scores of all query terms as: $\\sum_{q \\in Q}qtf\\cdot f(q)$\r\n\r\nThe Sample Code\r\n-----\r\n\r\nHere we explain how to add new ranking model by inspecting the sample code.\r\nThe sample code implements the Dirichlet Language Model.\r\n\r\nWe first look at the HPP file of the code file. Please note that you cannot modify this file. There are several private variables. They are either collection-wide statistics or the query statistics:\r\n- __collectionOccurence_: total count of the term in the collection\r\n- __collectionSize_: total terms in the collection\r\n- __documentOccurrences_: number of documents in which the term occurs\r\n- __documentCount_: total number of documents in the collection\r\n- __queryLength_: number of terms in the query\r\n\r\nYou can directly access these statistics in your ranking models. \r\nHowever, if you would like to use any additional variables you have to declare them as the \r\nkey-value pairs in the private variable __modelParas_.\r\n\r\nYou will see in the CPP File that there are several additional variables in __modelParas_: \r\n\r\n_mu_, _collectionFrequency_ and __muTimesCollectionFrequency_. \r\n\r\nYou can also put some computations that are documents independent  in the __preCompute()_ function. This will speed up the computation and also keep your code''s logic cleaner. \r\n\r\n**HPP File**\r\n```\r\n#include <string>\r\n#include <map>\r\n\r\nnamespace indri\r\n{\r\n  namespace query\r\n  {\r\n    class TermScoreFunction {\r\n    private:\r\n      double _collectionOccurence;\r\n      double _collectionSize;\r\n      double _documentOccurrences;\r\n      double _documentCount;\r\n      double _queryLength;\r\n      std::map<std::string, double> _modelParas;\r\n\r\n      void _preCompute();\r\n    public:\r\n      TermScoreFunction( double collectionOccurence, double collectionSize, double documentOccurrences, double documentCount, double queryLength, std::map<std::string, double>& paras );\r\n      double scoreOccurrence( double occurrences, int contextLength, double qtf, double docUniqueTerms );\r\n    };\r\n  }\r\n}\r\n```\r\n\r\nFor the CPP File, the most important function is _scoreOccurrence_. It computes the score of \r\nthe current document for the current query term. Please note that the arguments of this function \r\nis different from Indri''s implementation:\r\n- occurrences: term frequency of the document (tf)\r\n- contextSize: document length\r\n- qtf: query term frequency (_**note that each unique query term will be judged only once and you should take care of the qtf by yourself!**_)\r\n- docUniqueTerms: number of unique terms in the documents\r\n\r\nYou can use the variables defined in _modelParas to facilitate the computation. For example, in the sample code _modelParas["_muTimesCollectionFrequency"] is used to compute the document score. As mentioned before, \r\nyou need to take care of the qtf, that is why we multiply qtf to the score. Here the returned value of scoreOccurrence function is the score of current query term. Scores of all query terms will be added together to generate the final score of the document.\r\n\r\n\r\n**CPP File**\r\n```\r\n#include "indri/TermScoreFunction.hpp"\r\n#include <cmath>\r\n\r\nindri::query::TermScoreFunction::TermScoreFunction( double collectionOccurence, double collectionSize, double documentOccurrences, double documentCount, double queryLength, std::map<std::string, double>& paras ) {\r\n  _collectionOccurence = collectionOccurence;\r\n  _collectionSize = collectionSize;\r\n  _documentOccurrences = documentOccurrences;\r\n  _documentCount = documentCount;\r\n  _modelParas = paras;\r\n  _modelParas["mu"] = 2500;\r\n  _modelParas["collectionFrequency"] = _collectionOccurence ? (_collectionOccurence/_collectionSize) : (1.0 / _collectionSize*2.);\r\n  _modelParas["_muTimesCollectionFrequency"] = _modelParas["mu"] * _modelParas["collectionFrequency"];\r\n  //_preCompute();\r\n}\r\n\r\n\r\ndouble indri::query::TermScoreFunction::scoreOccurrence( double occurrences, int contextSize, double qtf, double docUniqueTerms ) {\r\n  double seen = ( double(occurrences) + _modelParas["_muTimesCollectionFrequency"] ) / ( double(contextSize) + _modelParas["mu"] );\r\n  return qtf * log( seen );\r\n}\r\n```');

-- --------------------------------------------------------

--
-- Table structure for table `models`
--

CREATE TABLE IF NOT EXISTS `models` (
  `mid` bigint(20) NOT NULL AUTO_INCREMENT,
  `uid` bigint(12) NOT NULL,
  `mname` varchar(255) NOT NULL,
  `mpara` varchar(255) NOT NULL,
  `mnotes` text NOT NULL,
  `mbody` text NOT NULL,
  `submitted_dt` datetime NOT NULL,
  `last_modified_dt` datetime NOT NULL,
  `last_compile_dt` datetime NOT NULL,
  `compile_status` tinyint(4) NOT NULL DEFAULT '-1',
  `compile_msg` mediumtext NOT NULL,
  PRIMARY KEY (`mid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `models`
--

INSERT INTO `models` (`mid`, `uid`, `mname`, `mpara`, `mnotes`, `mbody`, `submitted_dt`, `last_modified_dt`, `last_compile_dt`, `compile_status`, `compile_msg`) VALUES
(1, 3, '11sdf', 'sdfjdlsjf', 'xcvxcvsadfdsfsadfsdfs', 'sadfsdfsafsdfsfdfvcxvcxvcxvcxvcxv', '2016-03-03 02:50:48', '2016-03-03 02:50:48', '2016-04-16 21:34:03', 1, '../src/TermScoreFunction.cpp:1:1: error: ''sadfsdfsafsdfsfdfvcxvcxvcxvcxvcx'' does not name a type\n sadfsdfsafsdfsfdfvcxvcxvcxvcxvcx\n ^\nmake[1]: *** [TermScoreFunction.o] Error 1\nmake: *** [all] Error 2\n\n'),
(2, 3, 'sec', 'asdfczxc', '1111', 'sdfsdfv', '2016-03-03 02:57:28', '2016-03-03 02:57:28', '2016-04-14 14:04:08', 1, '../src/TermScoreFunction.cpp:1:1: error: ''sdfsdfv'' does not name a type\n sdfsdfv\n ^\nmake[1]: *** [TermScoreFunction.o] Error 1\nmake: *** [all] Error 2\n\n'),
(3, 3, 'test', '1', 'this is the formal test', '#include "indri/TermScoreFunction.hpp"\r\n#include <cmath>\r\n\r\nindri::query::TermScoreFunction::TermScoreFunction( double collectionOccurence, double collectionSize, double documentOccurrences, double documentCount, double queryLength, std::map<std::string, double>& paras ) {\r\n  _collectionOccurence = collectionOccurence;\r\n  _collectionSize = collectionSize;\r\n  _documentOccurrences = documentOccurrences;\r\n  _documentCount = documentCount;\r\n}\r\n\r\n\r\ndouble indri::query::TermScoreFunction::scoreOccurrence( double occurrences, int contextSize, double qtf, double docUniqueTerms ) {\r\n  return qtf * occurrences;\r\n}', '2016-03-03 18:58:01', '2016-03-03 18:58:01', '2016-04-14 14:04:18', 0, ''),
(4, 3, '11sdf', 'sdfjdlsjf', 'xcvxcvsadfdsfsadfsdfs', '#include "indri/TermScoreFunction.hpp"\r\n#include <cmath>\r\n\r\nindri::query::TermScoreFunction::TermScoreFunction( double collectionOccurence, double collectionSize, double documentOccurrences, double documentCount, double queryLength, std::map<std::string, double>& paras ) {\r\n  _collectionOccurence = collectionOccurence;\r\n  _collectionSize = collectionSize;\r\n  _documentOccurrences = documentOccurrences;\r\n  _documentCount = documentCount;\r\n  _modelParas = paras;\r\n  _modelParas["mu"] = 2500;\r\n  _modelParas["collectionFrequency"] = _collectionOccurence ? (_collectionOccurence/_collectionSize) : (1.0 / _collectionSize*2.);\r\n  _modelParas["_muTimesCollectionFrequency"] = _modelParas["mu"] * _modelParas["collectionFrequency"];\r\n  _preCompute();\r\n}\r\n\r\n\r\ndouble indri::query::TermScoreFunction::scoreOccurrence( double occurrences, int contextSize, double qtf, double docUniqueTerms ) {\r\n  double seen = ( double(occurrences) + _modelParas["_muTimesCollectionFrequency"] ) / ( double(contextSize) + _modelParas["mu"] );\r\n  return qtf * log( seen );\r\n}', '2016-04-16 20:45:02', '2016-04-16 20:45:02', '2016-04-16 21:34:03', 1, '../src/TermScoreFunction.cpp:1:1: error: ''sadfsdfsafsdfsfdfvcxvcxvcxvcxvcx'' does not name a type\n sadfsdfsafsdfsfdfvcxvcxvcxvcxvcx\n ^\nmake[1]: *** [TermScoreFunction.o] Error 1\nmake: *** [all] Error 2\n\n'),
(5, 3, '11sdf', 'sdfjdlsjf', 'xcvxcvsadfdsfsadfsdfs', '#include "indri/TermScoreFunction.hpp"\r\n#include <cmath>\r\n\r\nindri::query::TermScoreFunction::TermScoreFunction( double collectionOccurence, double collectionSize, double documentOccurrences, double documentCount, double queryLength, std::map<std::string, double>& paras ) {\r\n  _collectionOccurence = collectionOccurence;\r\n  _collectionSize = collectionSize;\r\n  _documentOccurrences = documentOccurrences;\r\n  _documentCount = documentCount;\r\n  _modelParas = paras;\r\n  _modelParas["mu"] = 2500;\r\n  _modelParas["collectionFrequency"] = _collectionOccurence ? (_collectionOccurence/_collectionSize) : (1.0 / _collectionSize*2.);\r\n  _modelParas["_muTimesCollectionFrequency"] = _modelParas["mu"] * _modelParas["collectionFrequency"];\r\n}\r\n\r\n\r\ndouble indri::query::TermScoreFunction::scoreOccurrence( double occurrences, int contextSize, double qtf, double docUniqueTerms ) {\r\n  double seen = ( double(occurrences) + _modelParas["_muTimesCollectionFrequency"] ) / ( double(contextSize) + _modelParas["mu"] );\r\n  return qtf * log( seen );\r\n}', '2016-04-16 20:48:55', '2016-04-16 20:48:55', '2016-04-16 21:34:03', 1, '../src/TermScoreFunction.cpp:1:1: error: ''sadfsdfsafsdfsfdfvcxvcxvcxvcxvcx'' does not name a type\n sadfsdfsafsdfsfdfvcxvcxvcxvcxvcx\n ^\nmake[1]: *** [TermScoreFunction.o] Error 1\nmake: *** [all] Error 2\n\n'),
(6, 3, '11sdf', 'sdfjdlsjf', 'xcvxcvsadfdsfsadfsdfs', '#include "indri/TermScoreFunction.hpp"\r\n#include <cmath>\r\n\r\nindri::query::TermScoreFunction::TermScoreFunction( double collectionOccurence, double collectionSize, double documentOccurrences, double documentCount, double queryLength, std::map<std::string, double>& paras ) {\r\n  _collectionOccurence = collectionOccurence;\r\n  _collectionSize = collectionSize;\r\n  _documentOccurrences = documentOccurrences;\r\n  _documentCount = documentCount;\r\n  _modelParas = paras;\r\n  _modelParas["mu"] = 2500;\r\n  _modelParas["collectionFrequency"] = _collectionOccurence ? (_collectionOccurence/_collectionSize) : (1.0 / _collectionSize*2.);\r\n  _modelParas["_muTimesCollectionFrequency"] = _modelParas["mu"] * _modelParas["collectionFrequency"];\r\n  //_preCompute();\r\n}\r\n\r\n\r\ndouble indri::query::TermScoreFunction::scoreOccurrence( double occurrences, int contextSize, double qtf, double docUniqueTerms ) {\r\n  double seen = ( double(occurrences) + _modelParas["_muTimesCollectionFrequency"] ) / ( double(contextSize) + _modelParas["mu"] );\r\n  return qtf * log( seen );\r\n}', '2016-04-16 21:33:13', '2016-04-18 12:52:33', '2016-04-18 12:53:12', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `query_paths`
--

CREATE TABLE IF NOT EXISTS `query_paths` (
  `query_tag` bigint(20) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `index_id` bigint(20) NOT NULL COMMENT 'query must be associated with index',
  `query_path` varchar(255) NOT NULL,
  `evaluation_path` varchar(255) NOT NULL,
  `notes` varchar(255) NOT NULL,
  `add_dt` datetime NOT NULL,
  PRIMARY KEY (`query_tag`),
  KEY `uid` (`uid`),
  KEY `index_path` (`index_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `query_paths`
--

INSERT INTO `query_paths` (`query_tag`, `uid`, `name`, `index_id`, `query_path`, `evaluation_path`, `notes`, `add_dt`) VALUES
(1, 3, 'wt2g', 5, 'wt2g', 'wt2g', 'query topics for WT2G', '2016-04-18 21:51:24');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `uid` bigint(12) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `active` varchar(255) NOT NULL COMMENT 'md5 in the activation URL',
  `regAt` datetime DEFAULT NULL,
  `activateAt` datetime DEFAULT NULL,
  `apikey` varchar(64) NOT NULL,
  `firstLoginAt` datetime DEFAULT NULL,
  `resetToken` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `resetComplete` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `isAdmin` tinyint(1) NOT NULL DEFAULT '0',
  `isTest` tinyint(1) NOT NULL DEFAULT '0',
  `firstname` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `middlename` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `lastname` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `institute` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uid`, `username`, `email`, `password`, `active`, `regAt`, `activateAt`, `apikey`, `firstLoginAt`, `resetToken`, `resetComplete`, `isAdmin`, `isTest`, `firstname`, `middlename`, `lastname`, `institute`) VALUES
(3, 'franklyn', 'yangpeilyn@gmail.com', '$2y$10$W4ComNNMhJVDyiMWeVn8k.SYi1nGSysZTbaty6sQ3.H3R/n41x28W', 'Yes', '2016-03-02 19:41:51', '2016-03-02 19:41:58', 'bp5yg7xe0muctk42iq9j6wlh1zvsr3ad8fno', '2016-03-02 19:42:00', NULL, NULL, 1, 0, 'Peilin', '', 'Yang', 'University of Delaware');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `index_paths`
--
ALTER TABLE `index_paths`
  ADD CONSTRAINT `index_paths_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`);

--
-- Constraints for table `models`
--
ALTER TABLE `models`
  ADD CONSTRAINT `models_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `query_paths`
--
ALTER TABLE `query_paths`
  ADD CONSTRAINT `query_paths_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`),
  ADD CONSTRAINT `query_paths_ibfk_2` FOREIGN KEY (`index_id`) REFERENCES `index_paths` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
