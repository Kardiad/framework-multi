<h1>Bienvenido <?=$data->name?></h1>
<script type="module">
    import {SocketClient} from '/public/SocketClient.js';
    const socket = new SocketClient("ws://localhost:8080");
</script>