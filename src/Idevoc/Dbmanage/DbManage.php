<?php namespace Idevoc\Dbmanage;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

/**
 * Database Backup Library.
 *
 * @author      Anshad
 * @version 	1.1.0
 */

class DbManage extends Compress
{


	/**
	* Take Database Backup
	* 
	* @param string $path, string $tables(comma seperated)
	*/

 	public static function backupDatabase( $path, $tables = '*' )
	{
		$database     = DB::getDatabaseName();
		$schema       = DB::getDoctrineSchemaManager();
		
		$return       = "";
		$return.="SET foreign_key_checks = 0;";
		$return.="\n\n\n";
        

		if($tables == '*') {
			$tables = $schema->listTableNames();
		} else {
			$tables = is_array($tables) ? $tables : explode(',',$tables);
		}

		foreach($tables as $table){
			$fields     = $schema->listTableColumns($table);
			$num_fields = count($fields);
			$rows       = DB::select('SELECT * FROM '.$table.'');
			$rows       = self::objectToArray($rows);
		    $count      = count($rows);
		    $rows_array = array();

		    for ($i=0; $i < $count; $i++) { 
			  $rows_array[] = array_merge(array_values($rows[$i]));		
		    }

			// get table structure to schema
			$return.= 'DROP TABLE IF EXISTS '.$table.';';
			$table_create = DB::selectOne('SHOW CREATE TABLE '.$table.'');
			$table_create = get_object_vars($table_create);
            $table_create = (array)$table_create;
            $table_create = array_values($table_create);
			$return.= "\n\n".$table_create[1].";\n\n";

			$array = array_dot($rows_array);

			// get table values to schema
			$row_count = count($rows);//DB::table($table)->count();
			for ($i=0; $i < $row_count; $i++) { 
				$return.= 'INSERT INTO '.$table.' VALUES(';
				for ($j=0; $j < $num_fields; $j++) { 
				    $array[$i.'.'.$j] = addslashes($array[$i.'.'.$j]);
					$array[$i.'.'.$j] = str_replace("'", '', $array[$i.'.'.$j]);
					$array[$i.'.'.$j] = str_replace("\n", '', $array[$i.'.'.$j]);
					$array[$i.'.'.$j] = str_replace("\r", '', $array[$i.'.'.$j]);
					$array[$i.'.'.$j] = str_replace('\"', '"', $array[$i.'.'.$j]);
					$array[$i.'.'.$j] = str_replace("’", "\'", $array[$i.'.'.$j]);
					$array[$i.'.'.$j] = str_replace("`", "\'", $array[$i.'.'.$j]);
					$array[$i.'.'.$j] = str_replace('“', '"', $array[$i.'.'.$j]);
					$array[$i.'.'.$j] = str_replace('”', '"', $array[$i.'.'.$j]);
					if (isset($array[$i.'.'.$j])) { 
						$return.= "'".$array[$i.'.'.$j]."'"; 
					} else { 
						$return.= "''"; 
					}
					if ( $j < ($num_fields-1) ) { 
						$return.= ', '; 
					}
			    }
			    $return.= ");\n";
			}
			$return.="\n\n\n";
		}

       $return.="SET foreign_key_checks = 1;";
       $return.="\n\n\n";

       $fileName = 'backup_'.$database.'_'.date('d.m.Y_H.i.s.A',time()).'.sql'; 
       $from     = $path.$fileName;
	   $to       = $path.$fileName.'.gz';
	   $handle   = fopen($from,'w+');
	   fwrite($handle,$return); // create .sql file
	   fclose($handle);

	   if (file_exists($from)){
		   @set_time_limit(0);
		   $gzip = new Compress();
	       $gzip->pack($from, $to); // compress to .gz
	       unlink($from); // delete temporary .sql file
       } 

	}

	/**
	* Convert Object to Array
	* 
	* @param obj $obj
	*/

	public static function objectToArray($obj) {
        if(!is_array($obj) && !is_object($obj)) return $obj;
		if(is_object($obj)) $obj = get_object_vars($obj);
        return array_map('self::'.__FUNCTION__, $obj);
    }
    

	
}