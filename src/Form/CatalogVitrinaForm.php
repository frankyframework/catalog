<?php
namespace Catalog\Form;

class CatalogVitrinaForm  extends \Franky\Form\Form
{

    public function __construct($name)
    {
        parent::__construct();


        $this->setAtributos(array(
            'name' => $name,
            'action' => "admin/catalog-vitrinas/submit.php",
            'method' => 'post'
        ));


        $this->add(array(
                'name' => 'callback',
                'type'  => 'hidden',

            )
        );


        $this->add(array(
                'name' => 'nombre',
                'label' => 'Nombre',
                'type'  => 'text',
                'required'  => true,
                'atributos' => array(
                    'maxlength' => 255,
                    'class' => 'required'
                 ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                 )
            )
        );
        
        $this->add(array(
                'name' => 'titulo',
                'label' => 'Titulo',
                'type'  => 'text',
                'required'  => true,
                'atributos' => array(
                    'maxlength' => 255,
                    'class' => 'required'
                 ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                 )
            )
        );
        
        $this->add(array(
            'name' => 'numero',
            'label' => 'Numero de items',
            'type'  => 'text',
            'required'  => true,
            'atributos' => array(
                'maxlength' => 2,
                'class' => 'required'
             ),
            'label_atributos' => array(
                'class'       => 'desc_form_obligatorio'
             )
        )
    );


        $this->add(array(
               'name' => 'category[]',
               'type'  => 'checkbox',
               'required'  => true,
              'required'  => false,
               'atributos' => array(
                   'class'       => '',

                ),
               'options' => array(

               ),
               'label_atributos' => array(
                   'class'       => 'desc_form_obligatorio',
                )
           )
        );




        $this->add(array(
            'name' => 'random',
            'type'  => 'checkbox',
            'atributos' => array(
                'class' => 'switch'
            ),
            'options' =>  array("1" => "Aleatorio"),


            )
        );



        $this->add(array(
                'name' => 'guardar',
                'type'  => 'submit',
                'atributos' => array(
                    'class'       => '_btn _btn-primary',
                    'value' => "Guardar"
                )

            )
        );

        $this->add(array(
                'name' => 'id',
                'type'  => 'hidden',

            )
        );
    }

}
?>
