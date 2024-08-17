<?php
  require_once './home-header.php';
  $blogs = $odbenterprise ->query("SELECT * FROM `blogs` WHERE status = 1 ORDER BY `id` DESC LIMIT 3")->fetchAll();
?>
        <!-- Hero -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.12.0/lottie.min.js" integrity="sha512-NbKgMv0o4r7P6qWzHvk9L4S7rQzZaccsgD52bffgInf0OCdDg45Ta8uBBKxAbIutsrM5TqB88DYp1EP0NePcdg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <div
          class="section-gap section-gap-top-lg hero-area position-relative"
          id="home"
        >
          <div class="position-absolute top-0 start-0 z-n1">
            <img src="frontend-assets/img/blur-left-1.png" alt="" class="img-fluid" />
          </div>
          <div class="position-absolute top-0 end-0 z-n1">
            <img src="frontend-assets/img/blur-right-1.png" alt="" class="img-fluid" />
          </div>
          <div class="container z-1 position-relative">
            <div
              class="row gap-5 gap-xl-0 align-items-center justify-content-center"
            >
              <div class="col-xl-6">
                <div class="hero-text">
                  <span
                    data-aos="fade-in"
                    data-aos-duration="800"
                    class="d-block"
                    >Welcome to rootCapture</span
                  >
                  <h1
                    class="mt-2 mb-4"
                    data-aos="fade-up"
                    data-aos-duration="800"
                  >
                    <span class="g-text text-uppercase">An In-Depth</span>
                    Virtual Cybersecurity Training Platform
                  </h1>
                  <h3
                    class="mb-3 opacity-70"
                    data-aos="fade-in"
                    data-aos-duration="500"
                  >
                    Ready to take your cybersecurity training up a notch?
                  </h3>
                  <p
                    class="opacity-70"
                    data-aos="fade-in"
                    data-aos-duration="500"
                  >
                    With rootCapture’s customizable live-fire cybersecurity
                    range and training platform, you’ll have an effective
                    environment for building and honing your cybersecurity
                    curriculum.
                  </p>
                  <div
                    class="d-flex flex-column flex-sm-row gap-2 mt-5"
                    data-aos="fade-in"
                    data-aos-duration="500"
                  >
                    <a
                      class="btn btn-primary"
                      href="#root_capture_difference"
                      role="button"
                      >What Makes Us Different?</a
                    >
                    <a
                      class="btn btn-secondary"
                      href="#contact_us"
                      role="button"
                    >
                      Schedule a Demo
                      <svg
                        stroke="currentColor"
                        fill="none"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        height="1em"
                        width="1em"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <line x1="7" y1="17" x2="17" y2="7"></line>
                        <polyline points="7 7 17 7 17 17"></polyline>
                      </svg>
                    </a>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 col-xl-6 imgs-for-services" id="1Img">
              <script>
                var animation = bodymovin.loadAnimation({
                    container: document.getElementById("1Img"),
                    path: "./frontend-assets/img/1.json",
                    render: "svg",
                    loop: true,
                    autoplay: true,
                    
                });
            </script>
                <!-- <img
                  src="frontend-assets/img/hero-1.png"
                  alt=""
                  class="img-fluid animate-bounce"
                  data-aos="fade-in"
                  data-aos-duration="800"
                /> -->
              </div>
            </div>
          </div>
        </div>
        <!-- ./ Hero -->

        <!-- About -->
        <div class="section-gap about" id="about">
          <!-- Section Title -->
          <div class="container">
            <div class="row">
              <div class="col-lg-12">
                <div class="section-title text-center">
                  <h2
                    class="col-lg-7 mx-auto"
                    data-aos="fade-up"
                    data-aos-duration="800"
                  >
                    A First-Class Cybersecurity Training Provider
                  </h2>
                  <p data-aos="fade-up" data-aos-duration="800">
                    rootCapture is a cost-effective, all-encompassing
                    Cybersecurity Training Solutions Provider that puts your
                    organizational wants and needs first. Our innovative training platform harnesses the power of practical education by providing an immersive, and interactive learning experience. rootCapture provides not
                    only realistic learning opportunities, but also an
                    academically motivated management process to ensure the
                    highest quality of teaching and knowledge-tracking.
                  </p>
                  <p data-aos="fade-up" data-aos-duration="800">
                  Our platform can truly be tailored to fit all of your training demands while simultaneously enhancing and enriching curriculum. Put rootCapture into action and experience the real-time training platform that will surely sharpen cybersecurity skills.
                  </p>
                </div>
              </div>
            </div>
          </div>
          <!-- ./ Section Title -->

          <div class="container">
            <div class="row gap-4 gap-lg-0 justify-content-center">
              <!-- About Option -->
              <div
                class="col-lg-4 col-md-6"
                data-aos="fade-up"
                data-aos-duration="800"
              >
                <div class="about-option text-center">
                  <span class="blur-layer"></span>
                  <div class="image mx-auto educationimg">
                    <!-- <img
                      src="frontend-assets/img/about-1.png"
                      alt=""
                      class="img-fluid"
                    /> -->
                  <span id="educational" class="educationsvg">
                  <script>
                      var animation = bodymovin.loadAnimation({
                          container: document.getElementById("educational"),
                          path: "./frontend-assets/img/Educational.json",
                          render: "svg",
                          loop: true,
                          autoplay: true,
                          
                      });
                  </script>
                    <!-- <img
                      src="frontend-assets/img/diff-4.png"
                      alt=""
                      class="img-fluid"
                      data-aos="fade-up"
                      data-aos-duration="800"
                    /> -->
                    </span>
                  </div>
                  <div class="title text-uppercase">Educational</div>
                </div>
              </div>
              <!-- ./ About Option -->

              <!-- About Option -->
              <div
                class="col-lg-4 col-md-6"
                data-aos="fade-up"
                data-aos-duration="800"
              >
                <div class="about-option green text-center">
                  <span class="blur-layer"></span>
                  <div class="image mx-auto">
                  <!-- Affordable.json -->
                  <span id="Affordable">
                  <script>
                      var animation = bodymovin.loadAnimation({
                          container: document.getElementById("Affordable"),
                          path: "./frontend-assets/img/Affordable.json",
                          render: "svg",
                          loop: true,
                          autoplay: true,
                          
                      });
                  </script>
                  </span>
                    <!-- <img
                      src="frontend-assets/img/about-2.png"
                      alt=""
                      class="img-fluid"
                    /> -->
                  </div>
                  <div class="title text-uppercase">Affordable</div>
                </div>
              </div>
              <!-- ./ About Option -->

              <!-- About Option -->
              <div
                class="col-lg-4 col-md-6"
                data-aos="fade-up"
                data-aos-duration="800"
              >
                <div class="about-option orange text-center">
                  <span class="blur-layer"></span>
                  <div class="image mx-auto">
                  <span id="Customizable">
                  <script>
                      var animation = bodymovin.loadAnimation({
                          container: document.getElementById("Customizable"),
                          path: "./frontend-assets/img/Customizable.json",
                          render: "svg",
                          loop: true,
                          autoplay: true,
                          
                      });
                  </script>
                  </span>
                    <!-- <img
                      src="frontend-assets/img/about-3.png"
                      alt=""
                      class="img-fluid"
                    /> -->
                  </div>
                  <div class="title text-uppercase">CUSTOMIZABLE</div>
                </div>
              </div>
              <!-- ./ About Option -->
            </div>
          </div>
        </div>
        <!-- ./ About -->

        <!-- Difference -->
        <div class="section-gap difference" id="service">
          <!-- Section Title -->
          <div class="container" id="root_capture_difference">
            <div class="row">
              <div class="col-lg-12">
                <div
                  class="section-title text-center"
                  data-aos="fade-up"
                  data-aos-duration="800"
                >
                  <h2>The rootCapture Difference</h2>
                </div>
              </div>
            </div>
          </div>
          <!-- ./ Section Title -->

          <!-- Diff 1 -->
          <div class="section-gap position-relative">
            <div
              class="position-absolute top-0 start-0 z-n1 translate-middle-y"
            >
              <img src="frontend-assets/img/blur-left-2.png" alt="" class="img-fluid" />
            </div>
            <div class="position-absolute top-0 end-0 z-n1 translate-middle-y">
              <img src="frontend-assets/img/blur-right-2.png" alt="" class="img-fluid" />
            </div>
            <div class="container">
              <div class="row align-items-center gap-5 gap-lg-0">
                <div class="col-lg-6 imgs-for-services" id="2Img">
                <script>
                var animation = bodymovin.loadAnimation({
                    container: document.getElementById("2Img"),
                    path: "./frontend-assets/img/2.json",
                    render: "svg",
                    loop: true,
                    autoplay: true,
                    
                });
            </script>
                  <!-- <img
                    src="frontend-assets/img/diff-1.png"
                    alt=""
                    class="img-fluid"
                    data-aos="fade-in"
                    data-aos-duration="800"
                  /> -->
                </div>
                <div class="col-lg-6">
                  <div class="diff-text">
                    <span data-aos="fade-up" data-aos-duration="800"
                      >Enhanced Education</span
                    >
                    <h3
                      data-aos="fade-up"
                      data-aos-duration="800"
                    >
                      Providing The Gift of Practical Knowledge
                    </h3>
                    <p
                      data-aos="fade-up"
                      data-aos-duration="800"
                    >
                    Discover the power of practical cyber-education with rootCapture! Our software teaches users the skill sets needed to defend against a wide variety of cyber-attack vectors.
                    </p>
                    <p
                      data-aos="fade-up"
                      data-aos-duration="800"
                    >
                      <span class=" text-white"
                        >By taking a comprehensive and interactive approach to
                        cyber-security instruction, </span>our <span class="g-text">system gives users</span> an efficient way of gaining the knowledge needed to become proficient cybersecurity professionals.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Diff 2 -->
          <div class="section-gap position-relative">
            <div class="position-absolute bottom-0 end-0">
              <img
                src="frontend-assets/img/section-vec-right.png"
                alt=""
                class="img-fluid"
                data-aos="fade-in"
                data-aos-duration="800"
              />
            </div>
            <div class="container position-relative z-1">
              <div class="row align-items-center gap-5 gap-lg-0">
                <div class="col-lg-6 order-lg-2">
                  <div
                    class="d-flex justify-content-start justify-content-lg-end imgs-for-services" id="3Img"
                  >
                  <script>
                      var animation = bodymovin.loadAnimation({
                          container: document.getElementById("3Img"),
                          path: "./frontend-assets/img/3.json",
                          render: "svg",
                          loop: true,
                          autoplay: true,
                          
                      });
                  </script>
                    <!-- <img
                      src="frontend-assets/img/diff-2.png"
                      alt=""
                      class="img-fluid"
                      data-aos="fade-up"
                      data-aos-duration="800"
                    /> -->
                  </div>
                </div>
                <div class="col-lg-6 order-lg-1">
                  <div class="diff-text">
                    <span data-aos="fade-up" data-aos-duration="800"
                      >Learning at Your Fingertips</span
                    >
                    <h3
                      data-aos="fade-up"
                      data-aos-duration="800"
                    >
                      An Expansive Approach to Cybersecurity Education
                    </h3>
                    <p
                      data-aos="fade-up"
                      data-aos-duration="800"
                    >
                     Our library of courses and educational materials cover multiple topics like cybersecurity research, digital forensics, ethical hacking, encryption, malware creation/defense, security techniques, security evasion methods and much more.
                    </p>
                    <p
                      data-aos="fade-up"
                      data-aos-duration="800"
                    >
                      <span class="text-white">With rootCapture,</span> our users will <span class="g-text">always be prepared</span> for the ever-evolving threat landscape in cyber space.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Diff 3 -->
          <div class="section-gap position-relative">
            <div class="position-absolute bottom-0 start-0">
              <img
                src="frontend-assets/img/section-vec-left.png"
                alt=""
                class="img-fluid"
                data-aos="fade-in"
                data-aos-duration="800"
              />
            </div>
            <div class="container position-relative z-1">
              <div class="row align-items-center gap-5 gap-lg-0">
                <div class="col-lg-6 imgs-for-services" id="4Img">
                <script>
                      var animation = bodymovin.loadAnimation({
                          container: document.getElementById("4Img"),
                          path: "./frontend-assets/img/4.json",
                          render: "svg",
                          loop: true,
                          autoplay: true,
                          
                      });
                  </script>
                  <!-- <img
                    src="frontend-assets/img/diff-3.png"
                    alt=""
                    class="img-fluid"
                    data-aos="fade-up"
                    data-aos-duration="800"
                  /> -->
                </div>
                <div class="col-lg-6">
                  <div class="diff-text">
                    <span data-aos="fade-up" data-aos-duration="800"
                      >Scale With Ease</span
                    >
                    <h3
                      data-aos="fade-up"
                      data-aos-duration="800"
                    >
                      You Can Scale it Up, or Scale it Down
                    </h3>
                    <p
                      data-aos="fade-up"
                      data-aos-duration="800"
                    >
                      Our scalability options allow you to design a unique solution that is adaptive to your ever changing business needs.
                    </p>
                    <p
                      data-aos="fade-up"
                      data-aos-duration="800"
                    >
                      <span  class="text-white">rootCapture can easily be tailored</span> to accommodate the
                      requirements of any educational institution or
                      organization - no matter how
                      <span class="g-text">complex or ambitious</span> the
                      goals maybe.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Diff 4 -->
          <div class="section-gap position-relative">
            <div class="position-absolute bottom-0 end-0">
              <img
                src="frontend-assets/img/section-vec-right.png"
                alt=""
                class="img-fluid"
              />
            </div>
            <div
              class="position-absolute top-0 end-0 z-n1 translate-middle-y mt-5"
            >
              <img src="frontend-assets/img/blur-right-3.png" alt="" class="img-fluid" />
            </div>
            <div class="container position-relative z-1">
              <div class="row align-items-center gap-5 gap-lg-0">
                <div class="col-lg-6 order-lg-2">
                  <div
                    class="d-flex justify-content-start justify-content-lg-end imgs-for-services" id="5Img"
                  >
                  <script>
                      var animation = bodymovin.loadAnimation({
                          container: document.getElementById("5Img"),
                          path: "./frontend-assets/img/5.json",
                          render: "svg",
                          loop: true,
                          autoplay: true,
                          
                      });
                  </script>
                    <!-- <img
                      src="frontend-assets/img/diff-4.png"
                      alt=""
                      class="img-fluid"
                      data-aos="fade-up"
                      data-aos-duration="800"
                    /> -->
                  </div>
                </div>
                <div class="col-lg-6 order-lg-1">
                  <div class="diff-text">
                    <span data-aos="fade-up" data-aos-duration="800"
                      >Creating Talent</span
                    >
                    <h3
                      data-aos="fade-up"
                      data-aos-duration="800"
                    >
                      Bridging the Skills Gap
                    </h3>
                    <p
                      data-aos="fade-up"
                      data-aos-duration="800"
                    >
                      With rootCapture, users can get ahead of any potential breach and prepare them for what's coming next with our state-of-the-art Cyber Range and Virtual Learning Solution.
                    </p>
                    <p
                      data-aos="fade-up"
                      data-aos-duration="800"
                    >
                      <span class="text-white">Our realistic training sessions</span> combined
                      <span class="g-text"
                        >with comprehensive scenarios</span>
                      will help ensure that users are well-equipped to enter the Cybersecurity
                     and Information Technology workforce.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- ./ Difference -->

        <!-- Features -->
        <div class="section-gap position-relative features">
          <div class="position-absolute top-0 start-0 z-n1 translate-middle-y">
            <img src="frontend-assets/img/blur-left-3.png" alt="" class="img-fluid" />
          </div>
          <div class="position-absolute top-0 end-0 z-n1">
            <img src="frontend-assets/img/blur-right-4.png" alt="" class="img-fluid" />
          </div>

          <!-- Section Title -->
          <div class="container">
            <div class="row">
              <div class="col-lg-12">
                <div class="section-title text-center">
                  <h2>rootCapture's Features</h2>
                  <p>
                    rootCapture is packed with a ton of exciting features and
                    benefits. Some of these features include...
                  </p>
                </div>
              </div>
            </div>
          </div>
          <!-- ./ Section Title -->

          <div class="container">
            <div class="row g-4 justify-content-center">
              <div
                class="col-lg-6 col-xl-4"
                data-aos="fade-up"
                data-aos-duration="800"
              >
                <!-- Features Item -->
                <div class="feat-item text-center">
                  <div class="image mx-auto">
                  <div
                    class="d-flex justify-content-start justify-content-lg-end" id="Team_Based_Exercises"
                  >
                  <script>
                      var animation = bodymovin.loadAnimation({
                          container: document.getElementById("Team_Based_Exercises"),
                          path: "./frontend-assets/img/Team_Based_Exercises.json",
                          render: "svg",
                          loop: true,
                          autoplay: true,
                          
                      });
                  </script>
                  </div>
                    <!-- <img
                      src="frontend-assets/img/feature-1.png"
                      alt=""
                      class="img-fluid"
                    /> -->
                  </div>
                  <div class="title">Team Based Exercises</div>
                  <p class="desc">
                    Immerse users in a variety of team-oriented exercises that
                    revolve around Blue Team, Red Team, and Purple Team roles.
                  </p>
                </div>
              </div>

              <div
                class="col-lg-6 col-xl-4"
                data-aos="fade-up"
                data-aos-duration="800"
              >
                <!-- Features Item -->
                <div class="feat-item child-2 text-center">
                  <div class="image mx-auto">
                  <!-- Capture_The_Flag.json -->
                  <div
                    class="d-flex justify-content-start justify-content-lg-end" id="Capture_The_Flag"
                  >
                  <script>
                      var animation = bodymovin.loadAnimation({
                          container: document.getElementById("Capture_The_Flag"),
                          path: "./frontend-assets/img/Capture_The_Flag.json",
                          render: "svg",
                          loop: true,
                          autoplay: true,
                          
                      });
                  </script>
                  </div>
                    <!-- <img
                      src="frontend-assets/img/feature-2.png"
                      alt=""
                      class="img-fluid"
                    /> -->
                  </div>
                  <div class="title">Capture The Flag Exercises</div>
                  <p class="desc">
                    
                    The rootCapture Cyber Range allows Administrators to not only use the provided out-of-the-box flags for Capture The Flag challenges, but also enables Administrators to incorporate, manage, and control their custom challenges.
                  </p>
                </div>
              </div>

              <div
                class="col-lg-6 col-xl-4"
                data-aos="fade-up"
                data-aos-duration="800"
              >
                <!-- Features Item -->
                <div class="feat-item child-3 text-center">
                  <div class="image mx-auto">
                  <!-- Individual_skills.json -->
                  <div
                    class="d-flex justify-content-start justify-content-lg-end" id="Individual_skills"
                  >
                  <script>
                      var animation = bodymovin.loadAnimation({
                          container: document.getElementById("Individual_skills"),
                          path: "./frontend-assets/img/Individual_skills.json",
                          render: "svg",
                          loop: true,
                          autoplay: true,
                          
                      });
                  </script>
                  </div>
                    <!-- <img
                      src="frontend-assets/img/feature-3.png"
                      alt=""
                      class="img-fluid"
                    /> -->
                  </div>
                  <div class="title">Individual Skills Training</div>
                  <p class="desc">
                    Put students to the test with a vast number of exercises,
                    courses, and assessments that develop individual skills as
                    it pertains to their unique cybersecurity role.
                  </p>
                </div>
              </div>

              <div
                class="col-lg-6 col-xl-4"
                data-aos="fade-up"
                data-aos-duration="800"
              >
                <!-- Features Item -->
                <div class="feat-item child-4 text-center">
                  <div class="image mx-auto">
                  <!-- Customizable_Range.json -->
                  <div
                    class="d-flex justify-content-start justify-content-lg-end" id="Customizable_Range"
                  >
                  <script>
                      var animation = bodymovin.loadAnimation({
                          container: document.getElementById("Customizable_Range"),
                          path: "./frontend-assets/img/Customizable_Range.json",
                          render: "svg",
                          loop: true,
                          autoplay: true,
                          
                      });
                  </script>
                  </div>
                    <!-- <img
                      src="frontend-assets/img/feature-4.png"
                      alt=""
                      class="img-fluid"
                    /> -->
                  </div>
                  <div class="title">A Customizable Range</div>
                  <p class="desc">
                    Our Cyber Range can be customized to fit unique
                    organizational demands, wants, and necessities.
                  </p>
                </div>
              </div>

              <div
                class="col-lg-6 col-xl-4"
                data-aos="fade-up"
                data-aos-duration="800"
              >
                <!-- Features Item -->
                <div class="feat-item child-5 text-center">
                  <div class="image mx-auto">
                  <!-- Automated_Attack_Scenarios.json -->
                  <div
                    class="d-flex justify-content-start justify-content-lg-end" id="Automated_Attack_Scenarios"
                  >
                  <script>
                      var animation = bodymovin.loadAnimation({
                          container: document.getElementById("Automated_Attack_Scenarios"),
                          path: "./frontend-assets/img/Automated_Attack_Scenarios.json",
                          render: "svg",
                          loop: true,
                          autoplay: true,
                          
                      });
                  </script>
                  </div>
                    <!-- <img
                      src="frontend-assets/img/feature-5.png"
                      alt=""
                      class="img-fluid"
                    /> -->
                  </div>
                  <div class="title">Automated Attack Scenarios</div>
                  <p class="desc">
                    Challenge trainees with automated attack vectors that are
                    designed to go through each and every phase in
                    breaching systems, networks, applications, and people.
                  </p>
                </div>

              </div>
             
            </div>
          </div>
        </div>
        <!-- ./ Features -->

        <!-- Contact us -->
        <div
          class="section-gap pb-0 contact-us position-relative contact-custom"
          id="contact_us"
        >
          <div class="position-absolute bottom-0 start-0">
            <img
              src="frontend-assets/img/section-vec-left.png"
              alt=""
              class=""
            />
          </div>
          <div class="position-absolute bottom-0 start-0 z-n1">
            <img src="frontend-assets/img/blur-left-4.png" alt="" class="img-fluid" />
          </div>
          <div class="position-absolute bottom-50 end-0 z-n1 translate-middle-y">
            <img src="frontend-assets/img/blur-right-5.png" alt="" class="img-fluid" />
          </div>

          <div class="container z-1 position-relative">
            <div class="row gap-5 gap-lg-0 align-items-center">
              <div class="col-lg-5 col-xxl-6">
                <div class="d-flex justify-start mobileViewjson imgs-for-services" id="contact-us">
                <script>
                      var animation = bodymovin.loadAnimation({
                          container: document.getElementById("contact-us"),
                          path: "./frontend-assets/img/6.json",
                          render: "svg",
                          loop: true,
                          autoplay: true,
                          
                      });
                </script>
                  <!-- <img
                    src="frontend-assets/img/contact-us.png"
                    alt=""
                    class="img-fluid noneForDesktop"
                    data-aos="fade-up"
                    data-aos-duration="800"
                  /> -->
                </div>
              </div>
              <div class="col-lg-6 col-xxl-5 offset-lg-1">
                <div
                  class="section-title mb-5"
                  data-aos="fade-up"
                  data-aos-duration="800"
                >
                  <h2>Contact Us</h2>
                  <p>
                    You can reach us anytime via
                    <a href="mailto:contact@rootcapture.com" class="text-white">contact@rootcapture.com</a>
                  </p>
                </div>
                <form class="contact-form" id="contactus" onsubmit="return validate()">
                  <div
                    class="mb-3"
                    data-aos="fade-up"
                    data-aos-duration="800"
                  >
                    <label for="name" class="form-label">Name</label>
                    <input
                      type="text"
                      class="form-control"
                      id="name" name="name"
                      placeholder="Your Name"
                    />
                  </div>
                  <div
                    class="mb-3"
                    data-aos="fade-up"
                    data-aos-duration="800"
                  >
                    <label for="email" class="form-label">Email</label>
                    <input
                      type="text"
                      class="form-control" id="email" name="email"  placeholder="Enter Your Email"/>
                  </div>
                  <div
                    class="mb-3"
                    data-aos="fade-up"
                    data-aos-duration="800"
                  >
                    <label for="name" class="form-label">Subject</label>
                    <input
                      type="text"
                      class="form-control"
                      id="subject"
                      name="subject"
                      placeholder="Your Subject"
                    />
                  </div>
                  <div
                    class="mb-3"
                    data-aos="fade-up"
                    data-aos-duration="800"
                  >
                    <label for="message" class="form-label">Message</label>
                    <textarea
                      class="form-control"
                      id="message"
                      name="message"
                      rows="3"
                      placeholder="Type your Message"
                    ></textarea>
                  </div>
                  <div
                    class="d-grid"
                    data-aos="fade-up"
                    data-aos-duration="800"
                  >
                  <button class="btn btn-primary" id="submit-form" type="submit">
                    Send Message
                  </button>
                  </div>
                </form>
              <div class="successMsg text-center" style="display:none">
                <img src="frontend-assets/img/Green-Round-Tick.png" width="50" height="50" alt="">
                  <p>Your inquiry has been successfully sent, our Cyber Range Specialist will contact you within 24 to 48 hours.</p>
                </div>
                
              </div>
           


            </div>
          </div>
        </div>

          <div class="col-md-6">
                <div class="d-flex justify-start">
                  <img
                    src="frontend-assets/img/contact-us.png"
                    alt=""
                    class="img-fluid noneForMobile"
                    data-aos="fade-up"
                    data-aos-duration="800"
                  />
                </div>
              </div>
        <!-- ./ Contact us -->

       <?php if(count($blogs) > 0){?>   
        <div class="section-gap position-relative section-gap-top-lg blogs" id="blog">
          <div class="position-absolute top-0 start-0 z-n1">
            <img src="frontend-assets/img/blur-left-5.png" alt="" class="img-fluid position-absolute" />
          </div>

          <div class="container">
            <div class="row">
              <div class="col-lg-12">
                <div class="section-title text-center">
                  <h2>What's New at rootCapture</h2>
                </div>
              </div>
            </div>
          </div>

          <div class="container">
              <div class="row gap-5 gap-lg-0 customBlogHeight">
                <?php foreach($blogs as $blogsV){ ?>
                  <div class="col-lg-4" data-aos="fade-up" data-aos-duration="800">
                    <div class="card h-100">
                      <img
                        src="https://rootcapture.com/adminenterprise/src/public/<?=$blogsV['banner']?>"
                        class="card-img-top"
                        alt=""
                      />
                      <div class="card-body">
                        <span class="card-date"><?php echo date('dM Y', strtotime($blogsV['created_at'])); ?></span>
                        <h5 class="card-title">
                          <a href="#">
                             <?php echo $blogsV['title']; ?>
                          </a>
                        </h5>
                        <a href="https://rootcapture.com/blog-detail.php?id=<?=base64_encode($blogsV['id'])?>" class="btn-link">
                            Read More
                            <svg
                              stroke="currentColor"
                              fill="none"
                              stroke-width="2"
                              viewBox="0 0 24 24"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              height="1em"
                              width="1em"
                              xmlns="http://www.w3.org/2000/svg"
                            >
                              <line x1="7" y1="17" x2="17" y2="7"></line>
                              <polyline points="7 7 17 7 17 17"></polyline>
                            </svg>
                        </a>
                      </div>
                    </div>
                  </div>
                <?php } ?>
              </div>
          </div>
        </div>
       <?php } ?>

        <!-- Newsletter 
        <div class="section-gap">
          
          <div class="container">
            <div
              class="newsletter position-relative"
              data-aos="fade-in"
              data-aos-duration="800"
            >
              <div class="position-absolute bottom-0 end-0 vec-width">
                <img
                  src="frontend-assets/img/section-vec-right.png"
                  alt=""
                  class="img-fluid"
                />
              </div>
              <div class="position-absolute bottom-0 start-0 vec-width">
                <img
                  src="frontend-assets/img/section-vec-left.png"
                  alt=""
                  class="img-fluid"
                />
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <div
                    class="section-title text-center col-lg-6 mx-auto"
                    data-aos="fade-up"
                    data-aos-duration="800"
                    data-aos-delay="250"
                  >
                    <span class="text-uppercase">Keep in touch with us!</span>
                    <h2>Subscribe For Our Latest Updates</h2>
                  </div>
                  <div class="col-lg-6 col-xxl-4 offset-lg-3 offset-xxl-4">
                    <form
                      data-aos="fade-in"
                      data-aos-duration="800"
                      data-aos-delay="500"
                    >
                      <div
                        class="form-wrap d-flex flex-column flex-sm-row gap-3 gap-sm-0 align-items-center position-relative"
                      >
                        <div class="floating-label">
                          <span>Email Address</span>
                          <input type="email" class="form-control" />
                        </div>
                        <button class="btn btn-primary">Subscribe</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
        </div>
        Newsletter -->

        <!-- Footer -->

<?php
  require_once './home-footer.php';
?>
 </body>
</html>
       