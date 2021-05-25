<?php
require_once "Generic.php";
require_once "ParamControl.php";

class TableControl{
  public static $generic;
  public static $db;
  public static $remove = array();

  public function __construct($generic) {
    self::$generic = $generic;
  }

  public function run($queries, $callback)  {
    $c = 0;
    $response = [];
    $db = self::$generic->connect(false);
    self::$db = $db;
    if(gettype($queries) === "string"){
      $nums = explode("-", $queries);
      $queries = [];
      $first = (int) filter_var($nums[0], FILTER_SANITIZE_NUMBER_INT);
      $last  = (int) filter_var($nums[1], FILTER_SANITIZE_NUMBER_INT);
      for($i=$first; $i<=$last; $i++) {
        $queries[] = "query$i";
      }
    }

    foreach($queries as $k => $_query){
      $_query = trim($_query);
      global ${$_query};
    	if(isset(${$_query})){
    		$fields = $this->get_all_table_fields(${$_query});
    		$counter = 0;
    		$tablename = $fields->query->table;
    		if(isset($fields->mysql)){//If this query already has a database table
    			foreach($fields->query->column_names as $k => $colname){//Loop through each query columns
    				$key = array_search($colname, $fields->mysql->column_names);
    				$db_col_details = ($key !== false) ? $fields->mysql->row[$key] : "";//This database column details
    				$qu_col_details = $fields->query->row[$k];                          //This query column details
    				if(array_search($colname, $fields->mysql->column_names) === false){//if this query column doesnt exist in database (Add)
    					$prev = "";
    					if(isset($fields->query->column_names[$k-1])){
    						$prev = $fields->query->column_names[$k-1];
    						$prev = "AFTER $prev";
    					}
    					$add = "ALTER TABLE $tablename ADD $colname $qu_col_details $prev";
    					if($db->query($add) or die($db->error." ($add)")){
                $response[] = "$colname added<br>";
    						$counter++;
    					};
    				}else if(($qu_col_details !== $db_col_details) && !empty($db_col_details)){//Exists but details don't match (Modify)
    					$add = "ALTER TABLE $tablename MODIFY $colname $db_col_details";
    					//$db->query($add) or die($db->error." ($add)");
    				}
    			}
          if ($counter) {
            $counter = ($counter > 1) ? "{$counter} columns" : "{$counter} column";
            $response[] = "$counter affected on $tablename table";
            $response[] = "----------------------><br>";
          }
    		}else{
    			$query = ${$_query};
    			$db->query($query) or die($db->error."($query)");
    			$db->query("ALTER TABLE {$tablename} CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;") or die($db->error."($tablename)");
          $response[] = "$tablename created";
          $response[] = "----------------------><br>";
    		}
    		$c++;
        $this::$remove[] = $fields;
    	}
    }
    if(gettype($callback) == "object"){
      $response = call_user_func($callback, $response);
    }
    $response[] = self::$generic->buildAllConstants(true);
    $response[] = "Done----------------------><br>";
    $db->close();
    return $response;
  }

  function get_all_table_fields($_query){
  	$db = self::$db;
    $all = new stdClass;
  	$query =  $this->queryToArray($_query);
  	$table = $query->table;
  	$all->query = $query;
  	//fetch existing columns from database table and build array with tablename
  	if($sql = $db->query("SHOW CREATE TABLE $table")){
  		if($row = $sql->fetch_assoc()){
  			$mysql =  $this->queryToArray($row['Create Table']);
  			$all->mysql = $mysql;
  		}
  	}
  	return $all;
  }

