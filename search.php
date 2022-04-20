<?php

require_once "header.php";
echo <<<_END

<br><br>
<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <div class="card shadow">
                <div class="card-header">
                    <h1>Advanced Search</h1>
                </div>
                <div class="card-body">
                    <h3>Search Options</h3>
                    <hr>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>Movie Search<h5>
                            </div>
                            <div class="card-body">
                                <p>Search for a movie based on subcategories</p>
                                <form action="movie-search.php">
                                    <button class="btn btn-primary btn-sm" href="">Search Movie</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>Actor Search<h5>
                            </div>
                            <div class="card-body">
                                <p>Search for an actor</p>
                                <form action="actor-search.php">
                                    <button class="btn btn-primary btn-sm" href="">Search Actor</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Director Search<h5>
                        </div>
                        <div class="card-body">
                            <p>Search for a director</p>
                            <form action="director-search.php">
                                <button class="btn btn-primary btn-sm" href="">Search Director</button>
                            </form>
                        </div>
                    </div>
                </div>
                </div>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>

<br><br><br><br><br>
_END;

require_once "footer.php";


?>