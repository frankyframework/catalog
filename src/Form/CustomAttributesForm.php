<?php
namespace Catalog\Form;

class CustomAttributesForm extends \Franky\Form\Form
{
    public function __construct($name)
    {
        

        parent::__construct();
       $this->setAtributos(array(
            'name' => $name,
            'action' => "/admin/catalog-custom-attributes/submit.php",
            'method' => 'post',
            'enctype' => "multipart/form-data"
        ));

        
       $this->add(array(
                'name' => 'callback',
                'type'  => 'hidden',
                
            )
        );
       $this->add(array(
                'name' => 'type_option',
                'type'  => 'hidden',
                
            )
        );
       
        $this->add(array(
                'name' => 'name',
                'label' => 'Nombre del atributo',
                'type'  => 'text',
                'required'  => true,
                'atributos' => array(
                    'class'       => 'required',
                    'maxlength' => 100
                 ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                 )
            )
        );

        $this->add(array(
            'name' => 'label',
            'label' => 'Etiqueta del atributo',
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
            'name' => 'type',
            'label' => 'Tipo de campo',
            'type'  => 'select',
            'required'  => true,
            'atributos' => array(
                'class'       => 'required',
             ),
            'options' => array('text' => "Texto",
                                "textarea" => "Texto largo",
                                "select" => "Drop Down",
                                "checkbox" => "Checkbox",
                                "radio" => "Radio",
                                "file" => "Archivo",
                                "multifile" => "Archivo multiple"),
            'label_atributos' => array(
                'class'       => 'desc_form_obligatorio'
                )
        )
    );

    $this->add(array(
        'name' => 'source',
        'label' => 'Clase de datos',
        'type'  => 'text',
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
            'name' => 'entity',
            'label' => 'Entidad de datos',
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
                    'name' => 'option_label[]',
                  //  'label' => '',
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
                'name' => 'option_value[]',
              //  'label' => '',
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
                'name' => 'required',
                'type'  => 'checkbox',
                'atributos' => array(
                    'class' => ''
                 ),
                'options' =>  array("1" => "Este campo es requerido"),


            )
        );


      
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
    
    public function addId()
    {
         $this->add(array(
                'name' => 'id',
                'type'  => 'hidden',
                
            )
        );
    }
 
}
?>