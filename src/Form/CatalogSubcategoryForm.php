<?php
namespace Catalog\Form;

class CatalogSubcategoryForm extends \Franky\Form\Form
{
    public function __construct($name)
    {

        parent::__construct();

       $this->setAtributos(array(
            'name' => $name,
            'action' =>  "/admin/catalog-subcategory/submit.php",
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
            'name' => 'id_category',
            'label' => 'Categoria:',
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
                'name' => 'name',
                'label' => 'Nombre:',
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
            'name' => 'url_key',
            'label' => 'URL KEY:',
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
                'name' => 'description',
                'label' => 'Descripcion:',
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
                    'class' => ''
                 ),
                'options' =>  array("1" => "Esta categoria es visible en busquedas"),


            )
        );
        $this->add(array(
                'label' => 'Restringir acceso a:',
                'name' => 'users[]',
                'type'  => 'checkbox',
                'options' => array(
                ),

            )
        );


        $this->add(array(
            'name' => 'image',
            'label' => _('Imagen de categoria'),
            'type'  => 'file',
            'atributos' => array(
                'id' => "image_category"
                )
            )
        );


        $this->add(array(
                'name' => 'meta_title',
                'label' => 'Meta titulo:',
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
                'label' => 'Meta descripcion:',
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
            'label' => 'Meta Keywords:',
            'type'  => 'textarea',
            'required'  => false,
            'atributos' => array(
                'class'       => ''
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
                    'value' => "Guardar"
                 )

            )
        );

    }

}
?>
