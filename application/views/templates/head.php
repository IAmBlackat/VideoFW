<head>
  <script type="text/javascript">
    var BASE_URL = "<?php echo base_url() ?>";
  </script>

  <title><?php echo $title_for_layout ?></title>
  <link rel="icon" type="image/png" href="<?php echo base_url().'uploads/favicon.png'?>">
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta name="description" content="<?php echo strip_quotes($description_for_layout) ?>" />
  <meta name="keywords" content="<?php echo strip_quotes($keyword_for_layout) ?>" />
  <meta name="ROBOTS" content="index, follow" />
  <meta property="og:title" content="<?php echo $title_for_layout ?>">
  <meta property="og:type" content="website">
  <meta property="og:description" content="<?php echo strip_quotes($description_for_layout) ?>" />
  <meta property="fb:app_id" content="<?php echo FACEBOOK_APP_ID?>" />
  <meta property="og:image" content="<?php echo $image_for_layout ?>" />
  <meta property="og:site_name" content="<?php echo SITE_NAME ?>" />
  <meta property="og:url" content="<?php echo $url_for_layout ?>" />
  <meta name="google-site-verification" content="B1AAeuc4Zk8hvGRUo07Pwle7f3sXw1L8x8K8so57iEc" />
  <meta name="propeller" content="299ad255b79219bb00361128eb4cf93d" />
  <!-- GOOGLE FONT
  ================================================== -->
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:700italic,700,400,400italic,300italic' rel='stylesheet' type='text/css'>
  <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,300italic,400italic,500,500italic,700italic,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
  <!-- Bootstrap -->
  <link href="<?php echo $theme_path ?>css/bootstrap.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>css/slick.css"/>
  <link rel="stylesheet" type="text/css" href="<?php echo $theme_path ?>css/slick-theme.css"/>
  <link href="<?php echo $theme_path ?>css/style.css" rel="stylesheet" />

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Open Graph for facebook
  http://graph.facebook.com/[UserName] replace [UserName]
  with yours and get your fb:admis content information where the XXXX goes.
  ================================================== -->

  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="<?php echo $theme_path ?>js/jquery-1.9.1.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="<?php echo $theme_path ?>js/bootstrap.js"></script>
  <script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
  <script type="text/javascript" src="<?php echo $theme_path ?>js/slick.min.js"></script>
  <script>
    $(document).ready(function () {
      $('.slider').slick({
        infinite: true,
        dots: true,
        autoplay: true,
      });
    });
  </script>
</head>
<script type="text/javascript" src="<?php echo $theme_path ?>js/fe.js"></script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-68554839-1', 'auto');
  ga('send', 'pageview');

</script>

<div id="fb-root"></div>
<script>
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.5&appId=518427084990365";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
</script>