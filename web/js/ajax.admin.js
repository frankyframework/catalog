function setOrdenImagesProducts(album,orden)
{
    var var_query = {
          "function": "setOrdenImagesProducts",
          "vars_ajax":[album,orden]
        };
    
    pasarelaAjax('GET', var_query, "setOrdenImagesProductsHTML", '');
}



function setOrdenImagesProductsHTML(response)
{
    var respuesta = null;
    if (response != "null")
    {
        respuesta = JSON.parse(response);
       
    }

    return true;
}


function eliminarFotoCatalogProduct(image)
{
    EliminarRegistro("eliminarFotoCatalogProduct",image,0,'¿Realmente quiere eliminar esta foto?',"eliminarFotoCatalogProductHTML");
}

function eliminarFotoCatalogProductHTML(response)
{
    var respuesta = null;

    if(response != "null")
    {
        respuesta = JSON.parse(response);

        if(respuesta[0]["message"] == "success")
        {

            $(".foto_"+respuesta[0]["id"]).fadeOut(500,function(){
                $(".foto_"+respuesta[0]["id"]).remove();
            });
        }
        else
        {
             _alert(respuesta[0]["message"],"Error")
        }

    }
}


function ajax_products_cargarProductosRelacionados(id)
{
     var var_query = {
          "function": "ajax_products_cargarProductosRelacionados",
          "vars_ajax":[id]
        };
    
    pasarelaAjax('GET', var_query, "ajax_products_cargarProductosRelacionadosHTML", var_query.vars_ajax);
}
function ajax_products_cargarProductosRelacionadosHTML(response,id){
    
    var respuesta = null;

    if(response != "null")
    {
        respuesta = JSON.parse(response);

        if(respuesta["error"])
        {

            if(respuesta["message"]){
                 _alert(respuesta["message"],"Error")
            }
        }
        else{
             $("input[type=checkbox]").prop('checked',false);
            $('.cont_productos_relacionados').html(respuesta.html);
           
            $(".contenedor_columnas_info_relacionados").htmlDataDum(respuesta.lista_admin_data_relacionados,".no_hay_datos_relacionados");
            for(var x = 0; x<respuesta.lista_admin_data_relacionados.length;x++)
            {
                $('[value='+respuesta.lista_admin_data_relacionados[x].id+']').prop('checked',true);
                    
            }
            
            $("input[name='relacionado[]']").unbind('change').change(function(){

                if($(this).is(':checked'))
                {
                    ajax_products_agregarProductoRelacionado(id,$(this).attr('value'))
                }
                else{
                    ajax_products_quitarProductoRelacionado(id,$(this).attr('value'))
                }
      
            });
        }

    }
    
}

function ajax_products_agregarProductoRelacionado(id_parent,id)
{
    var var_query = {
          "function": "ajax_products_agregarProductoRelacionado",
          "vars_ajax":[id_parent,id]
        };
    
    pasarelaAjax('GET', var_query, "ajax_products_agregarProductoRelacionadoHTML", var_query.vars_ajax);
}

function ajax_products_agregarProductoRelacionadoHTML(response,id_parent)
{
    var respuesta = null;

    if(response != "null")
    {
        respuesta = JSON.parse(response);

        if(respuesta["error"])
        {

            if(respuesta["message"]){
                 _alert(respuesta["message"],"Error")
            }
        }

    }
    ajax_products_cargarProductosRelacionados(id_parent);
}



function ajax_products_quitarProductoRelacionado(id_parent,id)
{
    var var_query = {
          "function": "ajax_products_quitarProductoRelacionado",
          "vars_ajax":[id_parent,id]
        };
    
    pasarelaAjax('GET', var_query, "ajax_products_agregarProductoRelacionadoHTML",var_query.vars_ajax );
}




/* VITRINAS */



function ajax_products_cargarProductosRelacionadosVitrina(id)
{
     var var_query = {
          "function": "ajax_products_cargarProductosRelacionadosVitrina",
          "vars_ajax":[id]
        };
    
    pasarelaAjax('GET', var_query, "ajax_products_cargarProductosRelacionadosVitrinaHTML", var_query.vars_ajax);
}
function ajax_products_cargarProductosRelacionadosVitrinaHTML(response,id){
    
    var respuesta = null;

    if(response != "null")
    {
        respuesta = JSON.parse(response);

        if(respuesta["error"])
        {

            if(respuesta["message"]){
                 _alert(respuesta["message"],"Error")
            }
        }
        else{
             $("input[type=checkbox]").prop('checked',false);
            $('.cont_productos_relacionados').html(respuesta.html);
           
            $(".contenedor_columnas_info_relacionados").htmlDataDum(respuesta.lista_admin_data_relacionados,".no_hay_datos_relacionados");
            for(var x = 0; x<respuesta.lista_admin_data_relacionados.length;x++)
            {
                $('[value='+respuesta.lista_admin_data_relacionados[x].id+']').prop('checked',true);
                    
            }
            
            $("input[name='relacionado[]']").unbind('change').change(function(){

                if($(this).is(':checked'))
                {
                    ajax_products_agregarProductoRelacionadoVitrina(id,$(this).attr('value'))
                }
                else{
                    ajax_products_quitarProductoRelacionadoVitrina(id,$(this).attr('value'))
                }
      
            });
        }

    }
    
}

