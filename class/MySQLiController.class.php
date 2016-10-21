
<?php



//数据库信息和数据库连接————————————————————————————————————————————————————————————————————————————————————————————————————

$dbr = new mysqli(DB_ADDRESS, DB_USER, DB_PASSWORD, DB_NAME);
$dbr->select_db( DB_NAME );

//数据库信息和数据库连接结束————————————————————————————————————————————————————————————————————————————————————————————————







class MySQLiController
{
	private $dbr;
    function __construct($dbr)
    {	
        $this->dbr = $dbr;
    }

    protected function getPrimaryKey($tableName)
    {
        $query = 'SHOW KEYS FROM ' . $tableName . ' WHERE Key_name = "PRIMARY"';
        $result = mysqli_query($this->dbr, $query);
        $aKeyInfo = $result->fetch_array();
        return $aKeyInfo['Column_name'];
    }


    // 属性 ——————————————————————————————————————————————————————————————————————————————————————————————————————————————————

    function __get($name)
    {
        switch($name)
        {
            case "version": // MySQL版本
            {
                $query = 'SELECT version()';
                $result = mysqli_query($this->dbr, $query);
                $aVersionInfo = $result->fetch_array();
                return $aVersionInfo["version()"];
            }
        }
    }

    //整体操作——————————————————————————————————————————————————————————————————————————————————————————————————————————————————
    
    //创建表
    /*本文件只需要设置 $tableMode 变量即可，参考以下格式
        '(
            entry_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            name TEXT NOT NULL,
            age INT NOT NULL,
            id TEXT NOT NULL
        )'
    */
    public function createTable($tableName, $tableMode)
    {
        $query = 'CREATE TABLE ' . $tableName . $tableMode;
        if( $this->dbr->query( $query ) )
        {
            return true;
        }
        else
        {
            
            return false;
        }   
    }

    // 删除表
    public function dropTable( $tableName )
    {
        $query = 'DROP TABLE ' . $tableName;
        if( $this->dbr->query( $query ) )
        {
            return true;
        }
        else
        {
            echo 'could not drop the table';
            return false;
        }
    }



    //备份数据库。默认备份全部数据库。如果需要备份部分表，传参表名数组
    public function backup( $tables = '*')
    {
        $data = "\n/*---------------------------------------------------------------".
                "\n  SQL DB BACKUP ".date("d.m.Y H:i")." ".
                "\n  HOST: {" . DB_ADDRESS . "}".
                "\n  DATABASE: {" . DB_NAME . "}".
                "\n  TABLES: {$tables}".
                "\n  ---------------------------------------------------------------*/\n";

        mysqli_query( $this->dbr, "SET NAMES `utf8` COLLATE `utf8_general_ci`"  ); // Unicode

        if($tables == '*')//get all of the tables
        { 
            $tables = array();
            $result = mysqli_query( $this->dbr, "SHOW TABLES");
            while($row = $result->fetch_row())
            {
                $tables[] = $row[0];
            }
        }
        else
        {
            $tables = is_array($tables) ? $tables : explode(',',$tables);
        }

        foreach($tables as $table)
        {
            $data.= "\n/*---------------------------------------------------------------".
                    "\n  TABLE: `{$table}`".
                    "\n  ---------------------------------------------------------------*/\n";           
            $data.= "DROP TABLE IF EXISTS `{$table}`;\n";
            $res = mysqli_query( $this->dbr, "SHOW CREATE TABLE `{$table}`");
            $row = $res->fetch_row();
            $data.= $row[1].";\n";

            $result = mysqli_query( $this->dbr, "SELECT * FROM `{$table}`");
            $num_rows = mysqli_num_rows($result);    

            if($num_rows>0)
            {
                $vals = Array(); $z=0;
                for($i=0; $i<$num_rows; $i++)
                {
                    $items = $result->fetch_row();
                    $vals[$z]="(";
                    for($j=0; $j<count($items); $j++)
                    {
                        if (isset($items[$j])) 
                        { 
                            $vals[$z].= "'".$this->dbr->real_escape_string( $items[$j] )."'"; 
                        } 
                        else 
                        { 
                            $vals[$z].= "NULL"; 
                        }
                        if ($j<(count($items)-1))
                        { 
                            $vals[$z].= ","; 
                        }
                    }
                    $vals[$z].= ")"; $z++;
                }
                $data.= "INSERT INTO `{$table}` VALUES ";      
                $data .= "  ".implode(";\nINSERT INTO `{$table}` VALUES ", $vals).";\n";
            }
        }

        // create backup
        $backup_file = 'db-backup-'.time() .'.sql';

        // get backup
        $mybackup = $data;

        // save to file
        if( @file_put_contents($backup_file, $mybackup) )
        {
             return true;
        }
        else
        {
            echo '数据库备份失败。';
        }
    }


    //返回所有的数据库  该函数的返回值需要循环使用fetch_array来依次取得每个数据库
    public function showDatabases()
    {
        $query = 'SHOW DATABASES';
        return mysqli_query($this->dbr, $query);
    }

