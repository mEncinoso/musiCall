<!Doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- own CSS -->
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <script type="text/javascript" src="{{URL::asset('js/rs.speech.js-master/src/rs.speech.js')}}"></script>

    <title> musiCall</title>

    <script type="text/javascript">
        var token="{{ csrf_token() }}";

        /**********************************************************/
        /*                       UI Methods                        / 
        /**********************************************************/
        function openNav() {
          document.getElementById("myNav").style.width = "100%";
        }
             
        function closeNav() {
          document.getElementById("myNav").style.width = "0%";
        }
        /**********************************************************/
        /*                    Language Methods                     / 
        /**********************************************************/
        function changeLanguage(language){

          switch(language){
            case "English":
              document.getElementById("mpPath").placeholder="Music Path";
              document.getElementById("uploadSongsButton").innerHTML="Upload Songs";
              document.getElementById("thName").innerHTML="Name";
              document.getElementById("thArtist").innerHTML="Artist";
              document.getElementById("thAlbum").innerHTML="Album";
              document.getElementById("thGenre").innerHTML="Genre";
              document.getElementById("thLength").innerHTML="Length";
              break;

            case "Español":
              document.getElementById("mpPath").placeholder="Ruta de la música";
              document.getElementById("uploadSongsButton").innerHTML="Subir canciones";
              document.getElementById("thName").innerHTML="Nombre";
              document.getElementById("thArtist").innerHTML="Artista";
              document.getElementById("thAlbum").innerHTML="Album";
              document.getElementById("thGenre").innerHTML="Género";
              document.getElementById("thLength").innerHTML="Duración";

              //Form language value
              document.getElementById("hLanguage").value="Español";
              break;
          }
        }

        /**********************************************************/
        /*               Song List/Database Methods                / 
        /**********************************************************/
        function initiate(){
        	showSongs();
        }

        function showSongs(){
         	var xhr = new XMLHttpRequest();
          xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              document.getElementById('mpSongs').innerHTML=this.responseText;
            }
          };

          var language=document.getElementById("hLanguage").value;

          xhr.open("POST", 'getSongs', true);
          xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          var parameters = "_token="+token+"&language="+language;
          xhr.send(parameters);   
        }

        function showFilteredSongs(filter, name){
          var xhr = new XMLHttpRequest();
          xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              document.getElementById('mpSongs').innerHTML=this.responseText;
            }
          };

          mp3.title="";
          var language=document.getElementById('selectLanguage').value;

          xhr.open("POST", 'getFilteredSong', true);
          xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          var parameters = "_token="+token+"&filter="+filter+"&name="+name+"&language="+language;
          xhr.send(parameters);   
        }

        function showOrderedSongs(order, field, filter, name){
          var xhr = new XMLHttpRequest();
          xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              document.getElementById('mpSongs').innerHTML=this.responseText;
            }
          };

          mp3.title="";
          var language=document.getElementById('selectLanguage').value;

          xhr.open("POST", 'getOrderedSongs', true);
          xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          var parameters = "_token="+token+"&order="+order+"&field="+field+"&filter="+filter+"&name="+name+"&language="+language;
          xhr.send(parameters);  
        }

        /**********************************************************/
        /*                Methods called by clicking               / 
        /**********************************************************/
        function playClickedSong(song, track){
        	mp3.title=track;
        	mp3.src="./music/"+song+".mp3";
        	showSongData(track);
        	document.getElementById("playIcon").src="./images/pause.png";
        	mp3.play();
        }

        function playSong(){
          //set first song of the list as default
          if(mp3.title==""){
            mp3.title="1";
          }

          var currentTime=mp3.currentTime;

        	var iconPath=document.getElementById("playIcon").src;
        	iconPath=iconPath.split("/");

        	if(iconPath[iconPath.length-1]=="play.png" ){
        		document.getElementById("playIcon").src="./images/pause.png";
            var actualTrack=mp3.title;
            var song=document.getElementById(actualTrack).innerHTML;
            mp3.src="./music/"+encodeURIComponent(song)+".mp3";

            if(currentTime>0){
              mp3.currentTime=currentTime;
              mp3.play();
              trackInfo.start();
            }else{
              showSongData(actualTrack);
              trackInfo=document.getElementById('trackInfo');
              mp3.play();
       		  }
          }else if(iconPath[iconPath.length-1]=="pause.png"){
             pauseSong();
          }
	      }

        function pauseSong(){
          document.getElementById("playIcon").src="./images/play.png";
          mp3.pause();
          trackInfo.stop();
        }

        function stopSong(){
          document.getElementById("playIcon").src="./images/play.png";
          mp3.pause();
          mp3.currentTime=0;
          document.getElementById("nowPlaying").innerHTML="";
        }

        function nextSong(){
        	var actualTrack=mp3.title;
	        actualTrack++;
        	if(document.getElementById(actualTrack)!=null){
	        	var actualTrack=mp3.title;
	        	actualTrack++;
	        	mp3.title=actualTrack;
        	}else{
        		actualTrack=1;
	        	mp3.title=actualTrack;
        	}
        	var song=document.getElementById(parseInt(actualTrack)).innerHTML;
        	mp3.src="./music/"+encodeURIComponent(song)+".mp3";
        	showSongData(actualTrack);
          trackInfo=document.getElementById('trackInfo');
        	document.getElementById("playIcon").src="./images/pause.png";
        	mp3.play();
        }

        function prevSong(){
        	var actualTrack=mp3.title;
	        actualTrack--;
        	if(document.getElementById(actualTrack)!=null){
	        	var actualTrack=mp3.title;
	        	actualTrack--;
	        	mp3.title=actualTrack;
        	}else{
        		actualTrack=parseInt(document.getElementById("1").title);
	        	mp3.title=actualTrack;
        	}
        	var song=document.getElementById(parseInt(actualTrack)).innerHTML;
        	mp3.src="./music/"+encodeURIComponent(song)+".mp3";
        	showSongData(actualTrack);
          trackInfo=document.getElementById('trackInfo');
        	document.getElementById("playIcon").src="./images/pause.png";
        	mp3.play();
        }

        function showSongData(track){
        	document.getElementById("message").innerHTML="";
        	var name=document.getElementById("s"+track+1).title;
        	var artist=document.getElementById("s"+track+2).title;
        	var album=document.getElementById("s"+track+3).title;
        	var genre=document.getElementById("s"+track+4).title;

        	document.getElementById("nowPlaying").innerHTML=
          "<marquee id='trackInfo'>"+name+" - <span onclick=\"showFilteredSongs('artist','"+artist+"'), openNav();\">"+artist+"</span>  - <span onclick=\"showFilteredSongs('album','"+album+"'), openNav();\">"+album+"</span> - <span onclick=\"showFilteredSongs('genre','"+genre+"'), openNav();\">"+genre+"</span></marquee>";
        }

        /**********************************************************/
        /*               Methods called by microphone              / 
        /**********************************************************/
        function recordAction(){
          var language = document.getElementById("selectLanguage").value;
          if(language == "English"){
            //English Voice Commands
            speechRs.rec_start('en-IN',function(final_transcript,interim_transcript){
              console.log(final_transcript,interim_transcript);
            });   

            speechRs.on("play song",function(){ 
              playSong();
            }); 

            speechRs.on("pause",function(){ 
              pauseSong();
            }); 

            speechRs.on("stop",function(){ 
              stopSong();
            }); 

            speechRs.on("next",function(){ 
              nextSong();
            }); 

            speechRs.on("back",function(){ 
              prevSong();
            }); 

            /*speechRs.on("play artist",function(){ 
              var name="";
              showFilteredSongs("artist", name);
            }); */

          }else if(language = "Español"){              
            //Español Voice Commands

            speechRs.rec_start('es-ES',function(final_transcript,interim_transcript){
              console.log(final_transcript,interim_transcript);
            });   

            speechRs.on("reproducir canción",function(){ 
              playSong();
            }); 

            speechRs.on("parar canción",function(){ 
              pauseSong();
            }); 

            speechRs.on("stop canción",function(){ 
              stopSong();
            });

            speechRs.on("siguiente canción",function(){ 
              nextSong();
            }); 

            speechRs.on("canción anterior",function(){ 
              prevSong();
            });

            //https://www.youtube.com/watch?v=BmdZtjxFFlQ
          }
        }
    </script>

  	</head>

  	<body onLoad="initiate();">
  		<nav class="navbar navbar-expand navbar-light bg-light">
		    <!--
          <a class="navbar-brand" href="">
		      </a>
        -->
		    <div>
		    	<span style="font-size:30px;" onclick="openNav()"> <img src="./images/logo.png" width="155" height="70"/></span>
        </div>
        <div class="navbar-nav ml-auto">
          <select id="selectLanguage" onchange="changeLanguage(document.getElementById('selectLanguage').value);">
            <option value="English">English</option>
            <option value="Español">Español</option>
          </select> 
        </div>  
	 	  </nav>

  	<div id="myNav" class="overlay" >
		 	<div class="overlay-content">
			  <div class="mpSongs" id="mpSongs">
			  </div>
		  </div>
		</div>

    <div class="container">
      <!-- Content here -->
      <h1 align="center"></h1>

      <div class="row">
				<form method="post" class="col-md-12 col-sm-12 col-xs-12" action="uploadSongs">
          {{ csrf_field() }}
	        <div class="mpHead input-group">
	          <input type="text" class="form-control" name="mpPath" id="mpPath" placeholder="Music Path" aria-label="Recipient's username" aria-describedby="basic-addon2">
            <input type="hidden" id="hLanguage" name="language" value="English"/>
				  	<div class="input-group-append">	        
              <button type="submit"  id="uploadSongsButton" class="btn btn-primary" style="z-index: 0">Upload Songs</button>
						</div>	               	
					</div>
				</form>   
      </div>

      <div class="row">
        <hr>
      </div>

			<div class="row align-items-center justify-content-center">
        <div class="mpMicrophone">
          <input type="image" class="microImg" src="./images/microphone.png" onclick="recordAction();"/>
				</div>
      </div>
      <div class="row">
        <hr>
      </div>

			<div class="row align-items-center justify-content-center">
        <div class="mpButtons">
          <input type="image" class="playImg col ml-3" src="./images/back.png" onclick="prevSong();"/>
          <input type="image" id="playIcon" class="playImg col ml-3" src="./images/play.png" onclick="playSong();"/>
          <input type="image" class="playImg col ml-3" src="./images/next.png" onclick="nextSong();"/>
        </div>
      </div>

      <div class="row">
        <hr>
      </div>

      <div class="row">
        <div id="message">
	        <?php
	          if(isset($message)){
	            echo $message;
	          }
	        ?>
	      </div>
        <br>
        <div id="nowPlaying" class="col-md-12 col-sm-12 col-xs-12">
          
        </div>
      </div>
           
      <div class="row">
        <hr>
        <audio id="mp3" title="" src="" class="col-md-12" controls controlsList="nodownload" onended="nextSong();"></audio>
        <script type="text/javascript">
          var mp3=document.getElementById("mp3");
          var trackInfo;
        </script>

        <br><br>
            	<!--
            	  <div> 
        					<button onclick="mp3.play()">Play</button> 
        					<button onclick="mp3.pause()">Pause</button> 
        					<button onclick="mp3.volume += 0.1">Vol+ </button> 
        					<button onclick="mp3.volume -= 0.1">Vol- </button> 
      			  	</div>
      				-->
      </div>
      
      <div class="row">
      </div>

    </div>

  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
 	</body>
</html>