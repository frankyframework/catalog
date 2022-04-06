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
            'name' => 'type',
            'label' => _catalog('Tipo de producto'),
            'type'  => 'select',
            'required'  => false,
            'options' => array('simple'=>_catalog("Simple"),"configurable" => _catalog("Configurable")),
            'atributos' => array(
                'class'       => 'required'
             ),
            'label_atributos' => array(
                'class'       => 'desc_form_no_obligatorio'
                )
        )
        );
        $this->add(array(
            'name' => 'set_attribute',
            'label' => _catalog('Set de atributos'),
            'type'  => 'select',
            'required'  => false,
            'options' => array(),
            'atributos' => array(
                'class'       => 'required'
             ),
            'label_atributos' => array(
                'class'       => 'desc_form_no_obligatorio'
                )
        )
        );
        $this->add(array(
                'name' => 'name',
                'label' => _catalog('Nombre'),
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
            'label' => _catalog('SKU'),
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
                'label' => _catalog('Descripción'),
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
                'label' => _catalog('Precio'),
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
                'label' => _catalog('IVA'),
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
            'options' =>  array("1" => _catalog("Incluye IVA")),


            )
        );

        $this->add(array(
            'name' => 'stock',
            'label' => _catalog('Stock'),
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
            'options' =>  array("1" => _catalog("Producto en stock")),


            )
        );

        $this->add(array(
            'name' => 'saleable',
            'type'  => 'checkbox',
            'atributos' => array(
                'class' => 'switch'
            ),
            'options' =>  array("1" => _catalog("Este producto se puede vender")),


            )
        );

        $this->add(array(
            'name' => 'stock_infinito',
            'type'  => 'checkbox',
            'atributos' => array(
                'class' => 'switch'
            ),
            'options' =>  array("1" => _catalog("Stock infinito")),


            )
        );

        $this->add(array(
            'name' => 'min_qty',
            'label' => _catalog('Minimo para vender'),
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
                
                'options' =>  array("1" => _catalog("Este item es visible en busquedas")),
            )
        );
        $this->add(array(
                'name' => 'videos',
                'label' => _catalog('¿Tienes videos? Coloca la url'),
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
                'label' => _catalog('Imagenes'),
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
                'name' => 'meta_keyword',
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
                    'class'       => '_btn _btn-primary',
                    'value' => _catalog("Guardar")
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
