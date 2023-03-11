function formValidate(idForm) {
    $.each($('#'+idForm).find('[required]'), function (indexInArray, valueOfElement) { 
        console.log(valueOfElement);
    });
}