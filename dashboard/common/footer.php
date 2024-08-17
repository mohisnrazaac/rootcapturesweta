</main>
</div>
</div>
   
   <div class="copyright row">

        <div class="col">
            Copyright © <?php echo date('Y'); ?> rootCapture. All rights reserved.
        </div>

        <div class="col ">

            <ul class="  d-flex flex-row-reverse " class="menu_items_right">
                <li class="ms-4">
                    <a href="" class="footer_menu">
                        <p>Privacy Policy</p>
                    </a>
                </li>
                <li class="ms-4">
                    <a href="" class="footer_menu">
                        <p>Terms of Services</p>
                    </a>
                </li>
                <li class="ms-4">
                    <a href="" class="footer_menu">English</a>
                </li>
            </ul>

        </div>

    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js"
        integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"
        integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js">
    </script>
 
    <script>
        document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('#sidebar-nav .collapse').forEach(function (collapseElement) {
        collapseElement.addEventListener('show.bs.collapse', function () {
            this.previousElementSibling.querySelector('.fa-caret-down').classList.add('rotate');
        });
        collapseElement.addEventListener('hide.bs.collapse', function () {
            this.previousElementSibling.querySelector('.fa-caret-down').classList.remove('rotate');
        });
    });
});

    </script>

<!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>


<script>
        $(document).ready(function () {
            // Initialize input mask for phone number
            $('#phoneNumber').inputmask('(999) 999-9999', {
                onKeyValidation: function (result) {
                    if (!result) {
                        // Show tooltip if alpha character is entered
                        $('#phoneNumber').tooltip('show');
                    } else {
                        // Hide tooltip if valid character is entered
                        $('#phoneNumber').tooltip('hide');
                    }
                }
            });

            // Enable Bootstrap tooltips
            $('[data-toggle="tooltip"]').tooltip();
        });

        // Graph
        // bebgin line chart display
    var lineChart = document.getElementById("line-chart").getContext('2d');

// line chart options
var options = {
    borderWidth: 2,
    cubicInterpolationMode: 'monotone', // make the line curvy over zigzag
    pointRadius: 2,
    pointHoverRadius: 5,
    pointHoverBackgroundColor: '#fff',
    pointHoverBorderWidth: 4
};

// create linear gradients for line chart
var gradientOne = lineChart.createLinearGradient(0,0,0,lineChart.canvas.clientHeight);
gradientOne.addColorStop(0, 'rgba(51, 169, 247, 0.3)');
gradientOne.addColorStop(1, 'rgba(0, 0, 0, 0)');

var gradientTwo = lineChart.createLinearGradient(0,0,0,lineChart.canvas.clientHeight);
gradientTwo.addColorStop(0, 'rgba(195, 113, 239, 0.15)');
gradientTwo.addColorStop(1, 'rgba(0, 0, 0, 0)');


new Chart(
    lineChart,
    {
        type: 'line',
        data: {
            labels: ['Jan','Feb','Mar','Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [
                {
                    label: 'Users',
                    data: [0,0,0,0,0,30,0,0,0,0,0,0],
                    ...options,
                    borderColor: 'rgb(3, 202, 223)',
                    fill: 'start',
                    backgroundColor: gradientTwo
                }
                // {
                //     label: 'Emergency',
                //     data: [150,230,195,260,220,300,320,490],
                //     ...options,
                //     borderColor: '#33a9f7',
                //     fill: 'start',
                //     backgroundColor: gradientOne
                // }
            ]
        },
        options: {
            plugins: {
                legend: {
                    display: false, // hide display data about the dataset
                },
                tooltip: { // modify graph tooltip
                    backgroundColor: 'rgb(3, 202, 223)',
                    caretPadding: 5,
                    boxWidth: 5,
                    usePointStyle: 'triangle',
                    boxPadding: 3
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false // set display to false to hide the x-axis grid
                    },
                    beginAtZero: true
                },
                y: {
                    ticks: {
                        callback: function(value, index, values) {
                            // return '$ ' + value // prefix '$' to the dataset values
                        },
                        stepSize: 1
                    }
                }
            }
        }
    }
)
    </script>