export class SocketClient{

    socket;

    data;

    constructor(url, options = []){
        this.socket = new WebSocket(url, options);
        this.socket.addEventListener('open', (res)=>{
            console.log(res)
            this.data = res;
        })
        this.socket.addEventListener('message', (res)=>{
            console.log(res)
            this.data = res;
        })
        this.socket.addEventListener('error', (res)=>{
            console.log(res)
            this.data = res;
        })
        this.socket.addEventListener('close', (res)=>{
            console.log(res)
            this.data = res;
        })
    }

    getData = () =>{
        return this.data
    }

    sendMessage = (data) => {
        this.socket.send(data)
    }

}