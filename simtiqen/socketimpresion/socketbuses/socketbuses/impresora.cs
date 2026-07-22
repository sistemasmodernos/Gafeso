using System;
using System.Data;
using System.Configuration;
using System.Drawing.Printing;
using System.Drawing;
using System.IO;

namespace socketbuses{
    class Impresora {
      string textoaimprimir;
    public void imprimir(string texto) {
        PrintDocument printDoc = new PrintDocument();
        printDoc.PrintPage += new PrintPageEventHandler(printDoc_PrintPage);
        printDoc.PrinterSettings.PrinterName = System.Configuration.ConfigurationSettings.AppSettings["IMPRESORA"];
        textoaimprimir = texto;
        //textoaimprimir = "Esto esta prueba \n segunda linea ";
        printDoc.Print();
    }
    void printDoc_PrintPage(Object sender, PrintPageEventArgs e)
    {
        
        Font printFont = new Font("Courier New", 11);
        Font fontnormal = new Font("Courier New", 11 );
        Font fontgrande = new Font("Courier New", 14);
        Font fontnormalN = new Font("Courier New", 12, FontStyle.Bold);
        Font fontgrandeN = new Font("Courier New", 14, FontStyle.Bold);
        float yPostion = 0;
        string texto="";
        StringReader  myReader = new StringReader(this.textoaimprimir);
        while ((texto = myReader.ReadLine()) != null)
        {

            if (texto.Contains("<grande>"))
            {
                if (texto.Contains("<negrita>"))
                    printFont = fontgrandeN;
                else
                    printFont = fontgrande;
            }
            else
            {
                if (texto.Contains("<negrita>"))
                    printFont = fontnormalN;
                else
                    printFont = fontnormal;
            }
            texto.Replace("<grande>", "");
            texto.Replace("</grande>", "");
            texto.Replace("<negrita>", "");
            texto.Replace("</negrita>", "");

            e.Graphics.DrawString(texto, printFont, Brushes.Black, 0, yPostion );
            yPostion = yPostion + printFont.GetHeight(e.Graphics);
        }
    }
}
}
