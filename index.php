<!-- HTML -->
<html>
    <head>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <!--Formulaire d'envoie des donnees d'une course et de son coureur-->
        <form action="sauvegardeBDD.php" method="post" class="form">
            Image de départ 
            <input type="file" id="imageD" name="imageD" accept = "image/*"></br>
            Image d'arriver 
            <input type="file" id="imageA" name="imageA" accept = "image/*"></br>
            <div class="form">
            <label for="nom">Nom</label>
            <input type="text" name="nom" id="nom" >
            </div>
            <div class="form">
            <label for="prenom">Prenom</label>
            <input type="text" name="prenom" id="prenom" required>
            </div>
            <div class="form">
            <label for="distance">Distance parcourue</label>
            <input type="text" name="distance" id="distance" required readonly="false">
            </div>
            <div class="form">
            <label for="tempsD">Temps de départ : </label>
            <input type="tempsD" name="tempsD" id="tempsD" required >
            </div>
            <div class="form">
            <label for="tempsA">Temps d'arrivé : </label>
            <input type="tempsA" name="tempsA" id="tempsA" required >
            </div>
            <div class="form">
            <label for="tempsM">Temps mis en seconde : </label>
            <input type="tempsM" name="tempsM" id="tempsM" required readonly="false">
            </div>
            <div class="form">
            <label for="vitesse">Vitesse en km/h : </label>
            <input type="vitesse" name="vitesse" id="vitesse" required readonly="false">
            </div>

            <div class="form">
            <input type="button" id="afficherTempsMis" value="Calculer le temps mis" >
            <input type="button" id="afficherVitesse" value="Calculer la vitesse">
            <input type="submit" value="Sauvegarder">
            </div>
        </form>
        <div>
        
        <button id="afficherdistance" >afficher les distances</button>
        <canvas id= "myCanvas" width="1030" height="1156"></canvas>
        
        </div>
        <div>
          <a href=classement.php> Classement</a>
        </div>
    </body>
</html>

