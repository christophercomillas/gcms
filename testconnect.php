<?php
	
	require_once 'config.php';

	//echo $verificationfolder;

	if(file_exists("\\\\172.16.161.205\\CFS_Txt\\Giftcheck"))
	{
		echo '<br /> GC Textfile Connected 172.16.161.205.';
	}
	else
	{
		echo '<br />Cant connect to 172.16.161.205 folder.';
	}

	if(file_exists("\\\\172.16.161.35\\fad_stores"))
	{
		echo '<br />GC Textfile Connected 172.16.161.35.';
	}
	else 
	{
		echo '<br />Cant connect to 172.16.161.35 folder.';
	}


	// if(file_exists("\\\\172.16.43.121\\talibon"))
	// {
	// 	echo '<br /> Talibon Store Connected';
	// }

	// if(file_exists("\\\\172.16.161.35\\fad_stores\\FAD_STORE_DATABASE\\HO_DATA\\GCRECEIVING\\NEW"))
	// {
	// 	echo '<br /> Fad Received New Connected';
	// }
	// else  
	// {
	// 	echo '<br /> Wala';
	// }
