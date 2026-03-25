<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "book_inventory";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully"; //Debugger

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (
        empty($_POST['title']) ||
        empty($_POST['author_id']) ||
        empty($_POST['isbn']) ||
        empty($_POST['publication_year']) ||
        empty($_POST['publisher']) ||
        empty($_POST['genre'])
    ) {
        $error = "Please fill in all required fields.";
    } else {
        // Get form values
$title = $_POST['title'];
$author_id = $_POST['author_id'];
$isbn = $_POST['isbn'];
$year = $_POST['publication_year'];
$publisher = $_POST['publisher'];
$genre = $_POST['genre'];

// Prepare SQL (prevents SQL injection)
$stmt = $conn->prepare("
    INSERT INTO books (title, author_id, isbn, publication_year, publisher, genre_id)
    VALUES (?, ?, ?, ?, ?, ?)
");

$stmt->bind_param("sissss", $title, $author_id, $isbn, $year, $publisher, $genre);

if ($stmt->execute()) {
    echo "<p style='color:green;'>Book added successfully!</p>";
} else {
    echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
}

$stmt->close();
    }
}

$sql = "SELECT author_id, author_name FROM authors";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

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
    padding-top: 5px;
}

.button1 {
    border: 2px solid #008CBA;
    float: right;
}

.collapsible {
  background-color: #3349a8ff;
  color: white;
  cursor: pointer;
  padding: 18px;
  width: 100%;
  border: none;
  text-align: left;
  outline: none;
  font-size: 15px;
}

.active, .collapsible:hover {
  background-color: #3349a8ff;
}

.content {
  padding: 0 18px;
  display: none;
  overflow: hidden;
  background-color: #f1f1f1;
}
</style>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>



<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<?php if (!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>
<!-- Above makes sure that, if form is empty, error. Otherwise, success-->



<button type="button" class="collapsible">Add New Book</button>
<div class="content">
<form action="" method="post">

<br />
<label for="title">Book Title:</label> 
<input type="text" name="title" value="<?php echo $_POST['title'] ?? ''; ?>" required />
<br /> 

<br /> 
<label for="author_id">Author Name:</label> 
<select name="author_id" id="author_id" required>
    <option value="">-- Select an Author --</option>
    <?php
    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $row['author_id'] . "'>" . htmlspecialchars($row['author_name']) . "</option>";
        }
    } else {
        echo "<option value=''>No authors found</option>";
    }
    ?>
</select>

<br /> 

<br /> <label for="isbn">ISBN</label> 
<input type="text" name="isbn" value="<?php echo $_POST['isbn'] ?? ''; ?>" required />
<br /> 

<br /> 
<label for="publication_year">Year of Publication:</label> 
<input type="text" name="publication_year" value="<?php echo $_POST['publication_year'] ?? ''; ?>" required />

<br /> <label for="publisher">Book Publisher:</label> 
<input type="text" name="publisher" value="<?php echo $_POST['publisher'] ?? ''; ?>" required />




  <label for="genre">Genre of the book:</label>
  <select name="genre" required>
  <option value="" disabled selected>Choose a Genre</option>
  <?php
  $genres = ["Biographical","Children's","Historical","Horror","Mystery","Non-Fiction","Sci-Fi","Young Adult","Fantasy"];
  foreach ($genres as $g) {
      $selected = (isset($_POST['genre']) && $_POST['genre'] == $g) ? "selected" : "";
      echo "<option value='$g' $selected>$g</option>";
  }

  
  ?>
</select>

  <br><br>
  <input type="submit" value="Submit">
</form>
</div>

    <div id="box">

</div>


<script>
var coll = document.getElementsByClassName("collapsible");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var content = this.nextElementSibling;
    if (content.style.display === "block") {
      content.style.display = "none";
    } else {
      content.style.display = "block";
    }
  });
}
</script>

</body>
</html>