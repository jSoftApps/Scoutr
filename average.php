<?php 

    // Everything below this point in the file is secured by the login system
    
    // We can display the user's username to them by reading it from the session array.  Remember that because
    // a username is user submitted content we must use htmlentities on it before displaying it to the user.
    
    // We can retrieve a list of members from the database using a SELECT query.
    // In this case we do not have a WHERE clause because we want to select all
    // of the rows from the database table.
    $query = "
        SELECT
            AVG(teamscore)
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
        
    // Finally, we can retrieve all of the found rows into an array using fetchAll
    $row = $stmt->fetchColumn();
    echo number_format((float)$row, 2, '.', '');