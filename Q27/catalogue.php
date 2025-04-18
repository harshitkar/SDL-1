<?php
// Connect to database
$conn = new mysqli('localhost', 'root', '', 'book_store');
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Handle Add Book
if (isset($_POST['add_book'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("INSERT INTO books (title, author, price, description) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $title, $author, $price, $description);
    $stmt->execute();
    header('Location: catalogue.php');
    exit();
}

// Handle Delete Book
if (isset($_GET['delete'])) {
    $book_id = $_GET['delete'];
    $conn->query("DELETE FROM books WHERE id = $book_id");
    header('Location: catalogue.php');
    exit();
}

// Handle Borrow Book
if (isset($_POST['borrow_book'])) {
    $book_id = $_POST['book_id'];
    $borrower_name = $_POST['borrower_name'];

    $stmt = $conn->prepare("UPDATE books SET borrowed_by_name = ? WHERE id = ?");
    $stmt->bind_param("si", $borrower_name, $book_id);
    $stmt->execute();
    header('Location: catalogue.php');
    exit();
}

// Handle Return Book
if (isset($_GET['return'])) {
    $book_id = $_GET['return'];
    $conn->query("UPDATE books SET borrowed_by_name = NULL WHERE id = $book_id");
    header('Location: catalogue.php');
    exit();
}

// Fetch all books
$result = $conn->query("SELECT * FROM books");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Books Catalogue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

    <h1>📚 Books Catalogue</h1>

    <!-- Add Book Form -->
    <div class="mb-4">
        <h4>Add New Book</h4>
        <form method="POST" class="row g-2">
            <div class="col-md-3">
                <input type="text" name="title" class="form-control" placeholder="Title" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="author" class="form-control" placeholder="Author" required>
            </div>
            <div class="col-md-2">
                <input type="number" step="0.01" name="price" class="form-control" placeholder="Price" required>
            </div>
            <div class="col-md-4">
                <input type="text" name="description" class="form-control" placeholder="Description">
            </div>
            <div class="col-12">
                <button type="submit" name="add_book" class="btn btn-primary">Add Book</button>
            </div>
        </form>
    </div>

    <!-- Display Books -->
    <div class="row">
        <?php while($book = $result->fetch_assoc()): ?>
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($book['title']); ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted">By <?php echo htmlspecialchars($book['author']); ?></h6>
                        <p class="card-text"><?php echo htmlspecialchars($book['description']); ?></p>
                        <p><strong>Price: $<?php echo $book['price']; ?></strong></p>
                        <?php if ($book['borrowed_by_name']): ?>
                            <p class="text-danger">Borrowed by: <?php echo htmlspecialchars($book['borrowed_by_name']); ?></p>
                            <a href="catalogue.php?return=<?php echo $book['id']; ?>" class="btn btn-warning btn-sm">Return Book</a>
                        <?php else: ?>
                            <p class="text-success">Available</p>

                            <!-- Borrow Form -->
                            <form method="POST" class="d-flex">
                                <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                                <input type="text" name="borrower_name" class="form-control form-control-sm me-2" placeholder="Enter Borrower's Name" required>
                                <button type="submit" name="borrow_book" class="btn btn-success btn-sm">Borrow</button>
                            </form>
                        <?php endif; ?>
                        <a href="catalogue.php?delete=<?php echo $book['id']; ?>" class="btn btn-danger btn-sm mt-2" onclick="return confirm('Delete this book?')">Delete</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

</body>
</html>
