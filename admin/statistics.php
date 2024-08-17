<script>
    var dataRender = false;
    $(".statistics-tabs").on('shown.bs.tab', function (e) { 
       
        try
        {

            getcorkThemeObject = localStorage.getItem("app-theme");
            getParseObject = JSON.parse(getcorkThemeObject)
            ParsedObject = getParseObject;

            if(!dataRender)
            {
                if (ParsedObject.settings.layout.darkMode) { 
                
                    var Theme = 'dark';

                    Apex.tooltip = {
                        theme: Theme
                    }

                
                    /*
                        =================================
                            Revenue Monthly | Options
                        =================================
                    */
                    var options1 = 
                    {
                        chart: {
                            fontFamily: 'Nunito, sans-serif',
                            height: 365,
                            type: 'area',
                            zoom: {
                                enabled: false
                            },
                            dropShadow: {
                            enabled: true,
                            opacity: 0.2,
                            blur: 10,
                            left: -7,
                            top: 22
                            },
                            toolbar: {
                            show: false
                            },
                        },
                        colors: [ 
                            <?php foreach($SQLGetallTeam as $SQLGetallTeamV){ ?>
                                '<?=$SQLGetallTeamV["color_code"]?>',
                            <?php } ?>
                            
                        ],
                        dataLabels: {
                            enabled: false
                        },
                        markers: {
                            discrete: [{
                            seriesIndex: 0,
                            dataPointIndex: 7,
                            fillColor: '#000',
                            strokeColor: '#000',
                            size: 5
                        }, {
                            seriesIndex: 2,
                            dataPointIndex: 11,
                            fillColor: '#000',
                            strokeColor: '#000',
                            size: 4
                        }]
                        },
                        subtitle: {
                            text: <?=$totalUser?>,
                            align: 'left',
                            margin: 0,
                            offsetX: 100,
                            offsetY: 20,
                            floating: false,
                            style: {
                            fontSize: '18px',
                            color:  '#00ab55'
                            }
                        },
                        title: {
                            text: 'Total Users',
                            align: 'left',
                            margin: 0,
                            offsetX: -10,
                            offsetY: 20,
                            floating: false,
                            style: {
                            fontSize: '18px',
                            color:  '#bfc9d4'
                            },
                        },
                        stroke: {
                            show: true,
                            curve: 'smooth',
                            width: 2,
                            lineCap: 'square'
                        },
                        series: [
                            <?php 
                                foreach($SQLGetallTeam as $SQLGetallTeamV){ 
                                  $id =  $SQLGetallTeamV['id'];
                                  $name =  $SQLGetallTeamV['name'];
                                  $sqlTotalUserJan = $odb -> query("SELECT count(`users`.`id`) as month_wise FROM `users` INNER JOIN `teams` ON `teams`.`id` = `users`.rank WHERE MONTH(`users`.`datetime`) = 1 AND `teams`.`name` LIKE '$name' AND users.college_id = $college_id");
                                  $totalUserJan = $sqlTotalUserJan->fetchColumn(); 
  
                                  $sqlTotalUserFeb = $odb -> query("SELECT count(`users`.`id`) as month_wise FROM `users` INNER JOIN `teams` ON `teams`.`id` = `users`.rank WHERE MONTH(`users`.`datetime`) = 2 AND `teams`.`name` LIKE '$name' AND users.college_id = $college_id");
                                  $totalUserFeb = $sqlTotalUserFeb->fetchColumn();
  
                                  $sqlTotalUserMar = $odb -> query("SELECT count(`users`.`id`) as month_wise FROM `users` INNER JOIN `teams` ON `teams`.`id` = `users`.rank WHERE MONTH(`users`.`datetime`) = 3 AND `teams`.`name` LIKE '$name' AND users.college_id = $college_id");
                                  $totalUserMar = $sqlTotalUserMar->fetchColumn();
  
                                  $sqlTotalUserApr = $odb -> query("SELECT count(`users`.`id`) as month_wise FROM `users` INNER JOIN `teams` ON `teams`.`id` = `users`.rank WHERE MONTH(`users`.`datetime`) = 4 AND `teams`.`name` LIKE '$name' AND users.college_id = $college_id");
                                  $totalUserApr = $sqlTotalUserApr->fetchColumn();

                                  $sqlTotalUserMay = $odb -> query("SELECT count(`users`.`id`) as month_wise FROM `users` INNER JOIN `teams` ON `teams`.`id` = `users`.rank WHERE MONTH(`users`.`datetime`) = 5 AND `teams`.`name` LIKE '$name' AND users.college_id = $college_id");
                                  $totalUserMay = $sqlTotalUserMay->fetchColumn();

                                  $sqlTotalUserJun = $odb -> query("SELECT count(`users`.`id`) as month_wise FROM `users` INNER JOIN `teams` ON `teams`.`id` = `users`.rank WHERE MONTH(`users`.`datetime`) = 6 AND `teams`.`name` LIKE '$name' AND users.college_id = $college_id");
                                  $totalUserJun = $sqlTotalUserJun->fetchColumn();

                                  $sqlTotalUserJul = $odb -> query("SELECT count(`users`.`id`) as month_wise FROM `users` INNER JOIN `teams` ON `teams`.`id` = `users`.rank WHERE MONTH(`users`.`datetime`) = 7 AND `teams`.`name` LIKE '$name' AND users.college_id = $college_id");
                                  $totalUserJul = $sqlTotalUserJul->fetchColumn();

                                  $sqlTotalUserAug = $odb -> query("SELECT count(`users`.`id`) as month_wise FROM `users` INNER JOIN `teams` ON `teams`.`id` = `users`.rank WHERE MONTH(`users`.`datetime`) = 8 AND `teams`.`name` LIKE '$name' AND users.college_id = $college_id");
                                  $totalUserAug = $sqlTotalUserAug->fetchColumn();

                                  $sqlTotalUserSep = $odb -> query("SELECT count(`users`.`id`) as month_wise FROM `users` INNER JOIN `teams` ON `teams`.`id` = `users`.rank WHERE MONTH(`users`.`datetime`) = 9 AND `teams`.`name` LIKE '$name' AND users.college_id = $college_id");
                                  $totalUserSep = $sqlTotalUserSep->fetchColumn();

                                  
                                  $sqlTotalUserOct = $odb -> query("SELECT count(`users`.`id`) as month_wise FROM `users` INNER JOIN `teams` ON `teams`.`id` = `users`.rank WHERE MONTH(`users`.`datetime`) = 10 AND `teams`.`name` LIKE '$name' AND users.college_id = $college_id");
                                  $totalUserOct = $sqlTotalUserOct->fetchColumn();

                                  $sqlTotalUserNov = $odb -> query("SELECT count(`users`.`id`) as month_wise FROM `users` INNER JOIN `teams` ON `teams`.`id` = `users`.rank WHERE MONTH(`users`.`datetime`) = 11 AND `teams`.`name` LIKE '$name' AND users.college_id = $college_id");
                                  $totalUserNov = $sqlTotalUserNov->fetchColumn();

                                  $sqlTotalUserDec = $odb -> query("SELECT count(`users`.`id`) as month_wise FROM `users` INNER JOIN `teams` ON `teams`.`id` = `users`.rank WHERE MONTH(`users`.`datetime`) = 12 AND `teams`.`name` LIKE '$name' AND users.college_id = $college_id");
                                  $totalUserDec = $sqlTotalUserDec->fetchColumn();
                            ?>
                                {
                                name: '<?=$SQLGetallTeamV["name"]?>',
                                data: [<?=$totalUserJan?>, <?=$totalUserFeb?>, <?=$totalUserMar?>, <?=$totalUserApr?>, <?=$totalUserMay?>, <?=$totalUserJun?>, <?=$totalUserJul?>, <?=$totalUserAug?>, <?=$totalUserSep?>, <?=$totalUserOct?>, <?=$totalUserNov?>, <?=$totalUserDec?>]
                                }, 
                            <?php } ?>
                            ],
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        xaxis: {
                            axisBorder: {
                            show: false
                            },
                            axisTicks: {
                            show: false
                            },
                            crosshairs: {
                            show: true
                            },
                            labels: {
                            offsetX: 0,
                            offsetY: 5,
                            style: {
                                fontSize: '12px',
                                fontFamily: 'Nunito, sans-serif',
                                cssClass: 'apexcharts-xaxis-title',
                            },
                            }
                        },
                        yaxis: {
                            labels: {
                            formatter: function(value, index) {
                                return (value)
                                // return (value / 1000) + 'K'
                            },
                            offsetX: -15,
                            offsetY: 0,
                            style: {
                                fontSize: '12px',
                                fontFamily: 'Nunito, sans-serif',
                                cssClass: 'apexcharts-yaxis-title',
                            },
                            }
                        },
                        grid: {
                            borderColor: '#191e3a',
                            strokeDashArray: 5,
                            xaxis: {
                                lines: {
                                    show: true
                                }
                            },   
                            yaxis: {
                                lines: {
                                    show: false,
                                }
                            },
                            padding: {
                            top: -50,
                            right: 0,
                            bottom: 0,
                            left: 5
                            },
                        }, 
                        legend: {
                            position: 'top',
                            horizontalAlign: 'right',
                            offsetY: -50,
                            fontSize: '16px',
                            fontFamily: 'Quicksand, sans-serif',
                            markers: {
                            width: 10,
                            height: 10,
                            strokeWidth: 0,
                            strokeColor: '#fff',
                            fillColors: undefined,
                            radius: 12,
                            onClick: undefined,
                            offsetX: -5,
                            offsetY: 0
                            },    
                            itemMargin: {
                            horizontal: 10,
                            vertical: 20
                            }
                            
                        },
                        tooltip: {
                            theme: Theme,
                            marker: {
                            show: true,
                            },
                            x: {
                            show: false,
                            }
                        },
                        fill: {
                            type:"gradient",
                            gradient: {
                                type: "vertical",
                                shadeIntensity: 1,
                                inverseColors: !1,
                                opacityFrom: .19,
                                opacityTo: .05,
                                stops: [100, 100]
                            }
                        },
                        responsive: [{
                            breakpoint: 575,
                            options: {
                            legend: {
                                offsetY: -50,
                            },
                            },
                        }]
                    }                    
                
                    var options = {
                        chart: {
                            type: 'donut',
                            width: 370,
                            height: 430
                        },
                        colors: ['#33FF36', '#8e9093'],
                        dataLabels: {
                        enabled: false
                        },
                        legend: {
                            position: 'bottom',
                            horizontalAlign: 'center',
                            fontSize: '12px',
                            markers: {
                            width: 10,
                            height: 10,
                            offsetX: -5,
                            offsetY: 0
                            },
                            itemMargin: {
                            horizontal: 4,
                            vertical: 10
                            }
                        },
                        plotOptions: {
                        pie: {
                            donut: {
                            size: '75%',
                            background: 'transparent',
                            labels: {
                                show: true,
                                name: {
                                show: true,
                                fontSize: '29px',
                                fontFamily: 'Nunito, sans-serif',
                                color: undefined,
                                offsetY: -10
                                },
                                value: {
                                show: true,
                                fontSize: '26px',
                                fontFamily: 'Nunito, sans-serif',
                                color: '#bfc9d4',
                                offsetY: 16,
                                formatter: function (val) {
                                    return val
                                }
                                },
                                total: {
                                show: true,
                                showAlways: true,
                                label: 'Total',
                                color: '#888ea8',
                                formatter: function (w) {
                                    return w.globals.seriesTotals.reduce( function(a, b) {
                                    return a + b
                                    }, 0)
                                }
                                }
                            }
                            }
                        }
                        },
                        stroke: {
                        show: true,
                        width: 15,
                        colors: '#0e1726'
                        },
                        series: [<?=$activeSession?>, <?=$inactiveSession?>],
                        labels: ['Active', 'Inactive'],
                
                        responsive: [
                        { 
                            breakpoint: 1440, options: {
                            chart: {
                                width: 325
                            },
                            }
                        },
                        { 
                            breakpoint: 1199, options: {
                            chart: {
                                width: 380
                            },
                            }
                        },
                        { 
                            breakpoint: 575, options: {
                            chart: {
                                width: 320
                            },
                            }
                        },
                        ],
                    } 

                    var chart_3_options = {
                        chart: {
                            type: 'donut',
                            width: 370,
                            height: 430
                        },
                        colors: [
                            <?php foreach($SQLGetallTeam as $SQLGetallTeamV){ ?>
                                '<?=$SQLGetallTeamV["color_code"]?>',
                            <?php } ?>
                        ],
                        dataLabels: {
                            enabled: false
                        },
                        legend: {
                            position: 'bottom',
                            horizontalAlign: 'center',
                            fontSize: '12px',
                            markers: {
                                width: 10,
                                height: 10,
                                offsetX: -5,
                                offsetY: 0
                            },
                            itemMargin: {
                                horizontal: 4,
                                vertical: 10
                            }
                        },
                        plotOptions: {
                            pie: {
                            donut: {
                                size: '75%',
                                background: 'transparent',
                                labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '29px',
                                    fontFamily: 'Nunito, sans-serif',
                                    color: undefined,
                                    offsetY: -10
                                },
                                value: {
                                    show: true,
                                    fontSize: '26px',
                                    fontFamily: 'Nunito, sans-serif',
                                    color: '#bfc9d4',
                                    offsetY: 16,
                                    formatter: function (val) {
                                    return val
                                    }
                                },
                                total: {
                                    show: true,
                                    showAlways: true,
                                    label: 'Total',
                                    color: '#888ea8',
                                    formatter: function (w) {
                                    return w.globals.seriesTotals.reduce( function(a, b) {
                                        return a + b
                                    }, 0)
                                    }
                                }
                                }
                            }
                            }
                        },
                        stroke: {
                            show: true,
                            width: 15,
                            colors: '#0e1726'
                        },
                        series: [
                            <?php foreach($SQLGetallTeam as $SQLGetallTeamV){ 
                                $name =  $SQLGetallTeamV['name'];
                                 $sqlTotalUsersRed = $odb -> query("SELECT count(`users`.`id`) as total_user FROM `users` INNER JOIN `teams` ON `teams`.`id` = `users`.rank WHERE `teams`.`name` LIKE '$name' AND users.college_id = $college_id");
                                 $totalUserRedTeam = $sqlTotalUsersRed->fetchColumn();
                                 echo $totalUserRedTeam.',';
                             } ?>    
                        ],
                        labels: [
                            <?php foreach($SQLGetallTeam as $SQLGetallTeamV){ ?>
                                '<?=strtok($SQLGetallTeamV["name"], " ")?>',
                            <?php } ?>
                        ],
                    
                        responsive: [
                            { 
                            breakpoint: 1440, options: {
                                chart: {
                                width: 325
                                },
                            }
                            },
                            { 
                            breakpoint: 1199, options: {
                                chart: {
                                width: 380
                                },
                            }
                            },
                            { 
                            breakpoint: 575, options: {
                                chart: {
                                width: 320
                                },
                            }
                            },
                        ],
                    }

                    var chart_4_options = {
                            chart: {
                                type: 'donut',
                                width: 370,
                                height: 430
                            },
                            colors: [<?php foreach($SQLGetTeam as $SQLGetTeamV){ ?> '<?=$SQLGetTeamV["color_code"]?>',<?php } ?>],
                            dataLabels: {
                            enabled: false
                            },
                            legend: {
                            position: 'bottom',
                            horizontalAlign: 'center',
                            fontSize: '12px',
                            markers: {
                            width: 10,
                            height: 10,
                            offsetX: -5,
                            offsetY: 0
                            },
                            itemMargin: {
                            horizontal: 4,
                            vertical: 10
                            }
                            },
                            plotOptions: {
                            pie: {
                            donut: {
                            size: '75%',
                            background: 'transparent',
                            labels: {
                                show: true,
                                name: {
                                show: true,
                                fontSize: '29px',
                                fontFamily: 'Nunito, sans-serif',
                                color: undefined,
                                offsetY: -10
                                },
                                value: {
                                show: true,
                                fontSize: '26px',
                                fontFamily: 'Nunito, sans-serif',
                                color: '#bfc9d4',
                                offsetY: 16,
                                formatter: function (val) {
                                    return val
                                }
                                },
                                total: {
                                show: true,
                                showAlways: true,
                                label: 'Total',
                                color: '#888ea8',
                                formatter: function (w) {
                                    return w.globals.seriesTotals.reduce( function(a, b) {
                                    return a + b
                                    }, 0)
                                }
                                }
                            }
                            }
                            }
                            },
                            stroke: {
                            show: true,
                            width: 15,
                            colors: '#0e1726'
                            },
                            series: [
                                <?php foreach($SQLGetTeam as $SQLGetTeamV){ 
                                    $id = $SQLGetTeamV['id'];
                                     $sqlTotalAsset = $odb -> query("SELECT count(asset.id) as total_asset,teams.name,teams.color_code FROM `asset` INNER JOIN `teams` ON `teams`.id = `asset`.`team` WHERE `teams`.`id` = $id AND asset.college_id = $college_id");
                                     $sqlTotalAsset = $sqlTotalAsset->fetch(); 
                                     echo $sqlTotalAsset["total_asset"].',';
                                    
                                }
                                ?>
                            ],
                            labels: [
                                <?php foreach($SQLGetTeam as $SQLGetTeamV){ 
                                    $id = $SQLGetTeamV['id'];
                                     $sqlTotalAsset = $odb -> query("SELECT count(asset.id) as total_asset,teams.name,teams.color_code FROM `asset` INNER JOIN `teams` ON `teams`.id = `asset`.`team` WHERE `teams`.`id` = $id AND asset.college_id = $college_id");
                                     $sqlTotalAsset = $sqlTotalAsset->fetchAll(); ?>
                                     '<?=$sqlTotalAsset["name"]?>', <?php  }?>
                            ],
                
                            responsive: [
                            { 
                                breakpoint: 1440, options: {
                                chart: {
                                    width: 325
                                },
                                }
                            },
                            { 
                                breakpoint: 1199, options: {
                                chart: {
                                    width: 380
                                },
                                }
                            },
                            { 
                                breakpoint: 575, options: {
                                chart: {
                                    width: 320
                                },
                                }
                            },
                            ],
                    }


                }
                else
                {   

                    var Theme = 'dark';

                    Apex.tooltip = {
                        theme: Theme
                    }

                    /**
                        ==============================
                        |    @Options Charts Script   |
                        ==============================
                    */
                    
                                
                    

                    var options1 = {
                        chart: {
                        fontFamily: 'Nunito, sans-serif',
                        height: 365,
                        type: 'area',
                        zoom: {
                            enabled: false
                        },
                        dropShadow: {
                            enabled: true,
                            opacity: 0.2,
                            blur: 10,
                            left: -7,
                            top: 22
                        },
                        toolbar: {
                            show: false
                        },
                        },
                        colors: 
                        [
                            <?php foreach($SQLGetallTeam as $SQLGetallTeamV){ ?>
                                '<?=$SQLGetallTeamV["color_code"]?>',
                            <?php } ?>    
                        ],
                        dataLabels: {
                            enabled: false
                        },
                        markers: {
                        discrete: [{
                        seriesIndex: 0,
                        dataPointIndex: 7,
                        fillColor: '#000',
                        strokeColor: '#000',
                        size: 5
                        }, {
                        seriesIndex: 2,
                        dataPointIndex: 11,
                        fillColor: '#000',
                        strokeColor: '#000',
                        size: 4
                        }]
                        },
                        subtitle: {
                        text: <?=$totalUser?>,
                        align: 'left',
                        margin: 0,
                        offsetX: 100,
                        offsetY: 20,
                        floating: false,
                        style: {
                        fontSize: '18px',
                        color:  '#4361ee'
                            }
                            },
                            title: {
                            text: 'Total Users',
                            align: 'left',
                            margin: 0,
                            offsetX: -10,
                            offsetY: 20,
                            floating: false,
                            style: {
                                fontSize: '18px',
                                color:  '#0e1726'
                            },
                            },
                        stroke: {
                        show: true,
                        curve: 'smooth',
                            width: 2,
                            lineCap: 'square'
                        },
                            series: 
                            [
                                <?php 
                                foreach($SQLGetallTeam as $SQLGetallTeamV){ 
                                  $id =  $SQLGetallTeamV['id'];
                                  $name =  $SQLGetallTeamV['name'];
                                  $sqlTotalUserJan = $odb -> query("SELECT count(`users`.`id`) as month_wise FROM `users` INNER JOIN `teams` ON `teams`.`id` = `users`.rank WHERE MONTH(`users`.`datetime`) = 1 AND `teams`.`name` LIKE '$name' AND users.college_id = $college_id");
                                  $totalUserJan = $sqlTotalUserJan->fetchColumn(); 
  
                                  $sqlTotalUserFeb = $odb -> query("SELECT count(`users`.`id`) as month_wise FROM `users` INNER JOIN `teams` ON `teams`.`id` = `users`.rank WHERE MONTH(`users`.`datetime`) = 2 AND `teams`.`name` LIKE '$name' AND users.college_id = $college_id");
                                  $totalUserFeb = $sqlTotalUserFeb->fetchColumn();
  
                                  $sqlTotalUserMar = $odb -> query("SELECT count(`users`.`id`) as month_wise FROM `users` INNER JOIN `teams` ON `teams`.`id` = `users`.rank WHERE MONTH(`users`.`datetime`) = 3 AND `teams`.`name` LIKE '$name' AND users.college_id = $college_id");
                                  $totalUserMar = $sqlTotalUserMar->fetchColumn();
  
                                  $sqlTotalUserApr = $odb -> query("SELECT count(`users`.`id`) as month_wise FROM `users` INNER JOIN `teams` ON `teams`.`id` = `users`.rank WHERE MONTH(`users`.`datetime`) = 4 AND `teams`.`name` LIKE '$name' AND users.college_id = $college_id");
                                  $totalUserApr = $sqlTotalUserApr->fetchColumn();

                                  $sqlTotalUserMay = $odb -> query("SELECT count(`users`.`id`) as month_wise FROM `users` INNER JOIN `teams` ON `teams`.`id` = `users`.rank WHERE MONTH(`users`.`datetime`) = 5 AND `teams`.`name` LIKE '$name' AND users.college_id = $college_id");
                                  $totalUserMay = $sqlTotalUserMay->fetchColumn();

                                  $sqlTotalUserJun = $odb -> query("SELECT count(`users`.`id`) as month_wise FROM `users` INNER JOIN `teams` ON `teams`.`id` = `users`.rank WHERE MONTH(`users`.`datetime`) = 6 AND `teams`.`name` LIKE '$name' AND users.college_id = $college_id");
                                  $totalUserJun = $sqlTotalUserJun->fetchColumn();

                                  $sqlTotalUserJul = $odb -> query("SELECT count(`users`.`id`) as month_wise FROM `users` INNER JOIN `teams` ON `teams`.`id` = `users`.rank WHERE MONTH(`users`.`datetime`) = 7 AND `teams`.`name` LIKE '$name' AND users.college_id = $college_id");
                                  $totalUserJul = $sqlTotalUserJul->fetchColumn();

                                  $sqlTotalUserAug = $odb -> query("SELECT count(`users`.`id`) as month_wise FROM `users` INNER JOIN `teams` ON `teams`.`id` = `users`.rank WHERE MONTH(`users`.`datetime`) = 8 AND `teams`.`name` LIKE '$name' AND users.college_id = $college_id");
                                  $totalUserAug = $sqlTotalUserAug->fetchColumn();

                                  $sqlTotalUserSep = $odb -> query("SELECT count(`users`.`id`) as month_wise FROM `users` INNER JOIN `teams` ON `teams`.`id` = `users`.rank WHERE MONTH(`users`.`datetime`) = 9 AND `teams`.`name` LIKE '$name' AND users.college_id = $college_id");
                                  $totalUserSep = $sqlTotalUserSep->fetchColumn();

                                  
                                  $sqlTotalUserOct = $odb -> query("SELECT count(`users`.`id`) as month_wise FROM `users` INNER JOIN `teams` ON `teams`.`id` = `users`.rank WHERE MONTH(`users`.`datetime`) = 10 AND `teams`.`name` LIKE '$name' AND users.college_id = $college_id");
                                  $totalUserOct = $sqlTotalUserOct->fetchColumn();

                                  $sqlTotalUserNov = $odb -> query("SELECT count(`users`.`id`) as month_wise FROM `users` INNER JOIN `teams` ON `teams`.`id` = `users`.rank WHERE MONTH(`users`.`datetime`) = 11 AND `teams`.`name` LIKE '$name' AND users.college_id = $college_id");
                                  $totalUserNov = $sqlTotalUserNov->fetchColumn();

                                  $sqlTotalUserDec = $odb -> query("SELECT count(`users`.`id`) as month_wise FROM `users` INNER JOIN `teams` ON `teams`.`id` = `users`.rank WHERE MONTH(`users`.`datetime`) = 12 AND `teams`.`name` LIKE '$name' AND users.college_id = $college_id");
                                  $totalUserDec = $sqlTotalUserDec->fetchColumn();
                            ?>
                                {
                                name: '<?=$SQLGetallTeamV["name"]?>',
                                data: [<?=$totalUserJan?>, <?=$totalUserFeb?>, <?=$totalUserMar?>, <?=$totalUserApr?>, <?=$totalUserMay?>, <?=$totalUserJun?>, <?=$totalUserJul?>, <?=$totalUserAug?>, <?=$totalUserSep?>, <?=$totalUserOct?>, <?=$totalUserNov?>, <?=$totalUserDec?>]
                                }, 
                            <?php } ?>      
                            ],
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        xaxis: {
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: false
                        },
                        crosshairs: {
                            show: true
                        },
                        labels: {
                            offsetX: 0,
                            offsetY: 5,
                            style: {
                                fontSize: '12px',
                                fontFamily: 'Nunito, sans-serif',
                                cssClass: 'apexcharts-xaxis-title',
                            },
                        }
                        },
                        yaxis: {
                        labels: {
                            formatter: function(value, index) {
                            return (value)
                            //   return (value / 1000) + 'K'
                            },
                            offsetX: -15,
                            offsetY: 0,
                            style: {
                                fontSize: '12px',
                                fontFamily: 'Nunito, sans-serif',
                                cssClass: 'apexcharts-yaxis-title',
                            },
                        }
                        },
                        grid: {
                        borderColor: '#e0e6ed',
                        strokeDashArray: 5,
                        xaxis: {
                            lines: {
                                show: true
                            }
                        },   
                        yaxis: {
                            lines: {
                                show: false,
                            }
                        },
                        padding: {
                            top: -50,
                            right: 0,
                            bottom: 0,
                            left: 5
                        },
                        }, 
                        legend: {
                        position: 'top',
                        horizontalAlign: 'right',
                        offsetY: -50,
                        fontSize: '16px',
                        fontFamily: 'Quicksand, sans-serif',
                        markers: {
                            width: 10,
                            height: 10,
                            strokeWidth: 0,
                            strokeColor: '#fff',
                            fillColors: undefined,
                            radius: 12,
                            onClick: undefined,
                            offsetX: -5,
                            offsetY: 0
                        },    
                        itemMargin: {
                            horizontal: 10,
                            vertical: 20
                        }
                        
                        },
                        tooltip: {
                        theme: Theme,
                        marker: {
                            show: true,
                        },
                        x: {
                            show: false,
                        }
                        },
                        fill: {
                            type:"gradient",
                            gradient: {
                                type: "vertical",
                                shadeIntensity: 1,
                                inverseColors: !1,
                                opacityFrom: .19,
                                opacityTo: .05,
                                stops: [100, 100]
                            }
                        },
                        responsive: [{
                        breakpoint: 575,
                        options: {
                            legend: {
                                offsetY: -50,
                            },
                        },
                        }]
                    }


                    
                    var options = {
                        chart: {
                            type: 'donut',
                            width: 370,
                            height: 430
                        },
                        colors: ['#33FF36', '#8e9093'],
                        dataLabels: {
                            enabled: false
                        },
                        legend: {
                            position: 'bottom',
                            horizontalAlign: 'center',
                            fontSize: '12px',
                            markers: {
                                width: 10,
                                height: 10,
                                offsetX: -5,
                                offsetY: 0
                            },
                            itemMargin: {
                                horizontal: 4,
                                vertical: 10
                            }
                        },
                        plotOptions: {
                            pie: {
                            donut: {
                                size: '75%',
                                background: 'transparent',
                                labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '29px',
                                    fontFamily: 'Nunito, sans-serif',
                                    color: undefined,
                                    offsetY: -10
                                },
                                value: {
                                    show: true,
                                    fontSize: '26px',
                                    fontFamily: 'Nunito, sans-serif',
                                    color: '#0e1726',
                                    offsetY: 16,
                                    formatter: function (val) {
                                    return val
                                    }
                                },
                                total: {
                                    show: true,
                                    showAlways: true,
                                    label: 'Total',
                                    color: '#000',
                                    formatter: function (w) {
                                    return w.globals.seriesTotals.reduce( function(a, b) {
                                        return a + b
                                    }, 0)
                                    }
                                }
                                }
                            }
                            }
                        },
                        stroke: {
                            show: true,
                            width: 15,
                            colors: '#fff'
                        },
                        series: [<?=$activeSession?>, <?=$inactiveSession?>],
                        labels: ['Active', 'Inactive'],
                    
                        responsive: [
                            { 
                            breakpoint: 1440, options: {
                                chart: {
                                width: 325
                                },
                            }
                            },
                            { 
                            breakpoint: 1199, options: {
                                chart: {
                                width: 380
                                },
                            }
                            },
                            { 
                            breakpoint: 575, options: {
                                chart: {
                                width: 320
                                },
                            }
                            },
                        ],
                    }
                
                    var chart_3_options = {
                        chart: {
                            type: 'donut',
                            width: 370,
                            height: 430
                        },
                        colors: [ <?php foreach($SQLGetallTeam as $SQLGetallTeamV){?>'<?=$SQLGetallTeamV["color_code"]?>',<?php } ?>],
                        dataLabels: {
                            enabled: false
                        },
                        legend: {
                            position: 'bottom',
                            horizontalAlign: 'center',
                            fontSize: '12px',
                            markers: {
                                width: 10,
                                height: 10,
                                offsetX: -5,
                                offsetY: 0
                            },
                            itemMargin: {
                                horizontal: 4,
                                vertical: 10
                            }
                        },
                        plotOptions: {
                            pie: {
                            donut: {
                                size: '75%',
                                background: 'transparent',
                                labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '29px',
                                    fontFamily: 'Nunito, sans-serif',
                                    color: undefined,
                                    offsetY: -10
                                },
                                value: {
                                    show: true,
                                    fontSize: '26px',
                                    fontFamily: 'Nunito, sans-serif',
                                    color: '#0e1726',
                                    offsetY: 16,
                                    formatter: function (val) {
                                    return val
                                    }
                                },
                                total: {
                                    show: true,
                                    showAlways: true,
                                    label: 'Total',
                                    color: '#888ea8',
                                    formatter: function (w) {
                                    return w.globals.seriesTotals.reduce( function(a, b) {
                                        return a + b
                                    }, 0)
                                    }
                                }
                                }
                            }
                            }
                        },
                        stroke: {
                            show: true,
                            width: 15,
                            colors: '#ffffff'
                        },
                        series: [
                            <?php foreach($SQLGetallTeam as $SQLGetallTeamV){ 
                                $name =  $SQLGetallTeamV['name'];
                                 $sqlTotalUsersRed = $odb -> query("SELECT count(`users`.`id`) as total_user FROM `users` INNER JOIN `teams` ON `teams`.`id` = `users`.rank WHERE `teams`.`name` LIKE '$name' AND users.college_id = $college_id");
                                 $totalUserRedTeam = $sqlTotalUsersRed->fetchColumn();
                                 echo $totalUserRedTeam.',';
                             } ?>    
                        ],
                        labels: [
                            <?php foreach($SQLGetallTeam as $SQLGetallTeamV){ ?>
                                '<?=strtok($SQLGetallTeamV["name"], " ")?>',
                            <?php } ?>
                        ],
                    
                        responsive: [
                            { 
                            breakpoint: 1440, options: {
                                chart: {
                                width: 325
                                },
                            }
                            },
                            { 
                            breakpoint: 1199, options: {
                                chart: {
                                width: 380
                                },
                            }
                            },
                            { 
                            breakpoint: 575, options: {
                                chart: {
                                width: 320
                                },
                            }
                            },
                        ],
                    }

                    var chart_4_options = {
                        chart: {
                            type: 'donut',
                            width: 370,
                            height: 430
                        },
                        colors: [<?php foreach($SQLGetTeam as $SQLGetTeamV){ ?> '<?=$SQLGetTeamV["color_code"]?>',<?php } ?>],
                        dataLabels: {
                            enabled: false
                        },
                        legend: {
                            position: 'bottom',
                            horizontalAlign: 'center',
                            fontSize: '12px',
                            markers: {
                                width: 10,
                                height: 10,
                                offsetX: -5,
                                offsetY: 0
                            },
                            itemMargin: {
                                horizontal: 4,
                                vertical: 10
                            }
                        },
                        plotOptions: {
                            pie: {
                            donut: {
                                size: '75%',
                                background: 'transparent',
                                labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '29px',
                                    fontFamily: 'Nunito, sans-serif',
                                    color: undefined,
                                    offsetY: -10
                                },
                                value: {
                                    show: true,
                                    fontSize: '26px',
                                    fontFamily: 'Nunito, sans-serif',
                                    color: '#0e1726',
                                    offsetY: 16,
                                    formatter: function (val) {
                                    return val
                                    }
                                },
                                total: {
                                    show: true,
                                    showAlways: true,
                                    label: 'Total',
                                    color: '#888ea8',
                                    formatter: function (w) {
                                    return w.globals.seriesTotals.reduce( function(a, b) {
                                        return a + b
                                    }, 0)
                                    }
                                }
                                }
                            }
                            }
                        },
                        stroke: {
                            show: true,
                            width: 15,
                            colors: '#fff'
                        },
                        series: [
                            <?php foreach($SQLGetTeam as $SQLGetTeamV){ 
                                $id = $SQLGetTeamV['id'];
                                    $sqlTotalAsset = $odb -> query("SELECT count(asset.id) as total_asset,teams.name,teams.color_code FROM `asset` INNER JOIN `teams` ON `teams`.id = `asset`.`team` WHERE `teams`.`id` = $id AND asset.college_id = $college_id");
                                    $sqlTotalAsset = $sqlTotalAsset->fetch(); 
                                    echo $sqlTotalAsset["total_asset"].',';
                                
                            }
                            ?>
                        ],
                        labels: [
                            <?php foreach($SQLGetTeam as $SQLGetTeamV){ 
                                $id = $SQLGetTeamV['id'];
                                    $sqlTotalAsset = $odb -> query("SELECT count(asset.id) as total_asset,teams.name,teams.color_code FROM `asset` INNER JOIN `teams` ON `teams`.id = `asset`.`team` WHERE `teams`.`id` = $id AND asset.college_id = $college_id");
                                    $sqlTotalAsset = $sqlTotalAsset->fetchAll(); ?>
                                    '<?=$sqlTotalAsset["name"]?>', <?php  }?>
                        ],                    
                        responsive: [
                            { 
                            breakpoint: 1440, options: {
                                chart: {
                                width: 325
                                },
                            }
                            },
                            { 
                            breakpoint: 1199, options: {
                                chart: {
                                width: 380
                                },
                            }
                            },
                            { 
                            breakpoint: 575, options: {
                                chart: {
                                width: 320
                                },
                            }
                            },
                        ],
                    }


               
                }
                dataRender = true;
            }
            else
            {
                // update grapgh when tab is clicked again and again
                    // getcorkThemeObject = localStorage.getItem("app-theme");
                    // getParseObject = JSON.parse(getcorkThemeObject)
                    // ParsedObject = getParseObject;

                    // if (ParsedObject.settings.layout.darkMode)
                    // {
                        
                    //     chart1.updateOptions({
                    //         colors: ['#e7515a', '#2196f3'],
                    //         subtitle: {
                    //             style: {
                    //             color:  '#00ab55'
                    //             }
                    //         },
                    //         title: {
                    //             style: {
                    //             color:  '#bfc9d4'
                    //             }
                    //         },
                    //         grid: {
                    //             borderColor: '#191e3a',
                    //         }
                    //     })

                    //     chart.updateOptions({
                    //         stroke: {
                    //             colors: '#0e1726'
                    //         },
                    //         plotOptions: {
                    //             pie: {
                    //             donut: {
                    //                 labels: {
                    //                 value: {
                    //                     color: '#bfc9d4'
                    //                 }
                    //                 }
                    //             }
                    //             }
                    //         }
                    //     })

                    //     chart3.updateOptions({
                    //         stroke: {
                    //         colors: '#0e1726'
                    //         },
                    //         plotOptions: {
                    //         pie: {
                    //             donut: {
                    //             labels: {
                    //                 value: {
                    //                 color: '#bfc9d4'
                    //                 }
                    //             }
                    //             }
                    //         }
                    //         }
                    //     })

                    //     chart4.updateOptions({
                    //         stroke: {
                    //             colors: '#0e1726'
                    //         },
                    //         plotOptions: {
                    //             pie: {
                    //             donut: {
                    //                 labels: {
                    //                 value: {
                    //                     color: '#bfc9d4'
                    //                 }
                    //                 }
                    //             }
                    //             }
                    //         }
                    //     })


                    // } 
                    // else
                    // {
                    
                    //     chart1.updateOptions({
                    //         colors: ['#1b55e2', '#e7515a'],
                    //         subtitle: {
                    //             style: {
                    //             color:  '#4361ee'
                    //             }
                    //         },
                    //         title: {
                    //             style: {
                    //             color:  '#0e1726'
                    //             }
                    //         },
                    //         grid: {
                    //             borderColor: '#e0e6ed',
                    //         }
                    //     })

                    //     chart.updateOptions({
                    //         stroke: {
                    //             colors: '#fff'
                    //         },
                    //         plotOptions: {
                    //             pie: {
                    //             donut: {
                    //                 labels: {
                    //                 value: {
                    //                     color: '#0e1726'
                    //                 }
                    //                 }
                    //             }
                    //             }
                    //         }
                    //     })
                    
                    //     chart3.updateOptions({
                    //         stroke: {
                    //         colors: '#ffffff'
                    //         },
                    //         plotOptions: {
                    //         pie: {
                    //             donut: {
                    //             labels: {
                    //                 value: {
                    //                 color: '#0e1726'
                    //                 }
                    //             }
                    //             }
                    //         }
                    //         }
                    //     })
                    //     chart4.updateOptions({
                    //         stroke: {
                    //             colors: '#fff'
                    //         },
                    //         plotOptions: {
                    //             pie: {
                    //             donut: {
                    //                 labels: {
                    //                 value: {
                    //                     color: '#0e1726'
                    //                 }
                    //                 }
                    //             }
                    //             }
                    //         }
                    //     })


                        
                    // }
                // end update grapgh when tab is clicked again and again
            }
           
        
            var chart1 = new ApexCharts(
                document.querySelector("#revenueMonthly"),
                options1
            );

            chart1.render();

       
            var chart = new ApexCharts(
                document.querySelector("#chart-2"),
                options
            ); 

            chart.render();


            var chart3 = new ApexCharts(
                document.querySelector("#chart-3"),
                chart_3_options
            );
           
            chart3.render();


              var chart4 = new ApexCharts(
                document.querySelector("#chart-4"),
                chart_4_options
            );
           
            chart4.render();

            const ps = new PerfectScrollbar(document.querySelector('.mt-container-ra'));


            // when theme toggle is clicked
                document.querySelector('.theme-toggle').addEventListener('click', function() { 
                    getcorkThemeObject = localStorage.getItem("app-theme");
                    getParseObject = JSON.parse(getcorkThemeObject)
                    ParsedObject = getParseObject;

                    if (ParsedObject.settings.layout.darkMode)
                    {
                        
                        chart1.updateOptions({
                            colors: 
                            [
                                <?php foreach($SQLGetallTeam as $SQLGetallTeamV){ ?>
                                    '<?=$SQLGetallTeamV["color_code"]?>',
                                <?php } ?>
                            ],
                            subtitle: {
                                style: {
                                color:  '#00ab55'
                                }
                            },
                            title: {
                                style: {
                                color:  '#bfc9d4'
                                }
                            },
                            grid: {
                                borderColor: '#191e3a',
                            }
                        })

                        chart.updateOptions({
                            stroke: {
                                colors: '#0e1726'
                            },
                            plotOptions: {
                                pie: {
                                donut: {
                                    labels: {
                                    value: {
                                        color: '#bfc9d4'
                                    }
                                    }
                                }
                                }
                            }
                        })

                        chart3.updateOptions({
                            stroke: {
                            colors: '#0e1726'
                            },
                            plotOptions: {
                            pie: {
                                donut: {
                                labels: {
                                    value: {
                                    color: '#bfc9d4'
                                    }
                                }
                                }
                            }
                            }
                        })

                        chart4.updateOptions({
                            stroke: {
                                colors: '#0e1726'
                            },
                            plotOptions: {
                                pie: {
                                donut: {
                                    labels: {
                                    value: {
                                        color: '#bfc9d4'
                                    }
                                    }
                                }
                                }
                            }
                        })


                    } 
                    else
                    {
                    
                        chart1.updateOptions({
                            colors: 
                            [
                                <?php foreach($SQLGetallTeam as $SQLGetallTeamV){ ?>
                                    '<?=$SQLGetallTeamV["color_code"]?>',
                                <?php } ?>
                            ],
                            subtitle: {
                                style: {
                                color:  '#4361ee'
                                }
                            },
                            title: {
                                style: {
                                color:  '#0e1726'
                                }
                            },
                            grid: {
                                borderColor: '#e0e6ed',
                            }
                        })

                        chart.updateOptions({
                            stroke: {
                                colors: '#fff'
                            },
                            plotOptions: {
                                pie: {
                                donut: {
                                    labels: {
                                    value: {
                                        color: '#0e1726'
                                    }
                                    }
                                }
                                }
                            }
                        })
                    
                        chart3.updateOptions({
                            stroke: {
                            colors: '#ffffff'
                            },
                            plotOptions: {
                            pie: {
                                donut: {
                                labels: {
                                    value: {
                                    color: '#0e1726'
                                    }
                                }
                                }
                            }
                            }
                        })
                        chart4.updateOptions({
                            stroke: {
                                colors: '#fff'
                            },
                            plotOptions: {
                                pie: {
                                donut: {
                                    labels: {
                                    value: {
                                        color: '#0e1726'
                                    }
                                    }
                                }
                                }
                            }
                        })


                        
                    }

                })
            //end when theme toggle is clicked

        } catch(e) {
            console.log(e);
        }


    })

  
</script>