  function queryToArray($query){
  	//Returns tablename, column names, datatypes
  	$q = str_replace('`','',$query);
  	$arr = explode('(', $q, 2);
  	$arr1 = substr($arr[1],0,-1);
  	$arr0 = explode(' ', trim($arr[0]));
  	//Get the table name
  	$table = end($arr0);
  	//split the query into columns and cleanup
  	$cols = preg_split('/\r\n|\r|\n/', $arr1);
  	$cols = array_map('trim', $cols);
  	$cols = array_filter($cols);
  	$response = new stdClass; $_qu = $_qus = array();
  	//extract columns from query and build array with tablename
  	$response->table = $table;
  	foreach($cols as $col_){
  		if(!empty($col_)){
  			$column_name = explode(' ',trim($col_))[0];
  			if(strlen($column_name) > 1){
  				if(stripos($column_name, 'primary') === false && stripos($column_name, 'unique') === false){
  					$response->column_names[] = trim($column_name);//Column names only
  					$response->row[] = substr($col_, -1) == ',' ?
  						strtolower(trim(str_replace($column_name, '', substr($col_, 0, -1)))) :
  						strtolower(trim(str_replace($column_name, '', $col_)));// Column details
  				}
  			}
  		}
  	}
  	return $response;
  }

  public function drop_columns()  {
    return $this::$remove;
  }
  public function update_generic_tables()  {
    $clientid=1;
    $paramControl = new ParamControl(self::$generic);
    $default_role = $paramControl->default_role();
    $db = self::$db;
    $query6="REPLACE into roles(
      roleid, transcid, clientid, rolename, roledesc)
      values
      ('1', concat(unix_timestamp(),round((rand() * 100) * (rand() * 100) )), '$clientid', 'Administrator', '$default_role'
    )";
    if($db->query($query6) or die($db->error)){
      $response[] = "Admin Role Added";
      $response[] = "----------------------><br>";
    }
    $query8="REPLACE into users(id,username, password, first_name, last_name, roleid, accesslevel, status, type, email) values ('1','administrator','email@admin','Developer','Digital Dreams','1','2','1','1','info@digitaldreamsng.com')";
    if($db->query($query8) or die($db->error)){
      $response[] = "Administrator Added";
      $response[] = "----------------------><br>";
    }
    $query2="REPLACE into company_info(id,name) values ('1','Set me up !!!')";
    if($db->query($query2) or die($db->error)){
      $response[] = "Default Company Added";
      $response[] = "---------------------->";
    }
    return $response;
  }

  public function update_transaction_tables()  {
    $clientid=1;
    $paramControl = new ParamControl(self::$generic);
    $default_role = $paramControl->default_role();
    $db = self::$db;

    $query="REPLACE INTO default_account_settings (sid, account_receivable,other_receivable,account_payable,sales,cost_of_sales,expenses,inventory,clientid) values ('1', '11000', '11400', '20000','40000','50000','12000','89000','$clientid')";
    if($db->query($query)){
      $response[] = "Default Account Settings updated";
    }else{
      $response[] = "{$db->error}. ({$query})";
    }
    $response[] = "----------------------><br>";

    $query="REPLACE into category_type (typeid, name, type, parent,category,clientid) values ('1', 'account receivable', '1', '1','1','$clientid'), ('2', 'account_payable', '-1', '10','1','$clientid'), ('3', 'cash', '1', '0','1','$clientid'), ('4', 'income', '-1', '21','1','$clientid'),('5', 'cost of sales', '1', '23','1','$clientid'),('6', 'fixed assets', '1', '5','1','$clientid'),('7', 'other current assets', '1', '4','1','$clientid'), ('8','expenses', '1', '24','1','$clientid'),('9', 'other current liabilities', '-1', '12','1','$clientid'),('10', 'long term liabilities', '-1', '14','1','$clientid'),('11', 'equity doesnt close', '-1', '16','1','$clientid'),('12', 'equity closed', '-1', '19','1','$clientid'),('13', 'equity retained', '-1', '18','1','$clientid'),('14',  'inventory', '1', '2','1','$clientid'), ('15', 'accumulated depreciation', '-1', '6','1','$clientid'),('16', 'other assets', '1', '8','1','$clientid')";
    if($db->query($query)){
      $response[] = "Account type Updated";
    }else{
      $response[] = "{$db->error}. ({$query})";
    }
    $response[] = "----------------------><br>";

  	$query="REPLACE INTO account_chart (typeid, account_id, account_type, account_name, inactive,clientid) values
    ('1', '10000', 0,'Petty Cash', 'FALSE','$clientid')
    ,('2',  '10100', 0, 'Cash on Hand', 'FALSE','$clientid')
    ,('3',  '10300', 0, 'Payroll Checking Account', 'FALSE','$clientid')
    ,('4',  '11000', '1', 'Accounts Receivable', 'FALSE','$clientid')
    ,('5',  '11400', '1', 'Other Receivables', 'FALSE','$clientid')
    ,('6',  '11500', '1', 'Allowance for Doubtful Account', 'FALSE','$clientid')
    ,('7',  '12000', '2', 'Goods Inventory', 'FALSE','$clientid')
    ,('8',  '12100', '2', 'Work in Progress Inventory', 'FALSE','$clientid')
    ,('9',  '12150', '2', 'Finished Goods Inventory', 'FALSE','$clientid')
    ,('10', '14000', '4', 'Prepaid Expenses', 'FALSE','$clientid')
    ,('11', '14100', '4', 'Employee Advances', 'FALSE','$clientid')
    ,('12', '14200', '4', 'Notes Receivable-Current', 'FALSE','$clientid')
    ,('13', '14300', '4', 'Prepaid Interest', 'FALSE','$clientid')
    ,('14', '14700', '4', 'Other Current Assets', 'FALSE','$clientid')
    ,('15', '15000', '5', 'Furniture and Fixtures', 'FALSE','$clientid')
    ,('16', '15100', '5', 'Equipment', 'FALSE','$clientid')
    ,('17', '15200', '5', 'Automobiles', 'FALSE','$clientid')
    ,('18', '15300', '5', 'Plant & Machineries', 'FALSE','$clientid')
    ,('19', '15400', '5', 'Leasehold Improvements', 'FALSE','$clientid')
    ,('20', '15500', '5', 'Buildings', 'FALSE','$clientid')
    ,('21', '15600', '5', 'Building Improvements', 'FALSE','$clientid')
    ,('22', '16900', '5', 'Land', 'FALSE','$clientid')
    ,('23', '17000', '6', 'Accum. Depreciation-Furniture', 'FALSE','$clientid')
    ,('24', '17100', '6', 'Accum. Depreciation-Equipment', 'FALSE','$clientid')
    ,('25', '17200', '6', 'Accum. Depreciation-Automobil', 'FALSE','$clientid')
    ,('26', '17300', '6', 'Accum. Depreciation-Plant/Mach', 'FALSE','$clientid')
    ,('27', '17400', '6', 'Accum. Depreciation-Leasehold', 'FALSE','$clientid')
    ,('28', '17500', '6', 'Accum. Depreciation-Computers', 'FALSE','$clientid')
    ,('29', '17600', '6', 'Accum. Depreciation-Bldg Imp', 'FALSE','$clientid')
    ,('30', '17650', '6', 'Accumu.Dep. Plant & Machinery', 'FALSE','$clientid')
    ,('31', '19000', '8', 'Deposits', 'FALSE','$clientid')
    ,('32', '19100', '8', 'Organization Costs', 'FALSE','$clientid')
    ,('33', '19150', '8', 'Accum Amortiz - Organiz Costs', 'FALSE','$clientid')
    ,('34', '19200', '8', 'Notes Receivable- Noncurrent', 'FALSE','$clientid')
    ,('35', '19900', '8', 'Other Noncurrent Assets', 'FALSE','$clientid')
    ,('36', '20000', '10', 'Accounts Payable', 'FALSE','$clientid')
    ,('37', '23000', '12', 'Accrued Expenses', 'FALSE','$clientid')
    ,('38', '23100', '12', 'Sales Tax Payable', 'FALSE','$clientid')
    ,('39', '23200', '12', 'Wages Payable', 'FALSE','$clientid')
    ,('43', '23600', '12', 'State Payroll Taxes Payable', 'FALSE','$clientid')
    ,('45', '23800', '12', 'Local Payroll Taxes Payable', 'FALSE','$clientid')
    ,('46', '23900', '12', 'Income Taxes Payable', 'FALSE','$clientid')
    ,('47', '24000', '12', 'Other Taxes Payable', 'FALSE','$clientid')
    ,('48', '24100', '12', 'Current Portion Long-Term Debt', 'FALSE','$clientid')
    ,('49', '24300', '12', 'Deposits from Customers', 'FALSE','$clientid')
    ,('50', '24700', '12', 'Other Current Liabilities', 'FALSE','$clientid')
    ,('51', '24800', '12', 'Suspense - Clearing Account', 'FALSE','$clientid')
    ,('52', '27000', '14', 'Notes Payable-Noncurrent', 'FALSE','$clientid')
    ,('53', '27100', '14', 'Deferred Revenue', 'FALSE','$clientid')
    ,('54', '27400', '14', 'Other Long-Term Liabilities', 'FALSE','$clientid')
    ,('55', '39002', '16', 'Beginning Balance Equity', 'FALSE','$clientid')
    ,('56', '39003', '16', 'Common Stock', 'FALSE','$clientid')
    ,('57', '39004', '16', 'Paid-in Capital', 'FALSE','$clientid')
    ,('58','39005', '18', 'Retained Earnings', 'FALSE','$clientid')
    ,('59', '39007', '19', 'Dividends Paid', 'FALSE','$clientid')
    ,('60', '40000', '21', 'Sales #1', 'FALSE','$clientid')
    ,('63', '40200', '21', 'Interest/Dividend Income', 'FALSE','$clientid')
    ,('64', '40250', '21', 'Other Income', 'FALSE','$clientid')
    ,('69', '50000', '23', 'Cost of Goods Sold', 'FALSE','$clientid')
    ,('70', '50500', '23', 'Raw Material Purchases', 'FALSE','$clientid')
    ,('71', '51000', '23', 'Direct Labor Costs', 'FALSE','$clientid')
    ,('72', '51500', '23', 'Indirect Labor Costs', 'FALSE','$clientid')
    ,('73', '52000', '23', 'Heat-Light and Power', 'FALSE','$clientid')
    ,('74', '52500', '23', 'Commissions', 'FALSE','$clientid')
    ,('75', '53000', '23', 'Miscellaneous Factory Costs', 'FALSE','$clientid')
    ,('76', '57000', '23', 'Cost of Sales- Salaries and Wa', 'FALSE','$clientid')
    ,('77', '57500', '23', 'Cost of Sales- Freight', 'FALSE','$clientid')
    ,('78',  '58000', '23', 'Cost of Sales- Other', 'FALSE','$clientid')
    ,('79',  '58500', '23', 'Inventory Adjustments', 'FALSE','$clientid')
    ,('80',  '59000', '23', 'Purchase Returns and Allowance', 'FALSE','$clientid')
    ,('81',  '59500', '23', 'Purchase Discounts', 'FALSE','$clientid')
    ,('82',  '60000', '24', 'Advertising Expense', 'FALSE','$clientid')
    ,('83',  '60500', '24', 'Diesel/Lubricants Expenses', 'FALSE','$clientid')
    ,('84',  '61000', '24', 'Auto Expenses', 'FALSE','$clientid')
    ,('85',  '61500', '24', 'Bad Debt Expense', 'FALSE','$clientid')
    ,('86',  '62000', '24', 'Bank Charges', 'FALSE','$clientid')
    ,('87',  '62500', '24', 'Fuel Expenses', 'FALSE','$clientid')
    ,('88',  '63000', '24', 'Charitable Contributions Exp', 'FALSE','$clientid')
    ,('89',  '63500', '24', 'Commissions and Fees Exp', 'FALSE','$clientid')
    ,('90',  '64000', '24', 'Depreciation Expense', 'FALSE','$clientid')
    ,('91',  '64500', '24', 'Hotel/Logding Expenses', 'FALSE','$clientid')
    ,('92',  '65000', '24', 'Employee Benefit Programs Exp', 'FALSE','$clientid')
    ,('93',  '65500', '24', 'Waybill/Road Expenses', 'FALSE','$clientid')
    ,('94',  '65550', '24', 'Electrical Expenses', 'FALSE','$clientid')
    ,('95', '66000', '24', 'Gifts/PR Expense', 'FALSE','$clientid')
    ,('96', '66500', '24', 'Income Tax Expense', 'FALSE','$clientid')
    ,('97',  '67000', '24', 'Insurance Expense', 'FALSE','$clientid')
    ,('98', '67500', '24', 'Interest Expense', 'FALSE','$clientid')
    ,('99', '68000', '24', 'Laundry and Cleaning Exp', 'FALSE','$clientid')
    ,('100', '68500', '24', 'Legal and Professional Expense', 'FALSE','$clientid')
    ,('101', '69000', '24', 'Licenses Expense', 'FALSE','$clientid')
    ,('103', '70000', '24', 'Machine Maintenance Expense', 'FALSE','$clientid')
    ,('104', '70500', '24', 'Meals and Entertainment Exp', 'FALSE','$clientid')
    ,('105', '71000', '24', 'Office Expense', 'FALSE','$clientid')
    ,('106', '71500', '24', 'Other Taxes', 'FALSE','$clientid')
    ,('107', '72000', '24', 'Payroll Tax Expense', 'FALSE','$clientid')
    ,('108', '72500', '24', 'Penalties and Fines Exp', 'FALSE','$clientid')
    ,('109', '73000', '24', 'Pension/Profit-Sharing Plan Ex', 'FALSE','$clientid')
    ,('110', '73500', '24', 'Postage Expense', 'FALSE','$clientid')
    ,('111', '74000', '24', 'Rent or Lease Expense', 'FALSE','$clientid')
    ,('112', '74500', '24', 'Repairs Expense', 'FALSE','$clientid')
    ,('113', '74600', '24', 'Registration Fees Expenses', 'FALSE','$clientid')
    ,('114', '75000', '24', 'Salaries Expense', 'FALSE','$clientid')
    ,('115', '75500', '24', 'Stationeries Expense', 'FALSE','$clientid')
    ,('116', '76000', '24', 'Telephone Expense', 'FALSE','$clientid')
    ,('117', '76500', '24', 'Travel Expense', 'FALSE','$clientid')
    ,('118', '77000', '24', 'Water supply Expense', 'FALSE','$clientid')
    ,('119', '77500', '24', 'Wages Expense', 'FALSE','$clientid')
    ,('120', '89000', '24', 'Other Expense', 'FALSE','$clientid')
    ,('121', '89500', '24', 'Purchase Disc- Expense Items', 'FALSE','$clientid')
    ,('122', '90000', '24', 'Gain/Loss on Sale of Assets', 'FALSE','$clientid')
    ,('123', '99500', '24', 'Heating/Utility expenses', 'FALSE','$clientid')
    ,('176', '41100', '21', 'Refund', 'FALSE','$clientid')
    ,('177', '41150', '21', 'Loan', 'FALSE','$clientid')";
    if($db->query($query)){
      $response[] = "Account chart Updated";
    }else{
      $response[] = "{$db->error}. ({$query})";
    }
    $response[] = "----------------------><br>";
    return $response;
  }

}





