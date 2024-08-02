<?php
//Avp Jaguar map data interpreter script by Bobblen
//based on reverse engineering by Rich Whitehouse and Bobblen (using tools also developed by Rich Whitehouse)
//
//map data can be dumped from RAM at offset 0x4FC00 using BigPEmu Debug version, this script only works with dumped map data
//
//this generates a text file to be used with the Blender python script avp_jaguar_map_builder.py
//note that not all bytes have been reverse engineered yet, a wall will be assumed if the type is unknown. This is fine for most maps
//
//usage: php avpjag_mapreader.php > level.txt
//don't forget to set your file name on line 18 and path on line 19!


ini_set('memory_limit','128M');

$type = 'textfile'; //currently only textfile

$filename = 'airduct5c.BIN';
$bin = fopen("D:\\Noesis\\".$filename, "r");

$length = 64; //get from header
$width = 64; //get from header
$totalsize = $length*$width;

//debug
//echo "AvP Jaguar map reader\n\n";
//echo "file name is ".$filename."\n";
//echo "file type is set to ".$type."\n";


if( $type == 'textfile' ) {
	
	$county = -1; //we want Y coord to be set to 0 after first iteration, so start at -1
			
		for ($x = 0; $x <= ($totalsize-1); $x++) {
		//get sector bytes
		$side1 = getByte($bin);
		$side2 = getByte($bin);
		$side3 = getByte($bin);
		$side4 = getByte($bin);
		$ceiling = getByte($bin);
		$floor = getByte($bin);
		$leftdoorisopenflag = getByte($bin);
		$frontdoorisopenflag = getByte($bin);
		
		//work out coordinates
		$countx = $x%$length; //max x is 63, wrap back to 0 once we've iterated through all x values for the row
		
		if($countx==0)
			{
			$county = $county + 1; //increase y by 1 after each full row x values (also sets it to 0 on first interation)
			}
		
		if (strlen($countx)==1)
			{
			$countx = '0'.$countx; //pad x to 2 digits
			}
			
		if (strlen($county)==1)
			{
			$county = '0'.$county;  //pad y to 2 digits
			}
			
		echo $countx.$county; //first print the coordinates of the current sector
		
		//now the codes for each face
		if(!empty($side1)) //left wall
			{
			switch(true)
				{
					case($side1 == 32 || $side1 == 40 || $side1 == 48  || $side1 == 64 || $side1 == 72 || $side1 == 80 || $side1 == 88 || $side1 == 200):
						echo "d"; //doors
						break;
					case($side1 == 104 || $side1 == 105 || $side1 == 112 ):
						echo "l"; //pillar left
						break;
					case($side1 == 120 || $side1 == 121):
						echo "p"; //pillars both
						break;
					case($side1 == 232 || $side1 == 233 || $side1 == 240 ):
						echo "r"; //pillar right
						break;
					default:
						echo "L"; //normal left wall
				}
			}
			else{echo "x";} //nothing
			
		if(!empty($side2)) //front wall
			{
			switch(true)
				{
					case($side2 == 32 || $side2 == 40 || $side2 == 48  || $side2 == 64 || $side2 == 72 || $side2 == 80 || $side2 == 88 || $side2 == 200):
						echo "d"; //doors
						break;
					case($side2 == 104 || $side2 == 105 || $side2 == 112):
						echo "l"; //pillar left
						break;
					case($side2 == 120 || $side2 == 121):
						echo "p"; //pillars both
						break;
					case($side2 == 232 || $side2 == 233 || $side2 == 240):
						echo "r"; //pillar right
						break;
					default:
						echo "F"; //normal front wall
				}
			}
			else{echo "x";} //nothing
			
		if(!empty($side3)) //right wall
			{
			switch(true)
				{
					case($side3 == 32 || $side3 == 40 || $side3 == 48  || $side3 == 64 || $side3 == 72 || $side3 == 80 || $side3 == 88 || $side3 == 200):
						echo "d"; //doors
						break;
					case($side3 == 104 || $side3 == 105 || $side3 == 112):
						echo "l"; //pillar left
						break;
					case($side3 == 120 || $side3 == 121):
						echo "p"; //pillars both
						break;
					case($side3 == 232 || $side3 == 233 || $side3 == 240):
						echo "r"; //pillar right
						break;
					default:
						echo "R"; //normal right wall
				}
			}
			else{echo "x";} //nothing
			
		if(!empty($side4)) //back wall
			{
			switch(true)
				{
					case($side4 == 32 || $side4 == 40 || $side4 == 48  || $side4 == 64 || $side4 == 72 || $side4 == 80 || $side4 == 88 || $side4 == 200):
						echo "d"; //doors
						break;
					case($side4 == 104 || $side4 == 105 || $side4 == 112):
						echo "l"; //pillar left
						break;
					case($side4 == 120 || $side4 == 121):
						echo "p"; //pillars both
						break;
					case($side4 == 232 || $side4 == 233 || $side4 == 240):
						echo "r"; //pillar right
						break;
					default:
						echo "B"; //normal back wall
				}
			}
			else{echo "x";} //nothing
			
		if(!empty($ceiling))
			{
			echo "C"; //Ceiling
			}
			else{echo "x";} //nothing
			
		if(!empty($floor))
			{
			echo "F"; //Floor
			}
			else{echo "x";} //nothing
			
		echo "\n";
		
		}
	
	}


////////////////////////////////////////////////////////

// Functions
function getByte( &$source )
{
	return ord( fread( $source,1 ) );
}



?>