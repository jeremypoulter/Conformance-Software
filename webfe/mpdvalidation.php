<?php

function mpdvalidator($result_array,$locate,$foldername){

global $string_info;
$url_array= $result_array;
chdir($url_array[1]);// Change default execution directory to the location of the mpd validator
				$mpdvalidator = syscall("ant run -Dinput=".$url_array[0]); //run mpd validator
						$mpdvalidator = str_replace('[java]',"",$mpdvalidator); //save the mpd validator output to variable
						$valid_word = 'Start XLink resolving'; 
						$report_start = strpos($mpdvalidator,$valid_word); // Checking the begining of the Xlink validation
						$mpdvalidator=substr ($mpdvalidator,$report_start); // 
						$mpdreport = fopen($locate.'/mpdreport.txt','a+b');
								fwrite($mpdreport,$mpdvalidator);//get mpd validator result to text file

						$temp_string = str_replace (array('$Template$'),array("mpdreport"),$string_info); // copy mpd report to html file 
            $mpd_rep_loc =  'temp/'.$foldername.'/mpdreport.html'; // location of mpd report

            file_put_contents($locate.'//mpdreport.html',$temp_string); // create HTML to contain mpd report
						$exit=false;
						
						if(strpos($mpdvalidator,"XLink resolving successful")!==false)// check if Xlink resolving is successful
                            $totarr[]='true';//incase of mpd validation success send true to client
							else{
							$totarr[]=$mpd_rep_loc;// if failed send client the location of mpdvalidator report
							$exit = true;// if failed terminate conformance check 
							}
						if(strpos($mpdvalidator,"MPD validation successful")!==false)//check if Xlink resolving is successful 
                          $totarr[]='true';//incase of mpd validation success send true to client
							else{
							$totarr[]=$mpd_rep_loc;/// if failed send client the location of mpdvalidator report
							$exit = true;// if failed terminate conformance check
							}
							if(strpos($mpdvalidator,"Schematron validation successful")!==false) // check if Schematron validation is successful
                          $totarr[]='true'; // if succesful send true to client
							else{
							$totarr[]=$mpd_rep_loc;/// if failed send client the location of mpdvalidator report
							$exit =true;// if failed terminate conformance check
							}
							if ($url_array[2] ===1)  // only mpd validation requested
                               $exit =true;	
							   
							   $function_result =[];
							   $function_result[0]=$exit;
							   $function_result[1]=$totarr;

return $function_result;							   

							   }


?>