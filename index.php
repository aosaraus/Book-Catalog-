
<?php
require_once('database.php');
//sort by courseID
$sort = array('courseID', 'price');
$order = 'courseID';

if(isset($_GET['sort']) && in_array($_GET['sort'],$sort)){
    $order = $_GET['sort'];
}

$page = 1; 
if (isset($_GET['page'])){
    $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
}

$items_per_page = 6; 

$offset = ($page - 1) * $items_per_page; 

$queryAll = "Select course.courseID, course.courseTitle,  group_concat(book.isbn13), group_concat(book.bookTitle), group_concat(book.price)
                From book 
                INNER JOIN coursebook on book.isbn13 = coursebook.book 
                INNER JOIN course on course.courseID = coursebook.course
                group by courseID
                ORDER BY $order";
                
$statement = $db->prepare($queryAll);
$statement->execute(); 
$numbers = $statement->fetchAll(); 
$statement->closeCursor();

$queryLimit = "Select course.courseID, course.courseTitle, group_concat(book.isbn13 SEPARATOR ','), group_concat(book.bookTitle SEPARATOR ', '), group_concat(book.price SEPARATOR ', $')
                FROM book
                INNER JOIN coursebook on book.isbn13 = coursebook.book 
                INNER JOIN course on course.courseID = coursebook.course
                GROUP BY courseID
                ORDER BY $order
                LIMIT $offset, $items_per_page";
                
$statement1 = $db->prepare($queryLimit);
$statement1->execute(); 
$books = $statement1->fetchAll(); 
$statement1->closeCursor();


?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title> Book Catalog</title>
    </head>
    <style>
        h1
        {
            text-align: center;
        }
        table 
        {
           border-collapse: collapse;
           width: 100%;
        }
        table, th, td
        {
            border: 1px solid black;
        }
        th, td 
        {
          
           text-align: center;
        }
    </style>
    <body>
        <main>
            
            <h1> CIS Department Book Catalog </h1> 
                 
               
                <table>
                    <tr>
                        <th><a href='?sort=courseID'> Course # </a></th>
                        <th> Course Title </th> 
                        <th> Book Image </th>
                        <th> Book Title </th>
                        <th><a href='?sort=price'> Price</a> </th>
                    </tr>
                    <?php $count = 0; ?>
                    <?php foreach ($numbers as $number) { ?>
                        <?php $count++; ?>
                    <?php } ?>
                    
                    <?php foreach ($books as $book) { ?>
                    
                    <tr>
                        
                        
                       
                        <td><?php echo '<a href = "http://www.cpp.edu/~cba/computer-information-systems/curriculum/courses.shtml"> '.$book[0];' </a>' ?></td>
                        <td><?php echo $book[1]; ?></td>
                        
                        <?php 
                        $searchString = ',';
                        if(strpos($book[2], $searchString) !== false){
                            $string = explode(",", $book[2]);
                            $image1 = 'images/'. $string[0] . '.jpg';
                            $image2 = 'images/'. $string[1] . '.jpg';?>
                        <td align="center"> <a href = "bookDetail.php?book=<?php echo $string[0]?>"> <img src= "<?php echo $image1 ?>" </a> <br> <a href = "bookDetail.php?book=<?php echo $string[1]?>"> <img src= "<?php echo $image2 ?>" </a></td>
                            
                        <?php } else {
                            $image = 'images/' .$book[2] . '.jpg'; ?>
                            <td align="center"> <a href = "bookDetail.php?book=<?php echo $book[2]?>"> <img src= "<?php echo $image ?>" </a></td>
                        <?php }
                        ?>
                        
                        
                        <td><?php echo $book[3]; ?></td>
                        <td align="right"><?php echo "$" . $book[4]; ?></td>
                    </tr>
                    <?php } ?>
                </table>
             
               <?php 
               $pages = ceil($count/6); 
               $page = 1;
              
               while ($pages >= 1) { ?>
                   <a href = "?page=<?php echo $page?>"> <?php echo $page?></a>
                   &nbsp;
                   <?php $pages--; ?>
                   <?php $page++; ?>
               <?php } ?>               
                
        </main>
        
    </body>
</html>
