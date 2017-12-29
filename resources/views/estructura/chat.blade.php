 <div class="fixed-action-btn chatOpen" style="bottom: 45px; right: 24px;">
            <a id="menu" class="btn btn-floating btn-large teal pulse" onclick="actionChat();"><center><i class="material-icons">message</i></center></a>
        </div>
        <div class="fixed-action-btn chatClose" style="bottom: 45px; right: 24px; display: none;">
            <a id="menu" class="btn btn-floating btn-large teal pulse" onclick="actionChat();"><center><i class="material-icons">close</i></center></a>
        </div>

        <div class="fixed-action-btn chat">

            <div class="header teal">

                <div class="toolbar">
                    <a class="cursosPointer" onclick="actionChat();"><i class="material-icons">close</i></a>
                </div>


                <!-- Imagen para el asesor -->
                <div class="hide-on-med-and-down">
                    <center>
                        <img class="circle" src="{{ asset('bower_components/AdminLTE/dist/img/user8-128x128.jpg') }}">
                    </center>
                </div>
                <!-- Mensaje de bienvenida -->
                <div class="hmsg">
                    <span>Hola somos Jalapeño Tours y nos apasiona servirle
                        y orientarle en su próximo viaje.
                        Aquí empiezan sus próximas vacaciones, ¿tienes alguna pregunta?</span>
                </div>
<!--                <div class="hfoot">
                    <center>
                        <a class="btn-floating blue"><i class="fa fa-facebook"></i></a>
                    </center>
                </div>-->
            </div>
            <div class="mensajes">


                <p class="left">Hola mundo</p>
                <p class="right">Hola como estas</p>
                <p class="left">Excelente</p>
                <p class="right">me da gusto</p>

                <p class="left">Hola mundo</p>
                <p class="right">Hola como estas</p>
                <p class="left">Excelente to</p>
                <p class="right">me da gusto</p>





            </div>
            <div class="container-msg valign-wrapper">
                <textarea id="msg" placeholder="Escribe tu mensaje..."></textarea>
                <button class="btn btn-floating btnChat"><center><i class="material-icons">send</i></center></button>
            </div>


            <div class="triangulo"></div>
        </div>
        <script>

            function actionChat() {
                $('.chat').toggle('.display');
                $('.chatOpen').toggle();
                $('.chatClose').toggle();

            }

        </script>