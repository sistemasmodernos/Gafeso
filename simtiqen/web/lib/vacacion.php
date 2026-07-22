<?php
 class Vacacion{
	
	var $mensaje;
	var $bitacora;
	var $compania;
	var $idmovi;
	var $idchofer;
	var $anno;
	var $dias;
	var $fechadi;
	var $fechaini;
	var $fechafin;
	var $razon;
	var $idtipo;
	var $nombre;
	var $destipo;
	
	//Contructor
 	function __construct(){
 	   $this->Limpiar();
 	}
    
	//Limpia las propiedades    
 	function Limpiar() {
		$this->mensaje = "";
	}

	//Crea Vacaciones para todos los choferes activos
    function CreaVacionesGlobales($pDias, $pAnno){
		if ($pDias == 0)
		{
			return "Debe escoger los días";
		}
		if ($pAnno == 0)
		{
			return "Debe escoger el año";
		}
        $sql="select idChofer,nombre from chofer where activo = 1";
        $link = new tiqmysql();
        $res = $link->bdEjecutar($sql);
		$retorno = "<h2>Se han creado Vacaciones para los siguientes choferes: </h2> <ul>";
        if($link->bdCantLineas($res)>0){
            while($linea=mysqli_fetch_array($res)){  
                $this->CreaMovimiento($linea["idChofer"],1,$pDias,$pAnno,date('Y-m-d'),date('Y-m-d'),'Generación Total');
				$retorno .= "<li>" .$linea["nombre"] . "</li>";
			}
        }
		$retorno .= "</ul>";
		return $retorno;
    }

	function CreaMovimiento($pChofer,$pIdTipo,$pDias,$pAnno,$pFechaini,$pFechafin,$pRazon){
		$bit='';
		$sql =sprintf("insert into placmovi (idchofer,idtipo,dias,fechadi,anno,fechaini,fechafin,razon)
		    values (%d,%d,%d,'%s',%d,'%s','%s','%s')",
			$pChofer,
			$pIdTipo,
            $pDias,
            date("Y-m-d H:i:s",time()),
            $pAnno,
			$pFechaini,
			$pFechafin,
			$pRazon);
		$bit= $bit . "Crea Movimiento de Vacacion para chofer " + $pChofer;
		$link =new tiqmysql();
		$link->bdAbreTran();  
		$res = $link->bdEjecutar($sql);
		$res = $link->bdUltimoId();
		$link->bdCierraTran();
       	//$this->bitacora->bitacorizar($bit);
		$retorno = "El movimiento fue creado correctamente";
		return $res;
	}

	function Cargar($idmovi){	
		$sql="select placmovi.*,chofer.nombre,plactipo.destipo from placmovi left join chofer on placmovi.idchofer = chofer.idchofer left join plactipo on placmovi.idtipo = plactipo.idtipo where idmovi = " . $idmovi;
		$link = new tiqmysql();
		$res = $link->bdEjecutar($sql);
		$retorno = "0";
		$para = new Parametro();
		$compania = $para->Retorna('nombrecia',3);
		if($link->bdCantLineas($res)>0){
			while($linea=mysqli_fetch_array($res)){  
				$retorno = "1";
				$this->compania = $compania;
				$this->idmovi = $linea["idmovi"];
				$this->idchofer = $linea["idchofer"];
				$this->anno = $linea["anno"];
				$this->dias = $linea["dias"];
				$this->fechadi = date_format(new DateTime($linea["fechadi"]), 'd/m/Y');
				$this->fechaini = date_format(new DateTime($linea["fechaini"]), 'd/m/Y');
				$this->fechafin = date_format(new DateTime($linea["fechafin"]), 'd/m/Y');
				$this->razon = $linea["razon"];
				$this->idtipo = $linea["idtipo"];
				$this->nombre = $linea["nombre"];
				$this->destipo = $linea["destipo"];
			}
		}
		return $retorno;
	}
	
    function ListarActivos($filtro){
       $link=new tiqmysql();
       $tira = "";
       $limite = "";
       if ($filtro == null){
       	$tira = "";
       }else{
       	 $primero = true;
          for($i = 0;$i<count($filtro);$i++){ 
              $valor = $filtro[$i];
              if ($primero){
                 $tira .= " and " . $valor;
                 $primero = false;
              }else{
                 $tira .= " and " . $valor;
              }
          }
       }
       return $link->bdEjecutar("select idtipo,concat(destipo,' ',if(comporta=1,' (SUMA días)',' (RESTA días)')) as nombre from plactipo where activo = 1 " . $tira . " order by 2 ". $limite);
    }      
	
function BoletaPlanilla($pidmovi){	
    $sql="select placmovi.*,chofer.nombre,plactipo.destipo from placmovi left join chofer on placmovi.idchofer = chofer.idchofer left join plactipo on placmovi.idtipo = plactipo.idtipo where idmovi = " . $pidmovi;
    $link = new tiqmysql();
	$this->idmovi = $pidmovi;
    $res = $link->bdEjecutar($sql);
	$retorno = "";
	$para = new Parametro();
	$compania = $para->Retorna('nombrecia',3);
    if($link->bdCantLineas($res)>0){
        while($linea=mysqli_fetch_array($res)){  
			$retorno .= "<table class='boleta'>";
			$retorno .= "<tr>";
			$retorno .= "<td colspan='3' class='encas'><span class='boltitulo'>" . $compania . "</span></td>";
			$retorno .= "</tr>";
			$retorno .= "<tr>";
			$retorno .= "<td colspan='3' class='encas'><span class='bolsubtitulo'>Boleta de Vacaciones</span></td>";
			$retorno .= "</tr>";
			$retorno .= "<tr>";
			$retorno .= "<td><span class='bolmarca'>Número: " .$linea["idmovi"] . "</span></td>";
			$retorno .= "<td>Tipo: " .$linea["destipo"] . "</td>";
			$retorno .= "<td>Fecha: " . date_format(new DateTime($linea["fechadi"]), 'd/m/Y') . "</td>";
			$retorno .= "</tr>";
			$retorno .= "<tr>";
			$retorno .= "<td colspan='2'>Chofer: <b>" .$linea["nombre"] . "<b></td>";
			$retorno .= "<td colspan='1'>Año: " .$linea["anno"] . "</td>";
			$retorno .= "</tr>";
			$retorno .= "<tr>";
			$retorno .= "<td><span class='bolmarca'>Días: " .$linea["dias"] . "</span></td>";
			$retorno .= "<td>Desde: " . date_format(new DateTime($linea["fechaini"]), 'd/m/Y') . "</td>";
			$retorno .= "<td>Hasta: " . date_format(new DateTime($linea["fechafin"]), 'd/m/Y') . "</td>";
			$retorno .= "</tr>";
			$retorno .= "<tr>";
			$retorno .= "<td colspan='3'>Razón: " .$linea["razon"] . "</td>";
			$retorno .= "</tr>";
			$retorno .= "<tr>";
			$retorno .= "<td colspan='2'><br><br><br><br>Firma Responsable</td>";
			$retorno .= "<td><br>" . $this->DevuelveFirma() . "<br>Firma Chofer</td>";
			$retorno .= "</tr>";
			$retorno .= "</table>";
		}
    }
	
	return $retorno;
}
    function GuardaFirma($datosFirma){
			$link =new tiqmysql();
            $sql = "insert into firma (idencomienda,tipo,datos,fecha) 
                values (".$this->idmovi.",2,' ".$datosFirma." ',now()) ";
  			$res = $link->bdEjecutar($sql);
            $sql = "update placmovi set penfirma=0 where idmovi=".$this->idmovi;
            $res = $link->bdEjecutar($sql);
	}
	
	function DevuelveFirma(){
			$link =new tiqmysql();
            $sql = "select datos from firma where idencomienda=".$this->idmovi." and tipo=2 ";
			
			$res = $link->bdEjecutar($sql);
            if($link->bdCantLineas($res)>0){
				$linea = mysqli_fetch_array($res);
                return $linea["datos"];
                }
            else
				return "";
	}

	function EstadodeCuenta($pIdChofer){
		$sql="select placmovi.*,chofer.nombre,plactipo.destipo,plactipo.comporta from placmovi left join chofer on placmovi.idchofer = chofer.idchofer left join plactipo on placmovi.idtipo = plactipo.idtipo where placmovi.idchofer = " . $pIdChofer . " order by anno,idmovi,fechadi";
		$link = new tiqmysql();
		$this->idmovi = $pidmovi;
		$res = $link->bdEjecutar($sql);
		$retorno = "";
		$para = new Parametro();
		$compania = $para->Retorna('nombrecia',3);
		if($link->bdCantLineas($res)>0){
			$retorno = '<table class="boleta"><tr><td colspan="8" class="encas"><span class="boltitulo">' . $compania . '</span></td>';
			$retorno .= '</tr><tr><td colspan="8" class="encas"><span class="bolsubtitulo">Estado de cuenta de Días de Vacaciones</span></td>';
			$retorno .= '</tr><tr><td colspan="8" class="encas">&nbsp;</td></tr>';
			$primero = 1;
			$ponenombre = 1;
			$totdiasmas = 0;
			$totdiasmen = 0;
			$annodiasmas = 0;
			$annodiasmen = 0;
			$annocorte = "1990";
			while($linea=mysqli_fetch_array($res)){  
				if ($ponenombre == 1){
					$retorno .= '<tr><td colspan="8"><span class="bolsubtitulo">Chofer: ' . $linea["nombre"] . '</span></td></tr>';
					$ponenombre = 0;
				}
				if ($linea["anno"] != $annocorte){
					if ($primero == 0){
						$retorno .= '<tr><td colspan="3" ><span style="font-weight: bold;">Total del a&ntilde;o ' . $annocorte . '</span></td>';
						$retorno .= '<td style="text-align: right; font-weight: bold;">' . $annodiasmas . '</td>';
						$retorno .= '<td style="text-align: right; font-weight: bold;">' . $annodiasmen . '</td>';
						$retorno .= '<td colspan="3" style="font-weight: bold;">Saldo ' . ($annodiasmas - $annodiasmen) .' días</td></tr>';
						$annodiasmas = 0;
						$annodiasmen = 0;
					}
					$primero = 0;
					$annocorte = $linea["anno"];
					$retorno .= '<tr><td colspan="8"><span class="bolsubtitulo">A&ntilde;o: ' . $annocorte .' </span></td></tr>';
				}
				$retorno .= '<tr>';
				$retorno .= '<td> ' . $linea["destipo"] . ' </td>';
				$retorno .= '<td><a href="veboletavaca.php?boleta=' . $linea["idmovi"] .'" target="_blank">' . $linea["idmovi"] .'</a></td>';
				$retorno .= '<td>' . date_format(new DateTime($linea["fechadi"]), 'd/m/Y') . '</td>';
				if ($linea["comporta"] == 1){
					$retorno .= '<td style="text-align: right;">' . $linea["dias"] . '</td>';
					$retorno .= '<td style="text-align: right;">&nbsp;</td>';
					$annodiasmas += $linea["dias"];
					$totdiasmas += $linea["dias"];
				}else{
					$retorno .= '<td style="text-align: right;">&nbsp;</td>';
					$retorno .= '<td style="text-align: right;">' . $linea["dias"] . '</td>';
					$annodiasmen += $linea["dias"];
					$totdiasmen += $linea["dias"];
				}
				$retorno .= '<td>' . date_format(new DateTime($linea["fechaini"]), 'd/m/Y') . '</td>';
				$retorno .= '<td>' . date_format(new DateTime($linea["fechafin"]), 'd/m/Y') . '</td>';
				$retorno .= '<td>' . $linea["razon"] . '</td>';
				$retorno .= '</tr>';
			}
			$retorno .= '<tr><td colspan="3" ><span style="font-weight: bold;">Total del a&ntilde;o ' . $annocorte . '</span></td>';
			$retorno .= '<td style="text-align: right; font-weight: bold;">' . $annodiasmas . '</td>';
			$retorno .= '<td style="text-align: right; font-weight: bold;">' . $annodiasmen . '</td>';
			$retorno .= '<td colspan="3" style="font-weight: bold;">Saldo ' . ($annodiasmas - $annodiasmen) .' días</td></tr>';
			$retorno .= '<tr><td colspan="8">&nbsp;</td></tr>';
			$retorno .= '<tr><td colspan="3" ><span style="font-weight: bold;">TOTAL FINAL</span></td>';
			$retorno .= '<td style="text-align: right; font-weight: bold;">' . $totdiasmas . '</td>';
			$retorno .= '<td style="text-align: right; font-weight: bold;">' . $totdiasmen . '</td>';
			$retorno .= '<td colspan="3" style="font-weight: bold;">Saldo ' . ($totdiasmas - $totdiasmen) .' días</td></tr>';
			$retorno .= '</table>';
		}
		return $retorno;
	}
	
   //Hereda el objeto bitac'acora    
	function HeredaBitacora($pbit){
		$pbit->nivel++;		
		$this->bitacora=$pbit;
		$this->bitacora->obPadre=$this;
	}

}

?>
