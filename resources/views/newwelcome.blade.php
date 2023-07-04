<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!--<title>Laravel</title>-->

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            .welcome-page {
			  position: fixed;
			  top: 0;
			  left: 0;
			  right: 0;
			  bottom: 0;
			  background: black;
			  z-index: 1;
			}

			.content {
			  user-select: none;
			  color: white;
			  display: flex;
			  align-items: center;
			  justify-content: center;
			  height: 100vh;
			  font-size: 5vw;
			  font-family: "verdana";
			  font-weight: bold;
			  letter-spacing: 20px;
			  opacity: 0;
			  animation: opacity 2s 2s forwards 1 ease-in-out;
			  position: relative;
			}
			.content p {
			  position: relative;
			  width: 80%;
			  text-align: center;
			  opacity: 1;
			  text-shadow: 10px 0px 2px rgba(255, 255, 255, 0.5);
			  animation: textShadow 3s 4s alternate infinite;
			}
			.content p:before {
			  content: attr(data-shadow);
			  position: absolute;
			  top: 0;
			  left: 50%;
			  transform: translate(-50%, 0) scale(2);
			  filter: blur(4px);
			  opacity: 0;
			  animation: rightToLeft 3s 3s forwards 1 ease-in-out;
			}
			.content span {
			  animation: backToPosition 2s 2s forwards 1 ease-in-out;
			  position: relative;
			  color: transparent;
			}
			.content span:before {
			  content: "";
			  position: absolute;
			  bottom: -20%;
			  left: 5%;
			  width: 90%;
			  height: 5px;
			  background: white;
			  opacity: 0;
			  filter: blur(5px);
			  animation: opacity 1s 5s alternate infinite;
			}
			.content span:nth-of-type(1) {
			  top: -70px;
			  left: -40px;
			}
			.content span:nth-of-type(2) {
			  top: 20px;
			  left: -50px;
			}
			.content span:nth-of-type(2):before {
			  animation: opacity 1s 5.25s alternate infinite;
			}
			.content span:nth-of-type(3) {
			  top: -50px;
			  left: 0px;
			}
			.content span:nth-of-type(3):before {
			  animation: opacity 1s 5.5s alternate infinite;
			}
			.content span:nth-of-type(4) {
			  top: -90px;
			  left: -40px;
			}
			.content span:nth-of-type(4):before {
			  animation: opacity 1s 5.75s alternate infinite;
			}
			.content span:nth-of-type(5) {
			  top: 100px;
			  left: -10px;
			}
			.content span:nth-of-type(5):before {
			  animation: opacity 1s 6s alternate infinite;
			}
			.content span:nth-of-type(6) {
			  top: -60px;
			  left: 20px;
			}
			.content span:nth-of-type(6):before {
			  animation: opacity 1s 6.25s alternate infinite;
			}
			.content span:nth-of-type(7) {
			  top: -80px;
			  left: 70px;
			}
			.content span:nth-of-type(7):before {
			  animation: opacity 1s 6.5s alternate infinite;
			}

			.corner {
			  width: 50px;
			  height: 50px;
			  animation: scale 2s alternate infinite ease-in;
			  filter: blur(2px);
			  position: absolute;
			  opacity: 0.1;
			}
			.corner:nth-of-type(1) {
			  top: 2rem;
			  left: 2rem;
			  border-top: 2px solid white;
			  border-left: 2px solid white;
			}
			.corner:nth-of-type(2) {
			  top: 2rem;
			  right: 2rem;
			  border-top: 2px solid white;
			  border-right: 2px solid white;
			  animation-delay: 0.5s;
			}
			.corner:nth-of-type(3) {
			  bottom: 2rem;
			  left: 2rem;
			  border-bottom: 2px solid white;
			  border-left: 2px solid white;
			  animation-delay: 1s;
			}
			.corner:nth-of-type(4) {
			  bottom: 2rem;
			  right: 2rem;
			  border-bottom: 2px solid white;
			  border-right: 2px solid white;
			  animation-delay: 1.5s;
			}

			.continue {
			  filter: blur(1px);
			  letter-spacing: 2px;
			  text-transform: uppercase;
			  text-align: center;
			  border: 2px solid white;
			  color: white;
			  position: absolute;
			  bottom: 20%;
			  left: 50%;
			  transform: translate(-50%, 0);
			  font-size: 1rem;
			  padding: 1rem 2rem;
			  box-shadow: 0 0 15px white inset;
			  cursor: pointer;
			  background: linear-gradient(90deg, transparent 33%, white 34%, transparent 35%, transparent 65%, white 66%, transparent 67%);
			  background-size: 300%;
			  background-position: left;
			  transition: all 0.5s;
			  animation: opacity 2s 7s forwards 1;
			  opacity: 0;
			}
			.continue:hover {
			  background-position: right;
			  color: green;
			  filter: blur(0);
			}

			.right-vert-line {
			  position: absolute;
			  background: linear-gradient(transparent, green);
			  opacity: 1;
			  top: 0;
			  bottom: 0;
			  right: -5%;
			  width: 2px;
			  animation: moveToLeft 2s 1s forwards ease-in-out;
			  filter: blur(1px);
			}

			.left-vert-line {
			  position: absolute;
			  background: linear-gradient(green, transparent);
			  opacity: 1;
			  top: 0;
			  bottom: 0;
			  right: 105%;
			  width: 2px;
			  animation: moveToRight 2s 1s forwards ease-in-out;
			  filter: blur(1px);
			}

			@keyframes moveToLeft {
			  from {
				right: 0%;
			  }
			  50% {
				opacity: 0.3;
			  }
			  to {
				right: 100%;
				opacity: 0;
			  }
			}
			@keyframes moveToRight {
			  from {
				right: 100%;
			  }
			  50% {
				opacity: 0.3;
			  }
			  to {
				right: 0%;
				opacity: 0;
			  }
			}
			@keyframes scale {
			  from {
				transform: scale(1);
			  }
			  to {
				transform: scale(1.2);
				opacity: 0.9;
			  }
			}
			@keyframes opacity {
			  from {
				opacity: 0;
			  }
			  to {
				opacity: 1;
			  }
			}
			@keyframes backToPosition {
			  to {
				left: 0;
				top: 0;
			  }
			}
			@keyframes rightToLeft {
			  0% {
				opacity: 0;
				transform: translate(-35%, 0) scale(2);
			  }
			  10% {
				opacity: 0.01;
			  }
			  50% {
				opacity: 0.5;
			  }
			  90% {
				opacity: 0.01;
			  }
			  100% {
				transform: translate(-65%, 0) scale(2);
				opacity: 0;
			  }
			}
			@keyframes moveTop {
			  0% {
				opacity: 1;
			  }
			  100% {
				top: -100%;
				bottom: 100%;
				opacity: 0;
			  }
			}
			@keyframes textShadow {
			  from {
				text-shadow: 10px 0px 2px rgba(255, 255, 255, 0.5);
				filter: blur(0px);
			  }
			  to {
				text-shadow: 5px 0px 2px rgba(255, 255, 255, 0.5);
				filter: blur(1px);
			  }
			}
			.container {
			  display: flex;
			  align-items: center;
			  justify-content: center;
			  width: 150px;
			  height: 150px;
			  transform: rotateX(45deg) rotate(45deg);
			  border-radius: 10px;
			  overflow: hidden;
			  opacity: 0;
			  animation: opacity 1s 12s 1 forwards;
			}

			.icons {
			  display: flex;
			  align-items: center;
			  transform: rotate(-45deg);
			  min-width: 220px;
			}

			.icon {
			  display: flex;
			  align-items: center;
			  flex: 1;
			  height: 210px;
			  padding: 1rem;
			  text-align: center;
			  font-size: 2rem;
			  cursor: pointer;
			  position: relative;
			  user-select: none;
			}

			.fb {
			  justify-content: flex-end;
			  transition: color 0.4s;
			}
			.fb:before {
			  content: "";
			  position: absolute;
			  right: 0;
			  top: 0;
			  left: 100%;
			  bottom: 0;
			  background: #405CA1;
			  transition: left 0.4s;
			  z-index: -1;
			  border-right: 1px solid rgba(0, 0, 0, 0.4);
			}
			.fb:hover {
			  color: white;
			}
			.fb:hover:before {
			  left: 0;
			}

			.git {
			  justify-content: flex-start;
			  transition: color 0.4s;
			}
			.git:before {
			  content: "";
			  position: absolute;
			  right: 100%;
			  top: 0;
			  left: 0;
			  bottom: 0;
			  background: #24292E;
			  transition: right 0.4s;
			  z-index: -1;
			}
			.git:hover {
			  color: white;
			}
			.git:hover:before {
			  right: 0;
			}

			* {
			  box-sizing: border-box;
			  margin: 0;
			  padding: 0;
			}

			a:link,
			a:active,
			a:visited {
			  color: inherit;
			  text-decoration: none;
			}

			body {
			  height: 100vh;
			  font-family: "Verdana";
			  display: flex;
			  align-items: center;
			  justify-content: center;
			}
        </style>
    </head>
    <body>
		<?php
			date_default_timezone_set("Asia/jakarta");
		?>
		
        <div class="welcome-page">
		  <div class="corner"></div>
		  <div class="corner"></div>
		  <div class="corner"></div>
		  <div class="corner"></div>
		  <div class="content">
			<p data-shadow="WELCOME"><span>W</span><span>E</span><span>L</span><span>C</span><span>O</span><span>M</span><span>E</span></p>
		  </div>
		  <div class="right-vert-line"></div>
		  <div class="left-vert-line"></div>
		  <div class="continue"><b><span id="jam" style="font-size:24"></span></b></div>
		</div>
		<div class="container">
		  <div class="icons">
			<div class="icon fb"><a href="#2" target="_blank">FB</a></div>
			<div class="icon git"><a href="#2" target="_blank">GIT</a></div>
		  </div>
		</div>

		<script type="text/javascript">
			window.onload = function() { jam(); }
		   
			function jam() {
				var e = document.getElementById('jam'),
				d = new Date(), h, m, s;
				h = d.getHours();
				m = set(d.getMinutes());
				s = set(d.getSeconds());
		   
				e.innerHTML = h +':'+ m +':'+ s;
		   
				setTimeout('jam()', 1000);
			}
		   
			function set(e) {
				e = e < 10 ? '0'+ e : e;
				return e;
			}
		</script>

    </body>
</html>
