CREATE TABLE `ap_section_feeds` (
           id INT AUTO_INCREMENT PRIMARY KEY,
          `section` varchar(32) COLLATE utf8_bin NOT NULL,
          `site` varchar(64) COLLATE utf8_bin NOT NULL,
          `url` varchar(256) COLLATE utf8_bin NOT NULL,
          `rss` varchar(256) COLLATE utf8_bin NOT NULL,
          `catagory` varchar(20) COLLATE utf8_bin NOT NULL,
          `enable` BOOLEAN,
          `last_post_date` DATETIME            
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


