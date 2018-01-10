<?php
    $validext = array(".jpg", ".jpeg", ".png", ".gif", ".bmp");
    if (!empty($_GET['photo']))
    {
        $temp = $_GET['photo'];
        if (file_exists("images/$temp"))
        {
            $ext = substr($temp, strpos($temp, '.')); // capture extension
            if (in_array($ext, $validext))
            {
                $photofile = $temp;
                $photoname = substr($photofile, 0, (strlen($photofile) - strlen(substr($photofile, strpos($photofile, '.'))))); // strip extension
                if (strpos($photoname, '/') !== FALSE)
                    $photoname = substr($photoname, strripos($photoname, '/') + 1); // remove any folder prefixes
                $photoname = str_replace('-', ' ', $photoname); // replace hyphens with spaces
                $photoname = preg_replace('/(?<=\d).(?=\d)/', '', $photoname); // remove character between image dimensions
                $photoname = preg_replace('/[0-9]+/', '', $photoname); // remove all numbers
                $photoname = trim(ucwords($photoname)); // trim string and capitalize first letter of every word
            }
            else
            {
                $error = "Sorry, the file <i> \"$temp\" </i> does not have the proper extension.";
                $photoname = "Error";
            }
        }
        else
        {
            $error = "We're sorry. We can't seem to find that file.";
            $photoname= "Error";
        }
    }
    else
    {
        $error = "We're sorry. We can't seem to find that file.";
        $photoname= "Error";
    }
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title><?php echo $photoname?> - Yoga Balance</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
        <link rel="stylesheet" href="assets/css/mailchimp.css" type="text/css" />
        <link rel="apple-touch-icon" sizes="57x57" href="images/favicon/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="images/favicon/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="images/favicon/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="images/favicon/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="images/favicon/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="images/favicon/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="images/favicon/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="images/favicon/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="images/favicon/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="images/favicon/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png">
        <link rel="manifest" href="images/favicon/manifest.json">
        <meta name="msapplication-TileColor" content="#663366">
        <meta name="msapplication-TileImage" content="images/favicon/ms-icon-144x144.png">
        <meta name="theme-color" content="#663366">
	</head>
	<body>

		<!-- Page Wrapper -->
			<div id="page-wrapper">

				<!-- Header -->
					<header id="header">
						<h1><a href="index.html">Yoga Balance</a></h1>
						<nav>
                            <a href="http://www.yelp.com/biz/yoga-balance-grayslake" class="social" onclick="trackOutboundLink('Yelp - Header');" target="_blank">
                                <i class="fa fa-yelp fa-lg"></i>
                            </a>
                            <a href="https://www.facebook.com/Yoga-Balance-Inc-949307748415341/" class="social" onclick="trackOutboundLink('Facebook - Header');" target="_blank">
                                <i class="fa fa-facebook fa-lg"></i></a>&nbsp;&nbsp;
							<a href="#menu">Menu</a>
						</nav>
					</header>

				<!-- Menu -->
					<nav id="menu">
						<div class="inner">
							<h2>Menu</h2>
							<ul class="links">
								<li><a href="index.html">Home</a></li>
								<li><a href="index.html#about">About</a></li>
                                <li><a href="index.html#classes">Classes</a></li>
                                <!---<li><a href="faq.html">New to Yoga?</a></li>--->
								<li><a href="index.html#pictures">Pictures</a></li>
								<li><a href="index.html#contact">Contact</a></li>
							</ul>
							<a href="#" class="close">Close</a>
						</div>
					</nav>


						<!-- Content -->
							<div class="wrapper">
								<div class="inner">
                                    <div style="margin-bottom: 3em; margin-top: -1em; text-align: center;">
                                        <?php 
                                            if (empty($error))
                                            {
                                                echo "<h3 class=\"major\">Photo - \"$photoname\"</h3><br>";
                                                echo "<a href=\"images/$photofile\" class=\"image\"><img src=\"images/$photofile\" alt=\"$photoname\" style=\"max-width: 100%; max-height: 65vh;\"></a>";
                                            }
                                            else
                                            {
                                                echo "<h3 class=\"major\">$error</h3><br>";
                                            }
                                        ?>
                                    </div>
								</div>
							</div>

					</section>

				<a name="contact" class="anchor"></a>
				<!-- Footer -->
					<section id="footer">
						<div class="inner">
							<h2 class="major">Get in touch</h2>
							<p></p>
							
                            <!-- Begin MailChimp Signup Form -->
                            <div class="mailchimp" id="mc_embed_signup">
                                <form action="//facebook.us11.list-manage.com/subscribe/post?u=7d18fc5a03ab56d88426b1f19&amp;id=a3d60e38d9" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" onsubmit="trackFormSubmission('Mailchimp Subscription - Image View');" target="_blank" novalidate>
                                    <div id="mc_embed_signup_scroll">
	                                    <h2>Subscribe to our mailing list!</h2>
                                        <div class="mc-field-group">
	                                        <label for="mce-FNAME">First Name </label>
	                                        <input type="text" value="" name="FNAME" class="required" id="mce-FNAME">
                                        </div>
                                        <div class="mc-field-group">
	                                        <label for="mce-LNAME">Last Name </label>
	                                        <input type="text" value="" name="LNAME" class="required" id="mce-LNAME">
                                        </div>
                                        <div class="mc-field-group">
	                                        <label for="mce-EMAIL">Email Address </label>
	                                        <input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
                                        </div>
	                                    <div id="mce-responses" class="clear">
		                                    <div class="response" id="mce-error-response" style="display:none"></div>
		                                    <div class="response" id="mce-success-response" style="display:none"></div>
	                                    </div>
                                        <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_7d18fc5a03ab56d88426b1f19_a3d60e38d9" tabindex="-1" value=""></div>
                                        <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
                                    </div><br />
                                    <a href="http://eepurl.com/b7yUxL" target="_blank">Powered by MailChimp</a>
                                </form>
                            </div>
                            <!--End MailChimp Signup Form-->

							<ul class="contact">
								<li class="fa-home">
									Yoga Balance Inc<br />
									997 N Corporate Circle, Suite B<br />
									Grayslake, IL 60030
								</li>
								<li class="fa-phone">(847) 807-1508</li>
								<li class="fa-envelope"><a href="mailto:lisa.yogabalance@gmail.com" target="_blank">lisa.yogabalance@gmail.com</a></li>
								<li class="fa-facebook"><a href="https://www.facebook.com/Yoga-Balance-Inc-949307748415341/" onclick="trackOutboundLink('Facebook - Footer');" target="_blank">facebook.com/Yoga-Balance-Inc</a></li>
                                <li class="fa-yelp"><a href="http://www.yelp.com/biz/yoga-balance-grayslake" onclick="trackOutboundLink('Yelp - Footer');" target="_blank">yelp.com/biz/yoga-balance-grayslake</a></li>
							</ul>
							<ul class="copyright">
								<li>&copy; Yoga Balance Inc. All rights reserved.</li><li>Design: <a href="mailto:webmaster@yogabalanceinc.com" target="_blank">Jack McKernan</a></li><li>Template: <a href="http://html5up.net" target="_blank">HTML5 UP</a></li>
							</ul>
						</div>
					</section>

			</div>

		<!-- Scripts -->
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.scrollex.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>
            <script>
                (function (i, s, o, g, r, a, m) {
                    i['GoogleAnalyticsObject'] = r; i[r] = i[r] || function () {
                        (i[r].q = i[r].q || []).push(arguments)
                    }, i[r].l = 1 * new Date(); a = s.createElement(o),
                m = s.getElementsByTagName(o)[0]; a.async = 1; a.src = g; m.parentNode.insertBefore(a, m)
                })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

                ga('create', 'UA-80975712-1', 'auto');
                ga('send', 'pageview');
            </script>
            <script>
                var trackOutboundLink = function(name) {
                    ga('send', 'event', 'Outbound Links', 'click', name, {'transport': 'beacon'});
                }

                var trackPDFOpen = function(name) {
                    ga('send', 'pageview', '/downloads/'+name);
                    ga('send', 'event', 'Downloads', 'pdf open', name, {'transport': 'beacon'});
                }

                var trackFormSubmission = function(name) {
                    ga('send', 'event', 'Forms', 'submit', name, {'transport': 'beacon'});
                }
            </script>

	</body>
</html>
<!--
	Solid State by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->