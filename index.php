<!DOCTYPE html>
<html>
<head>

    <!-- Se definen las etiquetas meta para establecer el tipo de caracteres,
    permitir la visualizacion en dispositivos moviles y parametros generales -->
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Genera tu autocotizacion en segundos.">
    <meta name="author" content="Sagaon Tech">

    <title>Completar el envio</title>

    <!-- Link a las hojas de estilos que define el formato de la pagina-->
    
    <link rel="icon" type="image/png" href="/V1/img/favicon.png" sizes="64x64">
    <link rel = "stylesheet" type = "text/css" href = "/V1/style/myStyle.css" />
    <link rel = "stylesheet" type = "text/css" href = "/V1/style/slideShowStyle.css" />
    <!-- Social media icons library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Se importa la libreria de Prototype.js para facilitar funciones de comunicacion -->
    <script type = "text/javascript" src = "/V1/JavaScript/prototype.js"></script>

    <!-- Script que define la conexión con la google-sheet (base de datos)-->
    <script>
        function getProduct() {
            var url_string = window.location.href;
            var url = new URL(url_string);
            var sku = url.searchParams.get("sku");
            new Ajax.Request('/V1/ajaxed/format.php', {
                method: 'post',
                parameters:{
                    command: "getProduct",
                    sku: sku
                    },
                onSuccess: function(transport){
                    var response = transport.responseText || "no reponse text";
                    var responseArray = response.split("-//-");
                    if(responseArray[0] == "1"){
                        document.getElementById("producto").innerHTML = responseArray[1];
                        document.getElementById("descripcion").innerHTML = responseArray[2];
                        document.getElementById("precio").innerHTML = responseArray[4];
                        var imgText = "<img src='img/"+responseArray[3]+"' style='max-width: auto; height: 250px;'>"
                        document.getElementById("imagen1").innerHTML = imgText;
                    }
                },
                onFailure: function(){
                    alert("Something went wrong...");
                }
                });
        }
        
        function regresar() {
            window.location.replace("https://www.sagaon.tech/");
        }
        
        function enviarCorreo(){
            var url_string = window.location.href;
            var url = new URL(url_string);
            var sku = url.searchParams.get("sku");
            var name = document.getElementById("name").value;
            var ap = document.getElementById("ap").value;
            var mail = document.getElementById("mail").value;
            var tel = document.getElementById("tel").value;
            if(name == "" || mail =="" || tel == ""){
                document.getElementById("response").innerHTML = "Complete los campos del formulario"
                document.getElementById("response").style.color = "#8B0000";
                return;
            }
            if(ap == ""){
                ap = "-";
            }
            new Ajax.Request('/V1/ajaxed/format.php', {
                method: 'post',
                parameters:{
                    command: "sendMail",
                    nombre: name,
                    apellido: ap,
                    mail: mail,
                    telefono: tel,
                    sku: sku
                },
                onSuccess: function(transport){
                    var response = transport.responseText || "no reponse text";
                    var responseArray = response.split("-//-");
                    if(responseArray[0] == "1"){
                        document.getElementById("response").innerHTML = responseArray[1];
                        document.getElementById("response").style.color = "#006400";
                    }else{
                        document.getElementById("response").innerHTML = responseArray[1];
                        document.getElementById("response").style.color = "#8B0000";
                    }
                },
                onFailure: function(){
                    alert("Something went wrong...");
                }
            });
        }
        
    </script>
</head>

