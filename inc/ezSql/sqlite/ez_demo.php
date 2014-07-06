<?php

	// Include ezSQL in order to use it..
	include_once "ez_sql.php";

	// Create a table..
	$db->query("CREATE TABLE test_table ( ColumnA INTEGER PRIMARY KEY, ColumnB TEXT(32) );");

	// Insert test data
	for($i=0;$i<3;++$i)
	{
		$db->query('INSERT INTO test_table (ColumnB) VALUES ("'.md5(microtime()).'");');
	}
	
	// Get list of tables from current database..
	$my_tables = $db->get_results("SELECT * FROM sqlite_master WHERE sql NOTNULL;");

	// Print out last query and results..
	$db->debug();

	// Loop through each row of results..
	foreach ( $my_tables as $table )
	{
		// Get results of DESC table..
		$db->get_results("SELECT * FROM $table->name;");

		// Print out last query and results..
		$db->debug();
	}

	// Get rid of the table we created..
	$db->query("DROP TABLE test_table;");

?>
	