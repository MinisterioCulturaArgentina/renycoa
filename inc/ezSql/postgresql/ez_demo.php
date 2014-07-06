<?

	// Include ezSQL in order to use it..
	include_once "ez_sql.php";

	// Get list of tables from current database..
	$my_tables = $db->get_results("SELECT tablename FROM pg_tables WHERE tablename !~ '^pg_' ORDER BY 1",ARRAY_N);
	
	// Print out last query and results..
	$db->debug();

	// Loop through each row of results..
	foreach ( $my_tables as $table )
	{
		
		$query = "SELECT a.attname AS column, format_type(a.atttypid, a.atttypmod) AS type, CASE WHEN a.attnotnull IS TRUE THEN 'not null' ELSE '' END || CASE WHEN d.adsrc IS NULL THEN '' ELSE ' default ' || substring(d.adsrc for 128) END AS modifiers"
			. " FROM pg_class c INNER JOIN pg_attribute a ON (a.attnum > 0 AND a.attrelid = c.oid) LEFT JOIN pg_attrdef d ON (c.oid = d.adrelid AND d.adnum = a.attnum AND a.atthasdef='t')"
			. " WHERE c.relname = '$table[0]' ORDER BY a.attnum";
	
		// Get results of DESC table..
		$db->get_results($query);

		// Print out last query and results..
		$db->debug();
	}

?>
