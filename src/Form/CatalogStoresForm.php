<?php
namespace Catalog\Form;

class CatalogStoresForm extends \Franky\Form\Form
{
    public function __construct($name)
    {

        parent::__construct();

       $this->setAtributos(array(
            'name' => $name,
            'action' =>  "/admin/catalog-stores/submit.php",
            'method' => 'post'
        ));

         $this->add(array(
                'name' => 'id',
                'type'  => 'hidden',
            )
        );
     
        $this->add(array(
                    'name' => 'callback',
                    'type'  => 'hidden',
                )
        );


        $this->add(array(
                'name' => 'nombre',
                'label' => _catalog('Nombre'),
                'type'  => 'text',
                'required'  => true,
                'atributos' => array(
                    'class'       => 'required',
                    'maxlength' => 255
                 ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                 )
            )
        );
        

        $this->add(array(
                'name' => 'url',
                'label' => _catalog('Url'),
                'type'  => 'text',
                'required'  => true,
                'atributos' => array(
                    'class'       => 'required',
                    'maxlength' => 255
                ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                )
            )
        );



        $this->add(array(
            'name' => 'idioma',
            'label' => _catalog('Idioma'),
            'type'  => 'select',
            'required'  => true,
            'options' => array(),
            'atributos' => array(
                'class'       => 'required'
             ),
            'label_atributos' => array(
                'class'       => 'desc_form_obligatorio'
                )
        )
        );

        $this->add(array(
            'name' => 'moneda',
            'label' => _catalog('Moneda'),
            'type'  => 'select',
            'required'  => true,
            'options' => array(),
            'atributos' => array(
                'class'       => 'required'
             ),
            'label_atributos' => array(
                'class'       => 'desc_form_obligatorio'
                )
        )
        );

        $this->add(array(
            'name' => 'marketplace',
            'type'  => 'checkbox',
            'atributos' => array(
                'class' => 'switch'
            ),
            'options' =>  array("1" => _catalog("Acepta marketplace")),


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
