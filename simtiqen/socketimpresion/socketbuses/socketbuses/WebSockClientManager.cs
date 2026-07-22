/*All rights reserved, Copyrights Ashish Patil, 2010. http://ashishware.com/
 * Permission granted to modify/use this code for non commercial purpose so long as the
 * original notice is retained. This code is for educational purpose ONLY. 
 * DISCLAIMER: The code is provided as is, without warranties of any kind. The author
 * is not responsible for any damage (of any kind) this code may cause. The author is also not
 * responsible for any misuse of the code. Use and run this code AT YOUR OWN RISK.
 * 
 */
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading;
using System.Diagnostics;
using System.Net;
using System.Net.Sockets;
using System.IO;
using System.Text.RegularExpressions;
using System.Security.Cryptography;

namespace socketbuses
{
    class WebSockClientManager
    {
        private List<WebSockClient> _clientList;
        private delegate void ClientHandler(WebSockClient c);
        private static int _port, _maxConnection;
        private static string _origin, _location;

        private WebSockClientManager()
        {

        }

        public WebSockClientManager(int maxConnection, string origin, string location, int port)
        {
            if (string.IsNullOrEmpty(location) || string.IsNullOrEmpty(origin))
            {
                throw new Exception("Location and Origin are required!");
            }

            _maxConnection = maxConnection;
            _origin = origin;
            _location = location;
            _port = port;
            _clientList = new List<WebSockClient>();
        }

        public void AddClient(WebSockClient c)
        {
            if (_clientList.Count >= _maxConnection)
            { // check if any connection is available
                List<int> closedClients = new List<int>();
                for (int i = 0; i < _clientList.Count; i++)
                {
                    if (_clientList[i].TcpClientInstance.Connected == false)
                    {
                        _clientList[i].TcpClientInstance.Close();

                        closedClients.Add(i);
                    }
                }

                foreach (int e in closedClients)
                {
                    _clientList.RemoveAt(e);
                }
            }

            if (_clientList.Count < _maxConnection)
            {
                _clientList.Add(c);

                Thread clientThread = new Thread(delegate()
                {
                    this.HandleClient(c); ;
                });
                c.ManagingThreadId = clientThread.ManagedThreadId;
                clientThread.Start();
                Console.WriteLine("New Thread Started:" + c.ManagingThreadId.ToString());
            }
            else
            {
                //sorry
                c.TcpClientInstance.Close();
            }

            Console.WriteLine("Thread count :" + Process.GetCurrentProcess().Threads.Count.ToString());

        }

        private void HandleClient(WebSockClient c)
        {
            try
            {
                int b;
                c.WebSocketConnectionStatus = WebSockClient.WebSockClientStatus.CONNECTING;
                c.IsSubscribed = true;
                using (NetworkStream n = c.TcpClientInstance.GetStream())
                using (StreamWriter streamWriter = new StreamWriter(n))
                {
                    byte[] buff = new byte[255];
                    c.WebSocketConnectionStatus = WebSockClient.WebSockClientStatus.CONNECTING;

                    while (c.TcpClientInstance.Connected)
                    {



                        /*
                        //Read and Validate client Handshake.
                        if (c.WebSocketConnectionStatus == WebSockClient.WebSockClientStatus.CONNECTING)
                            while (n.DataAvailable)
                            { n.Read(buff, 0, buff.Length); }
                        */

                        //Send Sever Handshake to client.
                        if (c.WebSocketConnectionStatus == WebSockClient.WebSockClientStatus.CONNECTING)
                        {
                            /*  string handshake =
                              "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" +
                              "Upgrade: WebSocket\r\n" +
                              "Connection: Upgrade\r\n" +
                              "WebSocket-Origin: "+_origin+"\r\n" +
                              "WebSocket-Location: "+_location+"\r\n" +
                              "Sec-WebSocket-Origin: " + _origin + "\r\n" +
                              "Sec-WebSocket-Location: " + _location + "\r\n" +
                              "\r\n";
                              streamWriter.Write(handshake);
                              streamWriter.Flush();
                             * */
                            saludo(n);
                        }

                        if (c.TcpClientInstance.Connected)
                        {
                            c.WebSocketConnectionStatus = WebSockClient.WebSockClientStatus.HANDSHAKEDONE;
                        }

                        // Read data from client. Do whatever is required.
                        string line;
			string todotexto="";
                        if (n.DataAvailable)
                        {
                            while (n.DataAvailable)
                            {
                                Console.WriteLine("imprimiendo");
                                while ((n.DataAvailable) && ((line = ReadLine(n)) != string.Empty))
                                {
                                    Console.WriteLine(line);
				    todotexto+=line+System.Environment.NewLine;
                                }
                            }
			    Impresora imp = new Impresora();
			    imp.imprimir(todotexto);
                            c.TcpClientInstance.Close();
                            Console.WriteLine("Client:" + c.ManagingThreadId.ToString() + " closed");
                            c.WebSocketConnectionStatus = WebSockClient.WebSockClientStatus.DISCONNECTED;
                        }


                        // If Writebuffer is full, write stuff to client
                        if (c.WriteBuffer != null && c.WriteBuffer.Length > 0 && c.IsSubscribed)
                        {
                            n.WriteByte(0x00);
                            n.Write(c.WriteBuffer, 0, c.WriteBuffer.Length);
                            n.WriteByte(0xff);
                            c.WriteBuffer = null;
                        }

                    }
                }
            }
            catch (Exception e)
            {
                if (c.TcpClientInstance.Connected == true)
                { Console.WriteLine(e.StackTrace); }
                return;


            }
            finally
            {
                c.TcpClientInstance.Close();
                Console.WriteLine("Client:" + c.ManagingThreadId.ToString() + " closed");
                c.WebSocketConnectionStatus = WebSockClient.WebSockClientStatus.DISCONNECTED;
            }

        }

