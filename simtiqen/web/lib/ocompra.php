<?php
class OCompra{
   var $idCompra;
   var $fecha;
   var $proveedor;
   var $credito;
   var $correo;
   var $estado;
   var $notas;
   var $solicitado;
   var $bitacora;
   var $lineas;
   var $remitente;
   var $placa;
   var $idremitenete;
   
   function Limpiar(){
     $this->proveedor="";
     $this->credito=0;
     $this->correo="";
     $this->estado=0;
     $this->notas="";
     $this->solicitado="";
     $this->remitente="";
     $this->placa="";
     $this->idremitente=1;
     
}
     function Cargar(){
         $link =new tiqmysql();      
         $sql = " select * from ocompra where idcompra=".$this->idCompra;
         $res = $link->bdEjecutar($sql);
         if($link->bdCantLineas($res)==0)
           return false;
        $datos = mysqli_fetch_array($res);
        $this->proveedor=$datos["proveedor"];
        $this->fecha = $datos["fecha"];
        $this->credito=$datos["credito"];
        $this->correo=$datos["correo"];
        $this->estado=$datos["estado"];
        $this->notas=$datos["notas"];
        $this->solicitado=$datos["solicitado"];
        $this->idremitente=$datos["idremitente"];
        $this->remitente=$datos["remitente"];
        $this->placa=$datos["placa"];
        $sql = "select * from docompra where idcompra=".$this->idCompra;
        $resulquery= $link->bdEjecutar($sql);
        $this->lineas=array();
        while($lin=mysqli_fetch_array($resulquery)){
            $nueva=$this->nuevaLinea();
            $nueva->idLinea = $lin["idlinea"];
            $nueva->codigo = $lin["codigo"];
            $nueva->descripcion = $lin["descripcion"];
            $nueva->cantidad = $lin["cantidad"];
            $nueva->precio = $lin["precio"];
            $nueva->descuento = $lin["descuento"];
            $nueva->exento  = $lin["exento"];
            $nueva->impuesto = $lin["impuesto"];
        }
        return true;
    }
     function Guardar($lnk){
        $this->Validar();
        if($link==null)
			$link =new tiqmysql();      
        if($this->idCompra==0){ //es nuevo
          $sql=sprintf("insert into ocompra (proveedor,credito,correo,estado,
          notas,solicitado,fecha,remitente,placa,idremitente) 
           values ('%s',%d,'%s',%d,'%s','%s',now(),'%s','%s',%d) ",
           sqlClearText($this->proveedor),
           $this->credito,
           sqlClearText($this->correo),
           $this->estado,
           sqlClearText($this->notas),
           $this->solicitado,
           $this->remitente,
           $this->placa,
           $this->idremitente
           );
           $bit="Crea orden de compra ";
        }
        else{
            $sql = sprintf(" update ocompra set proveedor='%s' , credito=%d,correo='%s',
              estado=%d,notas='%s',solicitado='%s' , remitente='%s', placa='%s' , idremitente=%d
              where idcompra=%d
            ",
            sqlClearText($this->proveedor),
            $this->credito,
            sqlClearText($this->correo),
            $this->estado,
            sqlClearText($this->notas),
            sqlClearText($this->solicitado),
            sqlClearText($this->remitente),
            sqlClearText($this->placa),
            $this->idremitente,
            $this->idCompra);
            $bit="Actualiza orden de compra ";
    }
        
        $nueva= ($this->idCompra==0);
        
        $link->bdAbreTran();
		$res = $link->bdEjecutar($sql);
		if($nueva){

		  $this->idCompra=$link->bdUltimoId();

        }
        $this->GuardaLinea($link);
        $bit.=$this->idCompra;
		$this->bitacora->Bitacorizar($bit);
        $link->bdCierraTran();
       return $this->idCompra;
}
function Anular(){
              $link =new tiqmysql(); 
              $sql="update ocompra set estado=1 where idcompra=".$this->idCompra;
              $res=$link->bdEjecutar($sql);
              $this->bitacora->Bitacorizar("Anula orden de compra ".$this->idCompra);
}

    function NuevaLinea(){
        $nuevo = new DCompra();
        array_push($this->lineas,$nuevo);
        return $nuevo;
    }
    function GuardaLinea($link){
        $modif="";
         for($x=0;$x<count($this->lineas);$x++)
         {
             $lin = $this->lineas[$x];
             
             if($lin->idLinea==0){
                  $sql=sprintf(" insert into docompra ( idcompra,codigo,descripcion,
                  cantidad,precio,descuento,exento,impuesto) 
                  values (%d,'%s','%s',%f,%f,%d,%d,%f)
                   ",
                   $this->idCompra,
                    sqlClearText($lin->codigo),
                    sqlClearText($lin->descripcion),
                   $lin->cantidad,
                   $lin->precio,
                   $lin->descuento,
                   $lin->exento,
                   $lin->impuesto
                   );
                   $link->bdEjecutar($sql);
                   $lin->idLinea=$link->bdUltimoId();
             }
             else
             {
                 $sql = sprintf("update docompra set codigo='%s',
                       descripcion='%s',
                       cantidad=%d,
                       precio=%f,
                       descuento=%f,
                       exento=%d,
                       impuesto=%f
                       where idlinea=%d
                       ",
                       $lin->codigo,
                       $lin->descripcion,
                       $lin->cantidad,
                       $lin->precio,
                       $lin->descuento,
                       $lin->exento,
                       $lin->impuesto,
                       $lin->idLinea                       
                       );
             }
             $modif.=$lin->idLinea.",";
            
        }
        if($modif=="")
           $sql="delete from docompra where idcompra=".$this->idCompra;
        else
           $sql="delete from docompra where idcompra=".$this->idCompra." and not idlinea in (".substr($modif,0,strlen($modif)-1).") ";
            
           $link->bdEjecutar($sql);
    }

    function Validar(){
        return true;
   }
  function ListaActivos($filtro,$cantlineas){
      $link =new tiqmysql();      
      if($filtro!="")
         $elfil = " and (proveedor like '%".trim($filtro)."%' or d.descripcion like'%".trim($filtro)."%') ";
      else
         $elfil="";
         
      $sql="select c.*,round(sum((d.cantidad*d.precio)-d.descuento+d.impuesto),2) as total
       ,max(d.descripcion) as producto
        from ocompra  c left join docompra d 
        on c.idcompra=d.idcompra
       where estado=0 ".$elfil."
 group by c.idcompra,c.fecha,c.proveedor,c.credito
order by idcompra desc limit ".$cantlineas;
      $res = $link->bdEjecutar($sql);
      return ResultArray($res);
    }



//Pasa el objeto a un string 
    function ToString(){
      $tira = "Id = " . $this->idCompra. "\n";
      $tira .= "Proveedor = " . $this->proveedor. "\n";
      $tira .= "Credito = " . $this->credito. "\n";
      $tira .= "Correo = " . $this->correo. "\n";
      for($x=0;$x<count($this->lineas);$x++){
          $lin = $this->lineas[$x];
         $tira .= $lin->codigo." ".$lin->producto." ".$lin->cantidad." ".$lin->precio."\n";
         }
      $tira .= "Notas = " . $this->notas. "\n";
      $tira .= "Solicitado = " . $this->solicitado. "\n";
      return $tira;
    }

     //Hereda el objeto bitácora    
	function HeredaBitacora($pbit){
		$pbit->nivel++;		
		$this->bitacora=$pbit;
		$this->bitacora->obPadre=$this;
	}
}
?>