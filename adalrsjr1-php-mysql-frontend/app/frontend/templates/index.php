<html>
    <head>
        <meta charset="utf-8"/>
        <title>Match</title>
        <link href='//fonts.googleapis.com/css?family=Lato:300' rel='stylesheet' type='text/css'>
        <style>
            body {
                margin: 50px 0 0 0;
                padding: 0;
                width: 100%;
                font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
                text-align: center;
                color: #aaa;
                font-size: 18px;
            }

            h1 {
                color: #719e40;
                letter-spacing: -3px;
                font-family: 'Lato', sans-serif;
                font-size: 100px;
                font-weight: 200;
                margin-bottom: 0;
            }
        </style>
    </head>
    <body>
        <h1>Profile Macth</h1>
        <div>match your profile with our products</div>
			<?php 
			$URL = 'match.default.svc.cluster.local:8100/match/public/login';
			if(gethostname() == 'linux-vm') {
				$URL = 'http://localhost/match/match/public/login';
			}
			?>
			<form action=<?php echo $URL; ?> method="get">
				User: <input type="text" name="user">
				<input type="submit" value="login">
			</form>
			
    </body>
</html>
