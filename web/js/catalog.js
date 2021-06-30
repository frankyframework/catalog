
  var catalogCarrucelRelacionados = function(){
    if($('.catalog_carrucel_productos_relacionados').length > 0)
    {
      $('.catalog_carrucel_productos_relacionados').slick({
          infinite: true,
          slidesToShow: 4,
          slidesToScroll: 4,
          dots: true,
          responsive: [
              {
                breakpoint: 980,
                settings: {
                  slidesToShow: 3,
                  slidesToScroll: 3,
                  infinite: true,
                  dots: true
                }
              },
              {
                breakpoint: 768,
                settings: {
                  slidesToShow: 2,
                  slidesToScroll: 2
                }
                },
              {
                breakpoint: 767,
                settings: {
                  slidesToShow: 1,
                  slidesToScroll: 1
                }
              }
          ]
        });
      }
  }
  catalogCarrucelRelacionados();