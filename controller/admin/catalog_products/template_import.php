<?php


$custom_attr = getDataCatalogCustomAttribute(0,'catalog_products');






header("Pragma: ");
header('Cache-control: ');
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0″, false");
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=template_productos.xls");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?=_catalog('Catalogo')?></title>
</head>

<body>
   <table width="100%" border="1" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
      <tr>
         <th width="" ><strong><?=_catalog('Tipo')?></strong></th>
         <th width="" ><strong><?=_catalog('Tienda')?></strong></th>
         <th width="" ><strong><?=_catalog('Set de atributos')?></strong></th>
         <th width="" ><strong><?=_catalog('ID Atributos configurables')?></strong></th>
         <th width="" ><strong><?=_catalog('ID Padre')?></strong></th>
         <th width="" ><strong><?=_catalog('Nombre')?></strong></th>
         <th width="" ><strong><?=_catalog('SKU')?></strong></th>
         <th width="" ><strong><?=_catalog('Categorias (JSON)')?></strong></th>
         <th width="" ><strong><?=_catalog('Descripcion')?></strong></th>
         <th width="" ><strong><?=_catalog('Visible en resultados (1/0)')?></strong></th>
         <th width="" ><strong><?=_catalog('Stock')?></strong></th>
         <th width="" ><strong><?=_catalog('¿En estock?')?></strong></th>
         <th width="" ><strong><?=_catalog('¿El stock es infinito?')?></strong></th>
         <th width="" ><strong><?=_catalog('¿Se puede vender (ecommerce)?')?></strong></th>
         <th width="" ><strong><?=_catalog('QTY minimo para comprar')?></strong></th>
         <th width="" ><strong><?=_catalog('Precio')?></strong></th>
         <th width="" ><strong><?=_catalog('IVA (%)')?></strong></th>
         <th width="" ><strong><?=_catalog('Incluye Iva')?></strong></th>
         <th width="" ><strong><?=_catalog('¿Requiere envio?')?></strong></th>
         <th width="" ><strong><?=_catalog('Meta titulo')?></strong></th>
         <th width="" ><strong><?=_catalog('Meta descripcion')?></strong></th>
         <th width="" ><strong><?=_catalog('Meta keywords')?></strong></th>
         <th width="" ><strong><?=_catalog('Url key')?></strong></th>
         <th width="" ><strong><?=_catalog('¿Esta activo?')?></strong></th>
         <th width="" ><strong><?=_catalog('Sumar imagenes')?></strong></th>
      
      <?php
      if(!empty($custom_attr['custom_imputs']))
      {

         foreach($custom_attr['custom_imputs'] as $key => $data_attrs)
         {
            if(!in_array($data_attrs['type'],['file','multifile']))
            {

               ?>
               <th width="" ><strong>
               <?php
               echo $data_attrs['label'];
               if(in_array($data_attrs['type'],['checkbox']))
               {
                  echo " (JSON)";
               }
               ?></strong></th><?php
            }
               
         }
      }
      ?>
     </tr>
    </table>

<table width="100%" border="1" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">


</table>
</body>
</html>