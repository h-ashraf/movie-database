<!DOCTYPE html>
<html>

<?php
    //Include header
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
                        <?//Create input for actor name ?>
                        <div class="card-body">
                            <h6>Director Name</h6>
                            <hr>
                            <div>
                                <h9>(e.g Quentin Tarantino)</h9>
                                <input type="text" name="director_name" value="<?php if(isset($_GET['director_name'])){ echo $_GET['director_name']; } ?>" >
                                <br>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" name="search" class="btn btn-primary btn-sm float-end">Search</button>
                        </div>
                </div>
            </form>
                <br>
            </div>

            <?//Create area to display results?>
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
                                $director_name = "";
                                $select_director_details_start = "";

                                //If director name input is set, set the variables used in this function
                                if(isset($_GET['director_name']) && $_GET['director_name'] !='') {
                                    $director_name = $_GET['director_name'];
                                }

                                //Set SQL variables
                                $directorSql = "(SELECT JSON_UNQUOTE(JSON_EXTRACT(crew, CONCAT('$[', seq_0_to_100.seq, '].job')))) LIKE 'Director' AND (SELECT JSON_UNQUOTE(JSON_EXTRACT(crew, CONCAT('$[', seq_0_to_100.seq, '].name')))) LIKE '%$director_name%'";
                                $select_director_details_start = "SELECT DISTINCT JSON_UNQUOTE(JSON_EXTRACT(crew, CONCAT('$[', seq_0_to_100.seq, '].id'))) AS director_id, JSON_UNQUOTE(JSON_EXTRACT(crew, CONCAT('$[', seq_0_to_100.seq, '].name'))) AS director_name, JSON_UNQUOTE(JSON_EXTRACT(crew, CONCAT('$[', seq_0_to_100.seq, '].profile_path'))) AS director_photo FROM credits JOIN seq_0_to_100";
                                
                                $where = " WHERE ";
                                $and = " AND ";
                                $end = ";";
                                $final_query = "";

                                //If director name is set..
                                if($director_name != "")
                                {
                                    //Concatenate query and set final_query
                                    $select_director_names = $select_director_details_start.$where.$directorSql.$end;
                                    $final_query = $select_director_names;
                                }
                                else
                                {
                                    $final_query = $select_director_details_start.$end;
                                }

                                //Define query to be executed
                                $query = "$final_query";
                                $result = mysqli_query($conn, $query);
                                $n = mysqli_num_rows($result);

                                //If result..
                                if($n > 0)
                                {
                                    //For loop displays results from database within cards
                                    for($i=0; $i<$n; $i++){
                                        $row = mysqli_fetch_assoc($result);
                                        ?>
                                        <div class="col-md-4 mt-3">
                                            <div class="card">
                                                <div class="card-body">
                                                <img src="https://image.tmdb.org/t/p/original<?= $row['director_photo']; ?>" class="card-img-top">
                                                    <h6 class="card-title"><?= $row['director_name']; ?></h5>
                                                    <form action="director.php" method="POST">
                                                        <button class="btn btn-primary btn-sm" name="id" value="<?=$row['director_id'];?>">See details</button>
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
                            
                            ?>
                        </div>
                        <?//Create area to display the films that starred selected actor(s)?>
                        <div class="card-body row">
                            <h3>Films directed by '<?php echo $director_name; ?>'</h3>
                            <hr>
                            <?php

                            //Set query to fetch movie details
                            $query2 = "SELECT m.id, m.title, m.poster_path, m.vote_average, m.tagline FROM movies m INNER JOIN credits c USING (id) WHERE JSON_EXTRACT(crew, '$[*].name') LIKE '%$director_name%'";
                            $result2 = mysqli_query($conn, $query2);
                            $n2 = mysqli_num_rows($result2);

                            //If result...
                            if($n2 > 0)
                            {
                                //Display movie details
                                for($i=0; $i<$n2; $i++)
                                {
                                    $row = mysqli_fetch_assoc($result2);
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
                                                <form action="movie.php" method="POST">
                                                    <button class="btn btn-primary btn-sm" name="id" value="<?=$row['id'];?>">See details</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                            }
                            else {
                                echo "<br><br><h2>No results</h2>";
                            }

                        }
                    ?>
        </div>
    </div>
</div>