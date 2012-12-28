<?php
require('fpdf.php');

class PDF extends FPDF
{
var $B;
var $I;
var $U;
var $HREF;

function PDF($orientation='L',$unit='mm',$format='A4')
{
	//om
	//Call parent constructor
	$this->FPDF($orientation,$unit,$format);
	//Initialization
	$this->B=0;
	$this->I=0;
	$this->U=0;
	$this->HREF='';
        $this->SetAutoPageBreak(false);
}

function BasicTable($header,$data)
{
    //Header
    $colwidth = 30;
    foreach($header as $col)
        $this->Cell($colwidth,7,$col,1);
    $this->Ln();
    //Data
    $rowcount = 1;

    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
        
    foreach($data as $row)
    {
        $colIndex = 0;
        foreach($row as $col){
            $this->Cell($colwidth,6,$col,'LR',0,'L',$fill);
        }

        if($rowcount % 20 ==0){
            $this->AddPage();
            foreach($header as $col)
            $this->Cell($colwidth,7,$col,1);
        }
        
        /*
        $this->Cell($size[1],6,$row['VERTICAL'],'LR',0,'L',$fill);
        $this->Cell($size[2],6,$row['USER'],'LR',0,'L',$fill);        
        $this->Cell($size[3],6,$row['MEETINGS'],'LR',0,'L',$fill);         
        $this->Cell($size[4],6,$row['CALLS'],'LR',0,'L',$fill);
        $this->Cell($size[5],6,$row['TASKS'],'LR',0,'L',$fill);
        $this->Cell($size[6],6,$row['TOTAL_COUNT'],'LR',0,'L',$fill);        
*/
        $fill = !$fill;
        $rowcount++;
        $this->Ln();
    }
}

function WriteHTML($html)
{
	//HTML parser
	$html=str_replace("\n",' ',$html);
	$a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
	foreach($a as $i=>$e)
	{
		if($i%2==0)
		{
			//Text
			if($this->HREF)
				$this->PutLink($this->HREF,$e);
			else
				$this->Write(5,$e);
		}
		else
		{
			//Tag
			if($e{0}=='/')
				$this->CloseTag(strtoupper(substr($e,1)));
			else
			{
				//Extract attributes
				$a2=explode(' ',$e);
				$tag=strtoupper(array_shift($a2));
				$attr=array();
				foreach($a2 as $v)
					if(ereg('^([^=]*)=["\']?([^"\']*)["\']?$',$v,$a3))
						$attr[strtoupper($a3[1])]=$a3[2];
				$this->OpenTag($tag,$attr);
			}
		}
	}
}

function OpenTag($tag,$attr)
{
	//Opening tag
	if($tag=='B' or $tag=='I' or $tag=='U')
		$this->SetStyle($tag,true);
	if($tag=='A')
		$this->HREF=$attr['HREF'];
	if($tag=='BR')
		$this->Ln(5);
}

function CloseTag($tag)
{
	//Closing tag
	if($tag=='B' or $tag=='I' or $tag=='U')
		$this->SetStyle($tag,false);
	if($tag=='A')
		$this->HREF='';
}

function SetStyle($tag,$enable)
{
	//Modify style and select corresponding font
	$this->$tag+=($enable ? 1 : -1);
	$style='';
	foreach(array('B','I','U') as $s)
		if($this->$s>0)
			$style.=$s;
	$this->SetFont('',$style);
}

function PutLink($URL,$txt)
{
	//Put a hyperlink
	$this->SetTextColor(0,0,255);
	$this->SetStyle('U',true);
	$this->Write(5,$txt,$URL);
	$this->SetStyle('U',false);
	$this->SetTextColor(0);
}
}

?>
