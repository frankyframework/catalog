<?php
namespace Catalog\Form;

class BuscadorPrincipalForm  extends \Franky\Form\Form
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
              'label' => _catalog('Buscar'),
              'type'  => 'text',
              'required'  => false,
              'atributos' => array(
                  'maxlength' => 50,
                  'class' => '_buscardor required',
                  'type_mobile'  => 'search',
                  'placeholder' => 'Buscar productos...'
               ),
              'label_atributos' => array(
                  'class'       => 'desc_form_no_obligatorio'
               )
          )
      );

        $this->add(array(
               'name' => 'buscar',
               'type'  => 'submit',
               'atributos' => array(
                   'class'       => 'btn btn-primary _buscar',
                   'value' => "Buscar"
                )

           )
        );

    }


}
?>
