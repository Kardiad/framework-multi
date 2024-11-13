export class SocketClient{

    socket;

    data;

    constructor(url, options = []){
        this.socket = new WebSocket(url, options);
        this.socket.addEventListener('open', (res)=>{
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
        console.log(this.socket);
    }

    getData = () =>{
        return this.data
    }

    sendMessage = (data, callback) => { 
        this.socket.addEventListener('message', (res)=>{
            callback(res);
        })
        this.socket.send(data)
    }

}