<?php
	error_reporting(E_ALL);
	ini_set('display_errors','On');


function usage() {
	echo <<<EOF
Usage:
	curl -X <http_method> http://hwdb.example.com/v1.0/host[?field=val[&field=val...]] -T <json file>
	Where,
		http_method     => 	GET | PUT | POST | DELETE
        json file       =>  will look like,
                            > cat host.json 
                            {
                                "host":{
                                    "name":"testtest.example.com",
                                    "type":"desktop",
                                    "model":"xw9400",
                                    "user":"kuma",
                                    "osmain":"RHEL-5-C"
                                }
                            }

        field           => any of the following.
                           -name|-n        hostname 
                           -slno|-s        serial number
                           -atag|-a        asset tag
                           -type|-t        server|desktop|vm|farm
                           -model|-m       bl460c|xw9400|z800|etc
                           -dept|-d        lighting|cfx|etc
                           -location|-l    tr-room1|cube102|etc
                           -crole|-c       condor role such as submitter|cm|schedd
                           -rtag|-r        release tag such as alpha|beta|prod-1|prod-2 etc
                           -owner|-o       who the host was assigned to
                           -user|u-        the current user
                           -osmain         RedHat/Microsoft version of the OS
                           -osdwa          DWA version of the OS
                           -wstart         start of warranty date (YYYY-MM-DD)
                           -wend           end of warranty date (YYYY-MM-DD)
                           -status         status such as prod|test|decomm etc
                           -fields|-f      output fiedls to be displayed

Example:

    curl -X GET "http://hwdb.example.com/v1.0/host/test.example.com"
    curl -X PUT "http://hwdb.example.com/v1.0/host" -T hw.json
    curl -X POST "http://hwdb.example.com/v1.0/host" -T hw.json
    curl -X DELETE "http://hwdb.example.com/v1.0/host/testtest.example.com"
    

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

$query = '';
//echo "from server : " . $input;

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

    #print_r($jdata);

/// PREPARE CONN

	$conn = mysql_connect('hwdb.example.com', 'hwuser', 'hwpassword');
	if (!$conn) {
	   die('Could not connect: ' . mysql_error());
	}
	mysql_select_db("hwdb", $conn);

    $updated = date("Y-m-d H:i:s");

/// GET => QUERY HOST

	if(strtoupper($_SERVER['REQUEST_METHOD']) == 'GET') {

		$query = '';
		if (isset ($_GET['name'])) 			$query = $query . "AND name = '" . $_GET['name'] . "' ";
		if (isset ($_GET['slno'])) 			$query = $query . "AND slno = '" . $_GET['slno'] . "' ";
		if (isset ($_GET['atag'])) 		$query = $query . "AND atag = '" . $_GET['atag'] . "' ";
		if (isset ($_GET['type'])) 			$query = $query . "AND type = '" . $_GET['type'] . "' ";
		if (isset ($_GET['model'])) 			$query = $query . "AND model = '" . $_GET['model'] . "' ";
		if (isset ($_GET['dept'])) 			$query = $query . "AND dept = '" . $_GET['dept'] . "' ";
		if (isset ($_GET['location'])) 		$query = $query . "AND location = '" . $_GET['location'] . "' ";
		if (isset ($_GET['crole'])) 		$query = $query . "AND crole = '" . $_GET['crole'] . "' ";
		if (isset ($_GET['rtag'])) 		$query = $query . "AND rtag = '" . $_GET['rtag'] . "' ";
		if (isset ($_GET['owner'])) 			$query = $query . "AND owner = '" . $_GET['owner'] . "' ";
		if (isset ($_GET['user'])) 		$query = $query . "AND user = '" . $_GET['user'] . "' ";
		if (isset ($_GET['osmain'])) 			$query = $query . "AND osmain = '" . $_GET['osmain'] . "' ";
		if (isset ($_GET['osdwa'])) 			$query = $query . "AND osdwa = '" . $_GET['osdwa'] . "' ";
		if (isset ($_GET['gcard'])) 			$query = $query . "AND gcard= '" . $_GET['gcard'] . "' ";
		if (isset ($_GET['gver'])) 			$query = $query . "AND gver= '" . $_GET['gver'] . "' ";
		if (isset ($_GET['cpu'])) 			$query = $query . "AND cpu= '" . $_GET['cpu'] . "' ";
		if (isset ($_GET['ram'])) 			$query = $query . "AND ram= '" . $_GET['ram'] . "' ";
		if (isset ($_GET['status'])) 			$query = $query . "AND status = '" . $_GET['status'] . "' ";

		if ($query) $query = preg_replace('/AND/','WHERE',$query,1);

        $fields = (isset($_GET['fields']) ? $_GET['fields'] : '*');
    
        // echo $fields . "\n";

		$query = "SELECT $fields FROM host " . ($query ? $query : '' ) . ";";

		$result = mysql_query($query);
		if (!$result) {
			die('Invalid query: ' . mysql_error() . "\n");
		}		
        // echo $query . "\n";

        $entry = array();
		while ($row = mysql_fetch_assoc($result)) {
            //$dataout = json_encode($row);
            ////$dataout = json_encode($row, JSON_PRETTY_PRINT ); // available from 5.4.0 onwards
            //$dataout = '{"host":' . $dataout . '}';
            //echo $dataout  . "\n";
            $entry['hosts'][] = $row;
		}
        //print_r($entry);
        //$dataout = json_encode($row, JSON_PRETTY_PRINT ); // available from 5.4.0 onwards
        if ($entry) echo prettyPrint(json_encode($entry)) . "\n";
	} 

/// POST => ADD HOST

	if(strtoupper($_SERVER['REQUEST_METHOD']) == 'POST') {

        if(! isset($_SERVER['HTTP_EXEC'])) {
            die ("Are you from Digops?\nUse tools from /usr/local/sys/bin, if yes!\n");
        }
		if (! isset ($jdata['host']['name'])) {
			die ("\nhost cannot be empty!\n" . usage());

		}

        isset($jdata['host']['name']) or $jdata['host']['name'] = '-';                        isset($jdata['host']['slno']) or $jdata['host']['slno'] = '-';          
        isset($jdata['host']['atag']) or $jdata['host']['atag'] = '-';          isset($jdata['host']['type']) or $jdata['host']['type'] = '-';                  
        isset($jdata['host']['model']) or $jdata['host']['model'] = '-';                isset($jdata['host']['dept']) or $jdata['host']['dept'] = '-'; 
        isset($jdata['host']['location']) or $jdata['host']['location'] = '-';  isset($jdata['host']['crole']) or $jdata['host']['crole'] = '-'; 
        isset($jdata['host']['rtag']) or  $jdata['host']['rtag'] = '-';         isset($jdata['host']['owner']) or $jdata['host']['owner'] = '-';
        isset($jdata['host']['user']) or $jdata['host']['user'] = '-';              isset($jdata['host']['osmain']) or $jdata['host']['osmain'] = '-'; 
        isset($jdata['host']['osdwa']) or $jdata['host']['osdwa'] = '-';                isset($jdata['host']['status']) or $jdata['host']['status'] = '-';
        isset($jdata['host']['gcard']) or $jdata['host']['gcard'] = '-';                isset($jdata['host']['gver']) or $jdata['host']['gver'] = '-';
        isset($jdata['host']['cpu']) or $jdata['host']['cpu'] = '-';                isset($jdata['host']['ram']) or $jdata['host']['ram'] = '-';
        isset($jdata['host']['wstart']) or $jdata['host']['wstart'] = 'NULL()'; isset($jdata['host']['wend']) or $jdata['host']['wend'] = 'NULL()';

        $query =    "INSERT INTO host (name,slno,atag,type,model,dept,location,crole,rtag," . 
                "owner,user,osmain,osdwa,gcard,gver,cpu,ram,wstart,wend,status,updated) VALUES('" . 
                       $jdata['host']['name'] .  "','" . $jdata['host']['slno'] .   "','" . $jdata['host']['atag'] .  "','" . 
                       $jdata['host']['type'] .  "','" . $jdata['host']['model'] .  "','" . $jdata['host']['dept'] .  "','" . $jdata['host']['location'] . "','" . 
                       $jdata['host']['crole'] . "','" . $jdata['host']['rtag'] .   "','" . $jdata['host']['owner'] . "','" . 
                       $jdata['host']['user'] .  "','" . $jdata['host']['osmain'] . "','" . $jdata['host']['osdwa'] . "','" . $jdata['host']['gcard'] .    "','" . 
                       $jdata['host']['gver'] .  "','" . $jdata['host']['cpu'] .    "','" . $jdata['host']['ram'] .   "','" . $jdata['host']['wstart'] .   "','" . 
                       $jdata['host']['wend'] .  "','" . $jdata['host']['status'] . "','" . $updated .  
                "');";

		$result = mysql_query($query);
		if (!$result) {
			die('Invalid query: ' . mysql_error() . "\n");
		}
        //echo $query . "\n";
		echo "added '" . $jdata['host']['name'] . "' successfully!\n";
        execIt();
	}

/// PUT => UPDATE HOST

	if(strtoupper($_SERVER['REQUEST_METHOD']) == 'PUT') {

        if(! isset($_SERVER['HTTP_EXEC'])) {
            die ("Are you from Digops?\nUse tools from /usr/local/sys/bin, if yes!\n");
        }
		if (! isset ($jdata['host'])) {
			die ("\nhost cannot be empty!\n" . usage());
		}

		$query = '';
        isset($jdata['host']['slno']) and $query .= ",slno='" . $jdata['host']['slno'] . "'";
        isset($jdata['host']['atag']) and $query .= ",atag='" . $jdata['host']['atag'] . "'";
        isset($jdata['host']['type']) and $query .= ",type='" . $jdata['host']['type'] . "'";
        isset($jdata['host']['model']) and $query .= ",model='" . $jdata['host']['model'] . "'";
        isset($jdata['host']['dept']) and $query .= ",dept='" . $jdata['host']['dept'] . "'";
        isset($jdata['host']['location']) and $query .= ",location='" . $jdata['host']['location'] . "'";
        isset($jdata['host']['crole']) and $query .= ",crole='" . $jdata['host']['crole'] . "'";
        isset($jdata['host']['rtag']) and $query .= ",rtag='" . $jdata['host']['rtag'] . "'";
        isset($jdata['host']['owner']) and $query .= ",owner='" . $jdata['host']['owner'] . "'";
        isset($jdata['host']['user']) and $query .= ",user='" . $jdata['host']['user'] . "'";
        isset($jdata['host']['osmain']) and $query .= ",osmain='" . $jdata['host']['osmain'] . "'";
        isset($jdata['host']['osdwa']) and $query .= ",osdwa='" . $jdata['host']['osdwa'] . "'";
        isset($jdata['host']['status']) and $query .= ",status='" . $jdata['host']['status'] . "'";
        isset($jdata['host']['wstart']) and $query .= ",wstart='" . $jdata['host']['wstart'] . "'";
        isset($jdata['host']['wend']) and $query .= ",wend='" . $jdata['host']['wend'] . "'";
        isset($jdata['host']['gcard']) and $query .= ",gcard='" . $jdata['host']['gcard'] . "'";
        isset($jdata['host']['gver']) and $query .= ",gver='" . $jdata['host']['gver'] . "'";
        isset($jdata['host']['cpu']) and $query .= ",cpu='" . $jdata['host']['cpu'] . "'";
        isset($jdata['host']['ram']) and $query .= ",ram='" . $jdata['host']['ram'] . "'";
        $query .= ",updated='" . $updated . "'";

		if ($query) {
		 	$query = preg_replace('/,/','',$query,1);
		  	$query = "UPDATE host SET " . $query . " WHERE name='" . $jdata['host']['name'] . "';";
			$result = mysql_query($query);
			if (!$result) {
				die('Invalid query: ' . mysql_error() . "\n");
			}		
            #echo $result . " " . mysql_error() . " \n";
            $result = '';
            $query = "SELECT name FROM host WHERE name='" . $jdata['host']['name'] . "';";
            $result = mysql_query($query);
            $row = mysql_fetch_assoc($result);
            #echo $result . " \n";
            if ( $row['name'] == $jdata['host']['name'] ) {
			    echo "updated '" . $jdata['host']['name'] . "' successfully!\n";
                execIt();
            }
		}	
	}

/// DELETE => REMOVE HOST

	if(strtoupper($_SERVER['REQUEST_METHOD']) == 'DELETE') {

        if(! isset($_SERVER['HTTP_EXEC'])) {
            die ("Are you from Digops?\nUse tools from /usr/local/sys/bin, if yes!\n");
        }
        if (isset ($_GET['name'])) {
            $result = '';
            $query = "SELECT name FROM host WHERE name='" . $_GET['name'] . "';";
            $result = mysql_query($query);
            $row = mysql_fetch_assoc($result);
            if ( ! isset($row['name']) ) {
                echo "no host " . $_GET['name'] . "'!\n";
                exit(1);
            }

            $query = "DELETE FROM host WHERE name='" . $_GET['name'] . "';";
            $result = mysql_query($query);
            if (!$result) {
                die('Invalid query: ' . mysql_error() . "\n");
            }      
            //echo $query . "\n";
            execIt();
            echo "deleted '" . $_GET['name'] . "' successfully!\n";
        } else {
            echo "host cannot be empty!\n";
		    usage();
		    exit (1);
        }    
	}


/// PLAY WITH THEM 
    #if(isset($_SERVER['HTTP_EXEC'])) {
    #    echo $_SERVER['HTTP_EXEC'] . "\n";
    #}

    //echo "sending these from servers\n";
    //var_dump(headers_list());

    //echo formExecline();

    function formExecline() {
        $line = '';
        global $query;
        $line .= 'HTTP_USER_AGENT=' . $_SERVER["HTTP_USER_AGENT"] . ",";
        $line .= 'SERVER_NAME=' . $_SERVER["SERVER_NAME"] . ",";
        $line .= 'REMOTE_ADDR=' . $_SERVER["REMOTE_ADDR"] . ",";
        $line .= 'SCRIPT_URI=' . $_SERVER["SCRIPT_URI"] . ",";
        $line .= 'QUERY_STRING=' . $_SERVER["QUERY_STRING"] . ",";
        $line .= 'REQUEST_TIME=' . $_SERVER["REQUEST_TIME"] . ",";
        $line .= 'EXEC_QUERY=' . str_replace("'","|",$query);
        return $line;
    } 
    
    function execIt() {
        $exec = $_SERVER['HTTP_EXEC'];
        $t = nl2br(chop(formExecline()));
        $query = "INSERT INTO exec (xwho,xhow) VALUES('$exec','$t')";
        //echo $query;
        $result = mysql_query($query);
        if (!$result) {
            die('Invalid query: ' . mysql_error() . "\n");
        }
    }

    function prettyPrint($json, $options = array()) {
        $tokens = preg_split('|([\{\}\]\[,])|', $json, -1, PREG_SPLIT_DELIM_CAPTURE);
        $result = '';
        $indent = 0;

        $format= 'txt';

        $ind = "\t";

        if (isset($options['format'])) {
            $format = $options['format'];
        }

        switch ($format) {
            case 'html':
                $lineBreak = '<br />';
                $ind = '&nbsp;&nbsp;&nbsp;&nbsp;';
                break;
            default:
            case 'txt':
                $lineBreak = "\n";
                $ind = "\t";
                break;
        }

        // override the defined indent setting with the supplied option
        if (isset($options['indent'])) {
            $ind = $options['indent'];
        }

        $inLiteral = false;
        foreach($tokens as $token) {
            if($token == '') {
                continue;
            }

            $prefix = str_repeat($ind, $indent);
            if (!$inLiteral && ($token == '{' || $token == '[')) {
                $indent++;
                if (($result != '') && ($result[(strlen($result)-1)] == $lineBreak)) {
                    $result .= $prefix;
                }
                $result .= $token . $lineBreak;
            } elseif (!$inLiteral && ($token == '}' || $token == ']')) {
                $indent--;
                $prefix = str_repeat($ind, $indent);
                $result .= $lineBreak . $prefix . $token;
            } elseif (!$inLiteral && $token == ',') {
                $result .= $token . $lineBreak;
            } else {
                $result .= ( $inLiteral ? '' : $prefix ) . $token;
                
                // Count # of unescaped double-quotes in token, subtract # of
                // escaped double-quotes and if the result is odd then we are
                // inside a string literal
                if ((substr_count($token, "\"")-substr_count($token, "\\\"")) % 2 != 0) {
                    $inLiteral = !$inLiteral;
                }
            }
        }
        return $result;
   }

	mysql_close($conn);

?>
