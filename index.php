<?php
  $insert=false;
  $update=false;
  $delete=false;
//connecting to the database
  $servername="localhost";
  $username="root";
  $password="";
  $db="notes";

  $conn=mysqli_connect($servername,$username,$password,$db);

  if(!$conn){
    die("sorry we failed to connect".mysqli_connect_error($conn));
  }
  if(isset($_GET['delete'])){
    $sno=$_GET['delete'];
    $delete=true;
    $sql="DELETE FROM `notes` WHERE `Sno`=$sno";
    $result=mysqli_query($conn,$sql);
  }
  if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST['snoEdit'])){
      //update the record
      $sno=$_POST["snoEdit"];
      $title=$_POST["titleEdit"];
      $description=$_POST["descriptionEdit"];

      $sql="UPDATE `notes` SET `title` = '$title', `description` = '$description' WHERE `notes`.`Sno` = '$sno'";
      $result=mysqli_query($conn,$sql);
      if($result){
        $update=true;
      }
      else{
        echo "we could not update the note successfully";
      }
    }
    else{
      $title=$_POST["title"];
      $description=$_POST["description"];

      $sql="INSERT INTO `notes` (`Sno`, `title`, `description`, `tstamp`) 
      VALUES (NULL, '$title', '$description', current_timestamp());";
      $result=mysqli_query($conn,$sql);
      if($result){
        // echo "the record has been successfully inserted";
        $insert=true;
      }
      else {
        echo "failed to insert record ".mysqli_error($conn);
      }
    }

  }
 
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>iNotes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
    
  </head>
  <body>

  <!-- editModal -->
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Edit your Note</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="/CRUD/index.php" method="POST">
            <div class="modal-body">
            <input type="hidden" name="snoEdit" id="snoEdit">
              <div class="mb-3">
                <label for="title" class="form-label">Note Title</label>
                <input type="text" class="form-control" id="titleEdit" name="titleEdit" aria-describedby="emailHelp">
              </div>
              <div class="mb-3">
                <label for="description" class="form-label">Note Description</label>
                <textarea class="form-control" id="descriptionEdit" name="descriptionEdit" rows="3"></textarea>
              </div>
              </div>
          <div class="modal-footer d-block mr-auto">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
    
    <nav class="navbar navbar-expand-lg  navbar-dark bg-dark">
        <div class="container-fluid">
          <a class="navbar-brand" href="#"><img src="/CRUD/logo.jpeg" height="34px" alt="img"></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">About</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Contact Us</a>
              </li>

            </ul>
            <form class="d-flex" role="search">
              <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
              <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
          </div>
        </div>
      </nav>
      <?php
        if($insert){
          echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
          <strong>Success!</strong> Your note has been added successfully.
          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
        }

        if($update){
          echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
          <strong>Success!</strong> Your note has been updated successfully.
          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
        }

        if($delete){
          echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
          <strong>Success!</strong> Your note has been deleted successfully.
          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
        }
      ?>
      <div class="container my-4">
        <h2>Add a Note to iNotes</h2>
        <form action="/CRUD/index.php" method="POST">
            <div class="mb-3">
              <label for="title" class="form-label">Note Title</label>
              <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
              <label for="description" class="form-label">Note Description</label>
              <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Note</button>
          </form>
      </div>

      <div class="container my-4">

      <table class="table" id="mytable" >
        <thead>
          <tr>
            <th scope="col">Sno</th>
            <th scope="col">Title</th>
            <th scope="col">Description</th>
            <th scope="col">Actions </th>
          </tr>
        </thead>
        

        <tbody>
        <?php
              $sql="SELECT * FROM `notes`";
              $result=mysqli_query($conn,$sql);
              $Sno=0;
              while($rows=mysqli_fetch_assoc($result)) {
                $Sno=$Sno+1;
                echo "<tr>
            <th scope='row'>". $Sno ."</th>
            <td>". $rows['title'] ."</td>
            <td>". $rows['description'] ."</td>
            <td> <button class='edit btn btn-sm btn-primary' id=". $rows['Sno'] .">Edit</button> 
            <button class='delete btn btn-sm btn-primary' id=". $rows['Sno'] .">Delete</button> </td>
          </tr>";
              }
        ?>
    
        </tbody>
      </table>
      </div>
      <hr>

    <!--  -->
    <script>
      edits=document.getElementsByClassName('edit');
      Array.from(edits).forEach((element)=>{
        element.addEventListener("click",(e)=>{
          tr=e.target.parentNode.parentNode;
          title=tr.getElementsByTagName("td")[0].innerText;
          description=tr.getElementsByTagName("td")[1].innerText;
          titleEdit.value=title;
          descriptionEdit.value=description;
          snoEdit.value=e.target.id;
          $('#editModal').modal('toggle');
        })
      })

      deletes=document.getElementsByClassName('delete');
      Array.from(deletes).forEach((element)=>{
        element.addEventListener("click",(e)=>{
          sno=e.target.id.substr(1,);
          if(confirm("press OK to delete the note!")){
            window.location=`/CRUD/index.php?delete=${sno}`;
          }
          else{
          }
          
        })
      })
    </script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script
      src="https://code.jquery.com/jquery-2.2.4.js"
      integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI="
      crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
     <!-- <script>
      $(document).ready( function () {
        $('#mytable').DataTable();
      });
    </script>  -->
  </body>
</html>