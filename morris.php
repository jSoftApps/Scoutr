<?php 

    // Everything below this point in the file is secured by the login system
    
    // We can display the user's username to them by reading it from the session array.  Remember that because
    // a username is user submitted content we must use htmlentities on it before displaying it to the user.
    
    // We can retrieve a list of members from the database using a SELECT query.
    // In this case we do not have a WHERE clause because we want to select all
    // of the rows from the database table.
    $query = "
        SELECT
            teamscore
        AS average
        FROM matchdata
    ";
    
    
    try
    {
        // These two statements run the query against your database table.
        $stmt = $db->prepare($query);
        $stmt->execute();
    }
    catch(PDOException $ex)
    {
        // Note: On a production website, you should not output $ex->getMessage().
        // It may provide an attacker with helpful information about your code. 
        die("Failed to run query: " . $ex->getMessage());
    }
    
    echo "<script>\r\n" ;
    echo "new Morris.Line({\r\n";
    echo "  // ID of the element in which to draw the chart.\r\n";
    echo "  element: 'myfirstchart',\r\n";
    echo "  // Chart data records -- each entry in this array corresponds to a point on\r\n";
    echo "  // the chart.\r\n";
    echo "  data: [\r\n";
    echo "    { year: '2008', value: 20 },\r\n";
    echo "    { year: '2009', value: 10 },\r\n";
    echo "    { year: '2010', value: 5 },\r\n";
    echo "    { year: '2011', value: 5 },\r\n";
    echo "    { year: '2012', value: 20 }\r\n";
    echo "  ],\r\n";
    echo "  // The name of the data record attribute that contains x-values.\r\n";
    echo "  xkey: 'year',\r\n";
    echo "  // A list of names of data record attributes that contain y-values.\r\n";
    echo "  ykeys: ['value'],\r\n";
    echo "  // Labels for the ykeys -- will be displayed when you hover over the\r\n";
    echo "  // chart.\r\n";
    echo "  labels: ['Value']\r\n";
    echo "});\r\n";
echo "</script>";
    
?>