<?php
  require_once 'home-header.php';
  $id = base64_decode($_GET['id']);
  $blogRec = $odbenterprise ->query("SELECT * FROM `blogs` WHERE status = 1 ORDER BY id DESC LIMIT 4")->fetchAll();
  $blogDet = $odbenterprise ->query("SELECT * FROM `blogs` WHERE id = '$id' AND status = 1")->fetchAll();
  $title = '';
  $created_at = '';
  $blogImg = '';
  $ourServices = '';
  $tags = '';
  $description = '';
  $auhtor = '';
  $comment = '';
  $blogid = 0;
  if(count($blogDet))
  {
    $title = $blogDet[0]['title'];
    $created_at = $blogDet[0]['created_at'];
    $blogImg = $blogDet[0]['banner'];
    $ourServices = $blogDet[0]['services'];
    $tags = $blogDet[0]['tags'];
    $description = $blogDet[0]['description'];
    $auhtor = $blogDet[0]['author'];
    $comment = $blogDet[0]['comment'];
    $blogid = $blogDet[0]['id'];
  }
  else
  {  
    echo '<meta http-equiv="refresh" content="3;url=home.php">';
  }
?>
<!--------- Single Blog page Section Start   ------->
<section class=" sngl-blg">
  <div class="container py-5">
      <div class="row justify-content-center">
        <div class="col-md-8 text-center mt-5 pt-5">
          <h1> <?=$title?> </h1>
        </div>
      </div>
      <div class="row">
        <div class="col-md-8 lft-sd mt-5 mt-md-0 ">
          <img src="https://rootcapture.com/adminenterprise/src/public/<?=$blogImg?>" class="img-fluid my-5 my-md-0 imgWidthHht" alt="">
          <!-- <img src="frontend-assets/blog-imgs/sngl-blg1.png" class="img-fluid my-5 my-md-0" alt=""> -->
          <div class="d-flex clc-sec mt-5 mt-md-0">
            <div class="d-flex align-items-center">
              <img src="frontend-assets/blog-imgs/user.png" class="img-fluid" alt="">
              <p>by rootCapture Content Team</p>
            </div>
            <div class="d-flex ms-2 ms-md-5 align-items-center">
              <img src="frontend-assets/blog-imgs/clock.png" class="img-fluid" alt="">
              <p> <?=date_format(date_create($created_at),'d M, Y')?> </p>
            </div>
            <div class="d-flex ms-2 ms-md-5 align-items-center">
              <img src="frontend-assets/blog-imgs/label.png" class="img-fluid" alt="">
              <p>Corporate Solution</p>
            </div>
          </div>

          <?=$description?>
          
          <div class="head-dv mb-5 text-center px-md-5 py-md-3">
            <h2><?=$auhtor?></h2>
            <h3>RootCapture Team</h3>
            <h4>Author</h4>
          </div>
         
          <div class="row">
            <div class="col-md-6 d-none d-md-block">
              <img src="frontend-assets/blog-imgs/met.png" class="img-fluid" alt="">
            </div>
            <div class="col-md-6 my-5 my-md-0">
              <img src="frontend-assets/blog-imgs/hlo.png" class="img-fluid mt-5 mt-md-0" alt="">
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="px-4 py-3 mt-5 mt-md-0 sid-div">
            <label for="searchhere" class="form-label">Search Here</label>
            <input type="name" class="form-control py-3" id="searchhere" aria-describedby="emailHelp" placeholder="search">
          </div>
          <div class="mt-5 py-3 sid-div px-4">
            <h3 class="my-2">Our Services</h3>
            <div class="accordion" id="accordionExample">
              <?php
                  $ourServices = json_decode($ourServices,true);
                  
                  if( isset($ourServices) && count($ourServices) )
                  {  $counter = 1;
                    foreach( $ourServices as $key => $ourServicesV )
                    {                    
              ?>
                      <div class="accordion-item">
                        <h2 class="accordion-header" id="heading<?=$counter?>">
                          <button class="accordion-button <?php if($counter != 1){ echo 'collapsed'; } ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?=$counter?>" aria-expanded="<?php if($counter != 1){ echo false; }else{echo true;} ?>" aria-controls="collapse<?=$counter?>"> <?=$key?> </button>
                        </h2>
                        <div id="collapse<?=$counter?>" class="accordion-collapse collapse <?php if($counter == 1){ echo 'show'; } ?>" aria-labelledby="heading<?=$counter?>" data-bs-parent="#accordionExample">
                          <div class="accordion-body">
                            <?=$ourServicesV?>
                          </div>
                        </div>
                      </div>
                <?php
                  $counter += 1;
                  }

                }
                ?>
              <!-- <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"> Learning at Your <br> Fingertips </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                  <div class="accordion-body">
                    <strong>This is the second item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                  </div>
                </div>
              </div> -->
            </div>
          </div>
          <div class="mt-5 py-3 sid-div px-4">
            <h3 class="my-2">Recent Posts</h3> <?php foreach($blogRec as $blogRecV){ ?> <div class="row rcnt-pst py-2 align-items-center">
              <div class="col-md-4 text-center text-md-start">
                <img src="https://rootcapture.com/adminenterprise/src/public/<?=$blogRecV['banner']?>" class="img-fluid" alt="">
              </div>
              <div class="col-md-8 text-center text-md-start col-lg-7">
                <div class="d-flex justify-content-center justify-content-md-start align-items-center">
                  <img src="frontend-assets/blog-imgs/clock.png" class="img-fluid" alt="">
                  <p> <?=date_format(date_create($blogRecV['created_at']),'d M, Y')?> </p>
                </div>
                <h5> <?=$blogRecV['title']?> </h5>
              </div>
            </div> <?php } ?>
          </div>
          <div class="mt-5 py-3 sid-div px-4">
            <h3 class="my-4">Gallery Posts</h3>
            <div class="row">
              <div class="col-6 col-md-4 mt-4 mt-md-0">
                <img src="frontend-assets/blog-imgs/galr1.png" class="img-fluid" alt="">
              </div>
              <div class="col-6 col-md-4 mt-4 mt-md-0">
                <img src="frontend-assets/blog-imgs/galr2.png" class="img-fluid" alt="">
              </div>
              <div class="col-6 col-md-4 mt-4 mt-md-0">
                <img src="frontend-assets/blog-imgs/galr3.png" class="img-fluid" alt="">
              </div>
              <div class="col-6 col-md-4 mt-4">
                <img src="frontend-assets/blog-imgs/galr4.png" class="img-fluid" alt="">
              </div>
              <div class="col-6 col-md-4 mt-4">
                <img src="frontend-assets/blog-imgs/galr5.png" class="img-fluid" alt="">
              </div>
              <div class="col-6 col-md-4 mt-4">
                <img src="frontend-assets/blog-imgs/galr6.png" class="img-fluid" alt="">
              </div>
            </div>
          </div>
          <div class="mt-5 py-3 sid-div px-4">
            <h3 class="my-4">Tags</h3>
            <ul class="listTag">
              <?php 
                $tagsExp = explode(',',$tags);
                foreach($tagsExp as $tagsExpV){
              ?>
              <li>
                <a class="tagField" href="javascript:void(0)"><?=$tagsExpV?></a>
              </li>
              <?php } ?>
            </ul>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="top-box">
            <h4>Ultimate Business Strategy Solution</h4>
            <p class="my-4">Gravida maecenas lobortis suscipit mus sociosqu convallis, mollis vestibulum donec aliquam risus sapien ridiculus, nulla sollicitudin eget in venenatis. Tortor montes platea iaculis posuere per mauris, eros porta blandit curabitur ullamcorper varius nostra ante risus egestas.</p>
          </div>
          <div class="row bnft-sec align-items-center">
            <div class="col-md-6 my-5 my-md-0">
              <img src="frontend-assets/blog-imgs/met.png" class="mb-5 mb-md-0 img-fluid" alt="">
            </div>
            <div class="col-md-6">
              <h1>Customer Benefits​</h1>
              <p>Catalysts for change before fully tested markets are maintain wireless scenarios after intermandated applications predominate revolutionary.</p>
              <ul>
                <li>
                  <img src="frontend-assets/blog-imgs/tick.png" alt=""> We use the latest diagnostic equipment
                </li>
                <li>
                  <img src="frontend-assets/blog-imgs/tick.png" alt=""> We are a member of Professional Service
                </li>
                <li>
                  <img src="frontend-assets/blog-imgs/tick.png" alt=""> Automotive service our clients receive
                </li>
              </ul>
            </div>
          </div>
          <p class="mt-4">Gravida maecenas lobortis suscipit mus sociosqu convallis, mollis vestibulum donec aliquam risus sapien ridiculus, nulla sollicitudin eget in venenatis. Tortor montes platea iaculis posuere per mauris, eros porta blandit curabitur ullamcorper varius nostra ante risus egestas.</p>
          <div class="row smth-sec mt-5 px-3 py-3 align-items-center">
            <div class="col-md-2 text-center text-md-start mb-3">
              <img src="frontend-assets/blog-imgs/met2.png" class="img-fluid" alt="">
            </div>
            <div class="col-md-9 text-center text-md-start mt-3 mt-md-0">
              <h3> David Smith</h3>
              <p>Nullam varius luctus pharetra ultrices volpat facilisis donec tortor, nibhkisys habitant curabitur at nunc nisl magna ac rhoncus vehicula sociis tortor nist hendrerit molestie integer.</p>
            </div>
          </div>
        </div>
      </div>
      <?php if($comment){ ?>
      <div class="row">
        <div class="col-md-8">
          <div class="row form-sec px-3 py-5 ">
            
            <div class="col-md-8">
                <h3>Leave a Reply</h3>
                <p>Your email address will not be published. Required fields are marked *</p>
                <label for="username" class="form-label">Username</label>
                <input type="name" class="form-control py-3" id="username" aria-describedby="emailHelp">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control py-3" id="email" aria-describedby="emailHelp">
                <label for="reply" class="form-label">Comment</label>
                <!-- <textarea type="email" class="form-control py-3" id="reply" aria-describedby="emailHelp"> -->
                <br>
                <textarea name="" id="reply" cols="60" rows="10"></textarea>
                <br>
                <button class="btn px-4 py-3 mt-4" type="submit">
                  <a href="javascript:void(0)" data-id="<?=$blogid?>" id="post_comment">Post Comment</a>
                </button>
            </div>
          </div>
        </div>
      </div> 
      <?php } ?>
  </div>
</section>
<!--------- Single Blog page Section End   ------->
<?php require_once 'home-footer.php'; ?>
 
