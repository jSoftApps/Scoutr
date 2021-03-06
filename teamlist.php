<?php

    // First we execute our common code to connection to the database and start the session
    require("includes/common.php");
    
    // At the top of the page we check to see whether the user is logged in or not
    if(empty($_SESSION['user']))
    {
        // If they are not, we redirect them to the login page.
        header("Location: login.php");
        
        // Remember that this die statement is absolutely critical.  Without it,
        // people can view your members-only content without logging in.
        die("Redirecting to login.php");
    }
    
    $query = "
        SELECT
            teamname,
            teamnumber,
            startingyear
        FROM team
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
    $rows = $stmt->fetchAll(); 
    
    // Include Header Files (CSS, Javascript, Meta Tags .etc)
    require ("header.php");

    // Bring in menus and sidebar
    require ("nav.php");
?> 

    <div id="page-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">Team List</h1>
        </div>
        <!-- /.col-lg-12 -->
      </div>
      <div class="row">
        <div class="col-lg-12">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Team Name</th>
                  <th>Team Number</th>
                  <th>Starting Year</th>
                </tr>
              </thead>
              <tbody>
              <?php foreach($rows as $row): ?> 
                <tr>
                  <td><a href="profile.php?teamid=<?php echo htmlentities($row['teamnumber'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlentities($row['teamname'], ENT_QUOTES, 'UTF-8'); ?></a></td>
                  <td><?php echo htmlentities($row['teamnumber'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?php echo htmlentities($row['startingyear'], ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
              <?php endforeach; ?> 
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

    <?php
      require ("footer.php");
    ?>