/*
function archiveDirectory(&$zip, $dir)
{
	$files=array();
	// Open a known directory, and proceed to read its contents
	if (is_dir($dir)) {
		if ($dh = opendir($dir)) {
			while (($file = readdir($dh)) !== false) {
				$files[]=$file;
			}
			closedir($dh);
			for($i=0;$i<count($files);$i++)
			{
				if($files[$i]=="." || $files[$i]==".." || $files[$i]=="...") continue;
				$directory=$dir."/".$files[$i];
				$filename=substr($directory,2);
				if(is_dir($dir."/". $files[$i])){archiveDirectory($zip,$directory);}
				else $zip->addFile($directory,$filename);
			}
		}
	}
}
function archiveArray(&$zip,&$files)
{
	for($i=0;$i<count($files);$i++)
	{
		if(!file_exists($files[$i]))continue;
		if($files[$i]=="." || $files[$i]==".." || $files[$i]=="...") continue;
		$directory=$files[$i];
		$filename=substr($directory,2);
		if(is_dir( $files[$i])){archiveDirectory($zip,$directory);}
		else $zip->addFile($directory,$filename);
	}
}

function backupDatabase($db)
{
	$number=0;$text="";
	$t_result=$db->query("show tables");
	while(list($table)=$t_result->fetch_array()){
		$d_count=0;$number=0;
		$text .="drop table if exists $table;~% ";
		$text .="create table $table (";
		$d_result=$db->query("describe $table") or die($db->error);
		while($d_line=$d_result->fetch_assoc())
		{
			if($d_count) $text .=",";
			$text .=$d_line["Field"]." ".$d_line["Type"];
			if($d_line["Null"] !="YES")$text .=" not null";
			$text .=" ".$d_line["Extra"]."";
			if($d_line["Key"]!="")$text .=", primary key(".$d_line["Field"].")";
			$d_count++;
		}
		$text .=");~%";
		$query="select * from $table";
		$result=$db->query($query) or die($db->error);
		while($line=$result->fetch_assoc())
		{

			if($number !=0) $text .=","; else $text .="insert into $table values ";
			$val_count=0;
			$text .="(";
			foreach($line as $val)
			{
				if($val_count !=0) $text .=",";
				$val=$db->real_escape_string($val);
				if(empty($val)) {$text .="NULL"; }
				else $text .="'$val'";
				$val_count++;
			}
			$text .= ")";
			$number++;
		}
		if($number !=0)$text .=";~%";

	}
	$t_result->free_result();
	return $text;
}


function backupTable($db, $table)
{
	$text="";
	$d_count=0;$number=0;
	$text .="drop table if exists $table;~% ";
	$text .="create table $table (";
	$d_result=$db->query("describe $table") or die($db->error);

	while($d_line=$d_result->fetch_assoc())
	{
		if($d_count) $text .=",";
		$text .=$d_line["Field"]." ".$d_line["Type"];
		if($d_line["Null"] !="YES")$text .=" not null";
		$text .=" ".$d_line["Extra"]."";
		if($d_line["Key"]!="")$text .=", primary key(".$d_line["Field"].")";
		$d_count++;
	}
	$text .=");~%";
	$query="select * from $table order by id";
	$result=$db->query($query) or die($db->error);
	while($line=$result->fetch_assoc())
	{

		if($number !=0) $text .=","; else $text .="insert into $table values ";
		$val_count=0;
		$text .="(";
		foreach($line as $val)
		{
			if($val_count !=0) $text .=",";
			$val=$db->real_escape_string();
			$text .="'$val'";
			$val_count++;
		}
		$text .= ")";
		$number++;
	}
	if($number !=0)$text .=";~%";
	return $text;
}
function backupFields($db, $table,$fields)
{
	$text="";
	$d_count=0;$number=0;
	$text .="drop table if exists $table;~% ";
	$text .="create table $table (";
	$d_result=$db->query("describe $table") or die($db->error);
	while($d_line=$d_result->fetch_assoc())
	{
		if(!array_keys($fields,$d_line["Field"]))continue;
		if($d_count) $text .=",";
		$text .=$d_line["Field"]." ".$d_line["Type"];
		if($d_line["Null"] !="YES")$text .=" not null";
		$text .=" ".$d_line["Extra"]."";
		if($d_line["Key"]!="")$text .=", primary key(".$d_line["Field"].")";
		$d_count++;
	}
	$text .=");~%";
	$query="select ";
	$field_count=0;
	for($i=0;$i<count($fields);$i++)
	{
		if($field_count)$query .=",";
		$query .=$fields[$i];
		$field_count++;
	}
	$query .=" from $table order by id";
	$result=$db->query($query) or die($db->error);
	while($line=$result->fetch_assoc())
	{

		if($number !=0) $text .=","; else $text .="insert into $table values ";
		$val_count=0;
		$text .="(";
		foreach($line as $val)
		{
			if($val_count !=0) $text .=",";
			$val=$db->real_escape_string($val);
			$text .="'$val'";
			$val_count++;
		}
		$text .= ")";
		$number++;
	}
	if($number !=0)$text .=";~%";
	return $text;
}

function getBackupPics($db,$table)
{
	$query="select picture_ref from $table where picture_ref <> '' order by id ";
	$result=$db->query($query) or die($db->error);
	while($line=$result->fetch_assoc())
	{
		$files[]="./Pictures/".$line["picture_ref"];
	}
	return $files;
}

function runQuery($file){
	global $db;
	if(file_exists($file)){
		$handle=fopen($file,"r");
		if($handle)
		{
			$query = fread($handle, filesize($file));
			fclose($handle);
		}
		$query_set=explode(";~%",$query);
		$num=count($query_set)-1;
		for($i=0;$i<$num;$i++)
		{
			$db->query($query_set[$i]) or die($db->error);
		}
	}
}


function getSync($db, $table)
{
	$text="";
	$query="select query from $table";
	$result=$db->query($query) or die($db->error);
	while($row=$result->fetch_assoc())
	{
		$text .=$row["query"].";";
	}
	return $text;
}
function runQueryText($sql)
{
	$query = $sql;
	$query_set=explode(";~%",$query);
	$num=count($query_set)-1;
	for($i=0;$i<$num;$i++)
	{
		$db->query($query_set[$i]) or die($db->error);
	}
}

function zipDir($source, $destination){
    if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }

    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        return false;
    }

    $source = str_replace('\\', '/', realpath($source));

    if (is_dir($source) === true)
    {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

        foreach ($files as $file)
        {
            $file = str_replace('\\', '/', $file);

            // Ignore "." and ".." folders
            if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
                continue;

            //$file = realpath($file);

            if (is_dir($file) === true){
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            }
            else if (is_file($file) === true)
            {
                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }
    }
    else if (is_file($source) === true)
    {
        $zip->addFromString(basename($source), file_get_contents($source));
    }

    return $zip->close();
}

function extractZip($zipFile, $extract_to){
	$zip = new ZipArchive;
	if(!is_dir($extract_to)){mkdir($extract_to,true, 0755);}
	$res = $zip->open($zipFile);
	if ($res === TRUE) {
	  	$zip->extractTo($extract_to);
	  	$zip->close();
		return true;
	} else {
	  return false;
	}
}

function emptyDir($dir){
	if(is_dir($dir)){
		$di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
		$ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);
		foreach ( $ri as $file ) {
			//$file = $file->getRealPath();
			$file->isDir() ?  rmdir($file) : unlink($file);
		}
		return true;
	}
}

public function backupDatabase($value=''){
$dir = "../database/backups/";
if(!is_dir($dir)){
  mkdir($dir,true,0755);
}
$file=$dir."DataBackup".time().".sql";
$handle=fopen($file,"w+");
if($handle){
  $text=backupDatabase($db);
  if(fwrite($handle, $text, strlen($text)))
  {
    fclose($handle);
    $asset = '../asset/';
    $zipfile = str_replace('.sql','',$file).'.zip';
    zipDir($asset, $zipfile);
    $target_url = 'http://www.enugucathsec2.org/rear/backup_restore_database.php';
    //$target_url = 'http://localhost/enugu_diocese/rear/backup_restore_database.php';
    $headers = array("Content-Type" => "multipart/form-data");
    $post = array(
      'sqlFile' => makeCurlFile($file),
      'zipFile' => makeCurlFile($zipfile)
    );
    $ch = curl_init($target_url);
    curl_setopt($ch, CURLOPT_POST,1);
    curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
       $result = curl_error($ch);
    }
    curl_close ($ch);
    unlink($zipfile);
    echo($result);
  }
}


$db->close();
}

public function restoreDatabase($value='')
{
extract($_POST, EXTR_OVERWRITE);
$dir = "../database/backups/";
$asset = '../asset/';
if(!is_dir($dir)){
  mkdir($dir,true,0755);
}
if(isset($_FILES)){
  foreach($_FILES as $posted => $file){
    $name = $_FILES[$posted]["name"];
    $filename = $dir.$name;
    if(move_uploaded_file($_FILES[$posted]["tmp_name"], $filename)){
      switch($posted){
        case 'sqlFile':
          runQuery($filename);
        break;
        case 'zipFile':
          emptyDir($asset);
          extractZip($filename, $asset);
        break;
      }
    }
  }
  emptyDir($dir);
}
echo 'done';
}

function makeCurlFile($file){
  $file = realpath($file);
  $mime = mime_content_type($file);
  $info = pathinfo($file);
  $name = $info['basename'];
  $output = new CURLFile($file, $mime, $name);
  return $output;
}

*/
?>
