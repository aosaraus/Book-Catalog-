<?php
require_once('database.php');  

$book = $_GET['book'];
$bookimg = 'images/' .$book. '.jpg';
$queryBook = "Select GROUP_CONCAT(DISTINCT course.CourseID SEPARATOR ', ') as courseID, GROUP_CONCAT(DISTINCT course.courseTitle SEPARATOR ', ') as courseTitle, course.credit, book.bookTitle, book.price, GROUP_CONCAT(DISTINCT author.firstName, ' ', author.lastName SEPARATOR ', ') as name, publisher.publisher, book.edition, book.publishdate, book.length, book.isbn13, book.description
              From book 
              INNER JOIN coursebook on book.isbn13 = coursebook.book               
              INNER JOIN course on course.courseID = coursebook.course
              INNER JOIN authorbook on book.isbn13 = authorbook.book
              INNER JOIN author on authorbook.author = author.authorID
              INNER JOIN publisher on publisher.publisherID = book.publisher
              Where book.isbn13 = $book";
$statement = $db->prepare($queryBook);
$statement->execute(); 
$details = $statement->fetch(); 
$statement->closeCursor();

             

?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title> Book Detail </title>
    </head>
    <style>
        table 
        {
           border-collapse: collapse;
           width: 60%;
           margin: auto;
        }
        td
        {
             border: 1px solid black;
             
        }
        h1
        {
            text-align: center;
        }
    </style>
    <body>
        <h1> Book Details </h1>
        <table>
           
            <tr>
                
                <td align = "center"><img src= "<?php echo $bookimg?>"> </td>
                <td>For course: <?php echo $details['courseID']. ' '. $details['courseTitle']?> ( <?php echo $details['credit'] ?> ) <br>
                    Book Title: <?php echo $details['bookTitle'] ?> <br>
                    Price: $<?php echo $details['price']?><br>
                    Author(s): <?php echo $details['name']?><br>
                    Publisher: <?php echo $details['publisher'] ?><br>
                    Edition: <?php echo $details['edition'] . ' edition (' . $details['publishdate'] .')' ?><br>
                    Length: <?php echo $details['length']?> pages <br>
                    ISBN-13: <?php echo $details['isbn13']?><br>
                </td>
                
            </tr>
            <tr>
                <td colspan = 2> Product Description: <br> <?php echo $details['description']?> </td>
            </tr>
        </table>
    </body>
</html>
