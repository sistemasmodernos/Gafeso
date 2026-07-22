using System;
using System.Collections.Generic;
using System.IO;
using System.Net;
using System.Net.Sockets;
using System.Security.Cryptography;
using System.Text;
using System.Text.RegularExpressions;
using System.Threading;
namespace socketbuses{
    class Program
    {
        static WebSockClientManager m;
        static int _port, _maxConnection;
        static string _origin, _location;


        static void Main(string[] args)
        {
            Console.WriteLine("Iniciando Socket");
            if (!int.TryParse(System.Configuration.ConfigurationSettings.AppSettings["PORT"], out _port))
            {
                Console.WriteLine("Invalid port number specified in config file.");
                return;
            }

            if (!int.TryParse(System.Configuration.ConfigurationSettings.AppSettings["MAXCONNECTIONCOUNT"], out _maxConnection))
            {
                Console.WriteLine("Invalid max connection number specified in config file.");
                return;
            }

            _location = System.Configuration.ConfigurationSettings.AppSettings["WEBSOCKETLOCATION"];
            _origin = System.Configuration.ConfigurationSettings.AppSettings["WEBSOCKETORIGIN"];
            Console.WriteLine("Listo para imprimir en "+System.Configuration.ConfigurationSettings.AppSettings["IMPRESORA"]);
            TcpListener t = new TcpListener(IPAddress.Loopback, _port);
            m = new WebSockClientManager(_maxConnection, _origin, _location, _port);
            t.Start();
            while (true)
            {
                TcpClient c = t.AcceptTcpClient();
                //c.Client.SetSocketOption(SocketOptionLevel.Tcp, SocketOptionName.KeepAlive, true);
                WebSockClient w = new WebSockClient(c);
                Thread.Sleep(300);
                m.AddClient(w);
                if (w.WebSocketConnectionStatus == WebSockClient.WebSockClientStatus.HANDSHAKEDONE)
                {
                    w.WriteBuffer = ASCIIEncoding.ASCII.GetBytes("Hello Client " + w.ManagingThreadId.ToString());
                }

            }

        }

   


    }
}