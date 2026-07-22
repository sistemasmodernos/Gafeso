/********************************************************************
*	El siguiente programa es un ejemplo bastante sencillo de 		*
*	impresión con JAVA. 											*
********************************************************************/
import javax.swing.*;
import java.awt.*;
import java.awt.event.*;


/********************************************************************
*	La siguiente clase llamada "Impresora", es la encargada de  	*
*	establecer la fuente con que se va a imprimir, de obtener el	*
*	trabajo de impresion, la página. En esta clase hay un método	*
*	llamado imprimir, el cual recibe una cadena y la imprime.		*
********************************************************************/
public class Impresora
{
    Font fuente = new Font("Dialog", Font.PLAIN, 10);
	PrintJob pj;	
	Graphics pagina;
	

	/********************************************************************
	*	A continuación el constructor de la clase. Aquí lo único que	*
	*	hago es tomar un objeto de impresion.							*
	********************************************************************/
	Impresora()
	{
		JobAttributes  theJobAttribs  = new JobAttributes();
		PageAttributes thePageAttribs = new PageAttributes();
 		theJobAttribs.setDialog(JobAttributes.DialogType.NONE);
		theJobAttribs.setPrinter("PDFill PDF"); // the printer to be used
		pj = Toolkit.getDefaultToolkit().getPrintJob(new Frame(), "PrintJob",theJobAttribs,thePageAttribs);

	}
			
	/********************************************************************
	*	A continuación el método "imprimir(String)", el encargado de 	*
	*	colocar en el objeto gráfico la cadena que se le pasa como 		*
	*	parámetro y se imprime.											*
	********************************************************************/
    public void imprimir(String Cadena)
	{
		//LO COLOCO EN UN try/catch PORQUE PUEDEN CANCELAR LA IMPRESION
		try
		{
			pagina = pj.getGraphics();
			pagina.setFont(fuente);
			pagina.setColor(Color.black);
			String[] matriz= Cadena.split("[\r\n]+");
			int pos=30;
			for (int x=0;x<matriz.length;x++){
				pagina.drawString(matriz[x], 10,pos);
				pos=pos+15;
			}

			pagina.dispose();
			pj.end();
		}catch(Exception e)
		{
			System.out.println("LA IMPRESION HA SIDO CANCELADA...");
		}
	}//FIN DEL PROCEDIMIENTO imprimir(String...)

					
}//FIN DE LA CLASE Impresora