<!-- Javascript -->
<script>
    
    let tableau = []; // tableau valeurs de x dans les photo.
    var compteur = 0; //compteur de click dans le canvas
  
  // met l'image de depart dans le canvas
  let imgInput = document.getElementById('imageD');
  imgInput.addEventListener('change', function(e) {
    if(e.target.files) {
      let imageFile = e.target.files[0]; //here we get the image file
      var reader = new FileReader();
      reader.readAsDataURL(imageFile);
      reader.onloadend = function (e) {
        var myImage = new Image(); // Creates image object        
        myImage.src = e.target.result; // Assigns converted image to image object
        myImage.onload = function(ev) {
          var myCanvas = document.getElementById("myCanvas"); // Creates a canvas object
          var myContext = myCanvas.getContext("2d"); // Creates a contect object
          myContext.drawImage(myImage,0,0,myCanvas.width, 578); // Draws the image on canvas
          let imgData = myCanvas.toDataURL("image/jpeg",0.75); // Assigns image base64 string in jpeg format to a variable
        }
      }
    }
  });

  // met l'image d'arriver dans le canvas
  let imgInput2 = document.getElementById('imageA');
  imgInput2.addEventListener('change', function(e) {
    if(e.target.files) {
      let imageFile = e.target.files[0]; //here we get the image file
      var reader = new FileReader();
      reader.readAsDataURL(imageFile);
      reader.onloadend = function (e) {
        var myImage = new Image(); // Creates image object        
        myImage.src = e.target.result; // Assigns converted image to image object
        myImage.onload = function(ev) {
          var myCanvas = document.getElementById("myCanvas"); // Creates a canvas object
          var myContext = myCanvas.getContext("2d"); // Creates a contect object
          myContext.drawImage(myImage,0,578,myCanvas.width, 578); // Draws the image on canvas
          // let imgData = myCanvas.toDataURL("image/jpeg",0.75); // Assigns image base64 string in jpeg format to a variable
        }
      }
    }
  });

  let dessine = false;
  let x = 0;
  let y = 0;

  // recupere le canvas et creer context de desssin
  const myCanvas = document.getElementById('myCanvas');
  const context = myCanvas.getContext('2d');

  // distance entre bord de la page et canvas
  const rect = myCanvas.getBoundingClientRect();

  // repurere les coordonnees dans le canvas et met desinne a true
  myCanvas.addEventListener('mousedown', e => {
    x = e.clientX - rect.left;
    y = e.clientY - rect.top;
    dessine = true;
  });

  // dessine les lignes vertical dans le canvas
  myCanvas.addEventListener('mouseup', e => {
    if (dessine === true) {
      ligneVerticale(context, x, 1156, 0);
      let nouveauplot = tableau.push(x);
      //alert(tableau); //test
      compteur +=1;
      //alert(compteur) //test
      x = 0;
      y = 0;
      dessine = false;
    }
  });

  // fonction de dessin d'une ligne verticale
  function ligneVerticale(context, x1, y1, y2) {
    context.beginPath();
    if(compteur < 2) { context.strokeStyle = 'red'; }
    else { context.strokeStyle = 'blue'; }
    context.lineWidth = 2;
    context.moveTo(x1, y1);
    context.lineTo(x1, y2);
    context.stroke();
    context.closePath();
  }

  // fonction de dessin d'une ligne horizontale 
  function ligneHorizontale(context, x1, x2, y1) {
    context.beginPath();
    context.strokeStyle = 'black';
    context.lineWidth = 2;
    context.moveTo(x1, y1);
    context.lineTo(x2, y1);
    context.stroke();
    context.closePath();
  }

  // placement des ligne horizontale entre les deux lignes vertical correspondante
  function distance(context) {
      context.font = '25px Arial';
      ligneHorizontale(context, tableau[0], tableau[1], 360);
      context.strokeText(10 + " m", (tableau[0] + tableau[1])/2, 350 );
      ligneHorizontale(context, tableau[2], tableau[3], 400);
      var distanceParcourue = ((Math.abs(tableau[2] - tableau[3]))*10)/Math.abs(tableau[0] - tableau[1]);
      context.strokeText(distanceParcourue.toPrecision(4) + " m", (tableau[2] + tableau[3])/2, 390 );
      // a changer de place
      document.getElementById("distance").value = distanceParcourue.toPrecision(4);
      var parcourue = distanceParcourue.toPrecision(4);
      return parcourue;

  }

  // button afficher distance
  const afficherdistance = document.getElementById('afficherdistance');
  afficherdistance.addEventListener('mousedown',  function(){ distance(context);});

  // button afficher temps mis
  const afficherTempsMis = document.getElementById('afficherTempsMis');
  afficherTempsMis.addEventListener('mousedown', function(){ var tempsD = document.getElementById('tempsD').value;
    var tempsA = document.getElementById('tempsA').value;
    tempsMis = Math.abs((tempsA - tempsD)*100);
    document.getElementById('tempsM').value = tempsMis.toPrecision(3);}); 

  // button afficher vitesse
  const afficherVitesse = document.getElementById('afficherVitesse');
  afficherVitesse.addEventListener('mousedown', function(){ var tempsD = document.getElementById('tempsD').value;
  var tempsA = document.getElementById('tempsA').value; var distance = document.getElementById('distance').value; var vitesse1=vitesse(distance, tempsD, tempsA);document.getElementById("vitesse").value = vitesse1 ;});

  // Calcul la vitesse en km/h
  function vitesse(distance, temps1, temps2)
  {
      var distanceEnKm = distance/1000;
      tempsD = temps1;
      tempsA = temps2;
      TempsMis = Math.abs(((temps2 - temps1)*100)/3600);
      var vitesse = distanceEnKm/(TempsMis);
      var vitesse2 = vitesse.toPrecision(3);    
      return vitesse2;
  }
</script>