<?php
	$timezone = "Asia/Manila";
	if(function_exists('date_default_timezone_set')){ date_default_timezone_set($timezone);}

	$todays_date = date("Y-m-d");
	$todays_time = date("G:i:s");
	
	$host = 'localhost';
	$user = 'root';
	//$pass = 'root321';
	$pass = 'itprog2013';
	$db   = 'gc';
	
	$link = new mysqli($host, $user, $pass, $db);
	mysqli_set_charset($link, 'utf8');

	/* check connection */
	if ($link->connect_error) {
		printf("Connect failed: %s\n", $link->connect_error);
		exit();
	}
 	$dir = dirname(__FILE__);

    function localserver_connect($host_loc,$user_loc,$pass_loc,$db_loc)
    {   
        $link_loc = new mysqli($host_loc,$user_loc,$pass_loc,$db_loc);
        mysqli_set_charset($link_loc, 'utf8');

        if ($link_loc->connect_error) 
        {
            return false;
            die();
        }
        return array($link_loc,true);
    }   
	/****** BEGIN LIVE CONFIG FOR FAD ***************/
	//$fadnew = "\\\\172.16.161.17\\fad_store\\CORP_DATA\\Documents\\GCRECEIVING\\NEW\\";
	// $fadrequis = "\\\\172.16.161.17\\fad_store\\CORP_DATA\\Documents\\GCREQUEST\\NEW\\";
 	// $fadused = '\\\\172.16.161.17\\fad_store\\CORP_DATA\\Documents\\GCRECEIVING\\USED\\';
 	//$fadrequis2 = $dir.'\\gc_textfiles\\requisition\\NEW\\';
 	//$fadused2 = $dir.'\\gc_textfiles\\fadcreated\\used\\';
 	//$fadnew2 = $dir.'\\gc_textfiles\\fadcreated\\new\\';
 	/******  END LIVE CONFIG FAD ***************/

 	/******** BEGIN LOCALHOST CONFIG FAD *************/
	// $fadrequis = $dir.'\\gc_textfiles\\requisition\\NEW\\';
	// $fadnew = $dir.'\\gc_textfiles\\fadcreated\\new\\';
 	// $fadused = $dir.'\\gc_textfiles\\fadcreated\\used\\';
 	// $fadrequis2 = $dir.'\\gc_textfiles\\requisition\\NEW\\';
 	// $fadnew2 = $dir.'\\gc_textfiles\\fadcreated\\new\\';
	/******** END LOCALHOST CONFIG FAD *************/

	/******* BEGIN POS LOCALHOST TEXTFILES *************/

	//check if folder exist 

	$verificationfolder = $dir.'\\gc_textfiles\\GC_TXT';
	$archivefolder = $dir.'\\gc_textfiles\\archives';

	// $query = $link->query("SELECT app_settingvalue FROM app_settings WHERE app_tablename = 'gc_textfiles_verification_folder'");

	// if($query)
	// {
	// 	$row = $query->fetch_object();
	// 	$verificationfolder = $row->app_settingvalue;
	// }

	// if(!file_exists($verificationfolder))
	// {
	// 	$verificationfolder = $dir.'\\gc_textfiles\\GC_TXT';
	// }	

	//echo $verificationfolder;

	/******* END POS LOCALHOST TEXTFILES ***************/

	$backupfolder = 'backupfiles/';


	//echo $verificationfolder;

	//psexec -i -s cmd.exe
	//net use x: \\172.16.161.205\CFS_Txt /persistent:yes
	//net use y: \\172.16.161.35\fad_stores /persistent:yes

