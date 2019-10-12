<?php

class Grid  {
    
    // SETTINGS ------------------------------------------------
    const MIN_LEN_WORD = 3; 
    const MAX_LEN_WORD = 13; 
    const DEFAULT_GRID_SIZE = 13;
    const MAX_WORD_COUNT = 10; 

    // END SETTINGS --------------------------------------------
    
    const RENDER_HTML = 0; 
    const RENDER_TEXT = 1; 
  
    private $_size;
    private $_cells; 
    private $_wordsList; 
    private $_arrayCOL;
    private $_errorMsg; 
    private $_answers; 
    
    
  public function __construct($size=self::DEFAULT_GRID_SIZE) {
        
        $this->_errorMsg='';
        
        if($size<self::MIN_LEN_WORD || $size>self::MAX_LEN_WORD) {
            $this->_errorMsg='size must be between '.self::MIN_LEN_WORD.' and '.self::MAX_LEN_WORD;
            echo $this->_errorMsg;
            return;
            }
        
	$this->_size=$size;
        $this->_wordsList=array();
        $this->_cells=array_fill(0,$this->_size*$this->_size,'');
        $this->_answers=array(); 
               
        $this->_arrayCOL = array(); 
        for($i=0;$i<(2*$this->_size*$this->_size);$i++) {
            $this->_arrayCOL[$i]=self::COL($i);
            }
		}

        
    public function __destruct() {
        if($this->_errorMsg!='') {return;}
        }
        
   
    public function renderWordsList($end=' ') {
        
             
        if($this->_errorMsg!='') {return;}
        $arr=array();
        $num=1;
        foreach($this->_wordsList as $word) {
            $label=$word->getLabel();
            $startp=$word->getStart();
            $endp=$word->getEnd();
            $orient=$word->getOrientation();
            if($word->isReversed()) {$label=strrev($label);}
            $arr[]="<span id=\"ws".$num."\" class=\"wordsearchitem\" >".$label."</span>";            
            array_push($this->_answers , $label , $startp , $endp , $orient); 
            $num++; 
            }
        //sort array for visual presentation. 
        sort($arr);
        $r='';
        foreach($arr as $label) {
            $r.=$label.$end;
            }        
        return $r;
        } //renderWordList

    //Create javascript array to hold answer data for client side.
    //Could do this server side , but no security is required or
    //latency needed. For this fun game,make it as responsive as
    //possible. So its okay to offload it to client. [word,start,end, ..]
    public function answerList() {

    $jsscript  = "<SCRIPT>var answerpos=[];var findingpos=[];var foundpos=[];var clickedpos=[];answerpos.push( ";
    //loop here  

    for ($i = 0; $i < count($this->_answers); $i++) {
              if( $i+1 < count($this->_answers) ){ $jsscript .= "\"".$this->_answers[$i]."\"".",";}
              else{ $jsscript .= "\"".$this->_answers[$i]."\"";}
                                 }
    $jsscript  .= ");</SCRIPT>";
     return $jsscript;
   } //answerList
  
   
  

    public function getNbWords() {
        return count($this->_wordsList);
        }
        
    public function gen() {        

        // Create a new grid     

        if($this->_errorMsg!='') {return;}        

        $size2=$this->_size*$this->_size;
        $i=rand(0,$size2-1); // on part d'une case au hasard
        
        // we go through all the boxes
        $cpt=0;
        while($cpt<$size2) {
            $this->placeWord($i);
            $cpt++;
            $i++;
            if($i==$size2) {$i=0;}
            }
        } // gen()
    
