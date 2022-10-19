<?php
namespace Catalog\Form;

class filtrosForm extends \Base\Form\filtrosForm
{
    public function addCategory()
    {
        $this->add(array(
            'name' => 'id_category',
            'label' => _catalog('Categoria'),
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
    }

    public function addStore()
    {
        $this->add(array(
            'name' => 'store_b',
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
    }


}
?>
