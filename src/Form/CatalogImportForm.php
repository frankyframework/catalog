<?php
namespace Catalog\Form;

class CatalogImportForm extends \Franky\Form\Form
{
    public function __construct($name)
    {

        parent::__construct();

       $this->setAtributos(array(
            'name' => $name,
            'action' =>  "/admin/catalog-products/importar/",
            'method' => 'post',
            'enctype' => "multipart/form-data"
        ));

        $this->add(array(
            'name' => 'my_url_friendly',
            'type'  => 'hidden'
        )
        );
        $this->add(array(
            'name' => 'archivo',
            'label' => _catalog('Archivo de catalogo'),
            'type'  => 'file',
            'atributos' => array(
             'class' => 'required'
            )
        )
        );
      
         $this->add(array(
                'name' => 'guardar',
                'type'  => 'submit',
                'atributos' => array(
                    'class'       => 'btn btn-primary btn-big float_right',
                    'value' => _catalog("Guardar")
                 )

            )
        );

    }

}
?>