    private function placeWord($start) {

        
        $word=new Word(
            $start, 
            -1, 
            rand(0,3), 
            '',
            (rand(0,1) == 1)
            );

        $inc=1; // incrément
        $len=rand(self::MIN_LEN_WORD,$this->_size); 
        
        switch($word->getOrientation()) {

            case Word::HORIZONTAL:
                $inc=1;
                $word->setEnd($word->getStart()+$len-1);
                 // if word placed on 2 lines we shift to the left
                while( $this->_arrayCOL[$word->getEnd()] < $this->_arrayCOL[$word->getStart()] ) {
                    $word->setStart($word->getStart()-1);
                    $word->setEnd($word->getStart()+$len-1);
                    }
                break;

            case Word::VERTICAL:
                $inc=$this->_size;
                $word->setEnd($word->getStart()+($len*$this->_size)-$this->_size);
                // if the word goes beyond the grid at the bottom, we shift upwards
                while($word->getEnd()>($this->_size*$this->_size)-1) {
                    $word->setStart($word->getStart()-$this->_size);
                    $word->setEnd($word->getStart()+($len*$this->_size)-$this->_size);
                    }
                break;

            case Word::DIAGONAL_LEFT_TO_RIGHT:
                $inc=$this->_size+1;
                $word->setEnd($word->getStart()+($len*($this->_size+1))-($this->_size+1));
                // if the word goes beyond the grid on the right, we shift to the left
                while( $this->_arrayCOL[$word->getEnd()] < $this->_arrayCOL[$word->getStart()] ) {
                    $word->setStart($word->getStart()-1);
                    $word->setEnd($word->getStart()+($len*($this->_size+1))-($this->_size+1));
                    }
                // if the word goes beyond the grid at the bottom, we shift upwards
                while($word->getEnd()>($this->_size*$this->_size)-1) {
                    $word->setStart($word->getStart()-$this->_size);
                    $word->setEnd($word->getStart()+($len*($this->_size+1))-($this->_size+1));
                    }
                break;

            case Word::DIAGONAL_RIGHT_TO_LEFT:
                $inc=$this->_size-1;
                $word->setEnd($word->getStart()+(($len-1)*($this->_size-1)));
                // if the word comes out of the grid on the left, we shift to the right
                while( $this->_arrayCOL[$word->getEnd()] > $this->_arrayCOL[$word->getStart()] ) {
                    $word->setStart($word->getStart()+1);
                    $word->setEnd($word->getStart()+(($len-1)*($this->_size-1)));
                    }
                // if the word goes beyond the grid at the bottom, we shift upwards
                while($word->getEnd()>($this->_size*$this->_size)-1) {
                    $word->setStart($word->getStart()-$this->_size);
                    $word->setEnd($word->getStart()+(($len-1)*($this->_size-1)));
                    }
                break;
            }

        // we build the SQL pattern ("A__O___") if the word crosses letters present in the grid.
        $s='';
        $flag=false;
        for($i=$word->getStart();$i<=$word->getEnd();$i+=$inc) {
            if($this->_cells[$i]=='') {$s.='_';}
            else {
                $s.=$this->_cells[$i];
                $flag=true;
                }
            }
   
        // if we find that '_' => no overlap we add the word
        if(!$flag) {
            $word->setLabel($this->getRandomWord($len)); // we must draw a word of length len
            if($word->isReversed()) {$word->setLabel(strrev($word->getLabel()));}
            $this->addWord($word);
            }

        // if not
        else {
            // if the pattern is an entire text we leave
            if(strpos($s,'_')===false) {return;}

            // draw one with this pattern
            $word->setLabel($this->getWordLike($s));
            $word->setReverse(false); // the new drawn word is not reversed.

            // add the word (null test in addword)
            $this->addWord($word);
            }

        } // placeWord()

        public function render($type=Grid::RENDER_HTML) {
        
        // Affiche la grille complète au format
        // TEXTE ou HTML (par défaut)
        
        if($this->_errorMsg!='') {return;}
        
        $r='';
        
        switch($type) {
            case Grid::RENDER_HTML:
            $cpt=0;
            //CSS moved to its own file. 
            $r.='<nav class="btn-bar nav-light"><table class="gridtable">'.PHP_EOL;
            $number=1;

                //letter box generator had two different types . Made it consistant.  
                //else {$r.='<td><A id="'.$number.'" onclick="return click_check(id);" class="btn btn-glass btn-primary"><i class="fa fa-fw fa-lg fa-chevron-right"></i>'.$letter.'</A></td>';}
            foreach($this->_cells as $letter) {
                if($cpt==0) {$r.='<tr>';}
                if($letter=='') {$r.='<td><A id="'.$number.'" onclick="return click_check(id);" class="btn btn-glass btn-primary"> '.chr(rand(65,90)).'</A></td>';}
                else {$r.='<td><A id="'.$number.'" onclick="return click_check(id);" class="btn btn-glass btn-primary">'.$letter.'</A></td>';}
                $cpt++;
                if($cpt==$this->_size) {$r.='</tr>'.PHP_EOL; $cpt=0;}
                $number++; 
                }
            $r.='</table><canvas id="wsGameCanvas" style="position: relative;top: -540px;left: 0px;width: 300px;height: 350px;" ></canvas>'.PHP_EOL;
            break;
            
            case Grid::RENDER_TEXT:
            $cpt=0;
            foreach($this->_cells as $letter) {
                if($letter=='') {$r.=chr(rand(65,90));}
                else {$r.=$letter;}
                $r.=' ';
                $cpt++;
                if($cpt==$this->_size) {$r.="\n"; $cpt=0;}
                }            
            break;
            }
                        
        return $r;
        }



       
 
        
    private function COL($x) {
        // IN : (int $x) = index de la case
        // OUT : (int) numéro de la colonne, de 1 à $this->_size
        return ($x % $this->_size)+1;
        }        

