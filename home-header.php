<?php
  require_once './includes/db-enterprise.php';
  $allBlogs = $odbenterprise ->query("SELECT * FROM `blogs` WHERE status = 1 ORDER BY `id` DESC Limit 6")->fetchAll();
?>
<!-- Header starts -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>rootCapture - A Live-Fire Cybersecurity Training Platform</title>
    <meta name="description" content="rootCapture is an affordable and effective Live-Fire Cybersecurity Training Platform that is designed to either create or enhance and enrich Cybersecurity/STEM curriculum for truly any organization or institution. With the power of rootCapture at your fingertips, you have the ability to create a riveting, immersive, intuitive learning experience.">
    <!-- <link rel="icon" type="image/x-icon" href="https://rootcapture.com/src/assets/img/favicon.ico"/> -->
    <link rel="icon" type="image/x-icon" href="https://rootcapture.com/src/assets/img/favicon-dark.ico"/>
    <link rel="stylesheet" href="frontend-assets/css/bootstrap.min.css" />
   <!--<link rel="stylesheet" href="frontend-assets/css/aos.css" />-->
    <link rel="stylesheet" href="frontend-assets/css/style.css" />
    <link rel="stylesheet" href="frontend-assets/css/blog-style.css">
     <link rel="stylesheet" href="frontend-assets/css/font-awesome.min.css">
    <style>
      body.loaderNone {
    overflow: hidden;
}
      #loading {
          position: fixed;
          display: block;
          width: 100%;
          height: 100%;
          overflow: hidden;
          margin: auto;
          top: 0;
          left: 0;
          bottom: 0;
          right: 0;
          text-align: center;
          opacity: 1;
          background-color: #1c1b40;
          z-index: 9999;
      }
      #loading-image {
          position: absolute;
          top: 11%;
          left: 30%;
          z-index: 100;
          width: 40%;
      }
      .widthConatinerAll{height: 100% !important; width: 100% !important;}
      @media screen and (min-width: 1400px) {
        .footer {
          height: 450px !important;
        }
      }

      @media screen and (min-width: 320px) and (max-width: 768px) {


#loading {
    
            position: fixed;
          display: block;
          width: 100%;
          height: 100%;
          overflow: hidden;
          margin: auto;
          top: 0;
          left: 0;
          bottom: 0;
          right: 0;
          text-align: center;
          opacity: 1;
          background-color: #1c1b40;
          z-index: 9999;
      }
      #loading-image {
    position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  height: 160px;
      }
}
    </style>
    
  </head>
  <!-- Header end -->
    <body class="loaderNone">
    <!-- BEGIN LOADER -->
    <div id="loading">      
      <img id="loading-image" src="https://rootcapture.com/assets/img/Loading-Animation-dark.gif" alt="Loading..." />    
    </div>
    <!--  END LOADER -->
   
      <div class="widthConatinerAll" style="margin-top:0px;" 
        data-bs-spy="scroll"
        data-bs-target="#navbar"
        data-bs-root-margin="0px 0px -40%"
        data-bs-smooth-scroll="true"
      >
        <!-- Navigation -->
        <nav
          class="navbar navbar-expand-lg bg-transparent fixed-top"
          data-bs-theme="dark"
          id="navbar"
        >
          <div class="container">
            <a class="navbar-brand" href="https://rootcapture.com/">
              <img src="frontend-assets/img/site-logo.svg" alt="" class="img-fluid" />
            </a>
            <a
              class="btn btn-sm btn-primary d-flex d-lg-none ms-auto"
              href="#contact_us"
              role="button"
              >Get in Touch</a
            >
            <div
              class="navbar-toggler border-0 text-light ms-2"
              data-bs-toggle="collapse"
              data-bs-target="#navbarSupportedContent"
              aria-controls="navbarSupportedContent"
              aria-expanded="false"
              aria-label="Toggle navigation"
            >
              <svg
                stroke="currentColor"
                fill="currentColor"
                stroke-width="0"
                viewBox="0 0 12 16"
                height="1em"
                width="1em"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  fill-rule="evenodd"
                  d="M11.41 9H.59C0 9 0 8.59 0 8c0-.59 0-1 .59-1H11.4c.59 0 .59.41.59 1 0 .59 0 1-.59 1h.01zm0-4H.59C0 5 0 4.59 0 4c0-.59 0-1 .59-1H11.4c.59 0 .59.41.59 1 0 .59 0 1-.59 1h.01zM.59 11H11.4c.59 0 .59.41.59 1 0 .59 0 1-.59 1H.59C0 13 0 12.59 0 12c0-.59 0-1 .59-1z"
                ></path>
              </svg>
            </div>
            <div
              class="collapse navbar-collapse mt-4 mt-lg-0"
              id="navbarSupportedContent"
            >
              <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                  <a class="nav-link" href="https://rootcapture.com/#home">Home</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="https://rootcapture.com/#about">About</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="https://rootcapture.com/#service">Services</a>
                </li>
                <?php if( count($allBlogs) > 0 ) {?>
                <li class="nav-item">
                  <a class="nav-link" href="https://rootcapture.com/multi-blogs.php">Blogs</a>
                </li>
                <?php } ?>
              </ul>
              <a
                class="btn btn-primary d-flex d-sm-none rounded"
                href="https://rootcapture.com/#contact_us"
                role="button"
                >Get in Touch</a
              >
            </div>
            <a
              class="btn btn-primary d-none d-lg-flex"
              href="https://rootcapture.com/#contact_us"
              role="button"
              >Get in Touch</a
            >
          </div>
        </nav>
        <!-- ./ Navigation -->