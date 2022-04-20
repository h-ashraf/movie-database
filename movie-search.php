<!DOCTYPE html>
<html>

<?php
    require_once "header.php";
?>

<br>


<form action="search.php" style="padding-left: 2%">
    <button class="btn btn-primary btn-sm">Return to previous page</button>
</form>

    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <form action="" method="GET">
                    <div class="card shadow mt-3">
                        <div class="card-header">
                            <h3>Filter Data</h3>
                        </div>
                        <div class="card-body">
                            <h6>Genre List</h6>
                            <hr>
                            <?php
                                //Set connection
                                $conn = mysqli_connect("localhost", "root", "", "the_movie_database");

                                //Set query to find all genres in 'movies' table, result and no. of rows
                                $find_genres_query =  "SELECT DISTINCT JSON_UNQUOTE(JSON_EXTRACT(genres, CONCAT('$[', seq_0_to_100.seq, '].id'))) AS genre_id, JSON_UNQUOTE(JSON_EXTRACT(genres, CONCAT('$[', seq_0_to_100.seq, '].name'))) AS genre_name FROM movies JOIN seq_0_to_100 HAVING genre_name IS NOT NULL";
                                $find_genres_result = mysqli_query($conn, $find_genres_query);
                                $find_genres_n = mysqli_num_rows($find_genres_result);

                                //If result...
                                if($find_genres_n > 0)
                                {
                                    //For each result...
                                    foreach($find_genres_result as $resultList)
                                    {
                                        //Checked array checks that genre has transferred to script
                                        $checked = [];
                                        if(isset($_GET['genres']))
                                        {
                                            $checked = $_GET['genres'];
                                        }

                                        ?>
                                            <div>
                                                <?//Generates a checkbox for each genre retrieved from database?>
                                                <input type="checkbox" name="genres[]" value="<?= $resultList['genre_id']; ?>" 
                                                    <?php if(in_array($resultList['genre_id'], $checked)) { echo "checked";}?>
                                                />
                                                <?= $resultList['genre_name']; ?>
                                            </div>
                                        <?php
                                    }

                                }
                                else
                                {
                                    echo "No results!";
                                }
                            ?>

                            <br>
                            <h6>Release Year</h6>
                            <hr>
                            <div>
                                <?//Create inputs for dates, if statement stores them after search ?>
                                <label>From</label>
                                <input type="date" name="from_date" value="<?php if(isset($_GET['from_date'])){ echo $_GET['from_date']; } ?>">
                                <br>
                                <label>To</label>
                                <input type="date" name="to_date" value="<?php if(isset($_GET['to_date'])){ echo $_GET['to_date']; } ?>">
                            </div>

                            <br>
                            <h6>Budget</h6>
                            <hr>
                            <div>
                                <?//Create inputs for budget ?>
                                <label>Min</label>
                                <input type="number" name="min_budget" min="0" max="999999999" value="<?php if(isset($_GET['min_budget'])){ echo $_GET['min_budget']; } ?>" >
                                <br>
                                <label>Max</label>
                                <input type="number" name="max_budget" min="0" max="999999999" value="<?php if(isset($_GET['max_budget'])){ echo $_GET['max_budget']; } ?>">
                            </div>

                            <br>
                            <h6>Revenue</h6>
                            <hr>
                            <div>
                                <?//Create inputs for revenue ?>
                                <label>Min</label>
                                <input type="number" name="min_revenue" min="0" max="999999999" value="<?php if(isset($_GET['min_revenue'])){ echo $_GET['min_revenue']; } ?>">
                                <br>
                                <label>Max</label>
                                <input type="number" name="max_revenue" min="0" max="999999999" value="<?php if(isset($_GET['max_revenue'])){ echo $_GET['max_revenue']; } ?>">
                            </div>

                        </div>
                        <?//End card with a 'search' button ?>
                        <div class="card-footer">
                            <button type="submit" name="search" class="btn btn-primary btn-sm float-end">Search</button>
                        </div>
                    </div>
                </form>
                <br>
            </div>
            
            <?//New area to contain results ?>
            <div class="col-md-9 mt-3">
                <div class="card">
                    <div class="card-body row">
                        <h3>Results</h3>
                        <hr>
                        <?php

                            //If user clicks Search...
                            if(isset($_GET['search']))
                            {
                                //Run this function
                                filter_data();
                            }
                            
                            //Function that filters the data stored in database
                            function filter_data()
                            {
                                //Set connection
                                $conn = mysqli_connect("localhost", "root", "", "the_movie_database");

                                //Create variables
                                $genres = "";
                                $from_date = "";
                                $to_date = "";
                                $min_budget = "";
                                $max_budget = "";
                                $min_revenue = "";
                                $max_revenue = "";

                                //The variables are currently in array format, so 'implode' them to readable CSV format so queries can be executed
                                if(isset($_GET['genres']) && $_GET['genres'] !='') {
                                    $genres = implode(", ",$_GET['genres']);
                                }

                                //If get method inputs are set, set the variables used in this function

                                if(isset($_GET['from_date']) && $_GET['from_date'] !=''){
                                    $from_date = $_GET['from_date'];
                                }
                                if(isset($_GET['to_date']) && $_GET['to_date'] !=''){
                                    $to_date = $_GET['to_date'];
                                }
                                if(isset($_GET['min_budget']) && $_GET['min_budget'] !=''){
                                    $min_budget = $_GET['min_budget'];
                                }
                                if(isset($_GET['max_budget']) && $_GET['max_budget'] !=''){
                                    $max_budget = $_GET['max_budget'];
                                }
                                if(isset($_GET['min_revenue']) && $_GET['min_revenue'] !=''){
                                    $min_revenue = $_GET['min_revenue'];
                                }
                                if(isset($_GET['max_revenue']) && $_GET['max_revenue'] !=''){
                                    $max_revenue = $_GET['max_revenue'];
                                }

                                //Define SQL variables. These will be concatenated into functioning SQL queries
                                $genresSql = "(JSON_EXTRACT(genres, '$[0].id') IN ($genres) OR JSON_EXTRACT(genres, '$[1].id') IN ($genres) OR JSON_EXTRACT(genres, '$[2].id') IN ($genres))";
                                $yearsSql = "release_date BETWEEN '$from_date' AND '$to_date'";
                                $budgetSql = "budget BETWEEN '$min_budget' AND '$max_budget'";
                                $revenueSql = "revenue BETWEEN '$min_revenue' AND '$max_revenue'";
                                $where = " WHERE ";
                                $and = " AND ";
                                $end = " ORDER BY popularity DESC;";
                                $final_query = "";

                                $genres_only = "";
                                $genres_and_date = "";
                                $genres_and_budget = "";
                                $genres_and_revenue = "";
                                $genres_and_date_and_budget = "";
                                $genres_and_date_and_revenue = "";
                                $genres_and_budget_and_revenue = "";
                                $genres_and_date_and_budget_and_revenue = "";

                                $years_only = "";
                                $years_and_budget = "";
                                $years_and_revenue = "";
                                $years_and_budget_and_revenue = "";

                                $budget_only = "";
                                $budget_and_revenue = "";

                                $revenue_only = "";

                                //Start of the query selects relevant movie details to be displayed
                                $start = "SELECT id, title, poster_path, vote_average, tagline FROM movies";

                                //If 'genres' (array) is set
                                if(isset($_GET['genres']))
                                {
                                    if($from_date != "" && $to_date != "" && $min_budget != "" && $max_budget != "" && $min_revenue != "" && $max_revenue != "")
                                    {
                                        $genres_and_date_and_budget_and_revenue = $start.$where.$genresSql.$and.$yearsSql.$and.$budgetSql.$and.$revenueSql.$end;
                                        $final_query = $genres_and_date_and_budget_and_revenue;
                                    }
                                    else if($min_budget != "" && $max_budget != "" && $min_revenue != "" && $max_revenue != "")
                                    {
                                        $genres_and_budget_and_revenue = $start.$where.$genresSql.$and.$budgetSql.$and.$revenueSql.$end;
                                        $final_query = $genres_and_budget_and_revenue;
                                    }
                                    else if($from_date != "" && $to_date != "" && $min_budget != "" && $max_budget != "")
                                    {
                                        $genres_and_date_and_budget = $start.$where.$genresSql.$and.$yearsSql.$and.$budgetSql.$end;
                                        $final_query = $genres_and_date_and_budget;
                                    }
                                    else if($from_date != "" && $to_date != "" && $min_revenue != "" && $max_revenue != "")
                                    {
                                        $genres_and_date_and_revenue = $start.$where.$genresSql.$and.$yearsSql.$and.$revenueSql.$end;
                                        $final_query = $genres_and_date_and_revenue;
                                    }
                                    else if($from_date != "" && $to_date != "")
                                    {
                                        $genres_and_date = $start.$where.$genresSql.$and.$yearsSql.$end;
                                        $final_query = $genres_and_date;
                                    }
                                    else if($min_budget != "" && $max_budget != "")
                                    {
                                        $genres_and_budget = $start.$where.$genresSql.$and.$budgetSql.$end;
                                        $final_query = $genres_and_budget;
                                    }
                                    else if($min_revenue != "" && $max_revenue != "")
                                    {
                                        $genres_and_revenue = $start.$where.$genresSql.$and.$revenueSql.$end;
                                        $final_query .= $genres_and_revenue;
                                    }
                                    else
                                    {
                                        $genres_only .= $start.$where.$genresSql.$end;
                                        $final_query .= $genres_only;
                                    }
                                }

                                //If date is set
                                else if($from_date != "" && $to_date != "")
                                {
                                    if(isset($_GET['genres']) && $min_budget != "" && $max_budget != "" && $min_revenue != "" && $max_revenue != ""){
                                        $genres_and_date_and_budget_and_revenue = $start.$where.$genresSql.$and.$yearsSql.$and.$budgetSql.$and.$revenueSql.$end;
                                        $final_query = $genres_and_date_and_budget_and_revenue;
                                    }
                                    else if($min_budget != "" && $max_budget != "" && $min_revenue != "" && $max_revenue != ""){
                                        $years_and_budget_and_revenue = $start.$where.$yearsSql.$and.$budgetSql.$and.$revenueSql.$end;
                                        $final_query = $years_and_budget_and_revenue;
                                    }
                                    else if(isset($_GET['genres']) && $min_budget != "" && $max_budget != ""){
                                        $genres_and_date_and_budget = $start.$where.$genresSql.$and.$yearsSql.$and.$budgetSql.$end;
                                        $final_query = $genres_and_date_and_budget;
                                    }
                                    else if(isset($_GET['genres']))
                                    {
                                        $genres_and_date = $start.$where.$genresSql.$and.$yearsSql.$end;
                                        $final_query = $genres_and_date;
                                    }
                                    else if($min_budget != "" && $max_budget != "")
                                    {
                                        $years_and_budget = $start.$where.$yearsSql.$and.$budgetSql.$end;
                                        $final_query = $years_and_budget;
                                    }
                                    else if($min_revenue != "" && $max_revenue != "")
                                    {
                                        $years_and_revenue = $start.$where.$yearsSql.$and.$revenueSql.$end;
                                        $final_query = $years_and_revenue;
                                    }
                                    else
                                    {
                                        $years_only .= $start.$where.$yearsSql.$end;
                                        $final_query .= $years_only;
                                    }
                                }

                                //If budget is set
                                else if($min_budget != "" && $max_budget != "")
                                {
                                    if(isset($_GET['genres']) && $from_date != "" && $to_date != "" && $min_revenue != "" && $max_revenue != ""){
                                        $genres_and_date_and_budget_and_revenue = $start.$where.$genresSql.$and.$yearsSql.$and.$budgetSql.$and.$revenueSql.$end;
                                        $final_query = $genres_and_date_and_budget_and_revenue;
                                    }
                                    else if(isset($_GET['genres']) && $from_date != "" && $to_date != "" && $min_revenue != "" && $max_revenue != ""){
                                        $years_and_budget_and_revenue = $start.$where.$yearsSql.$and.$budgetSql.$and.$revenueSql.$end;
                                        $final_query = $years_and_budget_and_revenue;
                                    }
                                    else if(isset($_GET['genres']) && $from_date != "" && $to_date != ""){
                                        $genres_and_date_and_budget = $start.$where.$genresSql.$and.$yearsSql.$and.$budgetSql.$end;
                                        $final_query = $genres_and_date_and_budget;
                                    }
                                    else if(isset($_GET['genres']))
                                    {
                                        $genres_and_budget = $start.$where.$genresSql.$and.$budgetSql.$end;
                                        $final_query = $genres_and_budget;
                                    }
                                    else if($from_date != "" && $to_date != "")
                                    {
                                        $years_and_budget = $start.$where.$yearsSql.$and.$budgetSql.$end;
                                        $final_query = $years_and_budget;
                                    }
                                    else if($min_revenue != "" && $max_revenue != "")
                                    {
                                        $budget_and_revenue = $start.$where.$budgetSql.$and.$revenueSql.$end;
                                        $final_query = $budget_and_revenue;
                                    }
                                    else {
                                        $budget_only .= $start.$where.$budgetSql.$end;
                                        $final_query .= $budget_only;
                                    }
                                }

                                //If revenue is set
                                else if($min_revenue != "" && $max_revenue != "")
                                {
                                    if(isset($_GET['genres']) && $from_date != "" && $to_date != "" && $min_budget != "" && $max_budget != ""){
                                        $genres_and_date_and_budget_and_revenue = $start.$where.$genresSql.$and.$yearsSql.$and.$budgetSql.$and.$revenueSql.$end;
                                        $final_query = $genres_and_date_and_budget_and_revenue;
                                    }
                                    else if(isset($_GET['genres']) && $min_budget != "" && $max_budget != ""){
                                        $genres_and_budget_and_revenue = $start.$where.$genresSql.$and.$budgetSql.$and.$revenueSql.$end;
                                        $final_query = $genres_and_budget_and_revenue;
                                    }
                                    else if(isset($_GET['genres']) && $from_date != "" && $to_date != ""){
                                        $genres_and_date_and_revenue = $start.$where.$genresSql.$and.$yearsSql.$and.$revenueSql.$end;
                                        $final_query = $genres_and_date_and_revenue;
                                    }
                                    else if(isset($_GET['genres']))
                                    {
                                        $genres_and_revenue = $start.$where.$genresSql.$and.$revenueSql.$end;
                                        $final_query = $genres_and_revenue;
                                    }
                                    else if($from_date != "" && $to_date != "")
                                    {
                                        $years_and_revenue = $start.$where.$yearsSql.$and.$revenueSql.$end;
                                        $final_query = $years_and_revenue;
                                    }
                                    else if($min_budget != "" && $max_budget != "")
                                    {
                                        $budget_and_revenue = $start.$where.$budgetSql.$and.$revenueSql.$end;
                                        $final_query = $budget_and_revenue;
                                    }
                                    else {
                                        $revenue_only = $start.$where.$revenueSql.$end;
                                        $final_query = $revenue_only;
                                    }
                                }
                                //If nothing is set, search all movies regardless
                                else
                                {
                                    $final_query = $start.$end;
                                }

                                //Set query to be executed to 'final_query'
                                $query = "$final_query";
                                $result = mysqli_query($conn, $query);
                                $n = mysqli_num_rows($result);

                                //If result..
                                if($n > 0)
                                {
                                    //For loop to select the 18 most popular results
                                    for($i=0; $i<18; $i++){
                                        $row = mysqli_fetch_assoc($result);
                                        ?>
                                        <div class="col-md-4 mt-3">
                                            <div class="card">
                                                <div class="card-body">
                                                <img src="https://image.tmdb.org/t/p/original<?= $row['poster_path']; ?>" class="card-img-top">
                                                <hr>
                                                    <h7 class="card-title">Rating: <?= $row['vote_average']; ?>/10</h7>
                                                    <hr>
                                                    <h5 class="card-title"><?= $row['title']; ?></h5>
                                                    <h7 class="card-title"><?= $row['tagline']; ?></h7>
                                                    <hr>
                                                    <?//Search button posts movie id to another page which displays full details of selected movie ?>
                                                    <form action="movie.php" method="POST">
                                                        <button class="btn btn-primary btn-sm" name="id" value="<?=$row['id'];?>">See details</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                }
                                else
                                {
                                    echo "<br><br><h2>No results!</h2>";
                                }

                        
                            }

                        ?>
            </div>

        </div>
        <br>
    </div>


</body>

</html>