        public void WriteData(string data)
        {

            foreach (WebSockClient wc in _clientList)
            {
                try
                {
                    if (wc.TcpClientInstance.Connected && wc.WebSocketConnectionStatus == WebSockClient.WebSockClientStatus.HANDSHAKEDONE
                        && wc.IsSubscribed)
                    {
                        wc.WriteBuffer = Encoding.ASCII.GetBytes(data);
                    }

                }
                catch
                {
                    Console.WriteLine("Writing to client failed.Closing client.");
                    wc.TcpClientInstance.Close();
                }
            }

        }
        public void saludo(NetworkStream stream)
        {
            var headers = new Dictionary<string, string>();
            string line = string.Empty;
            while ((line = ReadLine(stream)) != string.Empty)
            {
                var tokens = line.Split(new char[] { ':' }, 2);
                if (!string.IsNullOrEmpty(line) && tokens.Length > 1)
                {
                    headers[tokens[0]] = tokens[1].Trim();
                }
                Console.WriteLine(line);
            }

            String secWebSocketAccept = ComputeWebSocketHandshakeSecurityHash09(headers["Sec-WebSocket-Key"]);

            // send handshake to this client only
            var response = "HTTP/1.1 101 Web Socket Protocol Handshake" + Environment.NewLine +
            "Upgrade: WebSocket" + Environment.NewLine +
            "Connection: Upgrade" + Environment.NewLine +
            "WebSocket-Origin: " + headers["Origin"] + Environment.NewLine +
            "WebSocket-Location: ws://localhost:8181/imprimir" + Environment.NewLine +
            "Sec-WebSocket-Accept: " + secWebSocketAccept + Environment.NewLine +
            Environment.NewLine;
            
/*
            




            var key = new byte[8];
            //stream.Read(key, 0, key.Length);

            var key1 = headers["Sec-WebSocket-Key1"];
            var key2 = headers["Sec-WebSocket-Key2"];

            var numbersKey1 = Convert.ToInt64(string.Join(null, Regex.Split(key1, "[^\\d]")));
            var numbersKey2 = Convert.ToInt64(string.Join(null, Regex.Split(key2, "[^\\d]")));
            var numberSpaces1 = CountSpaces(key1);
            var numberSpaces2 = CountSpaces(key2);

            var part1 = (int)(numbersKey1 / numberSpaces1);
            var part2 = (int)(numbersKey2 / numberSpaces2);

            var result = new List<byte>();
            result.AddRange(GetBigEndianBytes(part1));
            result.AddRange(GetBigEndianBytes(part2));
            result.AddRange(key);

            var response =
                "HTTP/1.1 101 WebSocket Protocol Handshake" + Environment.NewLine +
                "Upgrade: WebSocket" + Environment.NewLine +
                "Connection: Upgrade" + Environment.NewLine +
                "Sec-WebSocket-Origin: " + headers["Origin"] + Environment.NewLine +
                "Sec-WebSocket-Location: ws://localhost:8181/imprimir" + Environment.NewLine +
                Environment.NewLine;
            */
            var bufferedResponse = Encoding.UTF8.GetBytes(response);
            stream.Write(bufferedResponse, 0, bufferedResponse.Length);
     /*       using (var md5 = MD5.Create())
            {
                var handshake = md5.ComputeHash(result.ToArray());
                stream.Write(handshake, 0, handshake.Length);
            }
      */ 
        }
        static int CountSpaces(string key)
        {
            return key.Length - key.Replace(" ", string.Empty).Length;
        }

        static string ReadLine(NetworkStream stream)
        {
            var sb = new StringBuilder();
            var buffer = new List<byte>();
            var line ="";
            int bit;

            if (stream.DataAvailable)
                bit = stream.ReadByte();
            else
                return "";
            while (bit != -1 && stream.DataAvailable)
            {
                buffer.Add((byte)bit);
                line = Encoding.UTF8.GetString(buffer.ToArray());
                if (line.EndsWith(Environment.NewLine))
                {
                    return line.Substring(0, line.Length - 2);
                }
                bit = stream.ReadByte();
            }
            return line.Substring(0, line.Length);
        }

        static byte[] GetBigEndianBytes(int value)
        {
            var bytes = 4;
            var buffer = new byte[bytes];
            int num = bytes - 1;
            for (int i = 0; i < bytes; i++)
            {
                buffer[num - i] = (byte)(value & 0xffL);
                value = value >> 8;
            }
            return buffer;
        }
        public static String ComputeWebSocketHandshakeSecurityHash09(String secWebSocketKey)
        {
            const String MagicKEY = "258EAFA5-E914-47DA-95CA-C5AB0DC85B11";
            String secWebSocketAccept = String.Empty;

            // 1. Combine the request Sec-WebSocket-Key with magic key.
            String ret = secWebSocketKey + MagicKEY;

            // 2. Compute the SHA1 hash
            SHA1 sha = new SHA1CryptoServiceProvider();
            byte[] sha1Hash = sha.ComputeHash(Encoding.UTF8.GetBytes(ret));

            // 3. Base64 encode the hash
            secWebSocketAccept = Convert.ToBase64String(sha1Hash);

            return secWebSocketAccept;
        }

    }
}
