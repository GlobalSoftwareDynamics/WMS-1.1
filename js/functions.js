$('a[rel=popover]').popover({
    html: true,
    trigger: 'hover',
    placement: 'bottom',
    content: function(){return '<img src="'+$(this).data('img') + '" />';}
});

function nombreProducto(val,val2) {
    $.ajax({
        type: "POST",
        url: "getAjax.php",
        data:{'idCatalogoProducto':val, 'numCampana':val2},
        success: function(data){
            $("#producto").html(data);
        }
    });
}

function nombreProductoSKU(val) {
    $.ajax({
        type: "POST",
        url: "getAjax.php",
        data:{'idProductoSel':val},
        success: function(data){
            $("#producto").html(data);
        }
    });
}

function precioUnitarioCatalogo(val,val2){

    $.ajax({
        type: "POST",
        url: "getAjax.php",
        data:{'idCatalogoProducto2':val, 'numCampana':val2},
        success: function(data){
            $("#precio").html(data);
        }
    });
}

function promocionCatalogo(val,val2){
    $.ajax({
        type: "POST",
        url: "getAjax.php",
        data:{'idCatalogoProducto3':val, 'numCampana':val2},
        success: function(data){
            $("#promocion").html(data);
        }
    });
}

function conteoInventarioStock(producto,val) {

    var idproducto = document.getElementById("idProducto").value;

    $.ajax({
        type: "POST",
        url: "getAjax.php",
        data:{'conteoInventarioStock':idproducto, 'ubicacion':val},
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
            $("#ubicacionAlmacen").html(data);
        }
    });
}

function getUbicacionAlmacen(val) {

    console.log(val);

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

function getcantidadprodID(val) {
    $.ajax({
        type: "POST",
        url: "getAjax.php",
        data:{'getcantidadprodID':val},
        success: function(data){
            $("#maxcantidad").html(data);
        }
    });
}

function getnombreprodID(val) {
    $.ajax({
        type: "POST",
        url: "getAjax.php",
        data:{'getnombreprodID':val},
        success: function(data){
            $("#nombreProdID").html(data);
        }
    });
}

function getidproducto(val) {
    $.ajax({
        type: "POST",
        url: "getAjax.php",
        data:{'getidproducto':val},
        success: function(data){
            $("#productoID").html(data);
        }
    });
}

function getpreciopromID(val) {
    $.ajax({
        type: "POST",
        url: "getAjax.php",
        data:{'getpreciopromID':val},
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

function getComparacionSKU(val1,val2,indice) {

    var producto = "Producto"+indice;
    var producto1 = "idProducto"+indice;
    console.log(producto);
    console.log(val1);
    console.log(String(val2));
    console.log(document.getElementById(producto1).value);
    if (val1 === document.getElementById(producto1).value) {
        console.log("Si");
        console.log(indice);
        document.getElementById(indice).style.backgroundColor = "#8BFF9A";
    }else{
        console.log("No");
        console.log(indice);
        document.getElementById(indice).style.backgroundColor = "#FF615B";
    }

}

function dias(total,cancelado) {
    $.ajax({
        type: "POST",
        url: "getAjax.php",
        data:{'dias':total, 'cancelado':cancelado},
        success: function(data){
            $("#fechadias").html(data);
        }
    });
}

function getproductoCatalogo(idCatalogo) {
    $.ajax({
        type: "POST",
        url: "getAjax.php",
        data:{'idCatalogoGetProducto':idCatalogo},
        success: function(data){
            $("#nombreProdID").html(data);
        }
    });
}

function diasPrestamo(total,cancelado) {
    $.ajax({
        type: "POST",
        url: "getAjax.php",
        data:{'diasPrestamo':total, 'cancelado':cancelado},
        success: function(data){
            $("#fechadias").html(data);

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

