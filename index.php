<? ob_start(); ?>

<!DOCTYPE html PUBLIC "where?">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>HWDB</title>
<style type="text/css">
<!--
    @import url("style.css");
-->
</style>
</head>
<body>

<?php
    $conn = mysql_connect('hwdb.example.com', 'hwdb_user', 'hwdb_password');
    if (!$conn) {
        die('Could not connect: ' . mysql_error());
    }
    //echo 'Connected successfully';
    mysql_select_db("hwdb", $conn);

?>

<div class="header">
DDU Hardware Database
</div>
<div id="search" class="search">
<form name="search" action="index.php" method="post">
    <table id="search_filter_wrap">
        <tr>
        <td>
            <table id='stab'>
            <tr>
                <td>
                    <label>Host</label> 
                    <?php
                    $boolSel = $_POST{'txtName'};
                    if ($_POST{'txtName'} == '') $boolSel = 'all';
                    echo "<input type='text' name='txtName' value='" . $boolSel ."'/>";
                    ?>
                </td>
                <td>
                    <label>Type</label> 
                    <select name='slctType'>
                        <option selected value='all'>all</option>

                        <?php       
                        $result = mysql_query("SELECT type FROM host GROUP BY type;");
                        if (!$result) {
                            die('Invalid query: ' . mysql_error());
                        } else {
                            while ($row = mysql_fetch_assoc($result)) {
                                $boolSel = '';
                                if ($row['type'] == $_POST{'slctType'}) $boolSel = "selected";
                                echo "<option $boolSel value='" . $row['type'] . "'>" . $row['type'] . "</option>";
                            }
                        }

                        ?>
                    </select>
                </td>
                <td>
                    <label>CPU</label>
                    <select name='slctCPU'>
                        <option selected value='all'>all</option>

                        <?php
                        $result = mysql_query("SELECT cpu FROM host GROUP BY cpu;");
                        if (!$result) {
                            die('Invalid query: ' . mysql_error());
                        } else {
                            while ($row = mysql_fetch_assoc($result)) {
                                $boolSel = '';
                                if ($row['cpu'] == $_POST{'slctCPU'} && isset($_POST{'slctCPU'})) $boolSel = "selected";
                                echo "<option $boolSel value='" . $row['cpu'] . "'>" . $row['cpu'] . "</option>";
                            }
                        }

                        ?>
                    </select>
                </td>

            </tr>
            <tr>
                <td>
                    <label>Model</label> 
                    <select name='slctModel'>
                        <option selected value='all'>all</option>
                        <?php      
                        $result = mysql_query("SELECT model FROM host GROUP BY model;");
                        if (!$result) {
                            die('Invalid query: ' . mysql_error());
                        } else {
                            while ($row = mysql_fetch_assoc($result)) {
                                $boolSel = '';
                                if ($row['model'] == $_POST{'slctModel'}) $boolSel = "selected";
                                echo "<option $boolSel value='" . $row['model'] . "'>" . $row['model'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <label>Dept</label> 
                    <select name='slctDept'>
                        <option selected value='all'>all</option>
                        <?php
                        $result = mysql_query("SELECT dept FROM host GROUP BY dept;");
                        if (!$result) {
                            die('Invalid query: ' . mysql_error());
                        } else {
                            while ($row = mysql_fetch_assoc($result)) {
                                $boolSel = '';
                                if ($row['dept'] == $_POST{'slctDept'}) $boolSel = "selected";
                                echo "<option $boolSel value='" . $row['dept'] . "'>" . $row['dept'] . "</option>";
                            }
                        }
                        ?>

                    </select>
                </td>
                <td>
                    <label>RAM</label>
                    <select name='slctRAM'>
                        <option selected value='all'>all</option>

                        <?php
                        $result = mysql_query("SELECT ram FROM host GROUP BY ram;");
                        if (!$result) {
                            die('Invalid query: ' . mysql_error());
                        } else {
                            while ($row = mysql_fetch_assoc($result)) {
                                $boolSel = '';
                                if ($row['ram'] == $_POST{'slctRAM'}  && isset($_POST{'slctRAM'})) $boolSel = "selected";
                                echo "<option $boolSel value='" . $row['ram'] . "'>" . $row['ram'] . "</option>";
                            }
                        }

                        ?>
                    </select>
                </td>

            </tr>
            <tr>
                <td>
                    <label>OSvMain</label>
                    <select name='slctOSvRH'>
                        <option selected value='all'>all</option>
                        <?php
                        $result = mysql_query("SELECT osmain FROM host GROUP BY osmain;");
                        if (!$result) {
                            die('Invalid query: ' . mysql_error());
                        } else {
                            while ($row = mysql_fetch_assoc($result)) {
                                $boolSel = '';
                                if ($row['osmain'] == $_POST{'slctOSvRH'}) $boolSel = "selected";
                                echo "<option $boolSel value='" . $row['osmain'] . "'>" . $row['osmain'] . "</option>";
                            }
                        }
                        ?>

                    </select>
                </td>
                <td>
                    <label>OSvDWA</label>
                    <select name='slctOSvDWA'>
                        <option selected value='all'>all</option>
                        <?php
                        $result = mysql_query("SELECT osdwa FROM host GROUP BY osdwa;");
                        if (!$result) {
                            die('Invalid query: ' . mysql_error());
                        } else {
                            while ($row = mysql_fetch_assoc($result)) {
                                $boolSel = '';
                                if ($row['osdwa'] == $_POST{'slctOSvDWA'}) $boolSel = "selected";
                                echo "<option $boolSel value='" . $row['osdwa'] . "'>" . $row['osdwa'] . "</option>";
                            }
                        }
                        ?>

                    </select>
                </td>
                <td>
                    <label>gCard</label>
                    <select name='slctGCard'>
                        <option selected value='all'>all</option>

                        <?php
                        $result = mysql_query("SELECT gcard FROM host GROUP BY gcard;");
                        if (!$result) {
                            die('Invalid query: ' . mysql_error());
                        } else {
                            while ($row = mysql_fetch_assoc($result)) {
                                $boolSel = '';
                                if ($row['gcard'] == $_POST{'slctGCard'} && isset($_POST{'slctGCard'})) $boolSel = "selected";
                                echo "<option $boolSel value='" . $row['gcard'] . "'>" . $row['gcard'] . "</option>";
                            }
                        }

                        ?>
                    </select>
                </td>

            </tr>



            <tr>
                <td>
                    <label>RelTag</label>
                    <select name='slctRelTag'>
                        <option selected value='all'>all</option>
                        <?php
                        $result = mysql_query("SELECT rtag FROM host GROUP BY rtag;");
                        if (!$result) {
                            die('Invalid query: ' . mysql_error());
                        } else {
                            while ($row = mysql_fetch_assoc($result)) {
                                $boolSel = '';
                                if ($row['rtag'] == $_POST{'slctRelTag'}) $boolSel = "selected";
                                echo "<option $boolSel value='" . $row['rtag'] . "'>" . $row['rtag'] . "</option>";
                            }
                        }
                        ?>

                    </select>
                </td>
                <td>
                    <label>CondRole</label>
                    <select name='slctCondRole'>
                        <option selected value='all'>all</option>
                        <?php
                        $result = mysql_query("SELECT crole FROM host GROUP BY crole;");
                        if (!$result) {
                            die('Invalid query: ' . mysql_error());
                        } else {
                            while ($row = mysql_fetch_assoc($result)) {
                                $boolSel = '';
                                if ($row['crole'] == $_POST{'slctCondRole'}) $boolSel = "selected";
                                echo "<option $boolSel value='" . $row['crole'] . "'>" . $row['crole'] . "</option>";
                            }
                        }
                        ?>

                    </select>
                </td>
                <td>
                    <label>gVer</label>
                    <select name='slctGVer'>
                        <option selected value='all'>all</option>

                        <?php
                        $result = mysql_query("SELECT gver FROM host GROUP BY gver;");
                        if (!$result) {
                            die('Invalid query: ' . mysql_error());
                        } else {
                            while ($row = mysql_fetch_assoc($result)) {
                                $boolSel = '';
                                if ($row['gver'] == $_POST{'slctGVer'} && isset($_POST{'slctGVer'})) $boolSel = "selected";
                                echo "<option $boolSel value='" . $row['gver'] . "'>" . $row['gver'] . "</option>";
                            }
                        }   
                                
                        ?>      
                    </select>
                </td>

            </tr>



            <tr>
                <td>
                    <label>Status</label>
                    <select name='slctStatus'>
                        <option selected value='all'>all</option>
                        <?php
                        $result = mysql_query("SELECT status FROM host GROUP BY status;");
                        if (!$result) {
                            die('Invalid query: ' . mysql_error());
                        } else {
                            while ($row = mysql_fetch_assoc($result)) {
                                $boolSel = '';
                                if ($row['status'] == $_POST{'slctStatus'}) $boolSel = "selected";
                                echo "<option $boolSel value='" . $row['status'] . "'>" . $row['status'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <label>War. Voids By (yyyy-mm-dd)</label>
                    <?php
                    $boolSel = $_POST{'txtWarExp'};
                    if ($_POST{'txtWarExp'} == '') $boolSel = '0000-00-00';
                    echo "<input type='text' name='txtWarExp' value='" . $boolSel ."'/>";
                echo '</td>';
                echo '<td>';
                    echo '<button type="submit">Search</button>';
                echo '</td>';
            echo '</tr>';
            echo '<tr style="height:8px; /*border-bottom: 1px solid #CCC*/"></tr>';
            echo '<tr style="height:5px;"></tr>';

            echo '<tr>';

                $ignorestale = $_POST{'chkIgnoreStale'};
                $checked = $_POST{'filter'};
                $filter = '';
                for($i=0; $i < count($checked); $i++){
                    $filter = $filter . ',' . $checked[$i];
                }
                if ($filter) {
                    $filter = substr($filter,1,strlen($filter)); 
                } else {
                    $filter = 'name,type,model,cpu,ram,gcard,gver,osmain,osdwa,user,dept,status,location';
                }
                
                $filter .= ',HOUR(TIMEDIFF(NOW(),  updated)) as age';
                //echo $filter;


                $count = 0;

                if ($_POST{'txtName'} != 'all' && $_POST{'txtName'} != '')  $query = $query . "AND name REGEXP '" . $_POST{'txtName'} . "' ";
                if ($_POST{'slctType'} != 'all' && $_POST{'slctType'} != '') $query = $query . "AND type = '" . $_POST{'slctType'} . "' ";
                if ($_POST{'slctModel'} != 'all' && $_POST{'slctModel'} != '') $query = $query . "AND model = '" . $_POST{'slctModel'} . "' ";
                if ($_POST{'slctDept'} != 'all' && $_POST{'slctDept'} != '') $query = $query . "AND dept = '" . $_POST{'slctDept'} . "' ";
                if ($_POST{'slctCPU'} != 'all' && $_POST{'slctCPU'} != '') $query = $query . "AND cpu = '" . $_POST{'slctCPU'} . "' ";
                if ($_POST{'slctRAM'} != 'all' && $_POST{'slctRAM'} != '') $query = $query . "AND ram = '" . $_POST{'slctRAM'} . "' ";
                if ($_POST{'slctGCard'} != 'all' && $_POST{'slctGCard'} != '') $query = $query . "AND gcard = '" . $_POST{'slctGCard'} . "' ";
                if ($_POST{'slctGVer'} != 'all' && $_POST{'slctGVer'} != '') $query = $query . "AND gver = '" . $_POST{'slctGVer'} . "' ";
                if ($_POST{'slctOSvRH'} != 'all' && $_POST{'slctOSvRH'} != '') $query = $query . "AND osmain = '" . $_POST{'slctOSvRH'} . "' ";
                if ($_POST{'slctOSvDWA'} != 'all' && $_POST{'slctOSvDWA'} != '') $query = $query . "AND osdwa = '" . $_POST{'slctOSvDWA'} . "' ";
                if ($_POST{'slctRelTag'} != 'all' && $_POST{'slctRelTag'} != '') $query = $query . "AND rtag = '" . $_POST{'slctRelTag'} . "' ";
                if ($_POST{'slctCondRole'} != 'all' && $_POST{'slctCondRole'} != '') $query = $query . "AND crole = '" . $_POST{'slctCondRole'} . "' ";
                if ($_POST{'slctStatus'} != 'all' && $_POST{'slctStatus'} != '') $query = $query . "AND status = '" . $_POST{'slctStatus'} . "' ";
                if ( ! empty($ignorestale)) $query = $query . "AND HOUR(TIMEDIFF(NOW(),  updated)) < '24' ";
                if ($_POST{'txtWarExp'} != '0000-00-00' && $_POST{'txtWarExp'} != '') $query = $query . "AND wend < '" . $_POST{'txtWarExp'} . "' "; #AND wend != '0000-00-00' ";

                if ($query) $query = preg_replace('/AND/','WHERE',$query,1);

                //if (isset($_POST{'slctSort1'})) $order = $_POST{'slctSort1'}; 
                //if (isset($_POST{'slctSort2'})) $order = $order . "," . $_POST{'slctSort2'}; 
                $order = ((isset($_POST{'slctSort1'})) ? $_POST{'slctSort1'} : 'type'); 
                $order = $order . "," . ((isset($_POST{'slctSort2'})) ? $_POST{'slctSort2'} : 'name'); 
                //print $order;
                
                if ($order) $query = $query . " ORDER BY " . $order;

                //print $query;

                $query = "SELECT $filter FROM host " . $query;
                $result = mysql_query($query);
                if (!$result) {
                    die('Invalid query: ' . mysql_error());
                }

                $num_rows = mysql_num_rows($result);

                echo '<td class="small">' . $num_rows . ' record(s) found! </td>';


                $sortfields = array(
                    "name"          => "Hostname",
                    "slno"         => "Sl.No",
                    "type"          => "Type",
                    "model"         => "Model",
                    "cpu"           => "CPU",
                    "ram"           => "RAM",
                    "gcard"         => "gCard",
                    "gver"          => "gVer",
                    "osmain"        => "OSvMain",
                    "osdwa"       => "OSvDWA",
                    "rtag"   => "RelTag",
                    "crole"   => "CondRole",
                    "dept"          => "Dept",
                    "location"          => "Location",
                    "status"        => "Status",
                    "wstart"     => "War.Start",
                    "wend"       => "War.End",
                    "updated"       => "LastUpdated"
                );

                echo '<td>'; 
                    echo "<label>1st sort-by</label>";
                    echo "<select name='slctSort1'>";
                        foreach ($sortfields as $i => $value) {
                            $boolSel = '';
                            if ( $i == $_POST{'slctSort1'} && isset($_POST{'slctSort1'})) $boolSel = "selected";
                            if ( ! isset($_POST{'slctSort1'}) && $i == 'type') $boolSel = "selected";
                            echo "<option $boolSel value='" . $i . "'>" . $value . "</option>";
                        }
                    echo "</select>";
                echo '</td>';
                echo '<td>';
                    echo "<label>2nd sort-by</label>";
                    echo "<select name='slctSort2'>";
                        foreach ($sortfields as $i => $value) {
                            $boolSel = '';
                            if ( $i == $_POST{'slctSort2'} && isset($_POST{'slctSort2'})) $boolSel = "selected";
                            echo "<option $boolSel value='" . $i . "'>" . $value . "</option>";
                        }
                    echo "</select>";
                echo '</td>';
            echo '</tr>';
            echo '</table>';
        echo '</td>';
        echo '<td style="width:20px;"></td>';
        echo '<td style="width:25px; border-left: 1px solid #CCC"></td>';
        echo '<td>';
            echo '<table>';
                echo "<tr><td style='font-size: 14px;'>Columns</td></tr>";
                echo "<tr><td></td></tr><tr><td></td></tr>";
                echo '<tr>';
                $checked = ''; if ( strpos($filter, "name")  !== false ) $checked = 'checked="checked"';
                echo "<td><input type='checkbox' name='filter[]' value='name' $checked>Hostname<br></td>";
                $checked = ''; if ( strpos($filter, "slno")  !== false ) $checked = 'checked="checked"';
                echo "<td><input type='checkbox' name='filter[]' value='slno' $checked>Sl.No<br></td>";
                $checked = ''; if ( strpos($filter, "type")  !== false ) $checked = 'checked="checked"';
                echo "<td><input type='checkbox' name='filter[]' value='type' $checked>Type<br></td>";
                echo '</tr>';
                echo '<tr>';
                $checked = ''; if ( strpos($filter, "model")  !== false ) $checked = 'checked="checked"';
                echo "<td><input type='checkbox' name='filter[]' value='model' $checked>Model<br></td>";
                $checked = ''; if ( strpos($filter, "cpu")  !== false ) $checked = 'checked="checked"';
                echo "<td><input type='checkbox' name='filter[]' value='cpu' $checked>CPU<br></td>";
                $checked = ''; if ( strpos($filter, "ram")  !== false ) $checked = 'checked="checked"';
                echo "<td><input type='checkbox' name='filter[]' value='ram' $checked>RAM<br></td>";
                echo '</tr>';
                echo '<tr>';
                $checked = ''; if ( strpos($filter, "gcard")  !== false ) $checked = 'checked="checked"';
                echo "<td><input type='checkbox' name='filter[]' value='gcard' $checked>gCard<br></td>";
                $checked = ''; if ( strpos($filter, "gver")  !== false ) $checked = 'checked="checked"';
                echo "<td><input type='checkbox' name='filter[]' value='gver' $checked>gVer<br></td>";
                $checked = ''; if ( strpos($filter, "osmain")  !== false ) $checked = 'checked="checked"';
                echo "<td><input type='checkbox' name='filter[]' value='osmain' $checked>OSvMain<br></td>";
                echo '</tr>';
                echo '<tr>';
                $checked = ''; if ( strpos($filter, "osdwa")  !== false ) $checked = 'checked="checked"';
                echo "<td><input type='checkbox' name='filter[]' value='osdwa' $checked>OSvDWA<br></td>";
                $checked = ''; if ( strpos($filter, "rtag")  !== false ) $checked = 'checked="checked"';
                echo "<td><input type='checkbox' name='filter[]' value='rtag' $checked>Release Tag<br></td>";
                $checked = ''; if ( strpos($filter, "crole")  !== false ) $checked = 'checked="checked"';
                echo "<td><input type='checkbox' name='filter[]' value='crole' $checked>Condor Role<br></td>";
                echo '</tr>';
                echo '<tr>';
                $checked = ''; if ( strpos($filter, "user")  !== false ) $checked = 'checked="checked"';
                echo "<td><input type='checkbox' name='filter[]' value='user' $checked>Last User<br></td>";
                $checked = ''; if ( strpos($filter, "owner")  !== false ) $checked = 'checked="checked"';
                echo "<td><input type='checkbox' name='filter[]' value='owner' $checked>Owner<br></td>";
                $checked = ''; if ( strpos($filter, "dept")  !== false ) $checked = 'checked="checked"';
                echo "<td><input type='checkbox' name='filter[]' value='dept' $checked>Dept<br></td>";
                echo '</tr>';
                echo '<tr>';
                $checked = ''; if ( strpos($filter, "status")  !== false ) $checked = 'checked="checked"';
                echo "<td><input type='checkbox' name='filter[]' value='status' $checked>Status<br></td>";
                $checked = ''; if ( strpos($filter, "location")  !== false ) $checked = 'checked="checked"';
                echo "<td><input type='checkbox' name='filter[]' value='location' $checked>Location<br></td>";
                $checked = ''; if ( strpos($filter, "atag")  !== false ) $checked = 'checked="checked"';
                echo "<td><input type='checkbox' name='filter[]' value='atag' $checked>Asset Tag<br></td>";
                echo '</tr>';
                echo '<tr>';
                $checked = ''; if ( strpos($filter, "wstart")  !== false ) $checked = 'checked="checked"';
                echo "<td><input type='checkbox' name='filter[]' value='wstart' $checked>War. Start<br></td>";
                $checked = ''; if ( strpos($filter, "wend")  !== false ) $checked = 'checked="checked"';
                echo "<td><input type='checkbox' name='filter[]' value='wend' $checked>War. End<br></td>";
                $checked = ''; if ( strpos($filter, ",updated")  !== false ) $checked = 'checked="checked"';
                echo "<td><input type='checkbox' name='filter[]' value='updated' $checked>Last Updated<br></td>";
                echo '</tr>';
            echo '</table>';
        echo '</td>';
        echo '<td style="width:20px;"></td>';
        echo '<td style="width:5px; border-left: 1px solid #CCC"></td>';
            $checked = ''; if ( ! empty($ignorestale) ) $checked = 'checked="checked"';
            echo "<td style='font-size: 14px;'><input type='checkbox' name='chkIgnoreStale' value='chkIgnoreStale' $checked>Ignore Stale<br></td>";
        echo '</tr>';
    echo '</table>';
echo '</form>';
echo '</div>';

while ($row = mysql_fetch_assoc($result)) {
    $csv .= implode( ',', $row ) . "\n"; 
}

$csv = $filter . "\n" . $csv;
//echo $csv;

if ($csv) {
    $myFile = "/work/intranet/hwdb/hwdb.csv";
    $fh = fopen($myFile, 'w') or die("can't open CSV file\n");;
    fwrite($fh, $csv);
    fclose($fh);
}

if(!mysql_data_seek($result,0))continue;

echo '<div class="host_wrap">';
    echo "<table id='hosttab' summary='The Host List'>";
    echo '<thead>';
        echo '<tr>';
            echo '<th scope="col" style="width:30px;">#</th>';
            if ( strpos($filter, "name")  !== false ) echo '<th scope="col">Hostname</th>';
            if ( strpos($filter, "slno")  !== false ) echo '<th scope="col">Sl.No</th>';
            if ( strpos($filter, "type")  !== false ) echo '<th scope="col">Type</th>';
            if ( strpos($filter, "model")  !== false ) echo '<th scope="col">Model</th>';
            if ( strpos($filter, "cpu")  !== false ) echo '<th scope="col">CPU</th>';
            if ( strpos($filter, "ram")  !== false ) echo '<th scope="col">RAM</th>';
            if ( strpos($filter, "gcard")  !== false ) echo '<th scope="col">gCard</th>';
            if ( strpos($filter, "gver")  !== false ) echo '<th scope="col">gVer</th>';
            if ( strpos($filter, "osmain")  !== false ) echo '<th scope="col">OSvMain</th>';
            if ( strpos($filter, "osdwa")  !== false ) echo '<th scope="col">OSvDWA</th>';
            if ( strpos($filter, "rtag")  !== false ) echo '<th scope="col">Release Tag</th>';
            if ( strpos($filter, "crole")  !== false ) echo '<th scope="col">Condor Role</th>';
            if ( strpos($filter, "user")  !== false ) echo '<th scope="col">Last User</th>';
            if ( strpos($filter, "owner")  !== false ) echo '<th scope="col">Owner</th>';
            if ( strpos($filter, "dept")  !== false ) echo '<th scope="col">Dept</th>';
            if ( strpos($filter, "status")  !== false ) echo '<th scope="col">Status</th>';
            if ( strpos($filter, "location")  !== false ) echo '<th scope="col">Location</th>';
            if ( strpos($filter, "atag")  !== false ) echo '<th scope="col">Asset Tag</th>';
            if ( strpos($filter, "wstart")  !== false ) echo '<th scope="col">War. Start</th>';
            if ( strpos($filter, "wend")  !== false ) echo '<th scope="col">War. End</th>';
            if ( strpos($filter, ",updated")  !== false ) echo '<th scope="col">LastUpdated</th>';
        echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
        while ($row = mysql_fetch_assoc($result)) {
        $count += 1;
        $stale = (! isset($row["age"]) || (int) ($row["age"]) >= 24) ? 'style="color: #C01515;"' : '';
        if ($row["type"] == 'enclosure' || $row["type"] == 'switch' || $row["type"] == 'router' ) $stale = '';
        
        echo '<tr>';
            echo '<td style="width:30px;">' . $count . '</td>';
            if ( strpos($filter, "name")  !== false ) echo '<td ' . $stale . ' >' . $row["name"] . '</td>';
            if ( strpos($filter, "slno")  !== false ) echo '<td ' . $stale . ' >' . $row["slno"] . '</td>';
            if ( strpos($filter, "type")  !== false ) echo '<td ' . $stale . ' >' . $row["type"] . '</td>';
            if ( strpos($filter, "model")  !== false ) echo '<td ' . $stale . ' >' . $row["model"] . '</td>';
            if ( strpos($filter, "cpu")  !== false ) echo '<td ' . $stale . ' >' . $row["cpu"] . '</td>';
            if ( strpos($filter, "ram")  !== false ) echo '<td ' . $stale . ' >' . $row["ram"] . '</td>';
            if ( strpos($filter, "gcard")  !== false ) echo '<td ' . $stale . ' >' . $row["gcard"] . '</td>';
            if ( strpos($filter, "gver")  !== false ) echo '<td ' . $stale . ' >' . $row["gver"] . '</td>';
            if ( strpos($filter, "osmain")  !== false ) echo '<td ' . $stale . ' >' . $row["osmain"] . '</td>';
            if ( strpos($filter, "osdwa")  !== false ) echo '<td ' . $stale . ' >' . $row["osdwa"] . '</td>';
            if ( strpos($filter, "rtag")  !== false ) echo '<td ' . $stale . ' >' . $row["rtag"] . '</td>';
            if ( strpos($filter, "crole")  !== false ) echo '<td ' . $stale . ' >' . $row["crole"] . '</td>';
            if ( strpos($filter, "user")  !== false ) echo '<td ' . $stale . ' >' . $row["user"] . '</td>';
            if ( strpos($filter, "owner")  !== false ) echo '<td ' . $stale . ' >' . $row["owner"] . '</td>';
            if ( strpos($filter, "dept")  !== false ) echo '<td ' . $stale . ' >' . $row["dept"] . '</td>';
            if ( strpos($filter, "status")  !== false ) echo '<td ' . $stale . ' >' . $row["status"] . '</td>';
            if ( strpos($filter, "location")  !== false ) echo '<td ' . $stale . ' >' . $row["location"] . '</td>';
            if ( strpos($filter, "atag")  !== false ) echo '<td ' . $stale . ' >' . $row["atag"] . '</td>';
            if ( strpos($filter, "wstart")  !== false ) echo '<td ' . $stale . ' >' . $row["wstart"] . '</td>';
            if ( strpos($filter, "wend")  !== false ) echo '<td ' . $stale . ' >' . $row["wend"] . '</td>';
            if ( strpos($filter, ",updated")  !== false ) echo '<td ' . $stale . ' >' . $row["updated"] . '</td>';
            //echo '<td>' . $age . '</td>';
        echo '</tr>';
        }
    echo '</tbody>';
    echo '</table>';

    mysql_close($conn);


?>
</div>

<a class="linkExport" href="download.php">Export to CSV </a>
<a class="manpage" href="http://web.example.com/wiki/hwdb.html">CLI Wiki Page</a>

</body>
</html>
<? ob_flush(); ?>
