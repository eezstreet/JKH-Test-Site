<?php
    mysql_connect('localhost', 'apitest_user', 'Dumb-Ass-Password') or die("COULD NOT CONNECT");
    mysql_select_db('apitest_api') or die("COULD NOT SELECT DB");
    
    function queryDB($query)
    {
        $sql = mysql_query($query) or die(mysql_error());
        $result = array();
        
        if(gettype($sql) == "boolean") return; // We don't return an array with INSERT, DELETE, DROP, etc..
        
        for($i = 0; $i < mysql_num_rows($sql); $i++)
        {
            $result[$i] = mysql_fetch_array($sql, MYSQL_ASSOC);
        }
        return $result;
    }
    
    function escapeDB($value)
    {
        return mysql_real_escape_string($value);
    }