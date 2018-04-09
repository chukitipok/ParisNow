<?php
include "header.php";
//test
?>

    <div class="autoplay">
        <div><img src="img/paris1.jpg" alt="First slide"></div>
        <div><img src="img/paris2.jpg" alt="Second slide"></div>
        <div><img src="img/paris3.jpg" alt="Third slide"></div>
    </div>

    <script type="text/javascript">
    $(document).ready(function() {
        $('.autoplay').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 2000
        })
    });
    </script>


<!--<div id="carouselHome" class="carousel slide" data-ride="carousel">-->
<!--    <ol class="carousel-indicators">-->
<!--        <li data-target="#carouselHome" data-slide-to="0" class="active"></li>-->
<!--        <li data-target="#carouselHome" data-slide-to="1"></li>-->
<!--        <li data-target="#carouselHome" data-slide-to="2"></li>-->
<!--    </ol>-->
<!--    <div class="carousel-inner">-->
<!--        <div class="carousel-item active">-->
<!--            <img class="d-block w-100" src="img/paris1.jpg" alt="First slide">-->
<!--        </div>-->
<!--        <div class="carousel-item">-->
<!--            <img class="d-block w-100" src="img/paris2.jpg" alt="Second slide">-->
<!--        </div>-->
<!--        <div class="carousel-item">-->
<!--            <img class="d-block w-100" src="img/paris3.jpg" alt="Third slide">-->
<!--        </div>-->
<!--    </div>-->
<!--    <a class="carousel-control-prev" href="#carouselHome" role="button" data-slide="prev">-->
<!--        <span class="carousel-control-prev-icon" ></span>-->
<!--        <span class="sr-only">Previous</span>-->
<!--    </a>-->
<!--    <a class="carousel-control-next" href="#carouselHome" role="button" data-slide="next">-->
<!--        <span class="carousel-control-next-icon" ></span>-->
<!--        <span class="sr-only">Next</span>-->
<!--    </a>-->
<!--</div>-->

<div class="wrapper mr-auto ml-auto">
    <div>
        <h2>Proposez nous un événement</h2>
        <button type="button" class="btn btn-outline-info">Partager un événement</button>
    </div>
    <div>
        <h2>Evénements recommandés</h2>
        <div class="content">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequuntur, dignissimos dolor doloribus
            expedita ipsa laudantium, modi molestiae, neque numquam officiis pariatur quasi rerum veritatis? Amet animi
            architecto dolore quisquam quo?
        </div>
        <div class="content">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi assumenda commodi cupiditate fugiat iste
            laborum libero nemo nulla quas quibusdam, reprehenderit sunt tempora voluptate? Dolorum maiores numquam
            odit quo vitae?
        </div>
        <div class="content">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium, alias aliquid culpa cum deserunt
            doloremque dolores fugiat hic impedit inventore iure minima nobis odio perferendis quasi rerum tempora?
            Ipsum, reprehenderit!
        </div>
    </div>
    <div>
        <h2>Les plus populaires</h2>
        <div class="content">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequuntur, dignissimos dolor doloribus
            expedita ipsa laudantium, modi molestiae, neque numquam officiis pariatur quasi rerum veritatis? Amet animi
            architecto dolore quisquam quo?
        </div>
        <div class="content">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi assumenda commodi cupiditate fugiat iste
            laborum libero nemo nulla quas quibusdam, reprehenderit sunt tempora voluptate? Dolorum maiores numquam
            odit quo vitae?
        </div>
        <div class="content">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium, alias aliquid culpa cum deserunt
            doloremque dolores fugiat hic impedit inventore iure minima nobis odio perferendis quasi rerum tempora?
            Ipsum, reprehenderit!
        </div>
    </div>
</div>

<?php
include "footer.php";
?>