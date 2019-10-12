<h1>WordsQuest Plugin</h1>

 You will need to install a MYSQL database table and words for<BR>
the words search to use in its puzzle. Its set to have at least<BR>
10 words in a 13x13 grid puzzle. wordsquest-grid.php has the<BR>
database configs.<BR>

<BR/>
<BR/>
--<BR/>
-- Database Setup: <BR/>
--<BR/>
<BR/>
----------------------------------------------------------<BR/>
<BR/>
--<BR/>
-- Table structure for table `wordsearch`<BR/>
--<BR/>
<BR/>
CREATE TABLE `wq_wordsearch` (<BR/>
          `word` varchar(20) COLLATE utf8_bin NOT NULL<BR/>
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;<BR/>
<BR/>
<BR/>
<BR/>
--<BR/>
-- Dumping data for table `wordsearch`<BR/>
--<BR/>
<BR/>
INSERT INTO `wq_wordsearch` (`word`) VALUES<BR/>
('ADDRESS'),<BR/>
('ALIAS'),<BR/>
<BR/>
etc.. etc.. etc..<BR/>
<BR/>
('DEPLOY'),<BR/>
('TIMESTAMP');<BR/>
<BR/>
--<BR/>
-- Indexes for dumped tables<BR/>
--<BR/>
<BR/>
--<BR/>
-- Indexes for table `wordsearch`<BR/>
--<BR/>
ALTER TABLE `wordsearch`<BR/>
  ADD PRIMARY KEY (`word`);<BR/>
COMMIT;<BR/>
<BR/>
