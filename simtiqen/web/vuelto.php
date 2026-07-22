<html>
<body>
  <div>
 <?php 
 $total = $_GET["monto"];
 ?>
  <div id='divvuelto'> 
          <input type="hidden" name="txtmontoori" id="txtmontoori" value="<?php echo $total; ?>">
           <div id='divvuelto2' style='background-color:yellow;display:block;margin:5px;padding:5px;text-align:center;' >
             <p>Total a Pagar: <?php echo number_format($total) ?></p>
             <p>Moneda:
                <select id="cmbmoneda" onchange="cambiamoneda();">
                   <option value="CRC" selected >Colones</option>
                   <option value="USD">Dólares</option>
                </select>
             </p>
             <div id="divtc" style="display: none;">
               Tipo Cambio: <input type="number" id="txttc" onchange="calculavuelto()" value="560">
             </div>
             <p>Paga con : <input type='text' id='txtvuelto' onchange='calculavuelto(); '></p>
             <p>Vuelto     :  <span id='spanvuelto'>0</span> </p>
  </div> </div>
  
 
 </div> 
</body>
</html>