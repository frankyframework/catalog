<?php
namespace Catalog\Form;

class UserMarketplaceForm extends \Franky\Form\Form
{
    public function __construct($name)
    {

        parent::__construct();

       $this->setAtributos(array(
            'name' => $name,
            'action' =>  "/mi-cuenta/marketplace/submit.user.php",
            'method' => 'post',
            'enctype' => "multipart/form-data"
        ));


        $this->add(array(
            'name' => 'id',
            'type'  => 'hidden',
            
        )
    );
        $this->add(array(
                'name' => 'rfc',
                'label' => _catalog('RFC'),
                'type'  => 'text',
                'required'  => true,
                'atributos' => array(
                    'class'       => 'required',
                    'maxlength' => 13
                ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                )
            )
        );

        $this->add(array(
            'name' => 'store',
            'label' => _catalog('Tienda'),
            'type'  => 'select',
            'required'  => true,
        'required'  => true,
            'atributos' => array(
                'class'       => 'required'
            ),
            'options' => array(

            ),
            'label_atributos' => array(
                'class'       => 'desc_form_obligatorio'
            )
            )
        ); 


        $this->add(array(
            'name' => 'ine_anverso',
            'label' => _catalog('INE anverso'),
            'type'  => 'file',
            'required'  => true,
            'atributos' => array(
                'id' => "image_inea",
                'class'       => 'required'
            ),
            'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                )
            )
        );
        $this->add(array(
            'name' => 'ine_reverso',
            'label' => _catalog('INE reverso'),
            'type'  => 'file',
            'required'  => true,
            'atributos' => array(
                'id' => "image_iner",
                'class'       => 'required'
            ),
            'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                )
            )
        );
        $this->add(array(
            'name' => 'comprobante',
            'label' => _catalog('Comprobante de domicilio'),
            'type'  => 'file',
            'required'  => true,
            'atributos' => array(
                'id' => "image_comprobante",
                'class'       => 'required'
            ),
            'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
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
