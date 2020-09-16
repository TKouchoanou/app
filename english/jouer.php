
<h1>Jouons avec les mots</h1>
<div class="form">
	<p>
		La difficulté correspondant au nombre de seconde d'attente pour
		afficher la traduction <span style="color: red"></span>
	</p>


	<p>
		<label for="niveau">* Veuillez choisir votre niveau de difficulté pour
			démarrer le jeu </label> <select name="niveau" id="niveau"
			onChange="startGame(this)">
			<option selected>demarrez</option>
			<option value="1">1 seconde</option>
			<option value="3">3 secondes</option>
			<option value="5">5 secondes</option>
			<option value="10">10 secondes</option>
			<option value="15">15 secondes</option>
			<option value="20">20 secondes</option>
		</select>

		<button onclick="stopAnimation();" disabled=true>Arrêter !</button>
		<button onclick="onPause();" disabled=true>Pause!</button>
		<button style="display: none" onclick="onLeftPause();">Continuer!</button>
	</p>

</div>

<div class="table-traduction"  height="100" wide="200"">
	<table style="width: 80%;">
		<thead>
			<tr>
				<th class="en" style="height: 50px;"><div height="30" wide="100">Mots Anglais</div></th>
				<th class="fr" style="height: 50px;"><div height="30" wide="100">Traduction Française</div></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="en" id="td-englishWord"> <div height="100" wide="100" id="englishWord" ></div></td>
				<td class="fr" id="td-frenchWord"><div height="100" wide="100" id="frenchWord"></div></td>
			</tr>
		</tbody>
	</table>
</div>


<script>

  /*document.addEventListener("onload",animation);

   var table = document.querySelector('table'); 
  table.addEventListener("onload",animation);
 window.addEventListener("load",animation); */ 

 /* 

 BUG A CORRIGER 
 1- animation avant inGame car il initialise des valeurs utiliser par inGame et gameDisableToggle() et endGame(del)
 2- Des fin inattendue après des pauses ? 
 3- durée post pause parfois longue à voir dans animation(n)
 bug arrêter aucours d'une pause , car dans le end game il n'arrête pas vraiment car inpause est dans 

 */
 

var  inGame = false; // permet de savoir si le jeu est encours 
var  gameId= -1;  // id du setIntervalle principal qui appel à chaque fois la fonction d'affichage de mot
var  currentSetTimeId= -1;  // id du setTimeout encour c-a-d celui affiche le mot actuellement vu à l'écran
var  previousSetIntervCtSecondId= -1; 
var  onLeftPauseSetTimeoutId= -1; 
var onLeftPauseSetTimeoutId2=-1; // pour gerer l'animation , délicatesse de la gestion de temps
var  inPause=false; // permet de savoir si le jeu est en pause c-a-d l'utilisateur à cliquer sur pause

var  ns=0; 
var  seconde=0;
var  timeCWisReaded=0; // Current Wourd =CW c'est le temps pendant lequel un mot a été lu en milliseconde , pour gérez multiplr pause aucour de l'animation d'un même mot
var  setTmStart=0;  // date en millisecone du moment où le setTimeout qui affichera la traduction actuelle à été lancé 
var  setTmEnd=0;    // date en millisecone du moment où le setTimeout qui affichera la traduction actuelle à été détruit par une pause 
var  timeBeforePause=0; //temps écoulé avant la pause, c'est à dire entre le lancemment du setTimeOut et lo moment où il a été détruit par le bouton pause
var  currentWord;   // le mot courant affiché dont la traduction va être affiché bientôt
var  flashDelay=0; //temps d'attente  pour l'affichage des traduction chaque de chaque mot en seconde, c'est le temps choisit par l'utilisateur en seconde
const displayTradRatio=1.7; // si l'utilisateur veut attendre 1 s pour voir la trad, la trad s'affichera au bout d'une seconde et restera affiché pendant 0.7 s
                            // soit (displayTradRatio*100-100)% du temps au bout du quel la trad s'affiche,dans ce cas ça fait 70% 
window.onbeforeunload =stopAnimation;  

// pour debugger à supprimer après
function catSeconde(){
  seconde++; 
  document.querySelectorAll('span')[1].textContent=" "+seconde+" secondes";
   //document.querySelector('span').style='color:red'; 
  }
  

function startGame(s){
  n=parseInt(s.value);
  flashDelay=n; 
  inGame=true;
  flashWords(words,n*1000);
  animation(n); 
  gameDisableToggle(); 


  seconde=0;
  previousSetIntervCtSecondId=setInterval(catSeconde,1000);
}

