<?php
use Catalog\model\CatalogproductsModel;
use Catalog\entity\CatalogproductsEntity;
use Base\model\CustomattributesModel;
use Base\entity\CustomattributesEntity;
use Base\model\CustomattributesvaluesModel;
use  Base\entity\CustomattributesvaluesEntity;

ini_set('memory_limit',-1);
ini_set('max_execution_time',0);


$CatalogproductsModel = new CatalogproductsModel();
$CatalogproductsEntity = new CatalogproductsEntity();
$CustomattributesModel              = new CustomattributesModel();
$CustomattributesEntity             = new CustomattributesEntity();
$CustomattributesvaluesModel        = new CustomattributesvaluesModel();
$CustomattributesvaluesEntity       = new CustomattributesvaluesEntity();



$values_attrs = [];
$custom_attr = getDataCustomAttribute(0,'catalog_products');


$CustomattributesvaluesEntity->entity('catalog_products');
$CustomattributesvaluesModel->setTampag(10000000000);
if($CustomattributesvaluesModel->getData($CustomattributesvaluesEntity->getArrayCopy()) == REGISTRO_SUCCESS)
{
   
   
   while($_values_attrs = $CustomattributesvaluesModel->getRows()){
   
   
      $value = $_values_attrs['value'];
   

      $values_attrs[$_values_attrs['id_ref']][$custom_attr['custom_imputs'][$_values_attrs['id_attribute']]['name']]= $value;
   
   }
   
}


$CatalogproductsModel->setPage(1);
$CatalogproductsModel->setTampag(1000000);
$CatalogproductsModel->setOrdensql("catalog_products.id ASC");
$result	 		= $CatalogproductsModel->getData($CatalogproductsEntity->getArrayCopy());
$lista_admin_data = array();


if($CatalogproductsModel->getTotal() > 0)
{
   while($registro = $CatalogproductsModel->getRows())
   {
     
      if(isset($values_attrs[$registro['id']]))
      {
         $lista_admin_data[] = array_merge($registro,$values_attrs[$registro['id']]);

      }
      else{
         $lista_admin_data[] = $registro;

      }
   }
}


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
<title>Catalogo</title>
</head>

<body>
   <table width="100%" border="1" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
      <tr>
         <th width="" ><strong>Tipo</strong></th>
         <th width="" ><strong>Set de atributos</strong></th>
         <th width="" ><strong>ID Atributos configurables</strong></th>
         <th width="" ><strong>ID Padre</strong></th>
         <th width="" ><strong>Nombre</strong></th>
         <th width="" ><strong>SKU</strong></th>
         <th width="" ><strong>Categorias (JSON)</strong></th>
         <th width="" ><strong>Descripcion</strong></th>
         <th width="" ><strong>Visible en resultados (1/0)</strong></th>
         <th width="" ><strong>Stock</strong></th>
         <th width="" ><strong>¿En estock?</strong></th>
         <th width="" ><strong>¿El stock es infinito?</strong></th>
         <th width="" ><strong>¿Se puede vender (ecommerce)?</strong></th>
         <th width="" ><strong>QTY minimo para comprar</strong></th>
         <th width="" ><strong>Precio</strong></th>
         <th width="" ><strong>IVA (%)</strong></th>
         <th width="" ><strong>Incluye Iva</strong></th>
         <th width="" ><strong>¿Requiere envio?</strong></th>
         <th width="" ><strong>Meta titulo</strong></th>
         <th width="" ><strong>Meta descripcion</strong></th>
         <th width="" ><strong>Meta keywords</strong></th>
         <th width="" ><strong>Url key</strong></th>
         <th width="" ><strong>¿Esta activo?</strong></th>
         <th width="" ><strong>Sumar imagenes</strong></th>
      
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

<?php foreach($lista_admin_data as $data): ?>
<tr>
         <td><?=$data['type']?></td>
         <td><?=$data['set_attribute']?></td>
         <td><?=$data['configurable']?></td>
         <td><?=$data['parent_id']?></td>
         <td><?=$data['name']?></td>
         <td><?=$data['sku']?></td>
         <td><?=$data['category']?></td>
         <td><?=htmlentities($data['descripction'])?></td>
         <td><?=$data['visible_in_search']?></td>
         <td><?=$data['stock']?></td>
         <td><?=$data['in_stock']?></td>
         <td><?=$data['stock_infinito']?></td>
         <td><?=$data['saleable']?></td>
         <td><?=$data['min_qty']?></td>
         <td><?=$data['price']?></td>
         <td><?=$data['iva']?></td>
         <td><?=$data['incluye_iva']?></td>
         <td><?=$data['envio_requerido']?></td>
         <td><?=$data['meta_title']?></td>
         <td><?=$data['meta_description']?></td>
         <td><?=$data['meta_keyword']?></td>
         <td><?=$data['url_key']?></td>
         <td><?=$data['status']?></td>
         <td></td>
      
         <?php
         if(!empty($custom_attr['custom_imputs']))
         {

            foreach($custom_attr['custom_imputs'] as $key => $data_attrs)
            {
               if(!in_array($data_attrs['type'],['file','multifile']))
               {
               ?>
               <td><?=htmlentities($data[$data_attrs['name']])?></td>
               <?php
               }
            }
                  
            
         }
         ?>
         </tr>
<?php endforeach;?>
</table>
</body>
</html>