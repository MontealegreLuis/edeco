$.validator.addMethod(
    'dateRange', 
    function() {
        var isRangeValid = new Date($("#startDate").val()) 
            <= new Date($("#stopDate").val());
        return isRangeValid;
    }, 
    '<ul><li>Por favor seleccione un rango válido, '
    + 'la fecha inicial antes de la final.</li></ul>'
);

// a new class rule to group all three methods
$.validator.addClassRules({
    requiredDateRange: {required:true, date:true, dateRange:true}
});