    private function getRandomWord($len) {
	global $wpdb;		
        $rowdata="";
        // IN (Int) : longueur du mot $len
        // OUT (String) : un mot au hasard de longueur $len
        $rqtxt='SELECT word FROM wq_wordsearch WHERE LENGTH(word)='.$len.' ORDER BY RAND() LIMIT 1';
	//$row = $this->_db->query( $rqtxt )->fetch();
	foreach( $wpdb->get_results( $rqtxt ) as $key => $row) {$rowdata = $row->word;}
        //Check For Duplicate, if found return empty string to loop back again. 
        if( $this->findDuplicate($rowdata) ) { return "";}
        return $rowdata;       
        }
        
    private function getWordLike($pattern) {
        global $wpdb;	
        $rowdata="";
        // Retourne un mot qui ressemble au pattern.
        // IN (String) : $pattern, ex : A__U__S
        // OUT (String) : un mot au hasard qui correspond, "" sinon
        $rqtxt='SELECT word FROM wq_wordsearch WHERE word LIKE "'.$pattern.'" ORDER BY RAND() LIMIT 1';
	//$row = $this->_db->query( $rqtxt )->fetch();
	foreach( $wpdb->get_results( $rqtxt ) as $key => $row) {$rowdata = $row->word;}
        //Check For Duplicate, if found return empty string to loop back again. 
        if( $this->findDuplicate($rowdata) ) { return "";}
        return $rowdata;
        }


    private function findDuplicate($checkthis) {

        $found=0;
        $arr=array();
        foreach($this->_wordsList as $word) {
            $label=$word->getLabel();
            if($word->isReversed()) {$label=strrev($label);}
            $arr[]=$label;
            }

        if(in_array( $checkthis , $arr, true)) {$found=1;} 
        return $found;
       }
        

    private function addWord($word) {

        // ajoute un mot :
        // - dans les cases de la grille
        // - à la liste des mots à trouver
        //Limit amount of words in search or come to end of list. Also, check for duplicates.
        if($word->getLabel()=='' || $this->getNbWords() >= self::MAX_WORD_COUNT ) {return;}      
       

        // Ajout dans les cases de la grille
        $j=0;
        switch($word->getOrientation()) {

            case Word::HORIZONTAL:
                for ($i=$word->getStart(); $j<strlen($word->getLabel()); $i++) {
                    $this->_cells[$i]=substr($word->getLabel(),$j,1);
                    $j++;                    
                    }
                break;

            case Word::VERTICAL:
                for($i=$word->getStart(); $j<strlen($word->getLabel()); $i+=$this->_size) {
                    $this->_cells[$i]=substr($word->getLabel(),$j,1);
                    $j++;
                    }
                break;

            case Word::DIAGONAL_LEFT_TO_RIGHT:
                for($i=$word->getStart(); $j<strlen($word->getLabel()); $i+=$this->_size+1) {
                    $this->_cells[$i]=substr($word->getLabel(),$j,1);
                    $j++;
                    }
                break;

            case Word::DIAGONAL_RIGHT_TO_LEFT:
                for($i=$word->getStart(); $j<strlen($word->getLabel()); $i+=$this->_size-1) {
                    $this->_cells[$i]=substr($word->getLabel(),$j,1);
                    $j++;
                    }
                break;

            } // switch

        // Ajout du mot à la liste
        $this->_wordsList[]=$word;

        } // addWord()        

    } // class Grid
?>
