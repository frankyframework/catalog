<?php
namespace Catalog\Form;

class BuscadorLateralForm  extends \Franky\Form\Form
{

  public function __construct($name)
  {
      parent::__construct();


             $this->setAtributos(array(
                  'name' => $name,
                  'action' => "",
                  'method' => 'get'
              ));



          $this->add(array(
                  'name' => 'q',
                  'label' => '',
                  'type'  => 'hidden',
                  'required'  => false
              )
          );

        

       $this->add(array(
               'name' => 'categoria[]',
               'label' => _('Categoría'),
               'type'  => 'checkbox',
               'required'  => false,
               'atributos' => array(
                   'class'       => '',

                ),
               'options' => array(

               ),
               'label_atributos' => array(
                   'class'       => ''
                )
           )
       );



               $this->add(array(
                    'name' => 'precio',

                    'type'  => 'text',
                    'required'  => false,
                    'atributos' => array(
                        'class'       => '',
                        'style' => 'border:0; color:#f6931f; font-weight:bold;',
                        'id' => 'filtro_precio',
                        'readonly' =>'readonly'

                     )

                )
            );
          

    }


}
?>
