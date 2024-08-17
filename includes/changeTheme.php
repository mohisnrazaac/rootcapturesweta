<?php
$team = '';
if ($user -> isAdmin($odb)) {
    $team = '';
} 
else if( $user -> isAssist($odb) ) {
	$team = '';
}
else if( $user -> isRedTeam($odb) ) {
	$team = 'red-team-bg';
}
else if( $user -> isBlueTeam($odb) ) {
	$team = 'blue-team-bg';
}
else if( $user -> isPurpleTeam($odb) ) {
	$team = 'purple-team-bg';
}
?>
<script>
    var teamBgColor = '<?php echo $team; ?>';
    var link = document.querySelector("link[rel~='icon']");
if (!link) {
    link = document.createElement('link');
    link.rel = 'icon';
    document.getElementsByTagName('head')[0].appendChild(link);
}

    function changeTheme()
    {   
        if( $('body').hasClass('dark') )
        {
            $('body').removeClass(teamBgColor);
            link.href = '<?=BASEURL?>src/assets/img/favicon-dark.png';
            document.querySelector('.navbar-logo').setAttribute('src', '<?=BASEURL?>frontend-assets/img/site-logo.svg');
        }
        else
        {
            $('body').addClass(teamBgColor);
            link.href = '<?=BASEURL?>src/assets/img/favicon.ico'; alert('ty'); 
            document.querySelector('.navbar-logo').setAttribute('src', '<?=BASEURL?>assets/img/RootCapture0.png');
        }
    }

    $(window).on('load', function() { 
        if( $('body').hasClass('dark') )
        {  alert('hello');
            $('body').removeClass(teamBgColor);
            link.href = '<?=BASEURL?>src/assets/img/favicon-dark.png';
            document.querySelector('.navbar-logo').setAttribute('src', '<?=BASEURL?>frontend-assets/img/site-logo.svg');
        }
        else
        {
            $('body').addClass(teamBgColor);
            link.href = '<?=BASEURL?>src/assets/img/favicon.ico';
            document.querySelector('.navbar-logo').setAttribute('src', '<?=BASEURL?>assets/img/RootCapture0.png');
        }
    })

  
</script>