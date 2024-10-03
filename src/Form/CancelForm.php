<?php
namespace Catalog\Form;

class CancelForm extends \Franky\Form\Form
{
    public function __construct($name)
    {

        parent::__construct();

       $this->setAtributos(array(
            'name' => $name,
            'action' =>  "",
            'method' => 'post'
        ));

         $this->add(array(
                'name' => 'id',
                'type'  => 'hidden',
            )
        );
       
        

        $this->add(array(
                'name' => 'message',
                'label' => _catalog('Razon del rechazo'),
                'type'  => 'textarea',
                'required'  => false,
                'atributos' => array(
                    'class'       => '',
                    'maxlength' => 255
                ),
                'label_atributos' => array(
                    'class'       => 'desc_form_no_obligatorio'
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
