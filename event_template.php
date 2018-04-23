<?php
  include_once "header.php";
  require_once "conf.inc.php";
  require_once "functions.php";

  $db = connectDB();
?>
 
    <div class="container">

      <!-- Page Heading -->
      <center>
        <h1 class="my-4">
          <?php 
              $query = $db->prepare ("SELECT category_name FROM category"); 
              $query->execute();
              $result = $query->fetch();
              echo $result['category_name'];  
          ?>
        </h1> 
      </center>

      <!--Boucle Event -->
      <?php
        $data = array (1, 2, 3, 4, 5);
        foreach ($data as $value){
      ?>
        <div id="General" class="row">
          <div id="image" class="col-md-3">
            <a href="#">
              <img  
                    src= "img/"<?php 
                            $query = $db->prepare ("SELECT m_event_picture FROM m_event"); 
                            $query->execute();
                            $value = $query->fetch();
                            echo $value['m_event_picture'];  
                          ?>  
              >
            </a>
          </div>
          <div class="col-md-5">
            <h3>
              <?php 
                $query = $db->prepare ("SELECT m_event_title FROM m_event"); 
                $query->execute();
                $value = $query->fetch();
                echo $value['m_event_title'];  
              ?>
            </h3>
              <p>
                <?php 
                  $query = $db->prepare ("SELECT m_event_description FROM m_event"); 
                  $query->execute();
                  $value = $query->fetch();
                  echo $value['m_event_description'];  
                ?> 
              </p>
          </div>
          <div class="col-md-7">
            <a class="btn btn-primary" href="#">Voir l'événement</a>
          </div>
        </div>
      <?php 
        }
      ?>
      <!-- Pagination -->
      <ul class="pagination justify-content-center">
        <li class="page-item">
          <a class="page-link" href="#" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
            <span class="sr-only">Previous</span>
          </a>
        </li>
        <li class="page-item">
          <a class="page-link" href="#">1</a>
        </li>
        <li class="page-item">
          <a class="page-link" href="#">2</a>
        </li>
        <li class="page-item">
          <a class="page-link" href="#">3</a>
        </li>
        <li class="page-item">
          <a class="page-link" href="#" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
            <span class="sr-only">Next</span>
          </a>
        </li>
      </ul>

    </div>
    <!-- /.container -->

<? 
  include "footer.php";
?>
  