<body onload="getProduct()">
    <!-- <div id="loading" class="loading">
        <img src="img/cargando.gif" style="max-width: auto; height: 300px;">
    </div> -->
    <div id="container" class="container">
        <div id="topBar" class="topBar">
            <img src="/V1/img/logo_1.png" alt="Sagaon Tech" style="max-width: auto; height: 90px;">
            <a href="https://www.sagaon.tech/">
                <div class='top1' id='top1'> Inicio </div>
            </a>
            <a href="https://www.sagaon.tech/pages/nosotros">
                <div class='top2' id='top2'> Nosotros </div>
            </a>
            <div class='top3' id='top3'>
                <div class="custom-select" style="width:100%;">
                    <select>
                        <option value="0">Producto</option>
                        <option value="1">CNC</option>
                        <option value="2">Laboratorio</option>
                        <option value="3">Empaque y Llenado</option>
                        <option value="4">Procesamiento de Alimentos</option>
                        <option value="5">Reparacion de celulares</option>
                        <option value="6">Otros</option>
                    </select>
                </div>
            </div>
            <a href="https://www.sagaon.tech/pages/contacto">
            <div class='top4' id='top4'> Contacto </div>
            </a>
            <div class='top5' id='top5'>
                <div class='top5_1' id='top5_1'>
                    <img src="img/phone.png">
                </div>
                <div class='top5_2' id='top5_2'>
                    <span>Llamanos<br>55-5018-0480</span>
                </div>
            </div>
            <a href="https://www.sagaon.tech/cart">
            <div class='top6' id='top6'> <img src="img/cart.png"> </div>
            </a>
        </div>
        <div id="intruccionesBlock" class="instruccionesBlock">
            <div id="instrucciones_2" class="instrucciones_2">
                <b>Instrucciones:</b>
                
                <p>Verifica que los datos correspondan al producto a cotizar, rellena
                los datos solicitados, da click en el botón "Enviar" y revisa
                tu correo electrónico, si no lo recibes, revisa tu Spam</p>
            </div>
        </div>
        <div id="formsContainer" class="formsContainer">
            <div id="productContainer" class="productContainer">
                <table class="productTable">
                    <tr class="productTableHeader"><td>
                        <b> Producto: </b>
                    </td></tr>
                    <tr  class="productTableRow_1"><td>
                        <span  id="producto">--Producto--</span>
                    </td></tr>
                    <tr class="productTableHeader"><td>
                        <b> Descripcion: </b>
                    </td></tr>
                    <tr class="productTableRow_2"><td>
                        <span  id="descripcion">--Descripcion--</span>
                    </td></tr>
                    <tr class="productTableHeader"><td>
                        <b> Precio: </b>
                    </td></tr>
                    <tr class="productTableRow_1"><td>
                        <span  id="precio">--Precio--</span>
                    </td></tr>
                    <tr class="productTableHeader"><td>
                        <b> Imagen: </b>
                    </td></tr>
                    <tr class="productTableRow"><td>
                        <div id="imagen1" class="imagen1">
                            --Imagen--
                        </div>
                    </td></tr>
                </table>
            </div>
            <div id="contact" class="contact">
                <form>
                    <div class="dataInput" align="center">
                        <span class="contactLabel">Nombre: </span>
                        <input type="text" class="dataField" id="name" required>
                    </div>
                    <div class="dataInput" align="center">
                        <span class="contactLabel">Apellido: </span>
                        <input type="text" class="dataField" id="ap" >
                    </div>
                    <div class="dataInput" align="center">
                        <span class="contactLabel">Correo: </span>
                        <input type="text" class="dataField" id="mail" required>
                    </div>
                    <div class="dataInput" align="center">
                        <span class="contactLabel">Telefono: </span>
                        <input type="text" class="dataField" id="tel" required>
                    </div>
                    <div id="boton" align="center" style="padding: 15px 0px 10px 0px;">
                        <div class="botonEnviar" id="botonEnviar" onclick="enviarCorreo()">
                            Enviar cotizacion
                        </div>
                    </div>
                </form>
                <div id="boton" align="center" style="padding: 0px 0px 10px 0px;">
                    <div class="botonEnviar" id="botonAtras" onclick="regresar();">
                        Atras
                    </div>
                </div>
                <div id="response" align="center" class="response">
                    
                </div>
            </div>
        </div>
        <!-- Slide show, Opiniones de mercado libre-->
        <div class="slideshow-container">
            <div class="mySlides fade">
                <div class="numbertext">1 / 8</div>
                <img src="img/comment_1.png" style="width: 100%; height: auto;">
            </div>
            <div class="mySlides fade">
                <div class="numbertext">2 / 8</div>
                <img src="img/comment_2.png" style="width: 100%; height: auto;">
            </div>
            <div class="mySlides fade">
                <div class="numbertext">3 / 8</div>
                <img src="img/comment_3.png" style="width: 100%; height: auto;">
            </div>
            <div class="mySlides fade">
                <div class="numbertext">4 / 8</div>
                <img src="img/comment_4.png" style="width: 100%; height: auto;">
            </div>
            <div class="mySlides fade">
                <div class="numbertext">5 / 8</div>
                <img src="img/comment_5.png" style="width: 100%; height: auto;">
            </div>
            <div class="mySlides fade">
                <div class="numbertext">6 / 8</div>
                <img src="img/comment_6.png" style="width: 100%; height: auto;">
            </div>
            <div class="mySlides fade">
                <div class="numbertext">7 / 8</div>
                <img src="img/comment_7.png" style="width: 100%; height: auto;">
            </div>
            <div class="mySlides fade">
                <div class="numbertext">8 / 8</div>
                <img src="img/comment_8.png" style="width: 100%; height: auto;">
            </div>
            <!-- Next and previous buttons -->
            <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
            <a class="next" onclick="plusSlides(1)">&#10095;</a>
        </div>
        <br>
        <!-- Puntos/Circulos del slide show -->
        <div class="dotImages">
            <span class="dot" onclick="currentSlide(1)"></span>
            <span class="dot" onclick="currentSlide(2)"></span>
            <span class="dot" onclick="currentSlide(3)"></span>
        </div>
        <!-- Barra de informacion de contacto y links del final-->
        <div id="bottomBar" class="bottomBar">
            <div id="cuadro1" class="cuadro1" align="center">
                <p><b>Más</b><p>
                <a href="https://www.sagaon.tech/collections/all">Catalogo</a><br>
                <a href="https://www.sagaon.tech/pages/contacto">Estamos Contratando</a><br>
                <a href="https://www.sagaon.tech/policies/refund-policy">Reembolsos<a/>
            </div>
            <div id="cuadro2" class="cuadro2" align="center">
                <p><b>Contactanos</b><p>
                Cel: 55-5018-0480<br>
                Whatsapp: 55-194-772-95<br>
                Correo: ventas@sagaon.tech<br>
                Horario: Lun-Vie 9AM-7PM / Sab 9AM-5PM
            </div>
            <div id="cuadro3" class="cuadro3" align="center">
                <p><b>Siguenos</b><p>
                <a href="https://www.facebook.com/Sagaon.Tech" class="fa fa-facebook"></a>
                <a href="https://www.instagram.com/sagaontech/" class="fa fa-instagram"></a>
                <a href="https://www.youtube.com/channel/UCe3I8UV8iqnB6u71H01SE9w?view_as=subscriber" class="fa fa-youtube"></a>
            </div>
        </div>
    </div>