    //返回数据库的所有表  该函数的返回值需要循环使用fetch_array来依次取得每个表
    public function showTables()
    {
        $query = 'SHOW TABLES';
        return mysqli_query($this->dbr, $query);
    }

    //返回传递表格每列的模式信息  该函数的返回值需要循环使用fetch_array来依次取得每个列的信息
    public function describeTable($tableName)
    {
        $query = 'DESCRIBE ' . $tableName ;
        return mysqli_query($this->dbr, $query);
    }

    /* TODO 不对
    //创建索引
    public function createIndex($tableName, $colName)
    {
        $query = 'ALTER TABLE ' . $tableName . ' ADD INDEX ' . $colName . ' (' . $colName . ')' ;
        echo $query . '<br />';
        //return 
        mysqli_query($this->dbr, $query);
        var_dump( $this->dbr->error ) ;
    }

    //删除索引
    public function dropIndex($tableName, $colName)
    {
        $query = 'ALTER TABLE ' . $tableName . ' DROP INDEX ' . $colName;
        mysqli_query($this->dbr, $query);
        var_dump( $this->dbr->error ) ;
    }
    */
    

    //权限操作——————————————————————————————————————————————————————————————————————————————————————————————————————————————————
    //创建管理员。获得当前数据库的所有权限。
    public function createAdministrator($username, $password)
    {  
        $query = ' GRANT ALL ON ' . DB_NAME . '.* TO "' . $username . '" IDENTIFIED BY "' . $password . '" WITH GRANT OPTION';
        return mysqli_query($this->dbr, $query);
    }

    //创建只读权限用户。只能读取当前数据库的信息
    public function createReadOnlyUser($username, $password)
    {  
        $query = ' GRANT SELECT ON ' . DB_NAME . '.* TO "' . $username . '" IDENTIFIED BY "' . $password . '"';
        return mysqli_query($this->dbr, $query);
    }
    
    //读取区域——————————————————————————————————————————————————————————————————————————————————————————————————————————————————
    //获得总行数
    public function allLineNum($tableName)
    {
        $query = 'SELECT *  FROM ' . $tableName;
        return mysqli_num_rows(mysqli_query($this->dbr, $query) );
    }

    
    //查找重复
    //返回一个两项数组。
    //如果有重复的，第一项是查询结果数组，可以通过fetch_array来循环查看重复项；第二项是有多少种重复。
    //如果没重复是，数组第一项是null，第二项是0
    public function getDuplicate($tableName, $column )
    {
        $query = 'select ' . $column . ',count(*) as count from ' . $tableName . ' group by ' . $column . ' having count>1';
        $result = mysqli_query($this->dbr, $query);
        $repetitionNum = $result->num_rows;
        if( $repetitionNum > 0 )
        {
            return array( $result, $repetitionNum);
        }
        else
        {
            return array( null, 0);
        }
    }

    //取得符合WHERE条件的一个或多个row。该函数的返回值需要循环使用fetch_array来依次取值
    public function getRow($tableName, $where )
    {
    	$query = 'SELECT * FROM ' . $tableName . ' WHERE ' . $where;
    	$result = $this->dbr->query( $query );
    	if( $result )
		{
			return $result;
		}
		else
		{
			echo "<p>could not get row</p>";
		}
    }


    //按照某一列的值来排序。该函数的返回值需要循环使用fetch_array来以此取值
    //第一个参数是用来排序的列，默认降序排列，第二个参数如果为true，则为升序
    public function getDataByRank($tableName, $col, $asc=false)
    {
    	if( $asc )
        {
            $query = 'SELECT * FROM ' . $tableName . ' ORDER BY ' . $col;
        }
        else
        {
            $query = 'SELECT * FROM ' . $tableName . ' ORDER BY ' . $col . ' DESC';
        }
        $result = $this->dbr->query( $query );
        if( $result )
		{
			return $result;
		}
		else
		{
			echo "<p>could not rank the column</p>";
		}
    }


    //特征值(最大、最小、平均)相关—————————————————————————————————————————————————
    //某列最大值的所在行
    public function getMaxRow($tableName, $column)
    {
        $query = 'SELECT * FROM ' . $tableName . ' WHERE ' . $column . '=' . $this->getMaxValue($tableName, $column);
        $result = mysqli_query( $this->dbr, $query );
        $row = $result->fetch_array();
        return  $row;
    }

    //某列最大值
    public function getMaxValue($tableName, $column)
    {
        $query = mysqli_query($this->dbr, 'SELECT MAX(' . $column . ') FROM ' . $tableName);
        $row = $query->fetch_row();
        return $row[0];
    }

    //某列最小值的所在行
    public function getMinRow($tableName, $column)
    {
        $query = 'SELECT * FROM ' . $tableName . ' WHERE ' . $column . '=' . $this->getMinValue($tableName, $column);
        $result = mysqli_query( $this->dbr, $query );
        $row = $result->fetch_array();
        return  $row;
    }

    //某列最小值
    public function getMinValue($tableName, $column)
    {
        $query = mysqli_query($this->dbr, 'SELECT MIN(' . $column . ') FROM ' . $tableName);
        $row = $query->fetch_array();
        return $row[0];
    }

