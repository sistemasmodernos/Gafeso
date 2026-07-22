
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Net;
using System.Net.Sockets;
using System.Threading;

namespace socketbuses
{
    class WebSockClient
    {
        public int ManagingThreadId { get; set; }
        public string WebSocketOrigin { get; set; }
        public string WebSocketLocationURL { get; set; }
        public bool IsSubscribed { get; set; }
        private byte[] _buffer = new byte[255];
        private byte[] _writeBuffer;
        private TcpClient _tcpClient;
        public WebSockClientStatus WebSocketConnectionStatus { get; set; }

        public byte[] WriteBuffer
        {
            set { _writeBuffer = value; }
            get { return _writeBuffer; }
        }

        public TcpClient TcpClientInstance
        { get { return _tcpClient; } }

        public WebSockClient(TcpClient t)
        {
            _tcpClient = t;
        }

        public enum WebSockClientStatus
        {
            CONNECTING = 0,
            HANDSHAKEDONE = 3,
            DISCONNECTED = 6,
            CLIENTSUBSCRIBED = 7,
            CLIENTUNSUBSCRIBED = 8
        }
        public WebSockClient(string webSockOrigin, string webSockLocationURL)
        {
            this.WebSocketLocationURL = webSockLocationURL;
            this.WebSocketOrigin = webSockOrigin;
        }

    }
}
