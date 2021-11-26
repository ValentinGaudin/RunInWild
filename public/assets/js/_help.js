// const modale = document.getElementById('help');


// document.getElementById('question').addEventListener('click') = {
//     if ( modale = style.display = "hidden") {
//         modale = style.display = "block";
//     } 
// }



// 		Voici une fenêtre d'information !<br /> 
// 		<p>Elle peut contenir tout type de balises HTML.</p> 
// 		<div>Remplir ce champ pour modifier le message de fermeture 
// 		<div class="boutons"><button onclick="$dialog.close()">Fermer</button>&nbsp;<button onclick="$dialog.returnValue = document.getElementById('closeMsg').value">Définir un message</button></div> 
// 	</dialog> 
// 	<div class="boutons"><button onclick="$dialog.show()">Ouvrir en mode normal</button>&nbsp;<button onclick="$dialog.showModal()">Ouvrir en mode modal</button></div> 
// 	<div class="boutons"><button onclick="alert($dialog.open)">Vérifier l'attribut <code>open</code></button></div> 
	
    

                var $dialog = document.getElementById('mydialog'); 
                if(!('show' in $dialog)){ 
                        document.getElementById('promptCompat').className = 'no_dialog'; 
                } 
                $dialog.addEventListener('close', function() { 
                        console.log('Fermeture. ', this.returnValue); 
                }); 
