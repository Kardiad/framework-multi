<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/css/css.css">
    <title>Document</title>
</head>
<body>
    <h1>Bienvenido</h1>
    <img src="/public/img/patata.jpg" width="300px" alt="">
    <?php foreach((array)$data as $user):?>
        <p><?= $user['nombre']?></p>
    <?php endforeach;?>
    <script type="module">
        import {SocketClient} from '/public/SocketClient.js';
        const socket = new SocketClient("ws://localhost:8080/test");
        setTimeout(()=>{
            console.log(socket.getData());
            socket.sendMessage(JSON.stringify(
                { 
                    "type": "message", 
                    "room": "nombre_de_la_sala", 
                    "data": "Este es el mensaje" 
                }
            ), (res) => { console.log(res)})
        }, 10000)
    </script>
</body>
</html>