$('a[rel=popover]').popover({
    html: true,
    trigger: 'hover',
    placement: 'bottom',
    content: function(){return '<img src="'+$(this).data('img') + '" />';}
});

function nombreProducto(val) {
    $.ajax({
        type: "POST",
        url: "getAjax.php",
        data:{'idCatalogoProducto':val},
        success: function(data){
            $("#producto").html(data);
        }
    });
}

function precioUnitarioCatalogo(val){

    $.ajax({
        type: "POST",
        url: "getAjax.php",
        data:{'idCatalogoProducto2':val},
        success: function(data){
            $("#precio").html(data);
        }
    });
}

function promocionCatalogo(val){
    $.ajax({
        type: "POST",
        url: "getAjax.php",
        data:{'idCatalogoProducto3':val},
        success: function(data){
            $("#promocion").html(data);
        }
    });
}

function conteoInventarioStock(producto,val) {
    $.ajax({
        type: "POST",
        url: "getAjax.php",
        data:{'conteoInventarioStock':producto, 'ubicacion':val},
        success: function(data){
            $("#stockfinal").html(data);
        }
    });
}

function clasecliente(val,array) {
    $.ajax({
        type: "POST",
        url: "getAjax.php",
        data:{'clasecliente':val},
        success: function(data){
            $("#clasecliente").html(data);
            $( function() {
                $("#nombreProveedor").autocomplete({
                    source: array
                });
            } );
        }
    });
}

function getubicacionprod(val) {
    $.ajax({
        type: "POST",
        url: "getAjax.php",
        data:{'getubicacionprod':val},
        success: function(data){
            $("#clasecliente").html(data);
        }
    });
}

function getUbicacionAlmacen(val) {
    $.ajax({
        type: "POST",
        url: "getAjax.php",
        data:{'almacen':val},
        success: function(data){
            $("#ubicacionAlmacen").html(data);
        }
    });
}

function getcantidadprod(val) {
    $.ajax({
        type: "POST",
        url: "getAjax.php",
        data:{'getcantidadprod':val},
        success: function(data){
            $("#maxcantidad").html(data);
        }
    });
}

function getprecioprom(val) {
    $.ajax({
        type: "POST",
        url: "getAjax.php",
        data:{'getprecioprom':val},
        success: function(data){
            $("#precioprom").html(data);
        }
    });
}

function montorestante(total,cancelado) {
    $.ajax({
        type: "POST",
        url: "getAjax.php",
        data:{'montorestante':total, 'cancelado':cancelado},
        success: function(data){
            $("#montorest").html(data);
        }
    });
}

function getSubcategoria(val) {
    $.ajax({
        type: "POST",
        url: "getAjax.php",
        data:{'categoria':val},
        success: function(data){
            $("#subcategoria").html(data);
        }
    });
}

function fechavenc(total,cancelado) {
    $.ajax({
        type: "POST",
        url: "getAjax.php",
        data:{'fechavenc':total, 'cancelado':cancelado},
        success: function(data){
            $("#fechavenc").html(data);
        }
    });
}

function fechavencPrestamo(total,cancelado) {
    $.ajax({
        type: "POST",
        url: "getAjax.php",
        data:{'fechavencPrestamo':total, 'cancelado':cancelado},
        success: function(data){
            $("#fechavenc").html(data);
        }
    });
}

function codigoCatalogo(val,val2) {
    $.ajax({
        type: "POST",
        url: "getAjax.php",
        data:{'campana':val,'tipo':val2},
        success: function(data){
            $("#catalogo").html(data);
        }
    });
}

