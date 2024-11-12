<h1>Bienvenido <?=$data->name?></h1>
<script type="module">
    import {SocketClient} from '/public/SocketClient.js';
    const socket = new SocketClient("ws://localhost:8080/test");
    setTimeout(()=>{
        socket.sendMessage(JSON.stringify({ "type": "message", "room": "nombre_de_la_sala", "data": "Este es el mensaje" }))
    }, 2000)
</script>