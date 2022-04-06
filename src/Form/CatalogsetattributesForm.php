<?php
namespace Catalog\Form;

class CatalogsetattributesForm extends \Franky\Form\Form
{
    public function __construct($name)
    {

        parent::__construct();

       $this->setAtributos(array(
            'name' => $name,
            'action' =>  "/admin/catalog-set-attributes/submit.php",
            'method' => 'post',
            'enctype' => "multipart/form-data"
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
                'name' => 'name',
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
                'name' => 'description',
                'label' => _catalog('Descripcion'),
                'type'  => 'textarea',
                'required'  => false,
                'atributos' => array(
                    'class'       => '',
                    'maxlength' => 255
                ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                )
            )
        );



        $this->add(array(
            'name' => 'attributes[]',
            'type'  => 'checkbox',
            'required'  => true,
           'required'  => true,
            'atributos' => array(
                'class'       => 'required',

             ),
            'options' => array(

            ),
            'label_atributos' => array(
                'class'       => 'desc_form_obligatorio',
             )
        )
     );


        $this->add(array(
            'name' => 'status',
            'type'  => 'checkbox',
            'atributos' => array(
                'class' => 'switch'
             ),
            'options' =>  array("1" => _catalog("Activar")),


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
