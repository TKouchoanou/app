
<div class="table-evaluate-game"  height="100" wide="200"">

   <form method="get" onSubmit=" return onSubmit(this)">
        <P> Ajoutez un mot à traduire <button onclick="onaddNEwInputForm();" disabled=false>Ici</button></p>
	<table style="width: 80%;">
		<thead>
			<tr>
				<th class="en" style="height: 50px;"><div height="30" wide="100">Mots Anglais</div></th>
				<th class="fr" style="height: 50px;"><div height="30" wide="100">Traduction Française</div></th>
			</tr>
		</thead>
		<tbody>
			<tr class="row" id="game-row-0">
				<td class="en" id="td-englishWord-0"><input type="hidden" name="motAnglais[0]" id="motAnglais-0" value=""/></td>
				<td class="fr" id="td-frenchWord-0"><input type="text" name="traductionEnFrançais[0]" id="traductionEnFrançais-0" onfocus="onInputChange(this)" oninput="onInputChange(this)" focus  required/></td>
			</tr>
                        <tr ><td colspan="2" ><input type="submit" style="text-align:center" id="add-form-submit" class="submit" value="Envoyer" /></td>
                        <td> </td>
                       </tr>
		</tbody>
	</table>
               
  </form>
</div>

<div class="table-result-game" style="display:none"  id="table-result-game" height="100" wide="200"">

         <h2> Vous avez effectuer un test.... </h2>
  <table>
     <caption>Résultat de votre evaluation</caption>

    <thead> <!-- En-tête du tableau -->
         <tr>
             <th>Mots Anglais</th> 
             <th>Traduction Française</th> 
             <th>Votre réponse</th> 
             <th>Resultat</th> 
        </tr>
    </thead>

   <tbody id="result-table-body"> <!-- Corps du tableau -->
         <tr id="result-row-0">
             <td id="res-motAnglais-0"></td>
             <td id="res-traductionEnFrançais-0"></td>
             <td id="res-test-traduction-0"></td> 
             <td id="res-cat-0">
               <div id="res-cat-div-0" style="width:20px;height:20px;background-color:#9584;"></div>
             </td>
         </tr>
      
   </tbody>
</table>
          
<div id="result-text"></div>

</div>
<script>


 window.addEventListener("load",function(){
	 tdFirst=document.getElementById("td-englishWord-0");  //word=getNExtWord(words);
	  word=words[32]; 
 tdFirst.innerHTML=word.en+tdFirst.innerHTML; document.getElementById("motAnglais-0").value=word.en; word.read=true;
 });

  var currentNumWord=0; 
  var win=0;   

 function onInputChange(input) {

 document.querySelector('button').disabled=input.value.length<2;
 }



function onSubmit(f) {
 
  f.style="display: none"; 
  console.log(f); 
  toBuildResultRow(f);
  document.getElementById("table-result-game").style="";
  document.getElementById("result-text").textContent=" Vous avez "+win+"/"+(f.length-2)/2+"\n \n"+getResultTexte((f.length-2)/2,win) ; 
 
  return false
 }


function getResultTexte(n,s){
	let note = s/n; 
     let text=""; 
     console.log(note);
	switch (true) {
	 case note===0: 
		 text="On peut dire que vous êtes nulle en Anglais";   
	    break;
	    
	  case (note<=0.25 && note>0):
		  text="On peut dire que vous êtes pour le moment médiocre mais en repartant du bon pieds avec beaucoup d'éffort vous serait peut-être un champignon";
	    break;
	  case (note<0.5 && note>0.25):
		  text="ça ne va pas encore il vous faut travailler votre vocabulaire, bon courage";
	    break;
	
	  case (note<0.75 && note>=0.5):
		  text="pas mal bravo, perséverez vous êtes en voie de devenir un champignon ";
	    break;
	  case (note<1 && note>=0.75):
		  text="Très bien, félicitation à vous avez un très bon niveau en Anglais. N'oubliez pas que c'est en continuant de travailler que vous le gardez. Bon courage champignon";
		break;
	  case note===1:
		  text="Wow Wow excellent super champignon, chapeau à vous"; 
		  break; 
	  default:  
	    break;
	}

	return text; 
	
}


function toBuildResultRow(f) {
 
 word=getWord(f[1].value); 
 document.getElementById("res-motAnglais-0").textContent=word.en;
 document.getElementById("res-traductionEnFrançais-0").textContent=word.fr;
 document.getElementById("res-test-traduction-0").textContent=f[2].value;
 
	 if(isTrad(word,f[2].value)){
		 document.getElementById("res-cat-div-0").style="width:20px;height:20px;background-color:green;"; win++;
	 }else{
		 document.getElementById("res-cat-div-0").style="width:20px;height:20px;background-color:red;"
			 }
 j=1; 
 
 for (let i = 3; i < f.length-2; i++) {
	 if(i%2!==0){
   word=getWord(f[i].value); 
   document.getElementById("result-table-body").append(getNEwResultRow(word,f[i+1].value,j)); 
   j++;
	 }
 }
	 
}



