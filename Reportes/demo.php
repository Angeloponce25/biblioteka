<?php
$Name = 'archivo.xls';

header("Content-type: application/vnd.ms-excel; charset=iso-8859-1"); // Archivo de Excel    
header('Content-Disposition: attachment; filename="'.$Name.'"');

echo "<table border='0'> 
<tr>
<td colspan='8' style='text-align:center; font-weight:bold; font-size:28px; background: #53F442; padding:8px;'>REPORTE DE INVENTARIO</td>
</tr>
        <tr>
        <td></td>
        <td>1</td>
        <td>2</td>
        <td>3</td>
        <td>4</td>
        <td>5</td>
        <td>6</td>
        <td>7</td>
        </tr>

        <tr> 
        <td style='font-weight:bold; border:1px solid #eee;'>#</td> 
        <td style='font-weight:bold; border:1px solid #eee;'>CLASIFICACION</td> 
        <td style='font-weight:bold; border:1px solid #eee;'>CODIGO</td> 
        <td style='font-weight:bold; border:1px solid #eee;'>ID</td> 
        <td style='font-weight:bold; border:1px solid #eee;'>PRODUCTO</td> 
        <td style='font-weight:bold; border:1px solid #eee;'>LABORATORIO</td>
        <td style='font-weight:bold; border:1px solid #eee;'>PRESENTACION</td>
        <td style='font-weight:bold; border:1px solid #eee;'>STOCK</td>
        </tr>";





echo "</table>";

?>