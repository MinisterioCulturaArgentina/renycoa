<?php

	// Include ezSQL in order to use it..
	include_once "ez_sql.php";

	// Get list of tables from current database..
	$sqlQuery = 'SELECT rdb$relation_name
                   FROM rdb$relations
                  WHERE rdb$system_flag = 0
                  ORDER BY 1';
	$my_tables = $db->get_results($sqlQuery, ARRAY_N);

	// Print out last query and results..
	$db->debug();

	// Loop through each row of results..
	foreach ( $my_tables as $table )
	{
	    $sqlQuery = 'SELECT a.rdb$field_name, c.rdb$type_name,
                            b.rdb$field_length, b.rdb$field_scale,
                            a.rdb$null_flag
                       FROM rdb$relation_fields a, rdb$fields b, rdb$types c
                      WHERE c.rdb$field_name = '."'".'RDB$FIELD_TYPE'."' ".
                       ' AND c.rdb$type = b.rdb$field_type
                        AND b.rdb$field_name = a.rdb$field_source
                        AND a.rdb$relation_name = '."'".$table[0]."'";

		// Get results of DESC table..
		$db->get_results($sqlQuery);

		// Print out last query and results..
		$db->debug();
	}

?>
	