function ajax_products_agregarProductoRelacionadoVitrina(id_parent,id)
{
    var var_query = {
          "function": "ajax_products_agregarProductoRelacionadoVitrina",
          "vars_ajax":[id_parent,id]
        };
    
    pasarelaAjax('GET', var_query, "ajax_products_agregarProductoRelacionadoVitrinaHTML", var_query.vars_ajax);
}

function ajax_products_agregarProductoRelacionadoVitrinaHTML(response,id_parent)
{
    var respuesta = null;

    if(response != "null")
    {
        respuesta = JSON.parse(response);

        if(respuesta["error"])
        {

            if(respuesta["message"]){
                 _alert(respuesta["message"],"Error")
            }
        }

    }
    ajax_products_cargarProductosRelacionadosVitrina(id_parent);
}



function ajax_products_quitarProductoRelacionadoVitrina(id_parent,id)
{
    var var_query = {
          "function": "ajax_products_quitarProductoRelacionadoVitrina",
          "vars_ajax":[id_parent,id]
        };
    
    pasarelaAjax('GET', var_query, "ajax_products_agregarProductoRelacionadoVitrinaHTML",var_query.vars_ajax );
}



/******* Productos configurables */


function ajax_products_cargarProductosConfigurables(id)
{
     var var_query = {
          "function": "ajax_products_cargarProductosConfigurables",
          "vars_ajax":[id]
        };
    
    pasarelaAjax('GET', var_query, "ajax_products_cargarProductosConfigurablesHTML", var_query.vars_ajax);
}
function ajax_products_cargarProductosConfigurablesHTML(response,id){
    
    var respuesta = null;

    if(response != "null")
    {
        respuesta = JSON.parse(response);

        if(respuesta["error"])
        {

            if(respuesta["message"]){
                 _alert(respuesta["message"],"Error")
            }
        }
        else{
             $("input[type=checkbox]").prop('checked',false);
            $('.cont_productos_configurables').html(respuesta.html);
           
            $(".contenedor_columnas_info_configurables").htmlDataDum(respuesta.lista_admin_data_configurables,".no_hay_datos_configurables");
            for(var x = 0; x<respuesta.lista_admin_data_configurables.length;x++)
            {
                $('[value='+respuesta.lista_admin_data_configurables[x].id+']').prop('checked',true);


                $('select[name=attr_configurable]').val(respuesta.lista_admin_data_configurables[x].id_attribute)
                    
            }
            
            $("input[name='configurable[]']").unbind('change').change(function(){

                if($(this).is(':checked'))
                {
                    ajax_products_agregarProductoConfigurable(id,$(this).attr('value'),$('select[name=attr_configurable]').val())
                }
                else{
                    ajax_products_quitarProductoConfigurable(id,$(this).attr('value'),$('select[name=attr_configurable]').val())
                }
      
            });
        }

    }
    
}

function ajax_products_agregarProductoConfigurable(id_parent,id,attr)
{
    
    var var_query = {
          "function": "ajax_products_agregarProductoConfigurable",
          "vars_ajax":[id_parent,id,attr]
        };
    
    pasarelaAjax('GET', var_query, "ajax_products_agregarProductoConfigurableHTML", var_query.vars_ajax);
}

function ajax_products_agregarProductoConfigurableHTML(response,id_parent)
{
    var respuesta = null;

    if(response != "null")
    {
        respuesta = JSON.parse(response);

        if(respuesta["error"])
        {

            if(respuesta["message"]){
                 _alert(respuesta["message"],"Error")
            }
        }

    }
    ajax_products_cargarProductosConfigurables(id_parent);
}



function ajax_products_quitarProductoConfigurable(id_parent,id,attr)
{
    var var_query = {
          "function": "ajax_products_quitarProductoConfigurable",
          "vars_ajax":[id_parent,id,attr]
        };
    
    pasarelaAjax('GET', var_query, "ajax_products_agregarProductoConfigurableHTML",var_query.vars_ajax );
}


function ajax_products_setAttrConfigurable(id_parent,attr)
{
    
    var var_query = {
          "function": "ajax_products_setAttrConfigurable",
          "vars_ajax":[id_parent,attr]
        };
    
    pasarelaAjax('GET', var_query, "ajax_products_setAttrConfigurableHTML", var_query.vars_ajax);
}

function ajax_products_setAttrConfigurableHTML(response,id_parent,attr)
{
    var respuesta = null;

    if(response != "null")
    {
        respuesta = JSON.parse(response);

        if(respuesta["error"])
        {

            if(respuesta["message"]){
                 _alert(respuesta["message"],"Error")
            }
        }

    }
    ajax_products_cargarProductosConfigurables(id_parent);
    
}




function catalog_setOrdenCategoria(orden)
{
    var var_query = {
          "function": "catalog_setOrdenCategoria",
          "vars_ajax":[orden]
        };
    
    pasarelaAjax('GET', var_query, "catalog_setOrdenCategoriaHTML", '');
}


