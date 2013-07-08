<?php
	error_reporting(E_ALL);
	ini_set('display_errors','On');


require('xml.php');

function usage() {
	echo <<<EOF
Usage:
	curl "http://hwdb.example.com/hwdb/<task>" -T <xml file>
	Where,
		<task>          => 	GET | PUT | POST | DELETE
        <xml file>      =>  will look like,
        > cat hw.xml
          <host name="test.example.com">
              <type>desktop</type>
              <model>xw9400</model>
              <user>knet</user>
              <owner>KO N NET</owner>
              <osmain>RHEL-5-C</osmain>
          </host>

Example:

    curl "http://hwdb.example.com/hwdb/get?type=desktop&dept=systems"
    curl "http://hwdb.example.com/hwdb/delete?host=test.example.com"
    curl "http://hwdb.example.com/hwdb/add" -T hw.xml
    curl "http://hwdb.example.com/hwdb/update" -T hw.xml


EOF;

}

/// READ CURL INPUT DATA 

	$putfp = fopen('php://input', 'r');
	$input = '';
	while($data = fread($putfp, 1024)) {
		$input .= $data;
	}
	fclose($putfp);

#	if ( $input == '' ) {
#		usage();
#		exit (0);
#	}

echo $input;

	// JSON FILE 
	$jdata = json_decode($input, true);
	/*try {
		$jdata = 	new RecursiveIteratorIterator(
			 		new RecursiveArrayIterator(json_decode($input, TRUE)),
			 		RecursiveIteratorIterator::SELF_FIRST);
	} catch (Exception $e) {
	   	//echo "no json file input!\n";
		echo 'Caught exception: ',  $e->getMessage(), "\n";

	}*/

    // XML FILE
    //$array =  xml2array(file_get_contents('hw.xml'));
    $jdata =  xml2array($input);

//print_r($jdata);

// exit;

/// PREPARE CONN

	$conn = mysql_connect('hwdb.example.com', 'hwdb_user', 'hwdb_password');
	if (!$conn) {
	   die('Could not connect: ' . mysql_error());
	}
	mysql_select_db("hwdb", $conn);

/// GET => QUERY HOST

	#if(strtoupper($_SERVER['REQUEST_METHOD']) == 'GET') {
	if(strtoupper($_GET{'method'}) == 'GET') {
	//    echo "operation : GET\n";
		$query = '';
		#if (isset ($_GET['host'])) 			$query = $query . "AND fqdn LIKE '%" . $_GET['host'] . "%' ";
		if (isset ($_GET['host'])) 			$query = $query . "AND fqdn = '" . $_GET['host'] . "' ";
		if (isset ($_GET['slno'])) 			$query = $query . "AND sl_no = '" . $_GET['slno'] . "' ";
		if (isset ($_GET['atag'])) 		    $query = $query . "AND asst_tag = '" . $_GET['atag'] . "' ";
		if (isset ($_GET['type'])) 			$query = $query . "AND type = '" . $_GET['type'] . "' ";
		if (isset ($_GET['model'])) 		$query = $query . "AND model = '" . $_GET['model'] . "' ";
		if (isset ($_GET['dept'])) 			$query = $query . "AND dept = '" . $_GET['dept'] . "' ";
		if (isset ($_GET['location'])) 		$query = $query . "AND location = '" . $_GET['location'] . "' ";
		if (isset ($_GET['crole'])) 		$query = $query . "AND condor_role = '" . $_GET['crole'] . "' ";
		if (isset ($_GET['rtag'])) 		    $query = $query . "AND release_tag = '" . $_GET['rtag'] . "' ";
		if (isset ($_GET['owner'])) 		$query = $query . "AND owner = '" . $_GET['owner'] . "' ";
		if (isset ($_GET['user'])) 		    $query = $query . "AND last_user = '" . $_GET['user'] . "' ";
		if (isset ($_GET['osmain'])) 		$query = $query . "AND osv_rh = '" . $_GET['osmain'] . "' ";
		if (isset ($_GET['osdwa'])) 		$query = $query . "AND osv_dwa = '" . $_GET['osdwa'] . "' ";
		if (isset ($_GET['status'])) 		$query = $query . "AND status = '" . $_GET['status'] . "' ";

		if ($query) $query = preg_replace('/AND/','WHERE',$query,1);

		$query = "SELECT * FROM host_test " . ($query ? $query : '' ) . ";";

		$result = mysql_query($query);
		if (!$result) {
			die('Invalid query: ' . mysql_error());
		}		

		while ($row = mysql_fetch_assoc($result)) {
		   	echo "| " . $row['fqdn'] . " | " . 	$row['sl_no'] . " | " . 	$row['asst_tag'] . " | " . 		$row['type'] . " | " . 		$row['model'] . " | " .  
						$row['dept'] . " | " . 	$row['location'] . " | " . 	$row['condor_role'] . " | " . 	$row['release_tag'] . " | " . 
						$row['owner'] . " | " . $row['last_user'] . " | " . $row['osv_rh'] . " | " . 		$row['osv_dwa'] . " | " . 	$row['status'] . " |\n";
		}

	} 

