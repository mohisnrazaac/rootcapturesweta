<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="./index.js"></script>
<script src="https://cdn.linearicons.com/free/1.0.0/svgembedder.min.js"></script>
<script>
  $(document).ready(function() {
        $('#example').DataTable();
  });
    $(document).ready(function() {
        $('#example').DataTable();

        $('.sub-menu ul').hide();

        $(".sub-menu a ").click(function () {
            $(this).parent(".sub-menu").children("ul").slideToggle("100");
            $(this).find(".right").toggleClass("fa-angle-up fa-angle-down");

        });

        $('.col_area_menu').click(()=>{
            $('.sidebar').toggleClass('ico_area');
        });

        $('.menu_mobile').click(()=>{
            $('.sidebar').toggleClass('mob_class');
        });

        $('.menu_mobile').click(()=>{
            $('.fa-bars').toggleClass('cross_ci');
        });

        $('.slide_to').click(()=>{
            $('.remain_nav').slideToggle('cross_ci');
            $('.slide_to').find(".right_mob").toggleClass("lnr-menu lnr-cross");
        });

    });
    
    function showNav()
    {
        document.querySelector('.remain_nav').style.setProperty("display", "flex");
    }

    function closeNav(){
        document.querySelector('.remain_nav').style.setProperty("display", "none");
    }

    function closeSide(){
        document.querySelector('.sidebar').style.setProperty("display", "none");
    }

    function dro() {
      document.getElementById("myDropdown").classList.toggle("show");
    }

    window.onclick = function(event) {
      if (!event.target.matches('.dropbtn')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
          var openDropdown = dropdowns[i];
          if (openDropdown.classList.contains('show')) {
            openDropdown.classList.remove('show');
          }
        }
      }
    }

    function dro2() {
      document.getElementById("myDropdown2").classList.toggle("show");
    }

    window.onclick = function(event) {
      if (!event.target.matches('.dropbtn')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
          var openDropdown = dropdowns[i];
          if (openDropdown.classList.contains('show')) {
            openDropdown.classList.remove('show');
          }
        }
      }
    }
</script>