function catalog_setOrdenCategoriaHTML(response)
{
    var respuesta = null;
    if (response != "null")
    {
        respuesta = JSON.parse(response);
       
    }

    return true;
}




function catalog_setOrdenSubcategoria(orden)
{
    var var_query = {
          "function": "catalog_setOrdenSubcategoria",
          "vars_ajax":[orden]
        };
    
    pasarelaAjax('GET', var_query, "catalog_setOrdenSubcategoriaHTML", '');
}


function catalog_setOrdenSubcategoriaHTML(response)
{
    var respuesta = null;
    if (response != "null")
    {
        respuesta = JSON.parse(response);
       
    }

    return true;
}







function ajax_catalog_importar_producto(id)
{
    
   
    var var_query = {
          "function": "ajax_catalog_importar_producto",
          "vars_ajax":[id]
        };
    
    pasarelaAjax('GET', var_query, "ajax_catalog_importar_productoHTML",[]);
}


function ajax_catalog_importar_productoHTML(response)
{
    var respuesta = null;
    if (response != "null")
    {
        respuesta = JSON.parse(response);

        if(respuesta.error == true)
        {
            _alert(respuesta.msg);
        }
        else{
            for(var i = 0; i < respuesta.data.length; i++)
            {
                $('.operacion_'+ respuesta.data[i].id).html(respuesta.data[i].operacion);
                if(respuesta.data[i].status == "error")
                {
                    $('.status_'+respuesta.data[i].id).html(respuesta.data[i].status).removeClass('status_pending').addClass('status_error');
                }
                else{
                    $('.status_'+respuesta.data[i].id).html(respuesta.data[i].status).removeClass('status_pending').addClass('status_complete');
                }
                $('html, body').stop().animate({
                    scrollTop: $('.status_'+respuesta.data[i].id).offset().top - 150
                }, 500);
            }

            
    
    
            $('.play_importacion').click();
        }
      
       
      
       
    }

    return true;
}



function ajax_getCatalogCustomAttrFrm(id)
{
    var categoria = [];
    //Recorremos todos los input checkbox con name = Colores y que se encuentren "checked"
    $("input[name='category[]']:checked").each(function ()
    {
    //Mediante la función push agregamos al arreglo los values de los checkbox
    categoria.push(($(this).attr("value")));
    });

    var subcategoria = [];
    //Recorremos todos los input checkbox con name = Colores y que se encuentren "checked"
    $("input[name='subcategory[]']:checked").each(function ()
    {
    //Mediante la función push agregamos al arreglo los values de los checkbox
    subcategoria.push(($(this).attr("value")));
    });

    var var_query = {
          "function": "ajax_getCatalogCustomAttrFrm",
          "vars_ajax":[id,JSON.stringify(categoria),JSON.stringify(subcategoria)]
        };
    
    pasarelaAjax('POST', var_query, "ajax_getCatalogCustomAttrFrmHTML",[],null);
}


function ajax_getCatalogCustomAttrFrmHTML(response)
{
    var respuesta = null;
    if (response != "null")
    {
        respuesta = JSON.parse(response);

        if(respuesta.html)
        {
            $('#content_customattr').html(respuesta.html);
        }
        

        tinymce.init({
            selector: '.editor_html',
            height: 500,
            menubar: false,
            codesample_dialog_width: '600',
            codesample_dialog_height: '400',
            plugins: [
              'advlist autolink lists link charmap print preview anchor textcolor',
              'searchreplace visualblocks code fullscreen',
              'insertdatetime media table contextmenu paste code help '
            ],
            toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify table | bullist numlist outdent indent | removeformat | code | help',
            content_css: [
              '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
              '//www.tinymce.com/css/codepen.min.css'],
           relative_urls: true,
          remove_script_host: false,
          
          });
    
    
            
        
       
    }

    return true;
}



function ajax_getFrmCategpry(id,parent)
{
    var var_query = {
          "function": "ajax_getFrmCategpry",
          "vars_ajax":[id,parent]
        };
    
    pasarelaAjax('GET', var_query, "ajax_getFrmCategpryHTML", '');
}



function ajax_getFrmCategpryHTML(response)
{
    var respuesta = null;
    if (response != "null")
    {
        respuesta = JSON.parse(response);


        $('#content_form').html(respuesta.html);

        $("#frmcategoria").validate();
        $('textarea[name=meta_keywords]').tagsInput({width:'auto'});

        $('#image_category').change(function(){
            if (this.files && this.files[0])
            {
                var reader = new FileReader();
                reader.onload = function (e)
                {
                    $('#imagen_previa').html('<img style="width: 150px" src="'+e.target.result+'" alt="Preview" />');
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

     
        $("checkbox.switch").change(function()
        {
                if($(this).is(":checked"))
                {
                    $(this).addClass('switchOn');
                }
                else
                {
                    $(this).removeClass('switchOn');
                }
        });
        $(".switch").trigger('change');
        
       
    }

    return true;
}
