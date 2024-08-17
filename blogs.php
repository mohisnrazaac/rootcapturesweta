<?php
require_once 'home-header.php';
$allBlogs = $odbenterprise ->query("SELECT * FROM `blogs` WHERE status = 1 ORDER BY `id`")->fetchAll();
  
?>

<!--------- Blog Page Hero Section Start   ------->
<div class="container-fluid blog-sec py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center  headingBlog">
            <h1>Create your account!</h1>
            <h2>Company's news & events</h2>
        </div>
    </div>
    <div class="row mt-5 py-5 justify-content-end">
        <div class="col-md-8">
            <img src="frontend-assets/blog-imgs/blog-hero.png" class="img-fluid" alt="">
        </div>
    </div>

    <div class="row px-5 mt-0 mt-md-5 py-5">
        <?php foreach($allBlogs as $allBlogsV){ ?>
            <div class="col-md-4 mt-5 mt-md-0">
                <a href="https://rootcapture.com/blog-detail.php?id=<?=base64_encode($allBlogsV['id'])?>">
                    <div class="card custom-card" style="width: 18rem;">
                        <img src="https://rootcapture.com/adminenterprise/src/public/<?=$allBlogsV['banner']?>" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h3><?php echo $allBlogsV['title']; ?> </h3>
                        </div>
                    </div>
            </a>
            </div>
        <?php } ?>  
    </div>
</div>
<!--------- Blog Page Hero Section End   ------->

<?php require_once 'home-footer.php';  ?>
</body>
</html>