/// PUT => ADD HOST

	#if(strtoupper($_SERVER['REQUEST_METHOD']) == 'PUT') {
	if(strtoupper($_GET{'method'}) == 'ADD') {
	//    echo "operation : ADD\n";
		if (! isset ($jdata['host_attr']['name'])) {
			die ("\nhost cannot be empty!\n" . usage());

		}

		isset($jdata['host_attr']['name']) or $jdata['host_attr']['name'] = '-'; 			isset($jdata['slno']) or $jdata['slno'] = '-'; 		
		isset($jdata['host']['atag']) or $jdata['host']['atag'] = '-'; 	        isset($jdata['host']['type']) or $jdata['host']['type'] = '-'; 			
		isset($jdata['host']['model']) or $jdata['host']['model'] = '-'; 		isset($jdata['host']['dept']) or $jdata['host']['dept'] = '-'; 
		isset($jdata['host']['location']) or $jdata['host']['location'] = '-'; 	isset($jdata['host']['crole']) or $jdata['host']['crole'] = '-'; 
		isset($jdata['host']['rtag']) or  $jdata['host']['rtag'] = '-';	        isset($jdata['host']['owner']) or $jdata['host']['owner'] = '-';
 		isset($jdata['host']['user']) or $jdata['host']['user'] = '-'; 		    isset($jdata['host']['osmain']) or $jdata['host']['osmain'] = '-'; 
		isset($jdata['host']['osdwa']) or $jdata['host']['osdwa'] = '-'; 		isset($jdata['host']['status']) or $jdata['host']['status'] = '-';
		isset($jdata['host']['wstart']) or $jdata['host']['wstart'] = 'NULL()'; isset($jdata['host']['wend']) or $jdata['host']['wend'] = 'NULL()';

		$query =    "INSERT INTO host_test (fqdn,sl_no,asst_tag,type,model,dept,location,condor_role,release_tag," . 
                	"owner,last_user,osv_rh,osv_dwa,war_start,war_end,status) VALUES('" . 
					$jdata['host_attr']['name'] . "','" . 	$jdata['host']['slno'] . "','" . 		$jdata['host']['atag'] . "','" . 
					$jdata['host']['type'] . "','" . 	$jdata['host']['model'] . "','" . 	    $jdata['host']['dept'] . "','" . $jdata['host']['location'] . "','" . 
					$jdata['host']['crole'] . "','" .   $jdata['host']['rtag'] . "'," . "'" . 	$jdata['host']['owner'] . "','" . 
					$jdata['host']['user'] . "','" . 	$jdata['host']['osmain'] . "','" . 		$jdata['host']['osdwa'] . "','" . $jdata['host']['wstart'] . "','" . 
					$jdata['host']['wend'] . "','" . 	$jdata['host']['status'] . 
					"');";

		$result = mysql_query($query);
		if (!$result) {
			die('Invalid query: ' . mysql_error() . "\n");
		}
		echo "added " . $jdata['host_attr']['name'] . " successfully!\n";
	}

/// POST => UPDATE HOST

	#if(strtoupper($_SERVER['REQUEST_METHOD']) == 'POST') {
	if(strtoupper($_GET{'method'}) == 'UPDATE') {
	//    echo "operation : UPDATE\n";
		if (! isset ($jdata['host_attr']['name'])) {
			die ("\nhost cannot be empty!\n" . usage());
		}

		$query = '';
		isset($jdata['host']['slno']) and $query .= ",sl_no='" . $jdata['host']['slno'] . "'";
		isset($jdata['host']['atag']) and $query .= ",asst_tag='" . $jdata['host']['atag'] . "'";
		isset($jdata['host']['type']) and $query .= ",type='" . $jdata['host']['type'] . "'";
		isset($jdata['host']['model']) and $query .= ",model='" . $jdata['host']['model'] . "'";
		isset($jdata['host']['dept']) and $query .= ",dept='" . $jdata['host']['dept'] . "'";
		isset($jdata['host']['location']) and $query .= ",location='" . $jdata['host']['location'] . "'";
		isset($jdata['host']['crole']) and $query .= ",condor_role='" . $jdata['host']['crole'] . "'";
		isset($jdata['host']['rtag']) and $query .= ",release_tag='" . $jdata['host']['rtag'] . "'";
		isset($jdata['host']['owner']) and $query .= ",owner='" . $jdata['host']['owner'] . "'";
		isset($jdata['host']['user']) and $query .= ",last_user='" . $jdata['host']['user'] . "'";
		isset($jdata['host']['osmain']) and $query .= ",osv_rh='" . $jdata['host']['osmain'] . "'";
		isset($jdata['host']['osdwa']) and $query .= ",osv_dwa='" . $jdata['host']['osdwa'] . "'";
		isset($jdata['host']['status']) and $query .= ",status='" . $jdata['host']['status'] . "'";
		isset($jdata['host']['wstart']) and $query .= ",war_start='" . $jdata['host']['wstart'] . "'";
		isset($jdata['host']['wend']) and $query .= ",war_end='" . $jdata['host']['wend'] . "'";

		if ($query) {
		 	$query = preg_replace('/,/','',$query,1);
		  	$query = "UPDATE host_test SET " . $query . " WHERE fqdn='" . $jdata['host_attr']['name'] . "';";
			$result = mysql_query($query);
			if (!$result) {
				die('Invalid query: ' . mysql_error() . "\n");
			}		
			echo "updated " . $jdata['host_attr']['name'] . " successfully!\n";
		}	
	}

/// DELETE => REMOVE HOST

	#if(strtoupper($_SERVER['REQUEST_METHOD']) == 'DELETE') {
	if(strtoupper($_GET{'method'}) == 'DELETE') {
	//    echo "operation : DELETE\n";
        if (isset ($_GET['host'])) {
            $query = "DELETE FROM host_test WHERE fqdn='" . $_GET['host'] . "';";
            $result = mysql_query($query);
            if (!$result) {
                die('Invalid query: ' . mysql_error() . "\n");
            }      
            echo "deleted " . $_GET['host'] . " successfully!\n";
        } else {
            echo "where is the host to delete you moron!\n";
		    usage();
		    exit (1);
        }    
	}

	mysql_close($conn);

?>
