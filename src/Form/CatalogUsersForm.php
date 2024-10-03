<?php
namespace Catalog\Form;

class CatalogUsersForm extends \Franky\Form\Form
{
    public function __construct($name)
    {

        parent::__construct();

       $this->setAtributos(array(
            'name' => $name,
            'action' =>  "mi-cuenta/username/submit.php",
            'method' => 'post',
           'enctype' => "multipart/form-data"
        ));


        $this->add(array(
                'name' => 'username',
                'label' => 'Usuario de marketplace:',
                'type'  => 'text',
                'required'  => true,
                'atributos' => array(
                    'class'       => 'required',
                    'maxlength' => 25
                 ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                 )
            )
        );

        $this->add(array(
            'name' => 'image',
            'label' => _catalog('Imagen de categoria'),
            'type'  => 'file',
            'atributos' => array(
                'id' => "image_category"
                )
            )
        );

        $this->add(array(
                'name' => 'meta_title',
                'label' => _catalog('Meta titulo'),
                'type'  => 'text',
                'required'  => true,
                'atributos' => array(
                    'class'       => 'required',
                    'maxlength' => 60
                ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                )
            )
        );


        $this->add(array(
                'name' => 'meta_description',
                'label' => _catalog('Meta descripcion'),
                'type'  => 'text',
                'required'  => true,
                'atributos' => array(
                    'class'       => 'required',
                    'maxlength' => 140
                ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                )
            )
        );

        $this->add(array(
            'name' => 'meta_keywords',
            'label' => _catalog('Meta Keywords'),
            'type'  => 'textarea',
            'required'  => false,
            'atributos' => array(
                'class'       => '',
            ),
            'label_atributos' => array(
                'class'       => 'desc_form_obligatorio'
            )
            ));
        
         $this->add(array(
                'name' => 'guardar',
                'type'  => 'submit',
                'atributos' => array(
                    'class'       => 'btn btn-primary btn-big float_right',
                    'value' => "Guardar"
                 )

            )
        );

    }

}
?>
