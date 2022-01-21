<?php
namespace Catalog\Form;

class ProductsForm  extends \Franky\Form\Form
{

    public function __construct($name)
    {
        parent::__construct();


        $this->setAtributos(array(
            'name' => $name,
            'action' => "admin/catalog-products/submit.php",
            'method' => 'post',
            'enctype' => "multipart/form-data"
        ));


        $this->add(array(
                'name' => 'callback',
                'type'  => 'hidden',

            )
        );


        $this->add(array(
                'name' => 'name',
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
            'name' => 'sku',
            'label' => 'SKU',
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
                'name' => 'description',
                'label' => 'Descripción',
                'type'  => 'textarea',
                'required'  => false,
                'atributos' => array(
                    'class' => 'editor_html',
                    'placeholder' => "Descripcion",
                    'rows'  => 20
                ),
                'label_atributos' => array(
                    'class'       => 'desc_form_no_obligatorio'
                )
            )
        );


        $this->add(array(
               'name' => 'category[]',
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
                'name' => 'price',
                'label' => _('Precio'),
                'type'  => 'text',
                'required'  => false,
                'atributos' => array(
                'class'   => "",
                    'maxlength' => 10,
                    'placeholder'=>'Únicamente ingresa dígitos',
                ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                )
            )
        );


        $this->add(array(
                'name' => 'iva',
                'label' => _('IVA'),
                'type'  => 'text',
                'required'  => false,
                'atributos' => array(
                'class'   => "",
                    'maxlength' => 2,
                    'placeholder'=>'IVA'
                ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                )
            )
        );

        $this->add(array(
            'name' => 'incluye_iva',
            'type'  => 'checkbox',
            'atributos' => array(
                'class' => 'switch'
            ),
            'options' =>  array("1" => "Incluye IVA"),


            )
        );

        $this->add(array(
            'name' => 'stock',
            'label' => 'Stock',
            'type'  => 'text',
            'required'  => false,
            'atributos' => array(
                    'class'       => '',
                    'maxlength' => 5
                ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                )
            )
        );

        $this->add(array(
            'name' => 'in_stock',
            'type'  => 'checkbox',
            'atributos' => array(
                'class' => 'switch'
            ),
            'options' =>  array("1" => "Producto en stock"),


            )
        );

        $this->add(array(
            'name' => 'saleable',
            'type'  => 'checkbox',
            'atributos' => array(
                'class' => 'switch'
            ),
            'options' =>  array("1" => "Este producto se puede vender"),


            )
        );

        $this->add(array(
            'name' => 'stock_infinito',
            'type'  => 'checkbox',
            'atributos' => array(
                'class' => 'switch'
            ),
            'options' =>  array("1" => "Stock infinito"),


            )
        );

        $this->add(array(
            'name' => 'min_qty',
            'label' => 'Minimo para vender',
            'type'  => 'text',
            'required'  => false,
            
            'atributos' => array(
                    'class'       => '',
                    'maxlength' => 5,
                    'value' => 1,
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
                    'class' => 'switch',
                    'value' => 1
                ),
                
                'options' =>  array("1" => "Este item es visible en busquedas"),
            )
        );
        $this->add(array(
                'name' => 'videos',
                'label' => '¿Tienes videos? Coloca la url',
                'type'  => 'text',
                'required'  => false,
                'atributos' => array(
                    'maxlength' => 250,
                    'class' => '',
                    'id' => 'videos'
                ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                )
            )
        );


        $this->add(array(
                'name' => 'images[]',
                'label' => 'Imagenes',
                'type'  => 'file',
                'required'  => false,
                'atributos' => array(
                    'class'=>'input-file',
                    'id' => 'photoimg',
                    'style'=>'display:none;',
                    'multiple' => true
                ),
                'label_atributos' => array(
                    'class'       => 'desc_form_no_obligatorio',

                )
            )
        );


        $this->add(array(
            'name' => 'url_key',
            'label' => 'URL KEY',
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
                'label' => 'Meta titulo',
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
                'label' => 'Meta descripcion',
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
                'name' => 'meta_keyword',
                'label' => 'Meta Keywords',
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