    //输出某一列平均值
    public function average($tableName, $column )
    {
        $query = mysqli_query($this->dbr, 'SELECT AVG(' . $column . ') AS average FROM ' . $tableName);
        $row =$query->fetch_array();
        return $row['average'];
    }

    //输出某一列的值大于或者大于等于某个值的行数。例如及格的人数。第三个可选参数是否闭区间
    public function getAboveLineNum($tableName, $column, $value, $closed=true )
    {
        if( $closed )
        {
            $query = 'SELECT * FROM ' . $tableName . ' WHERE ' . $column . '>=' . $value;
        }
        else
        {
             $query = 'SELECT * FROM ' . $tableName . ' WHERE ' . $column . '>' . $value;
        }
        if( $result = mysqli_query($this->dbr, $query) )
        {
            $overlineNum = 0;
            while($row = $result->fetch_array())
            {
                $overlineNum++;
            }
            return  $overlineNum;
        }
        else
        {
            echo 'hehe';
        }
    }

    



    //写入区域----------------------------------------------------------------------------------------------
	//插入新列  参数为一个数组，数组项为一个或多个新列模式字符串。类似于 'id INT UNSIGNED NOT NULL'
	public function insertColumn($tableName, $aColMode) 
	{	
		foreach( $aColMode as $colMode)
		{
			$query = 'ALTER TABLE ' . $tableName . ' ADD COLUMN ' . $colMode;
			$result = $this->dbr->query( $query );
			if( $result )
			{
				echo "<p>new column has been added</p>";
			}
			else
			{    var_dump( $result );
				//echo "<p>could not add new column</p>";
			}
		}
	}

	//删除列。参数为一个数组，数组项为一个或多个列名字符串
	public function dropColumn($tableName, $aColName)
	{	
		foreach( $aColName as $colName)
		{
			$query = 'ALTER TABLE ' . $tableName . ' DROP ' . $colName;
			$result = $this->dbr->query( $query );
			if( $result )
			{
				echo "<p>column has been droped</p>";
			}
			else
			{
				echo "<p>could not drop column</p>";
			}
		}
	}

	//插入新行。参数是一个数组，数组包含一项或多项，每一项是一行中值得字符串，例如'0, "li", "17"'
	//TODO 不知道为什么必须要给主键传0，看其他例子上也没有
	public function insertRow($tableName, $aValue)
	{
		foreach( $aValue as $value)
		{
			$query = 'INSERT INTO ' . $tableName . ' VALUES (' . $value . ')';
			$result = $this->dbr->query( $query );
			if( $result )
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}

	//删除行。参数是数组，包含一个或者多个项，每个项是一个WHERE子句
	public function deleteRow($tableName, $aWhere)
	{
		foreach( $aWhere as $value)
		{
			$query = 'DELETE FROM ' . $tableName . ' WHERE (' . $value . ')';
			$result = $this->dbr->query( $query );
			if( $result )
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}

    //以某一列为标准，只保留该列值重复的若干行中的一行
    public function setUniqueByColumn($tableName, $col )
    {
        $primaryKey = $this->getPrimaryKey();
        $query = 'DELETE FROM ' . $tableName . ' USING ' . $tableName . ',' . $tableName . ' e1 WHERE ' . $tableName . '.' . $primaryKey . ' > e1.' . $primaryKey . ' AND ' . $tableName . '.' . $col . ' = e1.' . $col . '  ';  
        $result = $this->dbr->query( $query );
        if( $result )
        {
            print "<p>duplicates has been delete</p>";   
            
        }
        else
        {
            print "<p>could not delete duplicates</p>";   
        }
    }

	//更新值。第二个参数是要更改的值所在的列，第三个参数是新值，第四个参数WHERE子句用来定位到所在行
	//TODO 这里即使给列名加上双引号，result也会是true，但实际上数据并没有更新
	/*public function updateData($tableName, $locValueCol, $newValue, $where)
	{
		$query = 'UPDATE ' . $tableName . ' SET ' . $locValueCol . ' = ' . $newValue . ' WHERE ' . $where;
		$result = $this->dbr->query( $query );
		if( $result )
        {
            return true;
        }
        else
		{
			return false;
		}
	}*/
    public function updateData($tableName, $aLocValueCol, $aNewValue, $where)
    {
        if( sizeof($aLocValueCol) !== sizeof($aNewValue))
        {
            throw new Exception('要更改的列数和提供的更改值数目不对应');
            return false;
        }
        /*function setUpdateStr( $value, $key )
        {
            $value . '="' .  $aNewValue[$key] . '",';
        }*/
        $sUpdate = '';
        foreach($aLocValueCol as $key=>$value)
        {
            $sUpdate .= $value . '="' .  $aNewValue[$key] . '",';
        }
        $sUpdate = substr($sUpdate, 0, -1);//删除最后一个逗号
        $query = 'UPDATE ' . $tableName . ' SET ' . $sUpdate . ' WHERE ' . $where;
        $result = $this->dbr->query( $query );
        if( $result )
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}

?>