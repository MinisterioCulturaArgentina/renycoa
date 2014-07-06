<?
$start = @$_REQUEST["start"];
$limit = @$_REQUEST["limit"];

$start = $start ? $start : 0;
$limit = $limit ? $limit : 5;

$data = array(
	array("company"=>'3m Co',                                       "hide2"=>true, "lastChange"=>'8/1 12:00am', "industry"=>'Manufacturing'),
	array("company"=>'Alcoa Inc',                                   "hide2"=>true, "lastChange"=>'9/1 12:00am', "industry"=>'Manufacturing'),
	array("company"=>'Altria Group Inc',                            "hide2"=>true, "lastChange"=>'10/1 12:00am', "industry"=>'Manufacturing'),
	array("company"=>'American Express Company',                    "hide2"=>true, "lastChange"=>'9/1 10:00am', "industry"=>'Finance'),
	array("company"=>'American International Group, Inc.',          "hide2"=>true, "lastChange"=>'9/1 11:00am', "industry"=>'Services'),
	array("company"=>'AT&T Inc.',                                   "hide2"=>true, "lastChange"=>'9/1 12:00am', "industry"=>'Services'),
	array("company"=>'Boeing Co.',                                  "hide2"=>false, "lastChange"=>'9/1 12:00am', "industry"=>'Manufacturing'),
	array("company"=>'Caterpillar Inc.',                            "hide2"=>true, "lastChange"=>'9/1 12:00am', "industry"=>'Services'),
	array("company"=>'Citigroup, Inc.',                             "hide2"=>true, "lastChange"=>'9/1 12:00am', "industry"=>'Finance'),
	array("company"=>'E.I. du Pont de Nemours and Company',         "hide2"=>true, "lastChange"=>'9/1 12:00am', "industry"=>'Manufacturing'),
	array("company"=>'Exxon Mobil Corp',                            "hide2"=>true, "lastChange"=>'9/1 12:00am', "industry"=>'Manufacturing'),
	array("company"=>'General Electric Company',                    "hide2"=>true, "lastChange"=>'9/1 12:00am', "industry"=>'Manufacturing'),
	array("company"=>'General Motors Corporation',                  "hide2"=>true, "lastChange"=>'9/1 12:00am', "industry"=>'Automotive'),
	array("company"=>'Hewlett-Packard Co.',                         "hide2"=>true, "lastChange"=>'9/1 12:00am', "industry"=>'Computer'),
	array("company"=>'Honeywell Intl Inc',                          "hide2"=>true, "lastChange"=>'9/1 12:00am', "industry"=>'Manufacturing'),
	array("company"=>'Intel Corporation',                           "hide2"=>true, "lastChange"=>'9/1 12:00am', "industry"=>'Computer'),
	array("company"=>'International Business Machines',             "hide2"=>true, "lastChange"=>'9/1 12:00am', "industry"=>'Computer'),
	array("company"=>'Johnson & Johnson',                           "hide2"=>true, "lastChange"=>'9/1 12:00am', "industry"=>'Medical'),
	array("company"=>'JP Morgan & Chase & Co',                      "hide2"=>true, "lastChange"=>'9/1 12:00am', "industry"=>'Finance'),
	array("company"=>'McDonald\'s Corporation',                     "hide2"=>true, "lastChange"=>'9/1 12:00am', "industry"=>'Food'),
	array("company"=>'Merck & Co., Inc.',                           "hide2"=>true, "lastChange"=>'9/1 12:00am', "industry"=>'Medical'),
	array("company"=>'Microsoft Corporation',                       "hide2"=>true, "lastChange"=>'9/1 12:00am', "industry"=>'Computer'),
	array("company"=>'Pfizer Inc',                                  "hide2"=>true, "lastChange"=>'9/1 12:00am', 'Services', "industry"=>'Medical'),
	array("company"=>'The Coca-Cola Company',                       "hide2"=>true, "lastChange"=>'9/1 12:00am', "industry"=>'Food'),
	array("company"=>'The Home Depot, Inc.',                        "hide2"=>true, "lastChange"=>'9/1 12:00am', "industry"=>'Retail'),
	array("company"=>'The Procter & Gamble Company',                "hide2"=>true, "lastChange"=>'9/1 12:00am', "industry"=>'Manufacturing'),
	array("company"=>'United Technologies Corporation',             "hide2"=>true, "lastChange"=>'9/1 12:00am', "industry"=>'Computer'),
	array("company"=>'Verizon Communications',                      "hide2"=>true, "lastChange"=>'9/1 12:00am', "industry"=>'Services'),
	array("company"=>'Wal-Mart Stores, Inc.',                       "hide2"=>true, "lastChange"=>'9/1 12:00am', "industry"=>'Retail'),
	array("company"=>'Walt Disney Company (The) (Holding Company)', "hide2"=>true, "lastChange"=>'9/1 12:00am', "industry"=>'Services')
);

$a = array();
for($i = $start; $i < $start + $limit; $i++) {
	$a[] = $data[$i];
}
$o = array(
	 "success"=>true
	,"totalCount"=>sizeof($data)
	,"rows"=>$a
);

echo json_encode($o);

// eof
?>