</body>

<script type = "text/javascript">
    var slideIndex = 1;
    showSlides(slideIndex);

    // Next/previous controls
    function plusSlides(n) {
        showSlides(slideIndex += n);
    }

    // Thumbnail image controls
    function currentSlide(n) {
        showSlides(slideIndex = n);
    }

    function showSlides(n) {
        var i;
        var slides = document.getElementsByClassName("mySlides");
        var dots = document.getElementsByClassName("dot");
        if(n > slides.length){
            slideIndex = 1;
        }
        if(n < 1){
            slideIndex = slides.length;
        }
        for(i = 0; i < slides.length; i++){
            slides[i].style.display = "none";
        }
        for(i = 0; i < dots.length; i++){
            dots[i].className = dots[i].className.replace(" active", "");
        }
        slides[slideIndex-1].style.display = "block";
        dots[slideIndex-1].className += " active";
    }
</script>

<script>
    var x, i, j, selElmnt, a, b, c;
    /* Look for any elements with the class "custom-select": */
    x = document.getElementsByClassName("custom-select");
    for (i = 0; i < x.length; i++) {
      selElmnt = x[i].getElementsByTagName("select")[0];
      /* For each element, create a new DIV that will act as the selected item: */
      a = document.createElement("DIV");
      a.setAttribute("class", "select-selected");
      a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
      x[i].appendChild(a);
      /* For each element, create a new DIV that will contain the option list: */
      b = document.createElement("DIV");
      b.setAttribute("class", "select-items select-hide");
      for (j = 1; j < selElmnt.length; j++) {
        /* For each option in the original select element,
        create a new DIV that will act as an option item: */
        c = document.createElement("DIV");
        c.innerHTML = selElmnt.options[j].innerHTML;
        c.addEventListener("click", function(e) {
            /* When an item is clicked, update the original select box,
            and the selected item: */
            var y, i, k, s, h;
            s = this.parentNode.parentNode.getElementsByTagName("select")[0];
            h = this.parentNode.previousSibling;
            for (i = 0; i < s.length; i++) {
              if (s.options[i].innerHTML == this.innerHTML) {
                s.selectedIndex = i;
                h.innerHTML = this.innerHTML;
                y = this.parentNode.getElementsByClassName("same-as-selected");
                for (k = 0; k < y.length; k++) {
                  y[k].removeAttribute("class");
                }
                this.setAttribute("class", "same-as-selected");
                break;
              }
            }
            h.click();
        });
        b.appendChild(c);
      }
      x[i].appendChild(b);
      a.addEventListener("click", function(e) {
        /* When the select box is clicked, close any other select boxes,
        and open/close the current select box: */
        e.stopPropagation();
        closeAllSelect(this);
        this.nextSibling.classList.toggle("select-hide");
        this.classList.toggle("select-arrow-active");
        if( this.innerHTML == "CNC" ){
            window.location.href = "https://www.sagaon.tech/collections/cnc"
        }else if( this.innerHTML == "Laboratorio" ){
            window.location.href = "https://www.sagaon.tech/collections/laboratorio/"
        }else if( this.innerHTML == "Empaque y Llenado" ){
            window.location.href = "https://www.sagaon.tech/collections/llenadoras"
        }else if( this.innerHTML == "Procesamiento de Alimentos" ){
            window.location.href = "https://www.sagaon.tech/collections/procesamiento-de-alimentos"
        }else if ( this.innerHTML == "Reparacion de celulares" ){
            window.location.href = "https://www.sagaon.tech/collections/reparacion-de-celulares"
        }else if ( this.innerHTML == "Otros" ){
            window.location.href = "https://www.sagaon.tech/collections/otros/"
        }
      });
    }

    function closeAllSelect(elmnt) {
      /* A function that will close all select boxes in the document,
      except the current select box: */
      var x, y, i, arrNo = [];
      x = document.getElementsByClassName("select-items");
      y = document.getElementsByClassName("select-selected");
      for (i = 0; i < y.length; i++) {
        if (elmnt == y[i]) {
          arrNo.push(i)
        } else {
          y[i].classList.remove("select-arrow-active");
        }
      }
      for (i = 0; i < x.length; i++) {
        if (arrNo.indexOf(i)) {
          x[i].classList.add("select-hide");
        }
      }
    }

    /* If the user clicks anywhere outside the select box,
    then close all select boxes: */
    document.addEventListener("click", closeAllSelect); 
</script>

</html>