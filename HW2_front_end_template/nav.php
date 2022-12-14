<?php
session_start();
if($_SESSION['Authenticated']==false)
{
    header("Location: index.html");
    exit();
}
echo <<<EOT
    <!doctype html>
    <html lang="en">


    <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">

      <!-- Bootstrap CSS -->

      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
      <title>Hello, world!</title>
      <style>
      .right{
        color:rgb(240, 240, 240);
      background-color: #008CBA;
        font-size: 20px;
        float: right;
      border-radius: 20px;
      }
      </style>
    </head>

    <body>
    </form>
      <nav class="navbar navbar-inverse">
        <div class="container-fluid">
      <button class="right" onclick="document.location='logout.php'">Log out</button>
          <div class="navbar-header">
            <a class="navbar-brand " href="#">Ober Eat</a>
          </div>

        </div>
      </nav>
      <div class="container">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#home">Home</a></li>
          <li><a href="#shop">shop</a></li>
        <li><a href="#myOrder">My Order</a></li>
        <li><a href="#shopOrder">Shop Order</a></li>
        <li><a href="#transaction">Transaction Record</a></li>


        </ul>

        <div class="tab-content">
          <div id="home" class="tab-pane fade in active">
            <h3>Profile</h3>
            <div class="row">
              <div class="col-xs-12">
          <iframe src="userinfo.php" width="100%" height="75" style="border:none"  scrolling= "no" ></iframe>
          <br>
                <button type="button " style="margin-left: 5px;" class=" btn btn-info " data-toggle="modal"
                data-target="#location">edit location</button>
                <!--  -->
                <div class="modal fade" id="location"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                  <div class="modal-dialog  modal-sm">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">edit location</h4>
                      </div>
              <form action="updateLocation.php" method="post">
                        <div class="modal-body">
                          <label class="control-label " for="latitude">latitude</label>
                          <input type="text" name="latitude" class="form-control" id="latitude" placeholder="enter latitude">
                            <br>
                          <label class="control-label " for="longitude">longitude</label>
                          <input type="text" name="longitude" class="form-control" id="longitude" placeholder="enter longitude">
                        </div>
                        <div class="modal-footer">
                          <input type="submit" class="btn btn-default" value="edit">
                        </div>
                </form>
                    </div>
                  </div>
                </div>

                <!--  -->
          <br>
                <iframe src="balanceinfo.php" width="100%" height="35" style="border:none"  scrolling= "no" ></iframe>
          <br>
                <!-- Modal -->
                <button type="button " style="margin-left: 5px;" class=" btn btn-info " data-toggle="modal"
                  data-target="#myModal">Add value</button>
                <div class="modal fade" id="myModal"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                  <div class="modal-dialog  modal-sm">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add value</h4>
                      </div>
              <form action="updateBalance.php" method="post">
                      <div class="modal-body">
                        <input type="text" name="value" class="form-control" id="value" placeholder="enter add value">
                      </div>
                      <div class="modal-footer">
                        <input type="submit" class="btn btn-default" value="Add">
                      </div>
            </form>
                    </div>
                  </div>
                </div>
              </div>

            </div>

            <!-- 
                    
                -->
            <h3>Search</h3>
            <div class=" row  col-xs-8">
              <form class="form-horizontal" action="redirect.php" method="post">
                <div class="form-group">
                  <label class="control-label col-sm-1" for="s_shop">Shop</label>
                  <div class="col-sm-5">
                    <input name="s_shop" id="s_shop" type="text" class="form-control" placeholder="Enter Shop name">
                  </div>
                  <label class="control-label col-sm-1" for="distance">distance</label>
                  <div class="col-sm-5">
                    <select name="distance" class="form-control" id="distance">
                      <option>near</option>
                      <option>medium </option>
                      <option>far</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
          
                  <label class="control-label col-sm-1" for="price_down">Price</label>
                  <div class="col-sm-2">
                    <input name="price_down" id="price_down" type="text" class="form-control" placeholder="Up">
                  </div>
                  <label class="control-label col-sm-1" for="price_up">~</label>
                  <div class="col-sm-2">
                    <input name="price_up" id="price_up" type="text" class="form-control" placeholder="Down">

                  </div>
                  <label class="control-label col-sm-1" for="Meal">Meal</label>
                  <div class="col-sm-5">
                    <input type="text" name="s_meal" class="form-control" id="s_meal" placeholder="Enter Meal">
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-sm-1" for="category"> category</label>     
                    <div class="col-sm-5">
                      <input name="category" id="category" type="text" class="form-control" id="category" placeholder="Enter shop category">
                    </div>
                    <input type="submit" style="margin-left: 18px" class="btn btn-primary" value="Search">
                </div>
              </form>
            </div>
            <iframe src="search.php" width="80%" height="400px"></iframe>
          </div>
          <div id="shop" class="tab-pane fade">
            <h3> Start a business </h3>
          <iframe src="registerCheck.php" width="150%" height="190" style="border:none"  scrolling= "no" ></iframe>
            <hr>
        
            <h3>ADD</h3>
            <div class="form-group ">
            <form action="product_add.php" method="post" Enctype="multipart/form-data">
              <div class="row">
                <div class="col-xs-6">
                  <label for="product_name">meal name</label>
                  <input class="form-control" id="product_name" type="text" name="product_name">
                </div>
              </div>
              <div class="row" style=" margin-top: 15px;">
                <div class="col-xs-3">
                  <label for="price">price</label>
                  <input class="form-control" id="price" type="text" name="price">
                </div>
                <div class="col-xs-3">
                  <label for="quantity">quantity</label>
                  <input class="form-control" id="quantity" type="text" name="quantity">
                </div>
              </div>
              <div class="row" style=" margin-top: 25px;">
                <div class=" col-xs-3">
                  <label for="myFile">????????????</label>
                  <input id="myFile" type="file" name="myFile" multiple class="file-loading">
                </div>
                <div class=" col-xs-3">
                  <input type="submit" class="btn btn-primary" value="Add">
                </div>
              </div>
            </form>
            </div>
            <iframe src="productinfo.php" width="80%" height="400px"></iframe>
          </div>
        <div id="myOrder" class="tab-pane fade">
        <iframe src="myOrder.php" width="100%" height="400px"></iframe>
        </div>
        <div id="shopOrder" class="tab-pane fade">
        <iframe src="shopOrder.php" width="100%" height="400px"></iframe>
        </div>
        <div id="transaction" class="tab-pane fade">
        <iframe src="transaction.php" width="100%" height="400px"></iframe>
        </div>


        </div>
      </div>

      <!-- Option 1: Bootstrap Bundle with Popper -->
      <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script> -->
      <script>
        $(document).ready(function () {
          $(".nav-tabs a").click(function () {
            $(this).tab('show');
          });
        });
      </script>

      <!-- Option 2: Separate Popper and Bootstrap JS -->
      <!--
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
        -->
    </body>

    </html>
EOT;
?>