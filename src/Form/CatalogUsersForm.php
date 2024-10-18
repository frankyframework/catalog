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
            'name' => 'tipo_persona',
            'label' => _catalog('Tipo de persona'),
            'type'  => 'select',
            'required'  => false,
            'options' => array(0=>_catalog("Fisica"),1 => _catalog("Moral")),
            'atributos' => array(
                'class'       => 'required'
             ),
            'label_atributos' => array(
                'class'       => 'desc_form_no_obligatorio'
                )
        )
        );

        $this->add(array(
                'name' => 'empresa',
                'label' => _catalog('Nombre de la empresa'),
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
            'name' => 'rfc',
            'label' => _catalog('RFC'),
            'type'  => 'text',
            'required'  => false,
            'atributos' => array(
                'class'       => '',
                'maxlength' => 13
            ),
            'label_atributos' => array(
                'class'       => 'desc_form_no_obligatorio'
            )
            )
        );

        $this->add(array(
            'name' => 'sector',
            'label' => _catalog('Sector'),
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
            'name' => 'direccion',
            'label' => _catalog('Direccion'),
            'type'  => 'textarea',
            'required'  => true,
            'atributos' => array(
                'class'       => 'required',
                'maxlength' => 500
            ),
            'label_atributos' => array(
                'class'       => 'desc_form_obligatorio'
                )
            )
        );

        $this->add(array(
            'name' => 'latitud',
            'label' => _catalog('Latitud'),
            'type'  => 'text',
            'required'  => false,
            'atributos' => array(
                'class'       => '',
                'maxlength' => 50
            ),
            'label_atributos' => array(
                'class'       => 'desc_form_no_obligatorio'
            )
            )
        );

        $this->add(array(
            'name' => 'longitud',
            'label' => _catalog('Longitud'),
            'type'  => 'text',
            'required'  => false,
            'atributos' => array(
                'class'       => '',
                'maxlength' => 50
            ),
            'label_atributos' => array(
                'class'       => 'desc_form_no_obligatorio'
            )
            )
        );

        $this->add(array(
            'name' => 'logo',
            'label' => _catalog('Logotipo de la empresa'),
            'type'  => 'file',
            'atributos' => array(
                'id' => "image_logo"
                )
            )
        );
        $this->add(array(
            'name' => 'image',
            'label' => _catalog('Imagen de portada'),
            'type'  => 'file',
            'atributos' => array(
                'id' => "image_portada"
                )
            )
        );

        $this->add(array(
            'name' => 'descripcion',
            'label' => _catalog('Descripcion'),
            'type'  => 'text',
            'required'  => true,
            'atributos' => array(
                'class'       => 'required',
                'maxlength' => 500
            ),
            'label_atributos' => array(
                'class'       => 'desc_form_obligatorio'
                )
            )
        );

        $dias = [
            "lunes" => "Lunes", 
            "martes" => "Martes", 
            "miescoles" => "Miercoles", 
            "jueves" => "Jueves",
            "viernes" => "Viernes",
            "sabado" => "Sabado",
            "domingo" => "Domingo"
        ];
        $horario = [];
        for($x = 0; $x <= 23 ; $x++) {
            $horario[$x.":00"] = $x.":00";
        }
        foreach($dias as $k => $v) {
            $this->add(array(
                'name' => $k,
                'type'  => 'checkbox',
                'atributos' => array(
                    'class' => 'switch'
                 ),
                'options' =>  array("1" => _catalog($v)),
                )
            );
    
            $this->add(array(
                'name' => 'hora_i_'.$k,
                'label' => _catalog('Abre'),
                'type'  => 'select',
                'required'  => false,
                'options' => $horario,
                'atributos' => array(
                    'class'       => ''
                 ),
                'label_atributos' => array(
                    'class'       => 'desc_form_no_obligatorio'
                    )
            )
            );
    
            $this->add(array(
                'name' => 'hora_f_'.$k,
                'label' => _catalog('Cierra'),
                'type'  => 'select',
                'required'  => false,
                'options' =>$horario,
                'atributos' => array(
                    'class'       => ''
                 ),
                'label_atributos' => array(
                    'class'       => 'desc_form_no_obligatorio'
                    )
            )
            );
        }
       
        $this->add(array(
            'name' => 'web',
            'label' => _catalog('Url de web de la empresa'),
            'type'  => 'text',
            'required'  => false,
            'atributos' => array(
                'class'       => 'url',
                'maxlength' => 255
            ),
            'label_atributos' => array(
                'class'       => 'desc_form_no_obligatorio'
            )
            )
        );
        $this->add(array(
            'name' => 'email',
            'label' => _catalog('E-mail de la empresa'),
            'type'  => 'text',
            'required'  => false,
            'atributos' => array(
                'class'       => 'email',
                'maxlength' => 255,
                'type_mobile'  => 'email'
            ),
            'label_atributos' => array(
                'class'       => 'desc_form_no_obligatorio'
            )
            )
        );

        $this->add(array(
            'name' => 'tel',
            'label' => _catalog('Telefono de la empresa'),
            'type'  => 'text',
            'required'  => false,
            'atributos' => array(
                'class'       => '',
                'maxlength' => 10,
                'type_mobile'  => 'tel'
            ),
            'label_atributos' => array(
                'class'       => 'desc_form_no_obligatorio'
            )
            )
        );

        $this->add(array(
            'name' => 'wa',
            'label' => _catalog('Whatsapp de la empresa'),
            'type'  => 'text',
            'required'  => false,
            'atributos' => array(
                'class'       => '',
                'maxlength' => 10,
                'type_mobile'  => 'tel'
            ),
            'label_atributos' => array(
                'class'       => 'desc_form_no_obligatorio'
            )
            )
        );


        $this->add(array(
            'name' => 'fb',
            'label' => _catalog('Facebook de la empresa (URL)'),
            'type'  => 'text',
            'required'  => false,
            'atributos' => array(
                'class'       => 'url',
                'maxlength' => 255,
            ),
            'label_atributos' => array(
                'class'       => 'desc_form_no_obligatorio'
            )
            )
        );
        $this->add(array(
            'name' => 'ins',
            'label' => _catalog('Instagram de la empresa (URL)'),
            'type'  => 'text',
            'required'  => false,
            'atributos' => array(
                'class'       => 'url',
                'maxlength' => 255,
            ),
            'label_atributos' => array(
                'class'       => 'desc_form_no_obligatorio'
            )
            )
        );
        $this->add(array(
            'name' => 'ttk',
            'label' => _catalog('Tiktok de la empresa (URL)'),
            'type'  => 'text',
            'required'  => false,
            'atributos' => array(
                'class'       => 'url',
                'maxlength' => 255,
            ),
            'label_atributos' => array(
                'class'       => 'desc_form_no_obligatorio'
            )
            )
        );
        $this->add(array(
            'name' => 'x',
            'label' => _catalog('X de la empresa (URL)'),
            'type'  => 'text',
            'required'  => false,
            'atributos' => array(
                'class'       => 'url',
                'maxlength' => 255,
            ),
            'label_atributos' => array(
                'class'       => 'desc_form_no_obligatorio'
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