function getWord(wordEn) {

 for (let i = 0; i < words.length; i++) {

    if(words[i].en===wordEn){
       return words[i]; 
      }
 }
   return false; 
}

function onaddNEwInputForm() {
    currentNumWord++; 

    word=getNExtWord(words); 
    nEwInputForm =document.querySelector('tbody'); 

   if(false!==word){
    nEwInputForm.prepend(getNEwInput(word,currentNumWord)); 
    }else{
     alert("Plus de mot disponible dans le dico à traduire"); 
    }
     
}


function getNEwResultRow(word,trad,n) {

	  resultRow=document.getElementById("result-row-0"); 
	  newResultRow=resultRow.cloneNode(true);
	  newResultRow.setAttribute("id", "result-row-"+n);
	  newResultRowChild=newResultRow.children; 
	  //td 1
	  newResultRowChild[0].setAttribute("id", "res-motAnglais-"+n);
	  newResultRowChild[0].textContent=word.en; 

	  newResultRowChild[1].setAttribute("id", "res-traductionEnFrançais-"+n);
	  newResultRowChild[1].textContent=word.fr; 
	  

	  newResultRowChild[2].setAttribute("id", "res-test-traduction-"+n);
	  newResultRowChild[2].textContent=trad; 

	  newResultRowChild[3].setAttribute("id", "res-cat-"+n);//dernier td
	  newResultRowChild[3].childNodes[0].id="res-cat-div-"+n;//div

	  console.log(word);
	  console.log(trad);
	  console.log(n);
	  if(word!==false && isTrad(word,trad)){
		  win++; 

		  newResultRowChild[3].childNodes[1].style="width:20px;height:20px;background-color:green;";
		  console.log(" passage...");
		  console.log(newResultRowChild[3].childNodes[1]);
		  
		 }else{
			   console.log(" passage...mot");
			   newResultRowChild[3].childNodes[1].style="width:20px;height:20px;background-color:red;";
		 }

		 console.log(newResultRow);
		

		 return newResultRow; 
	  
	
  }


function getNEwInput(word,n) {
     	
     gameRow=document.getElementById("game-row-0");
     newGameRow = gameRow.cloneNode(true); 
     newGameRow.setAttribute("id", "game-row-"+n);
     newGameRowChild=newGameRow.children; 

     console.log(newGameRowChild[0].childNodes[0]); 

     newGameRowChild[0].setAttribute("id", "td-englishWord-"+n);
     
     // input caché 
     newGameRowChild[0].childNodes[1].setAttribute("id", "motAnglais-"+n);// le mot en anglais est le premier noeud et l'input le deuxième
     newGameRowChild[0].childNodes[1].setAttribute("name", "motAnglais["+n+"]");
     newGameRowChild[0].childNodes[1].value=word.en;
     newGameRowChild[0].childNodes[0].textContent=word.en;

     newGameRowChild[1].setAttribute("id", "td-frenchWord-"+n); 
    // le input affiché 
     newGameRowChild[1].childNodes[0].setAttribute("placeholder", "traduction");
     newGameRowChild[1].childNodes[0].setAttribute("id", "traductionEnFrançais-"+n);
     newGameRowChild[1].childNodes[0].setAttribute("name", "traductionEnFrançais["+n+"]");
     newGameRowChild[1].childNodes[0].value="";
     word.read=true;
    
     return newGameRow; 
}

function isTrad(word,s){

   console.log("p--"+word.fr+"---"+s);
   wordTrad= word.fr.trim().toLowerCase(); 
   wordTradSend=s.trim().toLowerCase(); 
   
     
     if(wordTrad===wordTradSend){

         return true; 

       }else{            
        	wordTradClean=cleanWord(wordTrad);  
        	wordSendClean=cleanWord(wordTradSend); 
        	var wordSendArray = wordSendClean.split(" ");
        	var wordTradArray = wordTradClean.split(" ");
        	
        	 if(haveOccurence(wordSendArray,wordTradClean)||haveOccurence(wordTradArray,wordSendClean)){
                 
                   return true ; 
                   
            	}
           

        	var wordArray2 = wordTrad.split(" ");
        	var wordArraySend = wordTradSend.split(", ");
        	var wordArraySend2 = wordTradSend.split(" ");
        	
        	
        	return false; 

         }

}

  function haveOccurence(wordSendArray,wordTrad){

	  let n =0; 

	  for(i=0;i<wordSendArray.length;i++){

		  if(-1!==wordTrad.indexOf(wordSendArray[i])){
               n++; 
			  }
		  }
	  return n>=wordSendArray.length-2;
	}


function cleanWord(s){
	str=s; ; 
	do{
		beginLength=str.length; 
	    str=str.replace(/ une |le | la | du | des | de | à | un /g,"  "); 
	    endLength= str.length; console.log(str);
	}while(beginLength!==endLength)

		return str; 
 }


	


</script>