function gameDisableToggle(){
	
 var buttons =document.querySelectorAll('button'); 
 if(inGame){
    document.querySelector('select').disabled=true;
    buttons[0].disabled=false;
    buttons[1].disabled=false;
    buttons[2].style="display:none"; 
  }else if(inPause){//le seul endroit qu'on peut quiiter pour aller en pause c'est le jeu donc le button 0 sera toujours disable
  document.querySelector('select').disabled=true;
   buttons[1].disabled=true;
   buttons[2].style="color: blue";  
  }else{
    document.querySelector('select').disabled=false;
    document.querySelector('select').value="";//on vide le select pour imposer à nouveau son choix afin de demarrer le jeu
    buttons[0].disabled=true;
    buttons[1].disabled=true;
    buttons[2].style="display:none"; 
 }
}

 function nbWordToRead(words){
 
  let nbWords=0; 
  for (let i = 0; i < words.length; i++) {
   let word= words[i]; 
     if(!isReadedWord(word)){
        nbWords++; 
      }  
   }
  return nbWords; 
 }


 function onPause(){ 
    endGame(false);
    setTmEnd=Date.now(); 
    timeBeforePause=setTmEnd-setTmStart;
    timeCWisReaded=timeCWisReaded+timeBeforePause; 
    inPause=true; 
    inGame=false;//en pause où éteint définitivement on n'est pas en jeu
    gameId=-1; 
    gameDisableToggle();
  }

 function onLeftPause(){
  var buttons =document.querySelectorAll('button');
  
    if(isReadedWord(currentWord)){
    	onLeftPauseSetTimeoutId=setTimeout(flashWords,flashDelay*displayTradRatio*1000-timeCWisReaded,words,flashDelay*1000); 
	  //flashWords(words,flashDelay*displayTradRatio*1000-timeCWisReaded)
	  //si la traduction est dejà afficher tu lance un prochain mot à la fin du temp du tempsd'affichage restant pour le mot actuellement affiché 
	  }
    else{
		flashWord(currentWord,flashDelay*1000-timeCWisReaded);
		onLeftPauseSetTimeoutId=setTimeout(flashWords,flashDelay*displayTradRatio*1000-timeCWisReaded,words,flashDelay*1000); 
		console.log("Dans current temps "+currentWord,flashDelay*1000-timeCWisReaded); 
		  // si le mot n'est pas encore affiché tu le flash pour sont temps restant
		 } 

    onLeftPauseSetTimeoutId2=setTimeout(animation,flashDelay*displayTradRatio*1000-timeCWisReaded,flashDelay); 

    seconde=0;
    previousSetIntervCtSecondId=setInterval(catSeconde,1000);
	 
   //animation(flashDelay);
   inPause=false;//quand on arrête definitivement on est pas en pause 
   //il faut le signaler pour avoir le meilleur toogle des bouton dans gameDisableToggle() 
   inGame=true; 
   gameDisableToggle(); 
  }
 
 function stopAnimation(){
    endGame(true); 
    inGame=false;
    inPause=false; 
    gameId=-1;
    gameDisableToggle(); 
    }


 function animation(n){
  console.log(' je fais mon boulot'); ns=0;
// premier mot falshé aléatoirement pour ne pas attendre le delais du setInterval
  var nbWords=nbWordToRead(words); 
  var nbSecondeDeJeu=n*displayTradRatio*1000*nbWords+1000; // car un mot 
  
  var id=setInterval(flashWords,n*displayTradRatio*1000,words,n*1000); 
  gameId=id; 
  setTimeout(stopAnimation,nbSecondeDeJeu);
  
  console.log('seconde: '+nbSecondeDeJeu+' nbWords : '+nbWords); 

}

 

function endGame(del){
	 console.log(words);
   if(inGame===true){  
       if(gameId!==-1)clearInterval(gameId);
       if(currentSetTimeId!==-1) clearTimeout(currentSetTimeId); 
       if(onLeftPauseSetTimeoutId!==-1) clearTimeout(onLeftPauseSetTimeoutId); 
       if(onLeftPauseSetTimeoutId2!==-1) clearTimeout(onLeftPauseSetTimeoutId2); 
       if(previousSetIntervCtSecondId!==-1)clearTimeout(previousSetIntervCtSecondId);
      
       
    }

   if(del===true){
       for (let i = 0; i < words.length; i++) {
        let word= words[i]; 
         word.read=false;
       }
       
     }

   
  console.log("fin du jeu");
}



function flashWords(words,n) {
 
     let word=getNExtWord(words) ; 
     if(word!==false){
      // on ne passe par ici que quand on est sur un nouveau et et pas entre les pauses d'un même mot
      // d'où la pertinance de mettre à jour le temps écoulé pour un mot ici 
      timeCWisReaded=0; 
      flashWord(word,n);
      
     }else{ console.log('tapez poto'); }
}


 function getNExtWord(words){
     do{
          let j = getRandomArbitrary(0,words.length);
           
 
          let word= words[j]; 
  
          if(!isReadedWord(word)){  
              return word; } 

        }while(isNewWordToRead(words)); 
       
     return false; 

}



/**
 * Renvoie vraie s'il y a toujour un nouveau mot à lire dans le tableau
  c-a-d tout les mots ne son pas encore lue pour cette partie de jeu
 */
 function isNewWordToRead(words){

 for (let i = 0; i < words.length; i++) {
   let word= words[i]; 
      if(!isReadedWord(word)){
        return true; 
       }  
    }
   
   return false; 
 }


 function getRandomArbitrary(min, max) {
   return Math.trunc((Math.random() * (max - min) + min));
 }

function setFrenchWord(frenchWord,word) {
  frenchWord.textContent=word.fr;
  word.read=true;
}
 
/**
 * 
 */
function flashWord(word,n) {
	
     englishWord=document.getElementById("englishWord");
     frenchWord=document.getElementById("frenchWord");

     console.log(word);
     
     englishWord.textContent=word.en; 
     frenchWord.textContent="?"; 
     currentSetTimeId=setTimeout(setFrenchWord,n,frenchWord,word);
   
      currentWord=word; 
      setTmStart=Date.now();

  
  ns++; 
  console.log('setTimeOut seconde: '+n+' nb appel '+ns); 
  //console.log(word);  
}

/**
 * Est-ce un mot déjà lu ? renvoie true si oui et false sinon
 */
function isReadedWord(word){
	return (word.read!==undefined && word.read===true); 
}
</script>
