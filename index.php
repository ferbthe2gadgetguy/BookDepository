<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "book_inventory";


$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully"; //Debugger
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
    table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
    margin-left: auto;
    margin-right: auto;
    text-align: center;
    }
    th, td {
    padding: 15px;
    }
    th {
        background-color: #3366cc;
        color: white;
        cursor: pointer;
    }

.button {
    background-color: #008CBA;
    border-radius: 8px;
    border: none;
    color: black;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    color: white;
}

.button1 {
    border: 2px solid #008CBA;
    float: right;
}
</style>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php echo "Hello, World!"; ?> 

    
    <a href="http://localhost/bookLibrary/submission.php">
   <button class="button button1">Insert new book</button></a>

    <table id="myTable" class="table table-dark">
    <thead>
        <tr>
        <th onclick="sortTable(0)">ID <span></span></th>
        <th onclick="sortTable(1)">Book Title <span></span></th>
        <th onclick="sortTable(2)">Author <span></span></th>
        <th onclick="sortTable(3)">Genre <span></span></th>
        <th onclick="sortTable(4)">ISBN <span></span></th>
        <th onclick="sortTable(5)">Year <span></span></th>
        <th onclick="sortTable(6)">Publisher <span></span></th>
        </tr>
    </thead>
    <tbody>
           <?php
           $query = "
            SELECT books.*, authors.author_name AS author_name
            FROM books
            JOIN authors ON books.author_id = authors.author_id
            ORDER BY title ASC
            ";
            $result = mysqli_query($conn, $query);

            if (!$result) {
                die("Query failed: " . mysqli_error($conn));
            }

           while($row = mysqli_fetch_assoc($result))
           {
            ?>
            <tr>
                <td><?php echo $row['book_id'] ?></td>
                <td><?php echo $row['title'] ?></td>
                <td><?php echo $row['author_name'] ?></td>
                <td><?php echo $row['genre_id'] ?></td>
                <td><?php echo $row['isbn'] ?></td>
                <td><?php echo $row['publication_year'] ?></td>
                <td><?php echo $row['publisher'] ?></td>
            </tr>

            <!-- <td><a href="updateVoters.php?id=<?php echo $row['voterID'] ?>" class ="btn btn-success">Edit</a></td> --> 
            <!-- <td><a href="deleteVoters.php?id=<?php echo $row['voterID'] ?>" class ="btn btn-danger">Delete</a></td> -->
            <!-- Comments are leftovers for CRUD operations -->
                      
            <?php
           }
           ?>
    </tbody>
    </table>

<script>
let sortDirection = {};

function sortTable(n) {
  const table = document.getElementById("myTable");
  let rows = Array.from(table.rows).slice(1);
  let dir = sortDirection[n] === "asc" ? "desc" : "asc";
  sortDirection[n] = dir;

  rows.sort((a, b) => {
    let x = a.cells[n].innerText.toLowerCase();
    let y = b.cells[n].innerText.toLowerCase();

    // Detect numbers
    if (!isNaN(x) && !isNaN(y)) {
      return dir === "asc" ? x - y : y - x;
    }

    return dir === "asc"
      ? x.localeCompare(y)
      : y.localeCompare(x);
  });

  rows.forEach(row => table.tBodies[0].appendChild(row));

  // Update arrows
  document.querySelectorAll("th span").forEach(span => span.innerHTML = "");
  const arrow = dir === "asc" ? " ▲" : " ▼";
  table.rows[0].cells[n].querySelector("span").innerHTML = arrow;
}
</script>
</body>
</html>