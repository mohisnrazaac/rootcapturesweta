<?php
require_once 'home-header.php';
?>


<!--------- Blog Page Hero Section Start   ------->
<div class="blog-sec py-5">
  <div class="container">
    <!--------- <div class="row justify-content-center"><div class="col-md-6 text-center mt-5 pt-5"><h1>Create your account!</h1><h2>Company's news & events</h2></div></div>------->
    <div class="row justify-content-center newsletterCust">
      <div class=" col-md-8 col-sm-12 col-xs-12">
        <div class="text-white boxBlog">
          <h3 class="my-3"> Blogs, Newsletters & Resources </h3>
          <div class="row d-flex my-2 pr-2 pr-md-5 div1">
            <div class="col-md-9">
              <input type="email" class="form-control py-3 inputEmail" id="inp1" placeholder="Enter email address">
            </div>
            <div class="col-md-3 px-0">
              <button class="btn text-white px-4 py-2 buttonSub"> Subscribe </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--<img src="frontend-assets/blog-imgs/blog-hero.png" class="img-fluid" alt="">-->
    <div class="row mt-5 customDivImg">
      <div class="col-md-5">
        <div class="boxInner">
          <div class="blogsDetails text-white">
            <span>
              <i class="fa fa-calendar-o"></i>
              <time><?=($allBlogs)?date_format(date_create($allBlogs[0]['created_at']),'d M, Y'):''?></time>
            </span>
            <span>
              <!-- <i class="fa fa-clock-o"></i>2 min </span> -->
          </div>
          <h3 class="text-white"><?=($allBlogs)?$allBlogs[0]['title']:''?></h3>
          <p class="text-white"><?=($allBlogs)?$allBlogs[0]['description']:''?></p>
          <div class="boxBlankWhite"><a href="https://rootcapture.com/blog-detail.php?id=<?=($allBlogs)?base64_encode($allBlogs[0]['id']):''?>" class="btn text-white px-4 py-2 buttonSub"> Read More <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                              <line x1="7" y1="17" x2="17" y2="7"></line>
                              <polyline points="7 7 17 7 17 17"></polyline>
                            </svg></a></div>


        </div>
      </div>
      <div class="col-md-7">
        <div class="blog-right-img">
          <?php if($allBlogs){ ?>
          <img src="https://rootcapture.com/adminenterprise/src/public/<?=$allBlogs[0]['banner']?>" class="img-fluid" alt="">
          <?php } ?>
        </div>
      </div>
    </div>
    <hr class="customLine">
    <div class="row">
      <div class="col-md-12">
        <h3 class="text-white">Latest Articles</h3>
      </div>
    </div>
    <div class="row mt-0 mt-md-5 cardCustom">
    <?php foreach($allBlogs as $allBlogsV)
    { ?>
      <div class="col-md-6">
          <div class="card" style="width: 18rem;">
              <img src="https://rootcapture.com/adminenterprise/src/public/<?=$allBlogsV['banner']?>" class="card-img-top" alt="...">
              <div class="card-body">
                  <h3><?php echo $allBlogsV['title']; ?></h3>
                  <div class="blogsDetails text-white">
                    <span>
                      <i class="fa fa-calendar-o"></i>
                      <time><?=date_format(date_create($allBlogsV['created_at']),'d M, Y')?></time>
                    </span>
                    <span>
                      <!-- <i class="fa fa-clock-o"></i>2 min </span> -->
                  </div>
                  <a href="https://rootcapture.com/blog-detail.php?id=<?=base64_encode($allBlogsV['id'])?>" class="text-white readMore">Read More <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                              <line x1="7" y1="17" x2="17" y2="7"></line>
                              <polyline points="7 7 17 7 17 17"></polyline>
                            </svg></a>
              </div>
          </div>
      </div>
    <?php } ?>  
    </div>

    <div class="row  mt-3 mt-md-5  cardCustom">
      <div class="col-md-12 blogNextPrv">
        <a href="#" class="previousBtn">« Previous</a>
        <a href="#" class="nextBtn">Next »</a>
      </div>
    </div>
  </div>
</div>

   <?php require_once 'home-footer.php';  ?>
   </body>
</html>
 
<!--------- Blog Page Hero Section End   ------->













