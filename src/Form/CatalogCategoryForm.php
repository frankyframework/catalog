<?php
namespace Catalog\Form;

class CatalogCategoryForm extends \Franky\Form\Form
{
    public function __construct($name)
    {

        parent::__construct();

       $this->setAtributos(array(
            'name' => $name,
            'action' =>  "/admin/catalog-category/submit.php",
            'method' => 'post',
            'enctype' => "multipart/form-data"
        ));

         $this->add(array(
                'name' => 'id',
                'type'  => 'hidden',
            )
        );
        $this->add(array(
            'name' => 'parent_id',
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
                'name' => 'visible_in_search',
                'type'  => 'checkbox',
                'atributos' => array(
                    'class' => 'switch'
                 ),
                'options' =>  array("1" => _catalog("Esta categoria es visible en busquedas")),


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
                'label' => _catalog('Restringir acceso a'),
                'name' => 'users[]',
                'type'  => 'checkbox',
                'options' => array(
                ),

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
            'name' => 'url_key',
            'label' => _catalog('URL KEY'),
            'type'  => 'text',
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
